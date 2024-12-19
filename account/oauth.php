<?php
try {
    // ![수정필요] 카카오 API 환경설정 파일
    include_once "config.php";
    include_once("../admin/inc/dbcon.php"); // DB 연결

    // 기본 응답 설정
    $res = array('rst' => 'fail', 'code' => (__LINE__ * -1), 'msg' => '');

    // code && state 체크
    if (empty($_GET['code']) || empty($_GET['state']) || $_GET['state'] != $_COOKIE['state']) {
        throw new Exception("인증실패", (__LINE__ * -1));
    }

    // 토큰 요청
    $replace = array(
        '{grant_type}' => 'authorization_code',
        '{client_id}' => $kakaoConfig['client_id'],
        '{redirect_uri}' => $kakaoConfig['redirect_uri'],
        '{client_secret}' => $kakaoConfig['client_secret'],
        '{code}' => $_GET['code']
    );
    $login_token_url = str_replace(array_keys($replace), array_values($replace), $kakaoConfig['login_token_url']);
    $token_data = json_decode(curl_kakao($login_token_url));
    if (empty($token_data)) {
        throw new Exception("토큰요청 실패", (__LINE__ * -1));
    }
    if (!empty($token_data->error) || empty($token_data->access_token)) {
        throw new Exception("토큰인증 에러", (__LINE__ * -1));
    }

    // 프로필 요청
    $header = array("Authorization: Bearer " . $token_data->access_token);
    $profile_url = $kakaoConfig['profile_url'];
    $profile_data = json_decode(curl_kakao($profile_url, $header));
    if (empty($profile_data) || empty($profile_data->id)) {
        throw new Exception("프로필요청 실패", (__LINE__ * -1));
    }

    // 프로필정보 저장 -- DB를 통해 저장하세요
    $xml_data = json_encode($profile_data);
    $xml_data = json_decode($xml_data, true); // json 타입으로 변경

    $json_profile_data_array = array_merge($xml_data['properties'], $xml_data['kakao_account']); // 배열 합치기

    foreach ($json_profile_data_array as $key => $val) {
        if (!is_numeric($key)) {
            $_SESSION[$key] = $val; // Session의 키 값이 숫자인 경우 오류 발생 방지
        }
    }

    // DB 내 같은 이메일이 있는지 확인
    $sql_query = "SELECT * FROM membersKAKAO WHERE memEmail = '" . $json_profile_data_array["email"] . "'";
    $result = $mysqli->query($sql_query); // 쿼리 실행

    if (!$result) {
        throw new Exception("쿼리 실행 실패: " . $mysqli->error);
    }

    $memInfo = $result->fetch_array(MYSQLI_ASSOC); // 결과를 연관 배열로 가져오기

    // 회원 여부 확인 및 처리
    if ($memInfo) {
        // 기존 회원 처리 - 세션 설정
        $_SESSION['MemEmail'] = $memInfo['memEmail'];
        $_SESSION['MemId'] = $memInfo['memId'];
        $_SESSION['MUNAME'] = $memInfo['memName'];
        $_SESSION['Mgrade'] = $memInfo['memGrade']; // 예: 회원 등급
    } else {
        // 신규 회원 처리
        $sql_query = "INSERT INTO membersKAKAO (memName, memPassword, memEmail, memId, grade, memAddr, is_verified, is_email_verified)
                      VALUES (
                          '" . $json_profile_data_array["nickname"] . "',
                          'kakaoPassword',
                          '" . $json_profile_data_array["email"] . "',
                          'kakaoId',
                          'bronze',
                          'kakaoAddr',
                          1,
                          '" . $json_profile_data_array["is_email_verified"] . "'
                      )";

        if (!$mysqli->query($sql_query)) {
            throw new Exception("회원가입 실패: " . $mysqli->error);
        }

        // 신규 회원 데이터 가져오기
        $new_user_query = "SELECT * FROM membersKAKAO WHERE memEmail = '" . $json_profile_data_array["email"] . "'";
        $new_user_result = $mysqli->query($new_user_query);

        if ($new_user_result) {
            $new_user_data = $new_user_result->fetch_array(MYSQLI_ASSOC);

            // 세션 설정
            $_SESSION['MemEmail'] = $new_user_data['memEmail'];
            $_SESSION['MemId'] = $new_user_data['memId'];
            $_SESSION['MUNAME'] = $new_user_data['memName'];
            
        } else {
            throw new Exception("회원 정보 조회 실패: " . $mysqli->error);
        }
    }

    // 최종 성공 처리
    $res['rst'] = 'success';
} catch (Exception $e) {
    if (!empty($e->getMessage())) {
        $res['msg'] = $e->getMessage();
    }
    if (!empty($e->getCode())) {
        $res['code'] = $e->getCode();
    }
}

// 성공 처리
if ($res['rst'] == 'success') {
    // 성공 처리 코드 (예: 페이지 이동, 메시지 표시 등)
} else {
    // 실패 처리 코드 (예: 에러 메시지 표시 등)
}

// 데이터베이스 연결 종료
mysqli_close($mysqli);

// state 초기화
setcookie('state', '', time() - 3000);

// 페이지 이동
header("Location: ../account/kakao_login_ok.php");
exit;