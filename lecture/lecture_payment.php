<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

if (empty($_POST['lid']) || empty($_POST['mid']) || empty($_POST['total_price'])) {
  echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
  exit;
}

$ucid = $_POST['ucid'] ?? 0;
$lids = $_POST['lid'];
$mid = $_POST['mid'];
$total_price = $_POST['total_price'];

$lidsArray = explode(',', $lids);



$lidArr = [];
$placeholders = implode(',', $lidsArray); // lid 값들 문자열로 결합
$sql = "SELECT lid FROM lecture_order WHERE mid = '$mid' AND lid IN ($placeholders)";

// 쿼리 실행
$result = $mysqli->query($sql);

$purchased = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $purchased[] = $row['lid'];
  }
}

$duplicates = array_intersect($lidsArray, $purchased);
if (!empty($duplicates)) {
  echo json_encode([
    'status' => 'fail',
    'message' => '중복된 강의가 있습니다.',
    'duplicates' => $duplicates,
  ]);
  exit;
}

if (!empty($ucid)) {
  $up_sql = "UPDATE coupons_usercp SET status = 0, usedate = now() WHERE ucid = $ucid";
  $up_result = $mysqli->query($up_sql);
}


$sql = "INSERT INTO lecture_order (mid, lid, total_price, cid, status) VALUES ('$mid', '$lids', $total_price, $ucid, 1)";
$result = $mysqli->query($sql);
if (!$result) {
  echo json_encode(['status' => 'error', 'message' => $stmt->error]);
  exit;
} else {
  $del_sql = "DELETE FROM lecture_cart WHERE lid IN ($lids)";
  $del_result = $mysqli->query($del_sql);
  $response = [
    'status' => 'success',
    'message' => '구매 완료.',
  ];
}


echo json_encode($response);
$mysqli->close();
