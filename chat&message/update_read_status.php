<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// POST 데이터 가져오기
$data = json_decode(file_get_contents("php://input"), true);
$receiver_id = $data['receiver_id'] ?? null;

if ($receiver_id) {
    // `is_read` 업데이트 쿼리
    $sql_update = "UPDATE tomembermessages SET is_read = 1 WHERE receiver_id = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param("s", $receiver_id);

    if ($stmt_update->execute()) {
        // 쪽지 리스트 최신순 정렬해서 반환
        $sql_select = "SELECT sender_name, message_content, sent_at FROM tomembermessages WHERE receiver_id = ? ORDER BY sent_at DESC";
        $stmt_select = $mysqli->prepare($sql_select);
        $stmt_select->bind_param("s", $receiver_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode(["success" => true, "messages" => $messages]);
        $stmt_select->close();
    } else {
        echo json_encode(["success" => false, "error" => $stmt_update->error]);
    }

    $stmt_update->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid receiver ID"]);
}
?>