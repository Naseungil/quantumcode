<?php
$title = '수강평';
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$id = isset($_SESSION['AUID']) ? $_SESSION['AUID']  : $_SESSION['TUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}



$reply = '';

$review = '';
$review_sql = "SELECT * FROM lecture_review";
$review_result = $mysqli->query($review_sql);
while ($review_data = $review_result->fetch_object()) {
  $lrid = $review_data->lrid;
  $reply_sql = "SELECT * FROM lecture_reply WHERE lrid = $lrid";
  $reply_result = $mysqli->query($reply_sql);
  while ($reply_data = $reply_result->fetch_object()) {
    $reply .=
      "<div class=\"rereply d-flex gap-3 align-items-center mb-3 ml-3\">
        <div>
          <img src=\"../img/core-img/어드민_이미지.png\" alt=\"\">
        </div>
        <div>
          <h3>{$reply_data->t_id}</h3>
        </div>
        <form class=\"d-flex w-100\">
          <div class=\"w-100\">
            <p>{$reply_data->comment}</p>
            <textarea class=\" hidden form-control\">{$reply_data->comment}</textarea>
          </div>
            <div class=\"d-flex align-items-center gap-3 mx-3\">
              <button type=\"button\" class=\"btn btn-primary reply_edit\" data-id=\"{$reply_data->lpid}\">수정</button>
              <button type=\"button\" class=\"btn btn-danger reply_del\" data-id=\"{$reply_data->lpid}\">삭제</button>
            </div>
          </form>
        </div>";
  }
  $review .= "<div class=\"review d-flex gap-3 align-items-center mb-3\">
    <div>
      <img src=\"{$review_data->profile_image}\" width=\"50\" alt=\"\">
    </div>
    <div>
      <h5>{$review_data->username}</h5>
      <h6>{$review_data->regist_day}</h6>
      <img src=\"../img/icon-img/review.svg\" alt=\"\">
    </div>
    <div class=\"w-100\">
      <p class=\"w-100\">{$review_data->comment}</p>
    </div>
    <div class=\"mx-3\">
      <button class=\" btn btn-primary\" data-id=\"{$lrid}\">답글</button>
    </div>
  </div>
  $reply 
  <div class=\"reply hidden d-flex gap-3 align-items-center mb-3 ml-3\" data-id=\"{$lrid}\">
  </div>
  ";
  $reply = '';
}
?>

<?= $review ?>
<!-- <div class="reply d-flex gap-3 align-items-center mb-3 ml-3">
  <div>
    <img src="../img/core-img/어드민_이미지.png" alt="">
  </div>
  <div>
    <h3>admin</h3>
  </div>
  <form class="d-flex w-100">
    <div class="w-100">
      <textarea type="text" class="form-control " name="reply" id="reply"></textarea>
    </div>
    <div class=" mx-3">
      <button class=" btn btn-primary">작성</button>
    </div>
  </form>
</div> -->

<script>
  $('.content').on('click', '.review .btn', function() {
    let lrid = $(this).attr('data-id');
    let reply = `<div><img src="../img/core-img/어드민_이미지.png" alt=""></div><div><h3>admin</h3></div><form class="d-flex w-100" data-id="${lrid}"><div class="w-100"><textarea  class="form-control " name="reply" id="reply"></textarea></div><div class=" mx-3"><button class=" btn btn-primary">작성</button></div></form>`;
    if ($(`.reply[data-id="${lrid}"]`).hasClass('hidden')) {
      console.log('출력');

      $(`.reply[data-id="${lrid}"]`).removeClass('hidden');
      $(`.reply[data-id="${lrid}"]`).append(reply);
    } else {
      $(`.reply[data-id="${lrid}"]`).addClass('hidden');
      $(`.reply[data-id="${lrid}"]`).empty();
    }
  });
  $('.content').on('submit', '.reply form', function(e) {
    e.preventDefault();
    let lrid = $(this).closest('.reply').attr('data-id');
    let comment = $(this).find('textarea').val();

    let data = {
      lrid: lrid,
      comment: comment
    }
    console.log(data);

    $.ajax({
      url: 'lecture_reply.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      success: function(response) {
        if (response.result === 1) {
          alert('답글이 작성되었습니다.');
          location.reload(); // 페이지 새로고침
        } else {
          alert('작성 실패: ' + response.error);
        }
      },
      error: function(error) {
        console.error(error);
        alert('작성 중 오류가 발생했습니다.');
      }
    })
  });
  $('.content').on('click', '.reply_edit', function(e) {
    e.preventDefault();
    let lpid = $(this).attr('data-id');
    // let comment = $('.rereply textarea').val();
    let comment = $(this).closest('form').find('textarea').val();

    console.log(lpid, comment);
    console.log('출력');
    if ($(this).text() === '수정') {
      $(this).closest('form').find('textarea').removeClass('hidden');
      $(this).closest('form').find('p').addClass('hidden');
      $(this).text('작성');
    } else {
      let data = {
        lpid: lpid,
        comment: comment
      }
      $.ajax({
        url: 'lecture_reply_modify.php',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(response) {
          if (response.result === 1) {
            $(this).closest('form').find('textarea').addClass('hidden');
            $(this).closest('form').find('p').removeClass('hidden');
            alert('수정되었습니다');
            location.reload(); // 페이지 새로고침
          } else {
            alert('수정 실패: ' + response.error);
          }
        },
        error: function(error) {
          console.error(error);
          alert('수정 중 오류가 발생했습니다.');
        }
      })
      $(this).text('수정');
    }
  })

  $('.content').on('click', '.reply_del', function(e) {
    e.preventDefault();
    if (confirm('삭제하시겠습니까?')) {
      let lpid = $(this).attr('data-id');

      let data = {
        lpid: lpid,
      }

      $.ajax({
        url: 'lecture_reply_delete.php',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(response) {
          if (response.result === 1) {

            alert('삭제되었습니다');
            location.reload(); // 페이지 새로고침
          } else {
            alert('삭제 실패: ' + response.error);
          }
        },
        error: function(error) {
          console.error(error);
          alert('삭제 중 오류가 발생했습니다.');
        }
      })
    }
  })
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>