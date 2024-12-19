<?php
$title = '회원 상세 정보';
$teacher_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/teacher.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

if(!isset($_SESSION['AUID'])){
    echo "
      <script>
        alert('관리자로 로그인해주세요');
        location.href = '../index.php';
      </script>
    ";
}
$memId = $_GET['memId'];
if (!isset($memId)) {
  echo "<script>alert('관련 정보가 없습니다.'); location.href = '../members/member_list.php';</script>";
}

$sql = "SELECT * FROM membersKakao WHERE memId = $memId";  //여기서 memId는 숫자.
$result = $mysqli->query($sql); //쿼리 실행 결과
$data = $result->fetch_object();



?>



<div class="container">
  <div class="row teacher">
    <div class="col-3 mb-5">
      <div class="teacher_coverImg2 mb-3">
        <img src="<?= $data->memProfilePath; ?>" id="coverImg" alt="" width="200" height="200" style="object-fit: cover; border-radius: 25%;">
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
          <?php if (isset($data)) { ?> 
          <li class="my-2">
            <a href="teacher_view.php?memId=<?= $data->memId;?>" class="text-decoration-none">홈</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none"  id="nav_all_lectures">수강 중인 강의</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none" id="nav_personal_info">개인 정보</a>
          </li>
          <?php } ?> 
        </ul>
      </nav>
    </div>
    <div class="col-9 mb-3" id="main_content">
      <?php if (isset($lecture_dataArr)) { ?>
      <h4>현재 진행 중인 강의 TOP4</h4>
      <div class="d-flex flex-wrap"> <!-- Flex 컨테이너 justify-content-center -->
        <?php foreach ($lecture_dataArr as $item) { ?> 
        <div class="card m-3 shadow-lg" style="width: 18rem; height: 22rem; border-radius: 15px; overflow: hidden;"> 
          <!-- 이미지 섹션 -->
          <img class="card-img-top" src="<?= $item->cover_image ?>" alt="Card image cap" style="height: 12rem; object-fit: cover;">
          <!-- 카드 본문 -->
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-primary fw-bold"><?= $item->title ?></h5>
            <p class="card-text text-muted"><?= $item->description ?></p>
            <a href="/qc/admin/lecture/lecture_view.php?lid=<?= $item->lid; ?>" class="btn btn-primary mt-auto" style="border-radius: 10px;">
              해당 강의 보러가기
            </a>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <div class="d-flex justify-content-center mt-4">
        <button id="show_all_lecture" class="btn btn-primary">모든 수강중인 강의 보기</button>
      </div>
      <hr>
      <div class="container mt-4" id="personal_info">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">회원 개인 정보</h4>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th scope="col" class="text-center">구분</th>
                  <th scope="col" class="text-center">내용</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">회원 고유 ID</th>
                  <td>
                    <input type="text" class="form-control" name="name" id="name" placeholder="<?= $data->memId; ?>" disabled>
                  </td>
                </tr>
                <tr>
                  <th scope="row">이름</th>
                  <td>
                    <input type="text" class="form-control" name="name" id="name" placeholder="<?= $data->name; ?>" disabled>
                  </td>
                </tr>
                <tr>
                  <th scope="row">아이디</th>
                  <td>
                    <input type="text" class="form-control" name="id" id="id" placeholder="<?= $data->id; ?>" disabled>
                  </td>
                </tr>
                <tr>
                  <th scope="row">생년월일</th>
                  <td>
                    <input type="text" class="form-control" name="birth" id="birth" placeholder="<?= $data->birth; ?>" disabled>
                  </td>
                </tr>
                <tr>
                  <th scope="row">이메일</th>
                  <td>
                    <input type="text" class="form-control" name="email" id="email" placeholder="<?= $data->email; ?>" disabled>
                  </td>
                </tr>
                <tr>
                  <th scope="row">전화번호</th>
                  <td>
                    <input type="text" class="form-control" name="number" id="number" placeholder="<?= $data->number; ?>" disabled>
                  </td>
                </tr>
                <tr>
                  <th scope="row">가입일</th>
                  <td>
                    <input type="text" class="form-control" name="reg_date" id="reg_date" placeholder="<?= $data->reg_date; ?>" disabled>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <Form action="" id="teacher_save" method="" enctype="multipart/form-data">
        <div class="mt-3 d-flex justify-content-end">
          <a href="teacher_update.php?memId=<?= $data->memId; ?>" class="btn btn-primary btn-md">수정하기</a>
        </div>
      </Form>
    </div>
  </div>
</div>


<script>


</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php'); ?>