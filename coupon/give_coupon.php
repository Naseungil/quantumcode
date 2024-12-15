<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 사용자 인증 확인
if (!isset($_SESSION['MemEmail'])) {
    echo json_encode(['success' => false, 'error' => '로그인된 사용자가 아닙니다.']);
    exit;
}

$email = $_SESSION['MemEmail'];  //이걸 사용자 아이디 값으로 쓸거임
$memId = $_SESSION['MemId'];     //혹시 몰라서 적어둠
$cid = 1; // 발급할 쿠폰 ID. 1번 쿠폰을 발급한다고 가정

try {
    // 이미 쿠폰이 발급되었는지 확인
    $check_sql = "SELECT COUNT(*) FROM coupons_usercp WHERE userid = ? AND ucid = ?";
    $stmt = $mysqli->prepare($check_sql);
    $stmt->bind_param("si", $email, $cid);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => '이미 쿠폰이 발급되었습니다.']);
        exit;
    }

    // 쿠폰 발급
    $insert_sql = "INSERT INTO coupons_usercp (userid, couponid, regdate) VALUES (?, ?, NOW())";
    $stmt = $mysqli->prepare($insert_sql);
    $stmt->bind_param("si", $email, $cid);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '쿠폰이 성공적으로 발급되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'error' => '쿠폰 발급 중 오류가 발생했습니다.']);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => '서버 오류: ' . $e->getMessage()]);
}
