<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 세션에서 사용자 이메일 가져오기
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/index.php';</script>";
    exit;
}

$userEmail = $_SESSION['MemEmail'];

// SQL 쿼리 준비 (쿠폰 정보와 추가 정보 모두 가져오기)
$sql = "SELECT c.*, cu.use_max_date, cu.usedate, cu.reason
        FROM coupons_usercp cu
        JOIN coupons c ON cu.couponid = c.cid
        WHERE cu.userid = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $userEmail); // 사용자 이메일 바인딩
$stmt->execute();

// 결과 가져오기
$result = $stmt->get_result();

// 데이터를 배열로 저장
$coupons = [];
while ($row = $result->fetch_object()) { // fetch_object로 데이터 가져오기
    $coupons[] = $row;
}

// 스테이트먼트 닫기
$stmt->close();

// 쿠폰이 없을 경우 처리
if (empty($coupons)) {
    echo "<div class='alert alert-warning'>사용 가능한 쿠폰이 없습니다.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>쿠폰 목록</title>
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
    /* 쿠폰 이미지 크기 조정 */
    .coupon_view_imgbox img {
      max-width: 80%; /* 이미지 너비를 80%로 제한 */
      height: auto;
      border-radius: 8px;
    }

    /* 테이블 크기 조정 */
    .coupon_view_table th, .coupon_view_table td {
      font-size: 0.9rem; /* 폰트 크기 축소 */
      padding: 4px; /* 셀 간격 축소 */
    }

    /* 쿠폰 카드의 전체 크기 조정 */
    .coupon-card {
      padding: 1rem; /* 패딩 축소 */
      border-radius: 8px; /* 둥근 테두리 */
      font-size: 0.9rem; /* 카드 내부 폰트 크기 축소 */
    }

    /* 카드 내부 이미지와 정보 간격 축소 */
    .coupon_view_imgbox {
      padding: 8px; /* 이미지 영역의 패딩 축소 */
    }

    .coupon-info {
      padding: 8px; /* 정보 영역의 패딩 축소 */
    }
  </style>
</head>
<body>
  <div class="container mt-2">
    <h3 class="mb-4">나의 쿠폰 목록</h3>
    <?php foreach ($coupons as $data): ?>
      <div class="coupon-card border mb-3">
        <div class="row">
          <!-- 쿠폰 이미지 -->
          <div class="coupon_view_imgbox col-4 d-flex align-items-center justify-content-center">
            <img src="<?= htmlspecialchars($data->coupon_image); ?>" alt="상세_쿠폰 이미지" class="coupon_view_img">
          </div>

          <!-- 쿠폰 정보 -->
          <div class="coupon-info col-8">
            <table class="table table-borderless coupon_view_table">
              <tbody>
                <tr>
                  <th>쿠폰번호</th>
                  <td class="text-primary"><?= htmlspecialchars($data->cid); ?></td>
                  <th>할인구분</th>
                  <td><?= $data->coupon_type === 'fixed' ? '정액' : '정률'; ?></td>
                </tr>
                <tr>
                  <th>쿠폰이름</th>
                  <td><?= htmlspecialchars($data->coupon_name); ?></td>
                  <th>할인율</th>
                  <td>
                    <?= $data->coupon_price 
                        ? number_format($data->coupon_price).'원' 
                        : ($data->coupon_ratio ? $data->coupon_ratio.'%' : '할인 없음'); ?>
                  </td>
                </tr>
                <tr>
                  <th>쿠폰설명</th>
                  <td><?= htmlspecialchars($data->coupon_content); ?></td>
                  <th>상태</th>
                  <td><?= $data->status == '1' ? '활성화' : '비활성화'; ?></td>
                </tr>
                <tr>
                  <th>발급기간</th>
                  <td colspan="3"><?= htmlspecialchars($data->startdate); ?> ~ <?= htmlspecialchars($data->enddate); ?></td>
                </tr>
                <tr>
                  <th>사용여부</th>
                  <td colspan="3"><?= $data->usedate ? '사용됨 (' . htmlspecialchars($data->usedate) . ')' : '미사용'; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>