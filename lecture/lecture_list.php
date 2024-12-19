<?php
$title = "강의 목록";

$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/lecture.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

$search = '';
$search_keyword = $_GET['search_keyword'] ?? '';
$cate = $_GET['cate'] ?? '';
$keyword = '';

if ($cate) {
  $search .= " AND category LIKE '%$cate%' ";
  $cate_sql = "SELECT * FROM lecture_category WHERE code = '$cate'";
  $cate_result = $mysqli->query($cate_sql);
  $keyword = $cate_result->fetch_object()->name;
}

if ($search_keyword) {
  $search .= " AND (title LIKE '%$search_keyword%' OR description LIKE '%$search_keyword%')";
  $keyword = $search_keyword;
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

$length = 12;
$start_num = ($page - 1) * $length;

$block_ct = 5;
$block_num = ceil($page / $block_ct); //$page1/5 0.2 = 1
$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num / $length); //총75개 10개씩, 8
$total_block = ceil($total_page / $block_ct);

if ($block_end > $total_page) $block_end = $total_page;




$sql = "SELECT l.*, t.name 
FROM lecture_list l 
JOIN teachers t 
ON l.t_id = t.id 
WHERE 1=1 $search
ORDER BY lcid LIMIT $start_num, $length
";
$result = $mysqli->query($sql);
$dataArr = [];
while ($data = $result->fetch_object()) {
  $dataArr[] = $data;
}


$plat_sql = "SELECT * FROM lecture_category WHERE pcode = '' AND ppcode = '' ";
$plat_result = $mysqli->query($plat_sql);
$platArr = [];
while ($plat_data = $plat_result->fetch_object()) {
  $platArr[] = $plat_data;
}

$dev_sql = "SELECT * FROM lecture_category WHERE ppcode = '' AND pcode <> '' ";
$dev_result = $mysqli->query($dev_sql);
$devArr = [];
while ($dev_data = $dev_result->fetch_object()) {
  $devArr[] = $dev_data;
}

$tech_sql = "SELECT * FROM lecture_category WHERE ppcode <> '' AND pcode <> '' ";
$tech_result = $mysqli->query($tech_sql);
$techArr = [];
while ($tech_data = $tech_result->fetch_object()) {
  $techArr[] = $tech_data;
}


$tag_sql = "SELECT DISTINCT lecture_tag FROM lecture_list ";
$tag_result = $mysqli->query($tag_sql);
$tagArr = [];
while ($tag_data = $tag_result->fetch_object()) {
  $tagArr[] = $tag_data;
}



?>
<div class="search_banner">
  <div class="container">
    <h2>검색</h2>
    <div class="d-flex gap-5">
      <form action="" class="search_1 mb-3">
        <input type="text" name="search_keyword" size="60" class="search_keyword" placeholder="검색어를 입력해주세요">
        <button class="search_btn"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
      <button class="search_all">전체보기</button>
    </div>
  </div>
</div>
<div class="lecture_list container wrapper">
  <?php
  if ($keyword !== '') {
  ?>
    <p class="my-3"><?= $keyword ?>의 검색 결과는 총 <?= $row_num ?> 개 입니다</p>
  <?php
  }
  ?>

  <form id="filterForm" class="row">
    <div class="col-4 col-lg-2">
      <select class="form-select" name="status" id="status">
        <option value="" selected>Status</option>
        <option value="1">진행중</option>
        <option value="2">진행완료</option>
        <option value="3">진행전</option>
      </select>
    </div>
    <div class="col-4 col-lg-2">
      <select class="form-select" name="tag" id="tag">
        <option value="" selected>Tag</option>
        <?php
        if (!empty($tagArr)) {
          foreach ($tagArr as $tag) {
        ?>
            <option value="<?= $tag->lecture_tag ?>"><?= $tag->lecture_tag ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
    <div class="col-4 col-lg-2">
      <select class="form-select" name="plat" id="plat">
        <option value="" selected>platform</option>
        <?php
        if (!empty($platArr)) {
          foreach ($platArr as $pa) {
        ?>
            <option value="<?= $pa->code ?>"><?= $pa->name ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
    <div class="col-4 col-lg-2">
      <select class="form-select" name="dev" id="dev">
        <option value="" selected>Development</option>
        <?php
        if (!empty($devArr)) {
          foreach ($devArr as $da) {
        ?>
            <option value="<?= $da->code ?>"><?= $da->name ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
    <div class="col-4 col-lg-2">
      <select class="form-select" name="tech" id="tech">
        <option value="" selected>Technologies</option>
        <?php
        if (!empty($techArr)) {
          foreach ($techArr as $ta) {
        ?>
            <option value="<?= $ta->lcid ?>"><?= $ta->name ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
    <div class="col-4 col-lg-2">
      <select class="form-select" name="option" id="option">
        <option value="" selected>노출옵션</option>
        <option value="ispopular">인기</option>
        <option value="isrecom">추천</option>
        <option value="ispremium">프리미엄</option>
        <option value="isfree">무료</option>
      </select>
    </div>
  </form>
  <div class="row print mt-3">
    <?php
    foreach ($dataArr as $item) {
      $tuition = '';
      if ($item->dis_tuition > 0) {
        $tui_val = number_format($item->tuition);
        $distui_val = number_format($item->dis_tuition);
        $tuition .= "<p class=\"text-decoration-line-through tui \"> $tui_val 원 </p><p class=\"active-font \"> $distui_val 원 </p>";
      } else {
        $tui_val = number_format($item->tuition);
        $tuition .=  "<p class=\"active-font \"> $tui_val 원 </p>";
      }
    ?>
      <section class="col-md-3 mb-3 list d-flex flex-column justify-content-between">
        <div>
          <div class="cover mb-2">
            <img src="<?= $item->cover_image ?>" alt="">
          </div>
          <div class="title mb-2">
            <h5 class="small-font mb-0"><a href="lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <p class="name text-decoration-underline"><?= $item->name ?></p>
          </div>
          <div class="d-flex flex-column-reverse justify-content-start tuition">
            <?= $tuition ?>
          </div>
        </div>
        <ul>
          <li class="d-flex align-items-center gap-2"> <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/review.svg" alt=""> 5점 </li>
          <li class="like d-flex align-items-center"><img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/Heart.svg" width="10" height="10" alt="">500+</li>
          <li class="tag"><?= !empty($item->lecture_tag) ? "<span> {$item->lecture_tag}</span>" : '' ?> </li>
        </ul>
      </section>
    <?php
    }
    ?>
  </div>
  <nav class="page" aria-label="Page navigation">
    <ul class="pagination d-flex justify-content-center">
      <?php
      if ($block_num > 1) {
        $prev = $block_start - $block_ct;
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"lecture_list.php?page={$prev}\"><img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/CaretLeft.svg\" alt=\"페이지네이션 prev\"></a></li>";
      }
      ?>
      <?php
      for ($i = $block_start; $i <= $block_end; $i++) {
        // if($page == $i) {$active = 'active';} else {$active = '';}
        $page == $i ? $active = 'active' : $active = '';
      ?>
        <li class="page-item <?= $active; ?>"><a class="page-link" href="lecture_list.php?page=<?= $i; ?>"><?= $i; ?></a></li>
      <?php
      }
      $next = $block_end + 1;
      if ($total_block >  $block_num) {
      ?>
        <li class="page-item"><a class="page-link" href="lecture_list.php?page=<?= $next; ?>"><img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/CaretRight.svg" alt="페이지네이션 next"></a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
</div>

<script>
  const filterselect = document.querySelectorAll('#filterForm select');
  const filterForm = document.querySelector("#filterForm");
  const print = document.querySelector('.print');
  const pagination = document.querySelector(".pagination");
  const searchAll = document.querySelector('.search_all');

  //filter 기능 함수
  const fetchFilteredData = (page = 1) => {
    const formData = new FormData(filterForm);
    formData.append("page", page);
    fetch('lecture_filter.php', {
        method: 'post',
        body: formData
      }).then(res => res.json())
      .then(data => {
        console.log(data);
        print.innerHTML = data.lectures; // 강의 데이터 업데이트
        pagination.innerHTML = data.pagination; // 페이지네이션 업데이트
        bindPaginationEvents();
      }).catch((error) => console.error("Error:", error));;
  }
  const bindPaginationEvents = () => {
    const paginationLinks = document.querySelectorAll(".pagination .page-link");
    paginationLinks.forEach((link) => {
      const newLink = link.cloneNode(true);
      link.replaceWith(newLink);
    });
    paginationLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const page = e.target.dataset.page;
        fetchFilteredData(page);
      });
    });
  };
  filterselect.forEach((select) => {
    select.addEventListener("change", () => fetchFilteredData());
  });
  searchAll.addEventListener('click', () => {
    filterselect.forEach((select) => {
      select.value = "";
    })
    fetchFilteredData();
    const paginationLinks = document.querySelectorAll(".pagination .page-link");
    paginationLinks.forEach((link) => {
      const newLink = link.cloneNode(true);
      link.replaceWith(newLink);
    })
  })
  bindPaginationEvents();
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>