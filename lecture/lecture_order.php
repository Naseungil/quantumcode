<?php
$title = "강의 목록";

$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/lecture.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$userid = 5;
$total = 0;
$dataArr = [];
$lidArr = [];
// 현재 로그인한 userid 와 같은 것과 비교해서 목록을 출력 (임의로 홍길동)
$sql = "SELECT lc.*, ll.cover_image, ll.t_id, ll.title, ll.lid 
FROM lecture_cart lc
JOIN lecture_list ll
ON lc.lid = ll.lid
WHERE mid = $userid";
$result = $mysqli->query($sql);
while ($row = $result->fetch_object()) {
  $dataArr[] = $row;
  $total += $row->price;
  $lidArr[] = $row->lid;
}
$lid = implode(',', $lidArr);




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
$callnum = "0" . substr($user_data->number, 0, 2) . "-" . substr($user_data->number, 2, 4) . "-" . substr($user_data->number, 6);



?>
<div class="row">
  <div class="col-9">
    <div class="d-flex justify-content-between align-items-center order-head mb-3">
      <span class=""><img src="../img/icon-img/Check_Y.svg" alt="" class="mx-3"><strong class="w-100">전체선택</strong></span> <button class="btn btn-secondary sel_delete">선택 삭제</button>
    </div>
    <hr>
    <table class="table ">
      <thead>
        <tr class="visually-hidden">
          <th scope="col"><input type="checkbox" name="" id=""></th>
          <th scope="col">커버이미지</th>
          <th scope="col">강의 정보</th>
          <th scope="col">강의 가격</th>
        </tr>
      </thead>

      <tbody>
        <?php
        if (!empty($dataArr)) {
          foreach ($dataArr as $data) {
        ?>
            <tr>
              <th>
                <input type="checkbox" class="cart_check" name="l_check" id="l_check<?= $data->lid ?>" data-id="<?= $data->lid ?>" data-price="<?= $data->price ?>">
                <label for="l_check<?= $data->lid ?>" class="cart_label"></label>
              </th>
              <td><img src="<?= $data->cover_image ?>" width="150" alt=""></td>
              <td><?= $data->title ?></td>
              <td id="total_price"><?= number_format($data->price) ?> 원</td>
            </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-3 payment">
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
      <span class="font">결제 금액</span><span class="normal-font total_payment"> 0 원</span>
    </div>
    <div class="control m-3">
      <button type="button" class="payment_btn btn btn-primary w-100">결제하기</button>
    </div>
  </div>
</div>
<script>
  const paymentBtn = document.querySelector('.payment_btn');
  const coupon = document.querySelector('#coupon');
  let total_payment = document.querySelector('.total_payment').innerText;
  let numericValue = total_payment.replace(/[^0-9]/g, '');
  const lec_check = document.querySelectorAll('input[type="checkbox"]');
  const sel_delete = document.querySelector('.sel_delete');

  let checkArr = [];
  let priceArr = [];
  var sum_price = 0;
  let lid;
  let uctotal;

  // 결제 할때 fetch 함수를 통해 결제한 그 데이터를 저장
  paymentBtn.addEventListener('click', () => {
    const ucid = coupon.value;
    const mid = "<?= $userid ?>";
    // const lid = "<?= $lid ?>";
    // const total = numericValue;
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
      })
      .catch(error => {
        console.error('Error:', error); // 네트워크나 JSON 변환 에러 처리
      });
  })

  sel_delete.addEventListener('click', () => {
    if (lid) {
      console.log(lid);
      const data = new URLSearchParams({
        lid: lid
      });
      fetch('lecture_order_del.php', {
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

          if (result.status === 'success') {
            alert('데이터가 성공적으로 삭제되었습니다!');
            location.reload(); // 페이지 새로고침
          } else {
            alert('데이터 삭제에 실패했습니다: ' + result.message);
          }
        })
        .catch(error => {
          console.error('Error:', error); // 네트워크나 JSON 변환 에러 처리
        });
    }
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
  // 체크박스 클릭 시 데이터 저장
  lec_check.forEach(check => {
    check.addEventListener('change', (e) => {
      let check_id = e.target.getAttribute('data-id');
      let check_price = Number(e.target.getAttribute('data-price'));
      if (check.checked == 1) {
        sum_price += check_price;
        checkArr.push(check_id);
        console.log(sum_price);

      } else {
        checkArr = checkArr.filter(item => item !== check_id);
        sum_price -= check_price;
        console.log(sum_price);
      }
      lid = checkArr.join(',');

      document.querySelector('.total_payment').innerText = numberFormat(sum_price) + '원';
      // console.log(sum);
    })
  })
</script>



<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>