<?php
$title = '강의 보기';
$reset_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/reset.css\" rel=\"stylesheet\">";
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');



$userid = 5;

$tuition = '';

$lid = $_GET['lid'];

$sql = "SELECT l.*, t.name FROM lecture_list l LEFT JOIN teachers t ON l.t_id = t.id WHERE lid = $lid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();

if ($data->dis_tuition > 0) {
  $tui_val = number_format($data->tuition);
  $distui_val = number_format($data->dis_tuition);
  $tuition .= "<p class=\"text-decoration-line-through text-end \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
} else {
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

// $buy_sql = "SELECT * FROM lecture_order WHERE lid LIKE '%$lid%' AND mid = $userid";

// $buy_result = $mysqli->query($buy_sql) ;
// if($buy_result->num_rows > 0)
// {
//   $buy_data = $buy_result->fetch_object();

// }



$couponArr = [];
$coupon_sql = "SELECT cu.*, c.*  
FROM coupons_usercp cu
JOIN coupons c
ON c.cid = cu.couponid
WHERE cu.status = 1
AND c.status = 1
AND cu.userid = $userid
AND cu.use_max_date >=now() ";
$coupon_result = $mysqli->query($coupon_sql);
while ($coupon_row = $coupon_result->fetch_object()) {
  $couponArr[] = $coupon_row;
}


$user_sql = "SELECT * FROM members WHERE mid = $userid";
$user_result = $mysqli->query($user_sql);
$user_data = $user_result->fetch_object();
$callnum = substr($user_data->number, 0, 3) . "-" . substr($user_data->number, 3, 4) . "-" . substr($user_data->number, 7);


?>

<section class="info">
  <div>
    <div class="catogory mb-1 ">
      <p class="small-font"><?= $ppcode_name . ' / ' . $pcode_name . ' / ' . $cate_data->name ?></p>
    </div>
    <div class="title mb-2">
      <h4 class="normal-font"><?= $data->title ?></h4>
      <p class="name text-decoration-underline"><?= $data->name ?></p>
    </div>
    <div class="learnObj">
      <h6>학습 목표</h6>
      <p class="small-font"><?= $data->learning_obj ?></p>
    </div>
  </div>
  <ul>
    <li class=""> <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/review.svg" alt=""> 5점 <span class="text-decoration-underline small-font">수강평 보기</span></li>
    <li class="like"><img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/Heart.svg" alt="">500+</li>
    <li class="tag"><?= !empty($data->lecture_tag) ? "<span> {$data->lecture_tag}</span>" : '' ?> </li>
  </ul>
</section>
<section class="desc row mt-5">
  <div class="col-8">
    <h3 class="subtitle mb-5"><?= $data->sub_title ?></h3>
    <hr>
    <p class="description mb-5"><?= $data->description ?></p>
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
    <div class="control m-3 d-flex flex-column gap-3">
      <?php
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
      ?>
    </div>
  </div>
</aside>
<div class="modal fade" id="paybtn" tabindex="-1" aria-labelledby="directPay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="directPay">바로 결제하기</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl>
          <dt>신청자</dt>
          <dd><?= $user_data->name ?></dd>
          <dt>이메일</dt>
          <dd><?= $user_data->email ?></dd>
          <dt>전화번호</dt>
          <dd><?= $callnum ?></dd>

          <dt>쿠폰</dt>
          <dd>
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
          </dd>
        </dl>
        <div class="d-flex justify-content-between">
          <span class="font">결제 금액</span><span data-price="<?= $tui_val ?>" class="normal-font total_payment"> <?= $tui_val ?> 원</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
        <button type="button" class="btn btn-primary payment_btn">결제하기</button>
      </div>
    </div>
  </div>
</div>


<div class="d-flex gap-3 justify-content-end lecture_button">
  <a href="lecture_list.php" class=" btn btn-secondary insert">목록</a>
</div>
<script>
  const paymentBtn = document.querySelector('.payment_btn');
  const coupon = document.querySelector('#coupon');
  let total_payment = document.querySelector('.total_payment').innerText;
  let numericValue = total_payment.replace(/[^0-9]/g, '');
  var sum_price = numericValue;
  let uctotal;

  // 결제 할때 fetch 함수를 통해 결제한 그 데이터를 저장
  paymentBtn.addEventListener('click', () => {
    const ucid = coupon.value;
    const mid = "<?= $userid ?>";
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
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>