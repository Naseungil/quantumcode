<?php 

$title = "QnA";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');
// print_r($_SESSION); 잘나옴;
$user_id = $_SESSION['MemEmail'];

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지

if ($current_page < 1) $current_page = 1; // 페이지 범위 제한

// 총 게시물 수 계산
$total_items_sql = "SELECT COUNT(*) AS total FROM board WHERE category = 'qna'";
$total_items_result = $mysqli->query($total_items_sql);
$total_items_row = $total_items_result->fetch_assoc();
$total_items = $total_items_row['total'];

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);
if ($current_page > $total_pages) $current_page = $total_pages;

// 데이터 가져오기
$start_index = ($current_page - 1) * $items_per_page;
$sql = "SELECT pid, title, content, user_id, hit, likes, date FROM board WHERE category = 'qna' ORDER BY date DESC LIMIT ?, ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $start_index, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();
$qnas = $result->fetch_all(MYSQLI_ASSOC);

?>
<!-- 스타일 -->
<style>
  .modal-dialog {
    max-width: 700px; /* 원하는 너비로 변경 */
    width: 80%; /* 반응형으로 설정 */
  }

    /* 모달 외곽 스타일 */
  .modal-content {
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    border: none;
    overflow: hidden;
    min-height: 400px; /* 최소 높이 설정 */
    max-height: 80vh; /* 최대 높이 설정 */
    
  }

  /* 헤더 스타일 */
  .modal-header {
    background-color: #007bff;
    color: #fff;
    padding: 20px;
    border-bottom: none;
  }

  .modal-title {
    font-size: 1.5rem;
    font-weight: bold;
  }

  .btn-close {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 50%;
    opacity: 1;
  }

  /* 본문 스타일 */
  .modal-body {
    padding: 20px;
    background-color: #f9f9f9;
  }

  .form-label {
    font-weight: bold;
    color: #333;
  }

  .form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 1rem;
  }

  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }

  /* 버튼 스타일 */
  .btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    transition: background-color 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  /* 푸터 스타일 */
  .modal-footer {
    padding: 15px;
    background-color: #f1f1f1;
    border-top: none;
    text-align: right;
  }

  /* --- */
   
  #inquiryButton {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  #inquiryButton:hover {
    background-color: #0056b3;
  }

  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }

  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }
</style>

<div class="search_box">
  <div class="container">
    <h2><?= $title ?></h2>
    <form action="" class="d-flex">
      <input type="text" class="form-control" id="search_box" placeholder="검색어를 입력하세요.">
      <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>  
</div>

<div class="community container">
  <div class="row">
    <aside class="col-2 d-flex flex-column">
      <h6>커뮤니티</h6>
      <hr>
      <ul>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/notice.php"><li>공지사항<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/faq.php"><li>FAQ<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/qna.php" class="active"><li>QnA<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/board.php"><li>자유게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/study.php"><li>스터디 모집<i class="fa-solid fa-chevron-right"></i></li></a>
      </ul>
    </aside>
  
    <div class="qna content col-10">
      <div class="d-flex justify-content-between align-items-center">
        <h6>퀀텀코드에게 문의하세요.</h6>
        <!-- 문의하기 버튼 -->
        <button class="btn btn-primary" id="inquiryButton">문의하기</button>
      </div>
      <hr>
      <table class="table table-hover text-center">
        <thead>
          <tr>
            <th scope="col" class="num" style="width: 5%;">No</th>
            <th scope="col" style="width: 20%;">제목</th>
            <th scope="col" style="width: 30%;">내용</th>
            <th scope="col" style="width: 10%;">글쓴이</th>
            <th scope="col" style="width: 7.5%;">조회</th>
            <th scope="col" style="width: 7.5%;">답변</th>
            <th scope="col" style="width: 20%;">게시일</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($qnas as $index => $qna): ?>
          <tr>
            <th scope="row"><?= $total_items - $start_index - $index ?></th>
            <td class="post">
              <a href="#">
                <?= htmlspecialchars(mb_substr($qna['title'], 0, 11) . (mb_strlen($qna['title']) > 11 ? "..." : "")) ?>
              </a>
            </td>
            <td>
              <?= htmlspecialchars(mb_substr($qna['content'], 0, 18) . (mb_strlen($qna['content']) > 18 ? "..." : "")) ?>
            </td>
            <td><?= htmlspecialchars($qna['user_id']) ?></td>
            <td><?= htmlspecialchars($qna['hit']) ?></td>
            <td><?= htmlspecialchars($qna['likes']) ?></td>
            <td><?= htmlspecialchars($qna['date']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="#"><img src="../img/icon-img/CaretLeft.svg" alt=""></a></li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#"><img src="../img/icon-img/CaretRight.svg" alt=""></a></li>
        </ul>
      </nav>
    </div>
  </div>
</div>

<!-- 문의 모달 -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inquiryModalLabel">문의하기</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="inquiryForm" method="post">
          <div class="mb-3">
            <label for="inquiryTitle" class="form-label">문의 제목</label>
            <input type="text" class="form-control" id="inquiryTitle" name="title" placeholder="제목을 입력하세요" required>
          </div>
          <div class="mb-3">
            <label for="inquiryContent" class="form-label">문의 내용</label>
            <textarea class="form-control" id="inquiryContent" name="content" rows="10" placeholder="내용을 입력하세요" required></textarea>
          </div>
          <div class="mb-3 text-end">
            <button type="submit" class="btn btn-primary">제출하기</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // 모달 표시
  document.getElementById("inquiryButton").addEventListener("click", function () {
    const inquiryModal = new bootstrap.Modal(document.getElementById("inquiryModal"));
    inquiryModal.show();
  });

  // 폼 제출 이벤트
  document.getElementById("inquiryForm").addEventListener("submit", function (e) {
    e.preventDefault(); // 기본 동작 방지
    const formData = new FormData(this); // FormData로 폼 데이터 생성

    fetch("", {
      method: "POST",
      body: formData
    })
      .then(response => response.text())
      .then(result => {
        if (result.trim() === "success") {
          alert("문의가 성공적으로 제출되었습니다.");
          const inquiryModal = bootstrap.Modal.getInstance(document.getElementById("inquiryModal"));
          inquiryModal.hide(); // 모달 닫기
          location.reload(); // 페이지 새로고침
        } else {
          console.error(result); // 오류 메시지 확인
          alert("문의 제출 중 오류가 발생했습니다.1");
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("문의 제출 중 문제가 발생했습니다.2");
      });
  });
</script>

<?php
// 서버 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php'); // DB 연결

  // 데이터 가져오기
  $title = $_POST['title'] ?? '';
  $content = $_POST['content'] ?? '';
  $user_id = $_SESSION['MemEmail'] ?? 'guest'; // 세션 없을 경우 기본값 설정
  $category = 'qna';

  if (!empty($title) && !empty($content)) {
      // SQL 쿼리 작성
      $sql = "INSERT INTO board (title, content, user_id, category, date) VALUES (?, ?, ?, ?, NOW())";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("ssss", $title, $content, $user_id, $category);

      if ($stmt->execute()) {
          echo "success";
      } else {
          echo "DB error: " . $mysqli->error;
      }
      $stmt->close();
  } else {
      echo "Validation error: Fields cannot be empty.";
  }

  $mysqli->close();
  exit; // 응답 종료
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');