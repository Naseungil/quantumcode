<?php
$title = '강사 상세 페이지';
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


$tid = $_GET['tid'];
if (!isset($tid)) {
  echo "<script>alert('관련 정보가 없습니다.'); location.href = '../teachers/teacher_list.php';</script>";
}

$sql = "SELECT * FROM teachers WHERE tid = $tid";  //여기서 tid는 숫자.
$result = $mysqli->query($sql); //쿼리 실행 결과
$data = $result->fetch_object();


$sql2 = "SELECT * FROM lecture_list WHERE t_id = '$data->id'";
// echo $sql2;
$lecture_result = $mysqli->query($sql2); //쿼리 실행 결과
// $lecture_data = $lecture_result->fetch_object()
while($lecture_data = $lecture_result->fetch_object()){
  $lecture_dataArr[] = $lecture_data;
}
$lecture_dataArr = array_slice($lecture_dataArr, 0, 6); //총 6개만 보여줌


// $sql3 = "SELECT * FROM coupons_usercp WHERE userid = '$data->id'";
// $coupon_result = $mysqli->query($sql3); //쿼리 실행 결과
// while($coupon_data = $coupon_result->fetch_object()){
//   $coupon_dataArr[] = $coupon_data;
// }
// $coupon_dataArr = array_slice($coupon_dataArr, 0, 6); //총 6개만 보여줌
// print_r($coupon_dataArr)



?>

<div class="container">
  <div class="row teacher">
    <div class="col-3 mb-5">
      <div class="teacher_coverImg2 mb-3">
        <img src="<?= $data->cover_image; ?>" id="coverImg" alt="" width="200" height="200" style="object-fit: cover; border-radius: 25%;">
      </div>
      <div>
        <h5>이름 : <?= $data->name; ?></h5>
        <h6>아이디  : <?= $data->id; ?></h6>
      </div>
      <hr>
      <div class="d-flex justify-content-center align-items-center gap-5">
        <div class="text-center">
          <p>총 수강생 수</p>
          <p><?= $data->student_number ?></p>
        </div>
        <div class="text-center">
          <p>수강평 수</p>
          <p> </p>
        </div>
        <div class="text-center">
          <p>강의 평점</p>
          <p>4.6</p>
        </div>
      </div>
      <hr>
      <nav>
        <ul>
          <?php if (isset($data)) { ?> 
          <li class="my-2">
            <a href="teacher_view.php?tid=<?= $data->tid;?>" class="text-decoration-none">홈</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none"  id="nav_all_lectures">모든 강의</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none" id="nav_personal_info">개인 정보</a>
          </li>
          <?php } ?> 
        </ul>
      </nav>
    </div>
    <div class="col-9 mb-3" id="main_content">
      <table class="table">
        <h4>강사 소개글</h4>
        <tbody>
          <tr>
            <textarea disabled><?= $data->teacher_detail; ?></textarea>
          </tr>
        </tbody>
      </table>
      <hr>
      <?php if (isset($lecture_dataArr)) { ?>
      <h4>현재 진행 중인 강의 TOP6</h4>
      <div class="d-flex flex-wrap justify-content-center">
        <?php foreach ($lecture_dataArr as $item) { ?>
          <div class="card m-3 shadow-lg" style="width: 18rem; border-radius: 15px; overflow: hidden;">
            <!-- 이미지 섹션 -->
            <img 
              class="card-img-top" 
              src="<?= $item->cover_image ?>" 
              alt="Lecture Cover Image" 
              style="height: 12rem; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <!-- 카드 본문 -->
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-primary fw-bold text-truncate"><?= $item->title ?></h5>

              <a href="/qc/admin/lecture/lecture_view.php?lid=<?= $item->lid; ?>" 
                class="btn btn-primary mt-auto w-100" 
                style="border-radius: 10px;">
                해당 강의 보러가기
              </a>
            </div>
          </div>
        <?php } ?>
      </div>
      <?php } ?>
      <div class="d-flex justify-content-center mt-4">
        <button id="show_all_lecture" class="btn btn-primary">모든 강의 보기</button>
      </div>
      <hr>
      <div class="container mt-4" id="personal_info">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">강사 개인 정보</h4>
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
                    <input type="text" class="form-control" name="name" id="name" placeholder="<?= $data->tid; ?>" disabled>
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
          <a href="teacher_update.php?tid=<?= $data->tid; ?>" class="btn btn-primary btn-md">수정하기</a>
        </div>
      </Form>
    </div>
  </div>
</div>

<script>
  // 클릭 시 active 클래스 추가
  const navLinks = document.querySelectorAll('nav ul li a');
  navLinks.forEach((link) => {
    link.addEventListener('click', () => {
      // 모든 링크에서 active 클래스 제거
      navLinks.forEach((l) => l.classList.remove('active'));
      // 클릭한 링크에 active 클래스 추가
      link.classList.add('active');
    });
  });


    // "모든 강의 보기" 버튼 클릭 이벤트
    $('#show_all_lecture').click(function () {
        const tid = <?= json_encode($tid) ?>; // 현재 페이지의 tid 가져오기
        $(this).hide();
        // Ajax 요청
        $.ajax({
          //async, url, data(여기선 필요없을듯), type(여기선 필요없을듯), dataType 등이 요구됌
            async:false,
            url: `/qc/admin/teachers/get_all_lectures.php?tid=${tid}`,
            method: 'GET',
            dataType: 'json',
            error: function (xhr, status, error) {
                console.error('Error fetching lectures:', error);
                alert('강의를 가져오는 중 오류가 발생했습니다.');
            },
            success: function (data) {
              console.log(data);
                if (data.error) {
                    alert(`Error: ${data.error}`);
                    return;
                }
                // 강의 데이터 렌더링
                const $lectureContainer = $('.d-flex.flex-wrap');
                $lectureContainer.empty(); // 기존 강의 삭제

                $.each(data.result, function (index, item) {
                    const lectureCard = `
                        <div class="card m-3 shadow-lg" style="width: 18rem; height: 22rem; border-radius: 15px; overflow: hidden;">
                            <img class="card-img-top" src="${item.cover_image}" alt="강의 이미지" style="height: 12rem; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary fw-bold">${item.title}</h5>
                                <p class="card-text text-muted">${item.description}</p>
                                <a href="/qc/admin/lecture/lecture_view.php?lid=${item.lid}" class="btn btn-primary mt-auto" style="border-radius: 10px;">
                                    해당 강의 보러가기
                                </a>
                            </div>
                        </div>`;
                    $lectureContainer.append(lectureCard);
                });
            },
        });
    });

  //모든강의 ajax로 가져오기
  $('#nav_all_lectures').click(function (e) {
    e.preventDefault(); // 기본 동작 막기
    const tid = <?= json_encode($tid) ?>; // 현재 페이지의 tid 가져오기

    $('#main_content').empty(); // 기존 콘텐츠 숨기기.내용을 지우거나 .hide()로 숨길 수도 있음

    // Ajax 요청
    $.ajax({
        async: false,
        url: `/qc/admin/teachers/get_all_lectures.php?tid=${tid}`,
        method: 'GET',
        dataType: 'json',
        error: function (xhr, status, error) {
            console.error('Error fetching lectures:', error);
            alert('강의를 가져오는 중 오류가 발생했습니다.');
        },
        success: function (data) {
            console.log(data);
            if (data.error) {
                alert(`Error: ${data.error}`);
                return;
            }

            // 새로운 강의 데이터를 렌더링
            const $lectureContainer = $('<div class="d-flex flex-wrap justify-content-center"></div>'); // 새로운 컨테이너 생성
            $.each(data.result, function (index, item) {
                const lectureCard = `
                    <div class="card m-3 shadow-lg" style="width: 18rem; height: 22rem; border-radius: 15px; overflow: hidden;">
                        <img class="card-img-top" src="${item.cover_image}" alt="강의 이미지" style="height: 12rem; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary fw-bold">${item.title}</h5>
                            <p class="card-text text-muted">${item.description}</p>
                            <a href="/qc/admin/lecture/lecture_view.php?lid=${item.lid}" class="btn btn-primary mt-auto" style="border-radius: 10px;">
                                해당 강의 보러가기
                            </a>
                        </div>
                    </div>`;
                $lectureContainer.append(lectureCard);
            });

            // main_content 영역에 새로운 데이터를 추가
            $('#main_content').append($lectureContainer);
        },
    });
});



///////////////////////////////////////////////////////////////////
//개인정보에서 자기소개만 가져오기
$('#nav_personal_info').click(function (e) {
    e.preventDefault(); // 기본 동작 막기

    // 기존 콘텐츠 비우기
    $('#main_content').empty();

    // personal_info HTML 추가
    $('#main_content').html(`
        <div class="container mt-4" id="personal_info">
            <div class="card shadow-sm">
              <div class="card-body">
                <h4 class="card-title text-center mb-4">강사 개인 정보</h4>
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
                        <input type="text" class="form-control" name="name" id="name" placeholder="<?= $data->tid; ?>" disabled>
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
          <a href="teacher_update.php?tid=<?= $data->tid; ?>" class="btn btn-primary btn-md">수정하기</a>
        </div>
      </Form>
    `);
});









    

</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>