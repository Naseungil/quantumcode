<?php
session_start();
//print_r($_SESSION); //Array ( [MemEmail] => haemilyjh@naver.com [MemId] => 149 [MUNAME] => 윤네이버 [Mgrade] => bronze )
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

//처음 회원 가입했을때(즉 login_count가 0->1로 바뀌는 순간), 모달창 튀어나오게
$showModal = false; // 모달 표시 여부


if (isset($_SESSION['MemEmail'])) {
  $email = $_SESSION['MemEmail'];
  $memId = $_SESSION['MemId'];     //쪽지관련해서 쓸거.

  //처음 관심강의 선택하기 관련. `login_count`와 `first_coupon_issued`를 가져오기
  $sql = "SELECT login_count, first_coupon_issued FROM memberskakao WHERE memEmail = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($loginCount, $firstCouponIssued);
  $stmt->fetch();
  $stmt->close();


  //쪽지 갯수 확인하는 sql
  $sql_msg = "SELECT COUNT(*) AS unread_count FROM tomembermessages WHERE receiver_id = ? AND is_read = 0";
  $stmt = $mysqli->prepare($sql_msg);
  $stmt->bind_param("s", $memId);
  $stmt->execute();
  $stmt->bind_result($unreadCount);
  $stmt->fetch();
  $stmt->close();

  // 모든 쪽지 가져오기
  $sql_all_msgs = "SELECT * FROM tomembermessages WHERE receiver_id = ?";
  $stmt = $mysqli->prepare($sql_all_msgs);
  $stmt->bind_param("s", $memId);
  $stmt->execute();
  $result = $stmt->get_result();

  // 쪽지 데이터를 배열로 저장
  $messages = [];
  while ($row = $result->fetch_assoc()) {
      $messages[] = $row;
  }
  $stmt->close();

  // 로그인처음에만 + 쿠폰발급안된경우에만 모달창 보이게
  if ($loginCount == 1 && $firstCouponIssued == 0) {
      $showModal = true;
  }

}

?>

<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quantum Code</title>
  <!-- 캐싱문제 방지 -->
  <link rel="stylesheet" href="/qc/css/core-style.css?v=<?= time(); ?>">
  <!-- 제이쿼리랑 폰트어썸 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/css/common.css">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/css/core-style.css">

  <!-- favicon -->
   
  <?php
  if (isset($slick_css)) {
    echo $slick_css;
  }
  if (isset($lecture_css)) {
    echo $lecture_css;
  }
  if (isset($video_css)) {
    echo $video_css;
  }
  if (isset($main_css)) {
    echo $main_css;
  }
  if (isset($community_css)) {
    echo $community_css;
  }

  ?>
  <!-- Favicon 기본 설정 -->
  <link rel="apple-touch-icon" sizes="57x57" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <!-- 커스텀css... 필요하면 작성하나 비추 -->

  <style>
        

  </style>

</head>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<?php
if (isset($slick_js)) {
  echo $slick_js;
}
?>

<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/index.php">
        <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/img/main_logo1.png" alt="Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
        <div class="d-flex gap-3 align-items-center">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown ms-3">
              <a class="nav-link" href="#" id="lectureDropdown" role="button" aria-expanded="false">
                강의
              </a>
              <ul class="dropdown-menu" aria-labelledby="lectureDropdown">
                <li><a class="dropdown-item" href="#">프론트엔드</a></li>
                <li><a class="dropdown-item" href="#">백엔드</a></li>
                <li><a class="dropdown-item" href="#">게임</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="#">커뮤니티</a>
              <ul class="dropdown-menu" aria-labelledby="communityDropdown">
                <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/notice.php">공지사항</a></li>
                <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/faq.php">FAQ</a></li>
                <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/qna.php">QnA</a></li>
                <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/board.php">자유게시판</a></li>
                <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/questions.php">질문게시판</a></li>
                <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/study.php">스터디 모집</a></li> 
              </ul> <!-- <= href 알아서 수정바람 -->
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/event.php">이벤트</a>
            </li>
          </ul>
          <form class="d-flex search-form">
            <input class="form-control me-2" type="search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>
        <div class="ms-3 nav_sign d-flex gap-3">
          <!-- 로그인된 경우 ==> 장바구니, 쪽지, 닉네임, 로그아웃 다 보여주기 -->
        <?php if (isset($_SESSION['MUNAME'])): ?>
          <div class="d-flex align-items-center">
              <!-- 장바구니 아이콘 -->
              <a href="#" class="me-3 text-decoration-none" title="장바구니" data-bs-toggle="modal" data-bs-target="#cartModal">
                  <i class="fas fa-shopping-cart fa-lg"></i>
              </a>
              
              <!-- 쪽지 아이콘 => 새 쪽지가 있거나 없을때 분기 -->
              <a href="#" class="me-3 text-decoration-none position-relative" title="쪽지" data-bs-toggle="modal" data-bs-target="#messageModal">
                <i class="fas fa-envelope fa-lg"></i>
                <?php if ($unreadCount > 0): ?>
                  <!-- 읽지 않은 쪽지 개수 표시 -->
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $unreadCount ?>
                    <span class="visually-hidden">unread messages</span>
                  </span>
                <?php endif; ?>
              </a>
                            
              <!-- 사용자 이름 표시 -->
              <span class="text-primary me-3">
                  <?php echo htmlspecialchars($_SESSION['MUNAME']); ?>님
              </span>
              <!-- 로그아웃 버튼 -->
              <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/logout.php" class="btn btn-secondary">로그아웃</a>
          </div>
    

    <!-- 장바구니 모달 -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true" data-bs-backdrop="false">
      <div class="modal-dialog" id="cartModalDialog" style="position: absolute;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cartModalLabel"><i class="fas fa-shopping-cart me-2"></i>장바구니</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>장바구니에 담긴 상품이 없습니다.</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="fw-bold">합계:</span>
                <span class="text-primary fw-bold">0 원</span>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-custom">보러가기</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 쪽지 모달 -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true" data-bs-backdrop="false">
      <div class="modal-dialog" id="messageModalDialog" style="position: absolute;" >
        <div class="modal-content" id="message-modal-content" style="width:200%; height:450px">
          <div class="modal-header">
            <h5 class="modal-title" id="messageModalLabel"><i class="fas fa-envelope me-2"></i>쪽지</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="position: relative;">
            <!-- 쪽지 리스트 -->
            <ul class="list-group" id="messageList" style="position: absolute; top: 10px; left: 10px; width: 95%; max-height: calc(100% - 20px); overflow-y: auto;">
              <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $message): ?>
                  <li class="list-group-item d-flex flex-column mt-1">
                    <div class="d-flex justify-content-between align-items-center">
                      <strong class="text-primary"><?= htmlspecialchars($message['sender_name']); ?></strong>
                      <small class="text-muted"><?= date("Y-m-d H:i", strtotime($message['sent_at'])); ?></small>
                    </div>
                    <p class="mb-1 text-dark"><?= htmlspecialchars($message['message_content']); ?></p>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="list-group-item text-center">
                  <i class="fas fa-envelope-open-text text-secondary mb-2"></i><br>
                  <span class="text-muted">새로운 쪽지가 없습니다.</span>
                </li>
              <?php endif; ?>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">모든 쪽지 보기</button> <!--쪽지전체보기로 이동하는 a태그 만들것!!-->
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 로그인되지 않은 경우 -->
    <?php else: ?>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/logintest2.php" class="btn btn-primary">로그인</a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/signup.php" class="btn btn-secondary">회원가입</a>
    <?php endif; ?>
  </nav>

  <!-- 모달 HTML 위치는 자유롭게 변경 가능합니다. -->
  <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="welcomeModalLabel">환영합니다!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Quantum Code에 처음 오신 것을 환영합니다! 첫 방문을 기념하여 관심있는 분야를 선택하고 쿠폰을 발급받아 보세요!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="nextButton">다음</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 두 번째 모달 HTML -->
  <div class="modal fade" id="secondModal" tabindex="-1" aria-labelledby="secondModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="categoryForm" method="POST" action="../qc/lecture/save_categories.php">
          <div class="modal-header">
            <h5 class="modal-title" id="secondModalLabel">카테고리 선택</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>관심 있는 학습 카테고리를 선택하세요:</p>
            <div id="categoryButtons" class="d-flex flex-wrap gap-2">
              <button type="button" class="btn btn-outline-primary category-btn" data-value="JavaScript">JavaScript</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="TypeScript">TypeScript</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="Python">Python</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="Docker">Docker</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="React">React</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="Java">Java</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="MySQL">MySQL</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="PHP">PHP</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="C">C</button>
              <button type="button" class="btn btn-outline-primary category-btn" data-value="NodeJs">NodeJs</button>
            </div>
            <!-- 숨겨진 필드로 선택한 카테고리 저장 -->
            <input type="hidden" name="selectedCategories" id="selectedCategories" value="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            <button type="submit" class="btn btn-primary">저장하고 쿠폰 받기</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<!------- JavaScript -------->
<script>
document.addEventListener("DOMContentLoaded", function () {
  <?php if ($showModal): ?>
        // 첫 번째 모달 표시
        const welcomeModal = new bootstrap.Modal(document.getElementById("welcomeModal"), {
            keyboard: true,
            backdrop: "static",
        });
        welcomeModal.show();

        // "다음" 버튼 클릭 시 두 번째 모달 표시
        document.getElementById("nextButton").addEventListener("click", function () {
            welcomeModal.hide(); // 첫 번째 모달 닫기
            const secondModal = new bootstrap.Modal(document.getElementById("secondModal"));
            secondModal.show(); // 두 번째 모달 열기
        });
    <?php endif; ?>

  //선택한 것 지정해서 저장하는 코드
  const categoryButtons = document.querySelectorAll(".category-btn");
  const selectedCategoriesInput = document.getElementById("selectedCategories");
  const form = document.getElementById("categoryForm");
  let selectedCategories = []; // 선택된 카테고리 저장

  // 버튼 클릭 이벤트
  categoryButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const value = this.getAttribute("data-value");
      if (this.classList.contains("selected")) {
        // 선택 해제
        this.classList.remove("selected");
        selectedCategories = selectedCategories.filter((item) => item !== value);
      } else {
        // 선택
        this.classList.add("selected");
        selectedCategories.push(value);
      }
      console.log("Selected Categories:", selectedCategories); // 선택된 항목 확인용
    });
  });

  // 폼 제출 전에 숨겨진 필드에 선택된 카테고리 저장 및 쿠폰 발급
form.addEventListener("submit", function (event) {
    event.preventDefault(); // 기본 폼 제출 동작 막기

    if (selectedCategories.length === 0) {
        alert("적어도 하나의 카테고리를 선택해주세요.");
    } else {
        // 선택된 카테고리를 숨겨진 필드에 저장
        selectedCategoriesInput.value = JSON.stringify(selectedCategories);

        // 쿠폰 발급 AJAX 요청
        try{
        fetch("/qc/coupon/give_coupon.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({}),  //빈 객체를 넘겨준다. 
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
            if (data.success) {
                console.log(data.message); // 성공 메시지 출력

                // 쿠폰 발급 완료 모달 표시
                // const couponModal = new bootstrap.Modal(document.getElementById("couponModal"));
                // couponModal.show();

                
            } else {
                alert("쿠폰 발급 실패: " + data.error);
            }
          })
        }
        catch(error){
            console.error("AJAX 요청 오류:", error);
            alert("서버와 통신 중 문제가 발생했습니다.");
        };
    }
  });






  const setModalPosition = (icon, modalDialog) => {
      const iconRect = icon.getBoundingClientRect();
      modalDialog.style.top = `${iconRect.bottom + window.scrollY}px`; // 아이콘 아래
      modalDialog.style.left = `${iconRect.left}px`; // 아이콘의 왼쪽 정렬
  };

  //이하 코드는 장바구니와 쪽지 모달에 관련된 코드.
  const cartIcon = document.querySelector('.fa-shopping-cart'); // 장바구니 아이콘
  const cartModalDialog = document.getElementById('cartModalDialog');

  const messageIcon = document.querySelector('.fa-envelope'); // 쪽지 아이콘
  const messageModalDialog = document.getElementById('messageModalDialog');

  // 장바구니 아이콘 클릭 시 위치 설정
  cartIcon.parentElement.addEventListener('click', function () {
      setModalPosition(cartIcon, cartModalDialog);
  });

  // 쪽지 아이콘 클릭 시 위치 설정
  messageIcon.parentElement.addEventListener('click', function () {
      setModalPosition(messageIcon, messageModalDialog);
  });


  //안읽은(새) 쪽지가 있을때, 쪽지 모달을 클릭했을때, tomembermessages 테이블의 is_read 컬럼값을 1로 바꿔서 읽음 처리!
  const messageModal = document.getElementById("messageModal");
  // 모달이 열릴 때 실행되는 이벤트
  messageModal.addEventListener("show.bs.modal", function () {
      // AJAX 요청으로 읽음 처리
      fetch("/qc/chat&message/update_read_status.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/json",
          },
          body: JSON.stringify({ receiver_id: <?= json_encode($memId); ?> }), //받는사람이 결국 memId과 같기떄문...
      })
          .then((response) => response.json())
          .then((data) => {
              if (data.success) {
                  console.log("쪽지 읽음 처리 완료");
              } else {
                  console.error("쪽지 읽음 처리 실패", data.error);
              }
          })
          .catch((error) => {
              console.error("AJAX 요청 오류:", error);
          });
  });

});

</script>