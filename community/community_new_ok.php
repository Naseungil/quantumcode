<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

// if (isset($_SESSION['AUID'])) {
//     // 관리자 로그인 시
//     $user_id = $_SESSION['AUID'];
// }


$category = $_POST['category'];
$title1 = $_POST['title'];
$content = $_POST['content'];
$tag = $_POST['tag'];
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
//print_r($_FILES['file']['name']);

//파일 업로드
$file_name = time() . '_' . $_FILES['file']['name']; //이미지 중복 방지
$temp_path = $_FILES['file']['tmp_name'];
$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/qc/admin/board/upload/' . $file_name; //절대경로로 관리자 upload 폴더로 저장

if (move_uploaded_file($temp_path, $upload_path)) {
    //이미지 출력을 위해 데이터베이스 값 넣기 경로
    $img_path = '/qc/admin/board/upload/' . $file_name;
}

strpos($_FILES['file']['type'], 'image') !== false ? $is_img = 1 : $is_img = 0;



$max_file_size = 10*1024*1024;

if($_FILES['file']['size'] >$max_file_size ){
  echo "<script>
      alert('10MB 이상은 첨부할수 없습니다.');
      history.back();
  </script>";
}


$sql = "INSERT INTO board (category, title, content, img, is_img, start_date, end_date, user_id) VALUES ('$category', '$title1', '$content', '$img_path', $is_img, '$start_date', '$end_date','$user_id')";
$result = $mysqli->query($sql);

switch ($category) {
  case 'qna':
      $redirect_url = '/qc/coummunity/qna.php?category=qna';
      break;
  case 'study':
      $redirect_url = '/qc/coummunity/study.php?category=study';
      break;
  case 'questions':
      $redirect_url = '/qc/coummunity/questions.php?category=questions';
      break;
  case 'free':
      $redirect_url = '/qc/coummunity/free.php?category=free';
      break;
  default:
      die("카테고리를 선택 해주세요.");
}

if ($result) {
  echo "<script>
      alert('글 작성 완료');
      location.href='$redirect_url';
  </script>";
} else {
  echo "<script>
      alert('글 작성 실패');
      history.back();
  </script>";
}
?>