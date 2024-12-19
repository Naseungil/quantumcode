<?php
session_start();
include_once("../admin/inc/dbcon.php"); // DB 연결

try {
    // 기본 응답 초기화
    $res = array('rst' => 'fail', 'msg' => '');

    // POST 요청으로 전달된 데이터 가져오기
    if (empty($_SESSION['MemEmail'])) {
        throw new Exception('로그인 정보가 유효하지 않습니다.');
    }

    $email = $_SESSION['MemEmail'];

    // 로그인 기록 업데이트
    $update_sql = "UPDATE membersKAKAO 
                   SET lastLoginAt = NOW(), login_count = login_count + 1 
                   WHERE memEmail = ?";
    $update_stmt = $mysqli->prepare($update_sql);

    if (!$update_stmt) {
        throw new Exception('쿼리 준비 실패: ' . $mysqli->error);
    }

    $update_stmt->bind_param("s", $email);
    if (!$update_stmt->execute()) {
        throw new Exception('로그인 기록 업데이트 실패: ' . $update_stmt->error);
    }

    // 로그인 성공한 사용자 정보 조회
    $select_sql = "SELECT * FROM membersKAKAO WHERE memEmail = ?";
    $select_stmt = $mysqli->prepare($select_sql);

    if (!$select_stmt) {
        throw new Exception('쿼리 준비 실패: ' . $mysqli->error);
    }

    $select_stmt->bind_param("s", $email);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('회원 정보를 찾을 수 없습니다.');
    }

    $data = $result->fetch_assoc();

    // 성공 메시지 및 리다이렉트
    echo "<script>
        alert('카카로톡으로 로그인 되었습니다.');
        location.href='/qc/index.php';
    </script>";
} catch (Exception $e) {
    // 에러 처리
    $res['msg'] = $e->getMessage();

    echo "<script>
        alert('{$res['msg']}');
        location.href='loginTest2.php';
    </script>";
} finally {
    // 데이터베이스 연결 종료
    $mysqli->close();
}
?>