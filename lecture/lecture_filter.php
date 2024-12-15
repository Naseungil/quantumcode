<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$status = $_POST['status'] ?? '';
$tag = $_POST['tag'] ?? '';
$plat = $_POST['plat'] ?? '';
$dev = $_POST['dev'] ?? '';
$tech = $_POST['tech'] ?? '';
$option = $_POST['option'] ?? '';
$page = intval($_POST['page'] ?? 1); // 현재 페이지 (기본값 1)

$filter = '';

// 기본 변수 설정
$length = 12; // 한 페이지당 강의 수
$start_num = ($page - 1) * $length; // 데이터 시작점

if ($status) {
  $filter .= "  AND l.lecture_status = $status";
}
if ($tag) {
  $filter .= " AND l.lecture_tag = '$tag'";
}
if ($plat) {
  $plat_sql = "SELECT code FROM lecture_category WHERE ppcode = '$plat' ";
  $plat_result = $mysqli->query($plat_sql);
  $platArr = [];
  while ($data = $plat_result->fetch_object()) {
    $code_sql = "SELECT lcid FROM lecture_category WHERE code = '$data->code' ";
    $code_result = $mysqli->query($code_sql);
    $platArr[] = $code_result->fetch_object()->lcid;
  }
  $plats = implode(',', [...$platArr]);
  $filter .= " AND c.lcid IN ($plats)";
}
if ($dev) {
  $dev_sql = "SELECT code FROM lecture_category WHERE pcode = '$dev' ";
  $dev_result = $mysqli->query($dev_sql);
  $devArr = [];
  while ($data = $dev_result->fetch_object()) {
    $code_sql = "SELECT lcid FROM lecture_category WHERE code = '$data->code' ";
    $code_result = $mysqli->query($code_sql);
    $devArr[] = $code_result->fetch_object()->lcid;
  }
  $devs = implode(',', [...$devArr]);
  $filter .= " AND c.lcid IN ($devs)";
}
if ($tech) {
  $filter .= " AND c.lcid = $tech";
}
if ($option) {
  $filter .= " AND l.{$option} = 1";
}

// 전체 데이터 개수 조회
$count_sql = "SELECT COUNT(*) AS cnt FROM lecture_list l 
JOIN lecture_category c 
ON l.lcid = c.lcid
JOIN teachers t ON l.t_id = t.id
WHERE 1=1 $filter";
$count_result = $mysqli->query($count_sql);
$total_count = $count_result->fetch_assoc()['cnt'];
$total_page = ceil($total_count / $length); // 총 페이지 수

$sql = "SELECT l.*, c.*, t.name
FROM lecture_list l 
JOIN lecture_category c 
ON l.lcid = c.lcid
JOIN teachers t ON l.t_id = t.id
WHERE 1=1 $filter
LIMIT $start_num, $length";


$result = $mysqli->query($sql);

$lectures_html = '';

if ($result && $result->num_rows > 0) {


  while ($item = $result->fetch_object()) {

    $tuition = '';
    if ($item->dis_tuition > 0) {
      $tui_val = number_format($item->tuition);
      $distui_val = number_format($item->dis_tuition);
      $tuition .= "<p class=\"text-decoration-line-through tui \"> $tui_val 원 </p><p class=\"active-font \"> $distui_val 원 </p>";
    } else {
      $tui_val = number_format($item->tuition);
      $tuition .=  "<p class=\"active-font \"> $tui_val 원 </p>";
    }

    $lectures_html .= "
    <section class=\"col-md-3 mb-3 list d-flex flex-column justify-content-between\">
      <div>
        <div class=\"cover mb-2\">
          <img src=\"{$item->cover_image}\" alt=\"\">
        </div>
        <div class=\"title mb-2\">
          <h5 class=\"small-font mb-0\"><a href=\"lecture/lecture_view.php?lid=$item->lid\">{$item->title}</a></h5>
          <p class=\"name text-decoration-underline\">{$item->name}</p>
        </div>
        <div class=\"d-flex flex-column-reverse justify-content-start tuition\">
          {$tuition}
        </div>
      </div>
      <ul>
        <li class=\"d-flex align-items-center gap-2\"> <img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/review.svg\" alt=\"\"> 5점 </li>
        <li class=\"like d-flex align-items-center\"><img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/Heart.svg\" width=\"10\" height=\"10\" alt=\"\">500+</li>
        <li class=\"tag\"> <span> {$item->lecture_tag}</span>  </li>
      </ul>
    </section>";
  }
} else {
  $lectures_html = "검색 결과가 없습니다.";
}

$pagination_html = '';
$block_ct = 5;
$block_num = ceil($page / $block_ct);
$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

if ($block_end > $total_page) $block_end = $total_page;

if ($block_num > 1) {
  $prev = $block_start - $block_ct;
  $pagination_html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"lecture_list.php?page={$prev}\" data-page=\"$prev\">이전</a></li>";
}

for ($i = $block_start; $i <= $block_end; $i++) {
  $active = ($page == $i) ? 'active' : '';
  $pagination_html .= "<li class=\"page-item $active\"><a class=\"page-link\" href=\"lecture_list.php?page={$i}\" data-page=\"$i\">$i</a></li>";
}

if ($total_page > $block_end) {
  $next = $block_end + 1;
  $pagination_html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"lecture_list.php?page={$next}\" data-page=\"$next\">다음</a></li>";
}

// JSON 응답 반환
echo json_encode([
  'lectures' => $lectures_html,
  'pagination' => $pagination_html,
]);
exit;
