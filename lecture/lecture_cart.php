<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');


if (isset($_SESSION['MemEmail'])) {
  $memEmail = $_SESSION['MemEmail'];
  $memId = $_SESSION['MemId'];
  $memName = $_SESSION['MUNAME'];
}

$lid = $_GET['lid'];
// $userid = 5;

$sql = "SELECT * FROM lecture_list WHERE lid = $lid";
$result = $mysqli->query($sql);
if ($row = $result->fetch_object()) {
  if ($row->dis_tuition) {
    $tuition = $row->dis_tuition;
  } else {
    $tuition = $row->tuition;
  }
}

echo $tuition;
$pid_sql = "SELECT COUNT(*) AS cnt FROM lecture_cart 
WHERE lid=$lid AND mid = '$memEmail' ";
$pid_result = $mysqli->query($pid_sql);
if ($pid_result) {
  $data = $pid_result->fetch_object();
  if ($data->cnt > 0) {
    echo "<script>
    alert('중복 강의입니다.');
    location.href = history.back();
    </script>";
  } else {

    // 임시로 mid는 홍길동(5) 입력
    $cart_sql = "INSERT INTO lecture_cart (mid, lid, price) VALUES ( '$memEmail', $lid, $tuition )";
    $cart_result = $mysqli->query($cart_sql);
    if ($cart_result) {
      echo "<script>
    alert('수강바구니에 등록되었습니다.');
    location.href = 'lecture_order.php';
    </script>";
    }
  }
}
