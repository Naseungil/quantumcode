<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 로그인 확인
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href = '/qc/index.php';</script>";
    exit;
}

// 변수 설정
$memId = $_SESSION['MemEmail'];
$memName = trim($_POST['memName']);
$memAddr = trim($_POST['memAddr']);
$number = trim($_POST['number']);

?>