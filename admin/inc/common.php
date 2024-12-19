<?php

function fileUpload($file, $type)
{
  // 파일 크기 검사 (10MB 이하)
  if ($file['size'] > 102400000) {
    echo "
        <script>
            alert('10MB이하만 첨부할 수 있습니다.');
            history.back();
        </script>
        ";
    return false;
  }

  // 파일 포맷 검사 (이미지 파일만 허용)
  if (strpos($file['type'], $type) === false) {
    echo "
        <script>
            alert('이미지만 첨부할 수 있습니다.');
            history.back();
        </script>
        ";
    return false;
  }

  // 파일 업로드 경로 설정
  $save_dir = $_SERVER['DOCUMENT_ROOT'] . '/qc/admin/upload/';
  $filename = $file['name']; // insta.jpg
  $ext = pathinfo($filename, PATHINFO_EXTENSION); // 파일 확장자 추출
  $newFileName = date('YmdHis') . substr(rand(), 0, 6); // 새로운 파일명 생성
  $savefile = $newFileName . '.' . $ext; // 저장될 파일명

  // 파일 이동
  if (move_uploaded_file($file['tmp_name'], $save_dir . $savefile)) {
    return '/qc/admin/upload/' . $savefile; // 성공 시 경로 반환
  } else {
    return false; // 실패 시 false 반환
  }
}
