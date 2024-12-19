<?php
$title = '강의 목록';
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$id = $_SESSION['AUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('강사로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

$search = '';

$search_keyword = $_GET['search_keyword'] ?? '';

if ($search_keyword) {
  $search .= " and (title LIKE '%$search_keyword%' OR description LIKE '%$search_keyword%')";
}
//데이터의 개수 조회
$page_sql = "SELECT COUNT(*) AS cnt FROM lecture_list WHERE 1=1 $search";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();
$row_num = $page_data['cnt'];

//페이지네이션 
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

$length = 10;
$start_num = ($page - 1) * $length;
$block_ct = 5;
$block_num = ceil($page / $block_ct); //$page1/5 0.2 = 1

$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num / $length); //총75개 10개씩, 8
$total_block = ceil($total_page / $block_ct);

if ($block_end > $total_page) $block_end = $total_page;


$html = '';
$list = array();
$sql = "SELECT * FROM lecture_list WHERE 1=1 $search ORDER BY lid LIMIT $start_num, $length";
$result = $mysqli->query($sql);
while ($data = $result->fetch_object()) {
  $list[] = $data;
}




if (count($list) > 0) {
  $i = 1;
  $i = $i + $start_num;
  foreach ($list as $list) {
    $lcid = $list->lcid;
    $cate_sql = "SELECT * FROM lecture_category WHERE lcid = $lcid";
    if ($cate_result = $mysqli->query($cate_sql)) {
      $cate_data = $cate_result->fetch_object();
      $pcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$cate_data->pcode}' AND pcode = '{$cate_data->ppcode}'";
      $ppcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$cate_data->ppcode}'";

      $pcode_result = $mysqli->query($pcode_name_sql);
      $ppcode_result = $mysqli->query($ppcode_name_sql);

      $pcode_name = ($pcode_result && $pcode_result->num_rows > 0) ? $pcode_result->fetch_object()->name : "Unknown";
      $ppcode_name = ($ppcode_result && $ppcode_result->num_rows > 0) ? $ppcode_result->fetch_object()->name : "Unknown";
      $category = $ppcode_name . ' / ' . $pcode_name . ' / ' . $cate_data->name;
    }

    switch ($list->difficult) {
      case 1:
        $diff = '입문';
        break;
      case 2:
        $diff = '초급';
        break;
      case 3:
        $diff = '중급';
        break;
      case 4:
        $diff = '고급';
        break;
      case 5:
        $diff = '전문';
        break;
    }
    if ($list->dis_tuition > 0) {
      $tui_val =  number_format($list->dis_tuition);
    } else {
      $tui_val = number_format($list->tuition);
    }

    $html .= "<tr class=\"border-bottom border-secondary-subtitle\">
    <th >{$i}</th>
    <td><img src=\"{$list->cover_image}\" width=\"50\"></td>
    <td><a href=\"lecture_view.php?lid={$list->lid}\">{$list->title}</a></td>
    <td>{$list->t_id}</td>
    <td>{$tui_val}</td>
    <td>{$diff}</td>
    <td>{$category}</td>
    <td>{$list->regist_day}</td>
    <td><a href=\"lecture_modify.php?lid={$list->lid}\"><img src=\"../img/icon-img/Edit.svg\" width=\"20\"></a></td>
  </tr>";
    $i++;
  }
}

?>


<div class="container">
  <form action="" class="search mb-3">
    <input type="text" name="search_keyword" class="form-control ">
    <button class=" btn btn-secondary">검색</button>
  </form>
  <table class="table table-hover text-center">
    <thead>
      <tr class="border-bottom border-secondary-subtitle thline">
        <th scope="col">No</th>
        <th scope="col">Cover Image</th>
        <th scope="col">강의명</th>
        <th scope="col">강사명</th>
        <th scope="col">수강료</th>
        <th scope="col">난이도</th>
        <th scope="col">카테고리</th>
        <th scope="col">등록일</th>
        <th scope="col">Edit</th>
      </tr>
    </thead>
    <tbody>
      <?= $html; ?>
    </tbody>
  </table>
  <div class="d-flex justify-content-end">
    <button class=" btn btn-primary insert">등록</button>
  </div>
  <nav aria-label="Page navigation">
    <ul class="pagination d-flex justify-content-center">
      <?php
      if ($block_num > 1) {
        $prev = $block_start - $block_ct;
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"lecture_list.php?search_keyword={$search_keyword}&page={$prev}\"><img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/CaretLeft.svg\" alt=\"페이지네이션 prev\"></a></li>";
      }
      ?>
      <?php
      for ($i = $block_start; $i <= $block_end; $i++) {
        // if($page == $i) {$active = 'active';} else {$active = '';}
        $page == $i ? $active = 'active' : $active = '';
      ?>
        <li class="page-item <?= $active; ?>"><a class="page-link" href="lecture_list.php?search_keyword=<?= $search_keyword; ?>&page=<?= $i; ?>"><?= $i; ?></a></li>
      <?php
      }
      $next = $block_end + 1;
      if ($total_block >  $block_num) {
      ?>
        <li class="page-item"><a class="page-link" href="lecture_list.php?search_keyword=<?= $search_keyword; ?>&page=<?= $next; ?>"><img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/CaretRight.svg" alt="페이지네이션 next"></a></li>
      <?php
      }
      ?>
    </ul>
  </nav>

</div>
<script>
  $('.insert').click(function() {
    location.href = "lecture_insert.php"
  })
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>