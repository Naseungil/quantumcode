<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/common.php');


$lid = $_POST['lid'] ?? '';
$username = $_POST['username']?? '';
$comment = $_POST['comment'] ?? '';
$img = $_POST['img'];

//profile_image 회원 프로필은 어떻게 생성되는지 알아야 함
$review_sql = "INSERT INTO lecture_review (lid, profile_image, username, review, comment) 
VALUES 
($lid, '$img', '$username', 5, '$comment')";
$review_result = $mysqli->query($review_sql);

if ($review_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
