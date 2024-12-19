<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$lrid = $_POST['lrid'];
$comment = $_POST['comment'];

$update_sql = "UPDATE lecture_review SET comment = '$comment' WHERE lrid = $lrid";
$update_result = $mysqli->query($update_sql);

if ($update_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
