<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 로그인된 사용자의 이메일 가져오기
    if (!isset($_SESSION['MemEmail'])) {
        echo json_encode(["success" => false, "error" => "로그인이 필요합니다."]);
        exit;
    }
    $userEmail = $_SESSION['MemEmail'];

    // JSON 요청 데이터 가져오기
    $requestData = json_decode(file_get_contents('php://input'), true);  //

    //사실 하나만 있어도 상관없지만 혹시나 해서..
    if (isset($requestData['categories']) && is_array($requestData['categories'])) {
        $categories = $requestData['categories'];  // 요청값을 변수에 저장. 일반배열

        if (!empty($categories)) {
            // 카테고리를 JSON 형식으로 변환하여 저장 (이거 전에는 일반배열이므로)
            $categoriesJson = json_encode($categories, JSON_UNESCAPED_UNICODE);

            // 기존 레코드가 있는지 확인
            $checkQuery = "SELECT id FROM user_categories WHERE user_email = ?";
            $stmt = $mysqli->prepare($checkQuery);
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // 기존 레코드 업데이트
                $updateQuery = "UPDATE user_categories SET category = ?, created_at = NOW() WHERE user_email = ?";
                $updateStmt = $mysqli->prepare($updateQuery);
                $updateStmt->bind_param("ss", $categoriesJson, $userEmail);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                // 새 레코드 삽입
                $insertQuery = "INSERT INTO user_categories (user_email, category, created_at) VALUES (?, ?, NOW())";
                $insertStmt = $mysqli->prepare($insertQuery);
                $insertStmt->bind_param("ss", $userEmail, $categoriesJson);
                $insertStmt->execute();
                $insertStmt->close();
            }

            $stmt->close();

            // membersKakao 테이블의 first_coupon_issued 값을 1로 업데이트
            $updateCouponQuery = "UPDATE membersKakao SET first_coupon_issued = 1 WHERE memEmail = ?";
            $couponStmt = $mysqli->prepare($updateCouponQuery);
            $couponStmt->bind_param("s", $userEmail);
            $couponStmt->execute();
            $couponStmt->close();

            echo json_encode(["success" => true, "message" => "카테고리가 저장되었습니다."]);
        } else {
            echo json_encode(["success" => false, "error" => "선택된 카테고리가 없습니다."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "데이터가 유효하지 않습니다."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "잘못된 요청입니다."]);
}
?>