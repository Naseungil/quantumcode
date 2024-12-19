<?php
// DB 연결 설정 (dbcon.php를 포함하고 있으면 연결이 되어 있을 것입니다)
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');


// POST 데이터 받기...아마 이 부분 에러인거 같은데 gpt는 이게 맞다고 함...
$name = $_POST['name'] ;
$value = $_POST['value']; 

// 반환할 결과 초기화
//$return_data = array("result" => 0);  중복이 없으면 result = 0

if ($name && $value) {
    // SQL 쿼리 작성
    if ($name === 'email') {
        // 이메일 중복 체크
        $sql = "SELECT COUNT(*) AS cnt FROM memberskakao WHERE memEmail = '$value'";
    } elseif ($name === 'number') {
        //전화번호 중복 체크
        $sql = "SELECT COUNT(*) AS cnt FROM memberskakao WHERE number = '$value'";
    } 

    // $return_data = array('result'=>$sql);
    // echo json_encode($return_data);
    
    if (isset($sql)) {
      $result = $mysqli->query($sql);
      $row = $result->fetch_assoc();
      $row_num = $row['cnt'];

      if($row_num > 0){
        $return_data = array('result'=>$row_num, 'type'=>$name);
        echo json_encode($return_data);
      }else if($row_num == 0){
        $return_data = array('result'=>0, 'type'=>$name);
        echo json_encode($return_data);
      }
    }
}

$mysqli->close();
?>





