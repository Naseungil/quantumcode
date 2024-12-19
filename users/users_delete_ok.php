<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

if (isset($_GET['MemId'])) {
    $memId = intval($_GET['MemId']);

    // SQL 쿼리 준비
    $sql = "DELETE FROM membersKakao WHERE memId = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $memId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        session_destroy(); // 세션 종료
        echo "<script>alert('정상적으로 탈퇴되었습니다.'); location.href = '/qc/index.php';</script>";
    } else {
        echo "<script>alert('탈퇴에 실패했습니다. 다시 시도해주세요.'); history.back();</script>";
    }

    $stmt->close();
}
?>