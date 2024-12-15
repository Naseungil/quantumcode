<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

//첫 로그인 한 회원에게만 쿠폰이 발급되어야 한다. -> 헤더에서 해결
//발급되는 쿠폰은 coupons테이블의 cid = 1 이면서
//$email = $_SESSION['MemEmail'];값을 아이더처럼 사용할것이다.
//발급된 쿠폰은 coupons_usercp 에 발급된 데이터를 저장할 것이다.

if (isset($_SESSION['MemEmail'])) {
  $email = $_SESSION['MemEmail'];  //이메일을 id로 생각하자
  $memId = $_SESSION['MemId'];     

  // coupons 테이블에서 1번 쿠폰 가져오기
  $cid = 1;
  $coupon_sql = "SELECT * FROM coupons WHERE cid = ?";
  $stmt = $mysqli->prepare($coupon_sql);
  $stmt->bind_param("i", $cid);
  $stmt->execute();
  $result = $stmt->get_result();
  $coupon = $result->fetch_assoc(); // 쿠폰 데이터 가져오기
  $stmt->close();

  // 2. 쿠폰 정보가 존재하면 coupons_usercp 테이블에 저장
  if ($coupon) {
    // 이미 쿠폰이 발급된 회원인지 확인
    $check_sql = "SELECT * FROM coupons_usercp WHERE user_id = ? AND cid = ?";
    $stmt = $mysqli->prepare($check_sql);
    $stmt->bind_param("si", $email, $cid); 
    $stmt->execute();
    $check_result = $stmt->get_result();
    $stmt->close();

    //0개면 발급된 게 없으므로 쿠폰을 발급할 수 있게 해준다
    if ($check_result->num_rows === 0) {
        // 쿠폰 발급 처리 (coupons_usercp 테이블에 삽입)
        $insert_sql = "INSERT INTO coupons_usercp (user_id, cid, regdate) VALUES (?, ?, NOW())";
        $stmt = $mysqli->prepare($insert_sql);
        $stmt->bind_param("si", $email, $cid);

        if ($stmt->execute()) {
            echo "쿠폰이 성공적으로 발급되었습니다.";
        } else {
            echo "쿠폰 발급 중 오류가 발생했습니다: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "이미 쿠폰이 발급된 사용자입니다.";
    }
} else {
    echo "해당 쿠폰이 존재하지 않습니다.";
}

$mysqli->close();
} else {
echo "로그인된 사용자가 아닙니다.";
}

?>
