<?php

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

$userpw = $_POST['password'];
$password = hash('sha512',$userpw);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM membersKakao
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 8) {
    die("비밀번호는 8자 이상이어야 합니다.");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("비밀번호는 문자 한 개 이상을 포함해야 합니다.");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("비밀번호는 숫자 한 개 이상을 포함해야 합니다.");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("두 비밀번호는 동일해야 합니다.");
}


//다시 토큰 초기화
$sql = "UPDATE membersKakao
        SET mempassword = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE memEmail = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password, $user["memEmail"]);

$stmt->execute();

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
        <h1>비밀번호 재설정 성공!</h1>
        <p>로그인 페이지에서 새로운 비밀번호로 로그인 해 주세요!</p>
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