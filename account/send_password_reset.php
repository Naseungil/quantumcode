<?php
// Database 연결
include_once("../admin/inc/dbcon.php");

$email = $_POST["email"] ?? '';

if (empty($email)) {
    die("이메일을 입력해주세요.");
}

try {
    // 랜덤 토큰 생성 및 해시 처리
    do {
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);

        // 데이터베이스에 동일한 해시가 있는지 확인
        $check_sql = "SELECT COUNT(*) FROM membersKakao WHERE reset_token_hash = ?";
        $check_stmt = $mysqli->prepare($check_sql);
        $check_stmt->bind_param("s", $token_hash);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();
    } while ($count > 0); // 중복된 토큰이 있을 경우 반복

    // 토큰 만료 시간 설정 (30분)
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    // 업데이트 쿼리
    $sql = "UPDATE membersKakao
            SET reset_token_hash = ?,
                reset_token_expires_at = ?
            WHERE memEmail = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("쿼리 준비 실패: " . $mysqli->error);
    }

    $stmt->bind_param("sss", $token_hash, $expiry, $email);
    $stmt->execute();

    if ($mysqli->affected_rows > 0) {
        // 이메일 전송
        $mail = require __DIR__ . "/mailer.php";

        $mail->setFrom("haemilyjh@naver.com"); // 발신 이메일
        $mail->addAddress($email); // 받는 사람 이메일
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END

        <a href="http://localhost/qc/account/reset_password.php?token=$token">here</a> 
        Click There, if you want To Change Password. It is not a SpamMail. From QuantumCode.

        END;

        try {
            $mail->send();
            // echo "메세지가 보내졌습니다. 방금 작성하신 이메일을 확인해주세요.";
        } catch (Exception $e) {
            echo "메세지가 보내지지 않았습니다.. Mailer error: {$mail->ErrorInfo}";
        }

        // 동적 카운트다운 및 리다이렉션
        echo "<!DOCTYPE html>
<html lang='ko'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>저장 성공</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .message-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .message-box h1 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .message-box p {
            color: #6c757d;
            font-size: 18px;
        }
        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin-top: 15px;
            border: 4px solid #e9ecef;
            border-radius: 50%;
            border-top-color: #28a745;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class='message-box'>
        <h1>인증 이메일 발송 성공!</h1>
        <p>해당 이메일에서 인증을 완료해 주세요!</p>
        <div class='spinner'></div>
        <p id='countdown'>5초후 로그인 페이지로 이동합니다...</p>
    </div>
    <script>
        let countdown = 5; // 초기 카운트다운 시간
        const countdownElement = document.getElementById('countdown');
        const interval = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown + '초후 로그인 페이지로 이동합니다...';
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = '/qc/account/loginTest2.php'; //로그인페이지로 리디렉션
            }
        }, 1000); // 1초마다 실행
    </script>
</body>
</html>";

        exit();
    } else {
        echo "입력하신 이메일이 데이터베이스에 존재하지 않습니다.";
    }
} catch (Exception $e) {
    echo "오류가 발생했습니다: " . $e->getMessage();
} finally {
    $stmt->close();
    $mysqli->close();
}
