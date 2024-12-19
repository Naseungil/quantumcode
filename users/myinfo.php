<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 세션에서 사용자 이메일 가져오기
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/index.php';</script>";
    exit;
}

$userEmail = $_SESSION['MemEmail'];
$memId = $_SESSION['MemId'];
// SQL 쿼리 준비
$sql = "SELECT * FROM membersKakao WHERE memEmail = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $userEmail); // 사용자 이메일 바인딩
$stmt->execute();

// 결과 가져오기
$result = $stmt->get_result();
$data = $result->fetch_assoc(); // 결과를 배열로 가져오기

// 스테이트먼트 닫기
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>내 정보</title>
  <!-- 캐싱문제 방지 -->
  <link rel="stylesheet" href="/qc/css/core-style.css?v=<?= time(); ?>">
  <!-- 제이쿼리랑 폰트어썸 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/css/common.css">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/css/core-style.css">
  <style>
    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #ccc;
    
    }
    p {
    margin-top: 0;
    margin-bottom: 0;
  }
  </style>
</head>
<body>
  <div class="container mt-2">
    <h3>나의 정보 보기</h3>
    <div class="row mt-4">
      <!-- 프로필 섹션 -->
      <div class="col-md-4 text-center">
      <?php 
        $profileImage = $data['memProfilePath'] ?? '../img/icon-img/no-image.png';
      ?>
      <img src="<?= htmlspecialchars($profileImage); ?>" alt="프로필 이미지" class="profile-img mb-3" style="width: 150px; height: 150px; object-fit: cover; border-radius: 25%; border: 2px solid #ccc;">
        <p>등급 : <?= htmlspecialchars($data['grade']); ?></p>
        <p style="font-size: 12px;" class="mt-1">등급은 적립금과 매월 발행되는 쿠폰에 영향을 미칩니다.</p>
      </div>

      <!-- 정보 섹션 -->
      <div class="col-md-8">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>이름</th>
              <td><?= htmlspecialchars($data['memName']); ?></td>
            </tr>
            <tr>
              <th>이메일</th>
              <td><?= htmlspecialchars($data['memEmail']); ?></td>
            </tr>
            <tr>
              <th>생년월일</th>
              <td><?= $data['birth'] ? htmlspecialchars($data['birth']) : '정보 없음'; ?></td>
            </tr>
            <tr>
              <th>주소</th>
              <td><?= $data['memAddr'] ? htmlspecialchars($data['memAddr']) : '정보 없음'; ?></td>
            </tr>
            <tr>
              <th>가입일</th>
              <td><?= htmlspecialchars($data['memCreatedAt']); ?></td>
            </tr>
            <tr>
              <th>마지막 로그인</th>
              <td><?= htmlspecialchars($data['lastLoginAt']); ?></td>
            </tr>
            <tr>
              <th>로그인 횟수</th>
              <td><?= htmlspecialchars($data['login_count']); ?>회</td>
            </tr>
            <tr>
              <th>전화번호</th>
              <td><?= htmlspecialchars($data['number']); ?></td>
            </tr>
            <tr>
              <th>등급</th>
              <td><?= htmlspecialchars($data['grade']); ?></td>
            </tr>
          </tbody>
        </table>
        <div class="text-end">
          <a href="users_update.php?MemId=<?= htmlspecialchars($data['memId']); ?>" class="btn btn-primary btn-md">수정 및 탈퇴하기</a>
          <p style="font-size:14px; margin-top: 1rem;">내 이미지, 이름, 주소, 전화번호만 수정할 수 있습니다.</p>
          <p style="font-size:14px; margin-top: 0;">비밀번호는 로그인 페이지의 비밀번호 재설정에서 변경할 수 있습니다.</p>
        </div>
        
      </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>