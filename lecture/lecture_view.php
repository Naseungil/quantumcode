<?php
$title = '강의 보기';
$reset_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/reset.css\" rel=\"stylesheet\">";
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/lecture.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');


$tuition = '';

$lid = $_GET['lid'];

$sql = "SELECT l.*, t.name FROM lecture_list l LEFT JOIN teachers t ON l.t_id = t.id WHERE lid = $lid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();

if ($data->dis_tuition > 0) {
  $value = number_format($data->dis_tuition);
  $tui_val = number_format($data->tuition);
  $distui_val = number_format($data->dis_tuition);
  $tuition .= "<p class=\"text-decoration-line-through text-end \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
} else {
  $value = number_format($data->tuition);
  $tui_val = number_format($data->tuition);
  $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p>";
}


$lcid = $data->lcid;
$cate_sql = "SELECT * FROM lecture_category WHERE lcid = $lcid";
if ($cate_result = $mysqli->query($cate_sql)) {
  $cate_data = $cate_result->fetch_object();
  $pcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$cate_data->pcode}' AND pcode = '{$cate_data->ppcode}'";
  $ppcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$cate_data->ppcode}'";

  $pcode_result = $mysqli->query($pcode_name_sql);
  $ppcode_result = $mysqli->query($ppcode_name_sql);

  $pcode_name = ($pcode_result && $pcode_result->num_rows > 0) ? $pcode_result->fetch_object()->name : "Unknown";
  $ppcode_name = ($ppcode_result && $ppcode_result->num_rows > 0) ? $ppcode_result->fetch_object()->name : "Unknown";
}

switch ($data->difficult) {
  case 0:
    $diff = ' ';
    break;
  case 1:
    $diff = '입문';
    break;
  case 2:
    $diff = '초급';
    break;
  case 3:
    $diff = '중급';
    break;
  case 4:
    $diff = '고급';
    break;
  case 5:
    $diff = '전문';
    break;
}

$buy_sql = "SELECT * FROM lecture_order WHERE lid LIKE '%$lid%' AND mid = '$email'";

$buy_result = $mysqli->query($buy_sql);
if ($buy_result && $buy_result->num_rows > 0) {
  $buy_data = $buy_result->fetch_object();
  // 데이터가 있는 경우 추가 작업 수행
} else {
  $buy_data = '';
  // 데이터가 없는 경우 추가 작업 수행 (필요하면 이곳에 처리 로직 추가)
}

$couponArr = [];
$coupon_sql = "SELECT cu.*, c.*  
FROM coupons_usercp cu
JOIN coupons c
ON c.cid = cu.couponid
WHERE cu.status = 1
AND c.status = 1
AND cu.userid = '$email'
 ";
//  AND cu.use_max_date >=now()
$coupon_result = $mysqli->query($coupon_sql);
while ($coupon_row = $coupon_result->fetch_object()) {
  $couponArr[] = $coupon_row;
}

$user_sql = "SELECT * FROM memberskakao WHERE memEmail = '$email'";
$user_result = $mysqli->query($user_sql);
$user_data = $user_result->fetch_object();
if (isset($user_data->number)) {
  $callnum = substr($user_data->number, 0, 3) . "-" . substr($user_data->number, 3, 4) . "-" . substr($user_data->number, 7);
} else {
  $callnum = 0;
}

$reply = '';

$review = '';
$review_sql = "SELECT * FROM lecture_review WHERE lid = $lid";
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
        <div>
          <div class=\"w-100 d-flex \">
              <p>{$reply_data->comment}</p>
            </div>
        </div>
      </div>";
  }
  if ($review_data->username == $memName) {

    $edit = "<div class=\"d-flex align-items-center gap-3 mx-3\">
        <button type=\"button\" class=\"btn btn-primary review_edit\" data-id=\"{$review_data->lrid}\">수정</button>
        <button type=\"button\" class=\"btn btn-danger review_del\" data-id=\"{$review_data->lrid}\">삭제</button>
      </div>";
  } else {
    $edit = '';
  }
  $review .= "
  <div class=\"review d-flex gap-3 align-items-center mb-3\">
    <div>
      <img src=\"{$review_data->profile_image}\" width=\"50\" alt=\"\">
    </div>
    <div>
      <h5>{$review_data->username}</h5>
      <h6>{$review_data->regist_day}</h6>
      <img src=\"../img/icon-img/review.svg\" alt=\"\">
    </div>
    <form class=\"d-flex w-100\">
      <div class=\"w-100\">
      <p>{$review_data->comment}</p>
      <textarea class=\" hidden form-control\">{$review_data->comment}</textarea>
      </div>
      {$edit}
    </form>
  </div>
  $reply 
  ";
  $reply = '';
}

?>


<div class="wrapper">
  <section class="info ">
    <div class="container">
      <div class="catogory mb-1 ">
        <p class="small-font"><?= $ppcode_name . ' / ' . $pcode_name . ' / ' . $cate_data->name ?></p>
      </div>
      <div class="title mb-2">
        <h4 class="normal-font"><?= $data->title ?></h4>
        <p class="name text-decoration-underline"><?= $data->name ?></p>
      </div>
      <div class="learnObj mb-5">
        <h6>학습 목표</h6>
        <p class="small-font"><?= $data->learning_obj ?></p>
      </div>
      <ul>
        <li class=""> <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/review.svg" alt=""> 5점 <span class="text-decoration-underline small-font">수강평 보기</span></li>
        <li class="like"><img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/Heart.svg" alt="">500+</li>
        <li class="tag"><?= !empty($data->lecture_tag) ? "<span> {$data->lecture_tag}</span>" : '' ?> </li>
      </ul>
    </div>
  </section>

  <aside>
    <div class="lecture_coverImg">
      <img src="<?= $data->cover_image ?>" alt="">
    </div>
    <div class="tuition">
      <div class="tuitionInfo">
        <h4>수강료</h4>
        <div>
          <?= $tuition ?>
        </div>
      </div>
      <div class="asideDesc">
        <dl class="tuitionDesc">
          <dt>강의시간</dt>
          <dd>2시간 40분</dd>
        </dl>
        <dl class="tuitionDesc">
          <dt>난이도</dt>
          <dd><?= $diff ?></dd>
        </dl>
        <dl class="tuitionDesc">
          <dt>등록일</dt>
          <dd><?= $data->regist_day ?></dd>
        </dl>
        <dl class="tuitionDesc">
          <dt>마감일</dt>
          <dd><?= $data->expiration_day ?></dd>
        </dl>
      </div>
      <div class="control m-3 d-flex flex-column justify-content-center gap-3">
        <?php
        if (isset($user_data)) {
          if (!$buy_data) {
        ?>
            <button type="button" class=" btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#paybtn">결제하기</button>
            <a href="lecture_cart.php?lid=<?= $lid ?>" class="btn btn-secondary w-100">담기</a>
          <?php
          } else {
          ?>
            <a href="lecture_read.php?lid=<?= $lid ?>" class="btn btn-primary w-100">학습하기</a>
          <?php
          }
        } else {
          ?>
          <p class="loginMessage">로그인 후 이용해주세요</p>
        <?php
        }
        ?>
      </div>
    </div>
  </aside>

</div>
<div class="container view">
  <section class="desc row mt-5">
    <div class="col-8">
      <h3 class="subtitle mb-5"><?= $data->sub_title ?></h3>
      <hr>
      <p class="description mb-5"><?= $data->description ?></p>
      <hr>
    </div>
  </section>


  <?php
  if (!empty($data->pr_video) && $data->pr_video !== "Array") {
  ?>
    <div class="preview_video">
      <h5>미리보기</h5>
      <video src="<?= $data->pr_video ?>" controls muted></video>
      <hr>
    </div>

  <?php
  }
  ?>
  <div class="lecture_review row">
    <?= $review ?>
    <?php
    if (isset($email) && $email !== '') {
    ?>
      <form class="review d-flex gap-3 align-items-center mb-3 ml-3 col-8">
        <div>
          <img src="../img/icon-img/UsersFour.svg" width="50" alt="">
        </div>
        <div class="name">
          <h5><?= $memName ?></h5>
        </div>
        <div class="d-flex w-100">
          <div class="w-100">
            <textarea type="text" class="form-control " name="review" id="review"></textarea>
          </div>
          <div class=" mx-3">
            <button class=" btn btn-primary">작성</button>
          </div>
        </div>
      </form>
    <?php
    }
    ?>
  </div>

  <div class="list_control d-flex gap-3 justify-content-end lecture_button">
    <a href="lecture_list.php" class=" btn btn-secondary insert">목록</a>
  </div>
</div>
<div class="modal fade view" id="paybtn" tabindex="-1" aria-labelledby="directPay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="directPay">바로 결제하기</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li>
            <span>신청자</span>
            <strong><?= $user_data->memName ?></strong>
          </li>
          <li>
            <span>이메일</span>
            <strong><?= $user_data->memEmail ?></strong>
          </li>
          <li>
            <span>전화번호</span>
            <strong><?= $callnum ?></strong>
          </li>
        </ul>
        <div>

          <div>쿠폰</div>
          <div class="mb-3">
            <select class="form-select" name="coupon" id="coupon">
              <option value="0" selected>쿠폰 선택</option>
              <?php
              if (!empty($couponArr)) {
                foreach ($couponArr as $coupon) {
                  $price = 0;
                  if ($coupon->coupon_type === 'fixed') {
                    $price = $coupon->coupon_price;
                  } else {
                    $price = $coupon->coupon_ratio;
                  }
              ?>
                  <option value="<?= $coupon->ucid ?>" data-price="<?= $price ?>"><?= $coupon->coupon_name ?> </option>
              <?php
                }
              }
              ?>
            </select>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <b class="font">결제 금액</b><b data-price="<?= $value ?>" class="normal-font total_payment"> <?= $value ?> 원</b>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
        <button type="button" class="btn btn-primary payment_btn">결제하기</button>
      </div>
    </div>
  </div>
</div>

<script>
  const paymentBtn = document.querySelector('.payment_btn');
  const coupon = document.querySelector('#coupon');
  let tuition = document.querySelector('.tuition');
  let tuitionOst = tuition.offsetTop;
  let total_payment = document.querySelector('.total_payment').innerText;
  let numericValue = total_payment.replace(/[^0-9]/g, '');
  var sum_price = numericValue;
  let uctotal;

  // 결제 할때 fetch 함수를 통해 결제한 그 데이터를 저장
  paymentBtn.addEventListener('click', () => {
    const ucid = coupon.value;
    const mid = "<?= $email ?>";
    const lid = "<?= $lid ?>";
    const total = numericValue;
    console.log(mid, lid, sum_price);
    const data = new URLSearchParams({
      ucid: ucid,
      lid: lid,
      mid: mid,
      total_price: sum_price,
    });
    fetch('lecture_payment.php', {
        method: 'post',
        body: data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then(result => {
        console.log('Success:', result);
        location.reload();
      })
      .catch(error => {
        console.error('Error:', error); // 네트워크나 JSON 변환 에러 처리
      });
  })

  // 쿠폰 목록을 선택한다면 결제금액에 반영
  coupon.addEventListener('change', (e) => {
    let ucid = e.target.value;
    let ucprice = e.target.options[e.target.selectedIndex].getAttribute('data-price');
    if (ucid > 0) {
      sum_price -= Number(ucprice);
      uctotal = ucprice;
    } else {
      sum_price += Number(uctotal);
      uctotal = null;
    }
    document.querySelector('.total_payment').innerText = numberFormat(sum_price) + '원';
    console.log(ucid, ucprice, uctotal);
  })

  // 천자리 마다 , 해주는 함수
  function numberFormat(number, thousandSeparator = ',') {
    const integerPart = Math.floor(number).toString(); // 정수 부분만 처리
    return integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
  }

  //스크롤 이벤트 ( aside )
  window.addEventListener('scroll', () => {
    if (tuitionOst < window.scrollY - 50) {
      tuition.classList.add('sticky');
    } else {
      tuition.classList.remove('sticky');
    }
  });
  //수강평 작성
  $('.lecture_review form').on('submit', function(e) {
    e.preventDefault();
    let lid = <?= $lid ?>;
    let username = '<?= $memName ?>';
    let comment = $(this).find('textarea').val();
    let img = $(this).find('img').attr('src');

    let data = {
      lid: lid,
      username: username,
      img: img,
      comment: comment
    }
    console.log(data);

    $.ajax({
      url: 'lecture_review.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      success: function(response) {
        if (response.result === 1) {
          alert('수강평이 작성되었습니다.');
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
  $('.lecture_review .review_edit').on('click', function(e) {
    e.preventDefault();
    let lrid = $(this).attr('data-id');
    // let comment = $('.rereply textarea').val();
    let comment = $(this).closest('form').find('textarea').val();
    if ($(this).text() === '수정') {
      $(this).closest('form').find('textarea').removeClass('hidden');
      $(this).closest('form').find('p').addClass('hidden');
      $(this).text('작성');
    } else {
      let data = {
        lrid: lrid,
        comment: comment
      }
      $.ajax({
        url: 'lecture_review_modify.php',
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
  $('.lecture_review .review_del').on('click', function(e) {
    e.preventDefault();
    if (confirm('삭제하시겠습니까?')) {
      let lrid = $(this).attr('data-id');

      let data = {
        lrid: lrid,
      }

      $.ajax({
        url: 'lecture_review_delete.php',
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
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>