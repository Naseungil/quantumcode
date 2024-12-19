<!-- 사실상 안 쓰는 부분이나 참고할 일이 있어서 안 지움 -->

<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 세션 확인
if (!isset($_SESSION['MemEmail'])) {
    die('로그인이 필요합니다.');
}

// 로그인된 사용자 이메일
$userEmail = $_SESSION['MemEmail'];

// 1. 사용자 카테고리와 CombinedCustomOrder 생성
$query = "
    SELECT 
        lc.code, 
        IFNULL(lc.pcode, NULL) AS pcode, 
        IFNULL(lc.ppcode, NULL) AS ppcode
    FROM 
        user_categories AS uc
    INNER JOIN 
        lecture_category AS lc
    ON 
        JSON_CONTAINS(uc.category, JSON_QUOTE(lc.name))
    WHERE 
        uc.user_email = ?
";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die('카테고리 쿼리 준비 실패: ' . $mysqli->error);
}

$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('사용자 카테고리가 없습니다.');
}

// 2. 각 카테고리에 대해 강의 검색
while ($row = $result->fetch_assoc()) {
    // CombinedCustomOrder 생성
    $combinedCustomOrder = ($row['ppcode'] !== null ? $row['ppcode'] : 'NULL') 
        . ($row['pcode'] !== null ? $row['pcode'] : 'NULL') 
        . $row['code'];

    echo "<h3>Category Code: " . $combinedCustomOrder . "</h3>";

    // `lecture_list` 테이블에서 category와 일치하는 데이터 가져오기 (학생수 기준으로 정렬, 최대 10개)
    $lectureQuery = "
        SELECT 
            lid, category, title, cover_image, t_id, isfree, ispremium, ispopular, isrecom, 
            tuition, dis_tuition, regist_day, expiration_day, sub_title, difficult 
        FROM 
            lecture_list 
        WHERE 
            category = ?
        ORDER BY 
            student_count DESC 
        LIMIT 10
    ";

    $lectureStmt = $mysqli->prepare($lectureQuery);
    if (!$lectureStmt) {
        die('강의 쿼리 준비 실패: ' . $mysqli->error);
    }

    $lectureStmt->bind_param("s", $combinedCustomOrder);
    $lectureStmt->execute();
    $lectureResult = $lectureStmt->get_result();

    if ($lectureResult->num_rows > 0) {
        while ($lectureRow = $lectureResult->fetch_assoc()) {
            // 한 줄로 데이터 출력
            echo implode(" | ", [
                "ID: " . $lectureRow['lid'],
                "Category: " . $lectureRow['category'],
                "Title: " . $lectureRow['title'],
                "Cover Image: " . $lectureRow['cover_image'],
                "Instructor: " . $lectureRow['t_id'],
                "Free: " . ($lectureRow['isfree'] ? "Yes" : "No"),
                "Premium: " . ($lectureRow['ispremium'] ? "Yes" : "No"),
                "Popular: " . ($lectureRow['ispopular'] ? "Yes" : "No"),
                "Recommended: " . ($lectureRow['isrecom'] ? "Yes" : "No"),
                "Tuition: " . $lectureRow['tuition'],
                "Discounted Tuition: " . ($lectureRow['dis_tuition'] ? $lectureRow['dis_tuition'] : "None"),
                "Start Date: " . $lectureRow['regist_day'],
                "End Date: " . ($lectureRow['expiration_day'] ? $lectureRow['expiration_day'] : "None"),
                "Subtitle: " . $lectureRow['sub_title'],
                "Difficulty: " . $lectureRow['difficult']
            ]);
            echo "<br>";
        }
    } else {
        echo "해당 카테고리에 대한 강의가 없습니다.<br>";
    }

    echo "<hr>";
    $lectureStmt->close();
}

// 연결 종료
$stmt->close();
$mysqli->close();
?>