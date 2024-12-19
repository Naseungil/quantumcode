<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

if (!isset($_SESSION['MemId'])) {
    echo "
        <script>
            alert('회원으로 로그인해주세요');
            location.href = '../index.php';
        </script>
    ";
}

$MemId = $_SESSION['MemId']; // print_r($MemId); 

$sql = "SELECT * FROM membersKakao WHERE memId = $MemId"; 
$result = $mysqli->query($sql); // 쿼리 실행 결과
$data = $result->fetch_object();

?>

<div class="container mt-5">
  <div class="row">
    <div class="col-3 mb-5">
      <div class="member_coverImg2 mb-3">
        <img src=" <?= $data->memProfilePath ?? '../img/icon-img/no-image.png'; ?>" id="coverImg" alt="" width="80" height="80" style="object-fit: cover; border-radius: 25%; border: 2px solid #ccc;">
      </div>
      <div>
        <h5>이름 : <?= $data->memName; ?></h5>
        <h6>아이디  : <?= $data->memEmail; ?></h6>
      </div>
      <hr>
      <div class="d-flex justify-content-center align-items-center gap-5">
        <div class="text-center">
          <p>총 강의 수</p>
          <p></p>
        </div>
        <div class="text-center">
          <p>평균 진도율</p>
          <p> </p>
        </div>
        <div class="text-center">
          <p>강의 평점</p>
          <p></p>
        </div>
      </div>
      <hr>
      <nav>
        <ul>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="dashboard.php" id="myDashboard">대시보드</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myLectures.php" id="myLectures">수강 중인 강의</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myInfo.php" id="myInfo">개인 정보</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myCoupons.php" id="myCoupons">쿠폰</a>
          </li>
        </ul>
      </nav>
    </div>

    <div class="col-9 mb-3" id="main_content">
      <h4>메뉴를 클릭하면 해당 내용이 여기에 표시됩니다.</h4>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".load-page"); // 모든 메뉴 링크
    const mainContent = document.getElementById("main_content"); // 콘텐츠 영역

    links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault(); // 기본 링크 동작 방지
            const page = this.getAttribute("data-page"); // data-page 속성 가져오기

            if (page) {
                // 페이지 로드 AJAX
                fetch(page)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("네트워크 접속 안됌");
                        }
                        return response.text(); // 응답 텍스트 변환
                    })
                    .then(html => {
                        mainContent.innerHTML = html; // 로드한 콘텐츠 삽입
                    })
                    .catch(error => {
                        console.error("에러 로딩 :", error);
                        mainContent.innerHTML = "<p>콘텐츠를 로드할 수 없습니다. 나중에 다시 시도해주세요.</p>";
                    });
            }
        });
    });
});
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>