<?php 
$title = "자유게시판";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

//검색창 검색어 받기
$search_keyword = $_POST['search_keyword'] ?? '';

// 검색 조건
$search_where = '';
if ($search_keyword) {
    $search_where = " AND board.title LIKE '%$search_keyword%'";
}

// 카테고리 값 받기
$category = $_GET['category'] ?? 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// 게시글 수 쿼리
$count_sql = "SELECT COUNT(*) as total FROM board WHERE 1=1";

if ($category !== 'all') {
    $count_sql .= " AND category = '$category'";
}
if ($search_keyword) {
    $count_sql .= " AND content LIKE '%$search_keyword%'";
}

// 실행 및 결과 가져오기
$count_result = $mysqli->query($count_sql);
$total_count = $count_result->fetch_object()->total;




// 페이지네이션
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$list = 10;
$start_num = ($page - 1) * $list;
$total_page = ceil($total_count / $list);

// 페이지네이션을 위한 블록 크기 (각 블록에 포함할 페이지 개수)
$block_ct = 5; 
$total_block = ceil($total_page / $block_ct);

// 현재 페이지가 속한 블록 계산
$block_num = ceil($page / $block_ct);
$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

// 블록 끝 페이지가 총 페이지 수를 초과하면, 마지막 페이지를 끝으로 설정
if ($block_end > $total_page) {
    $block_end = $total_page;
}

// 이전, 다음 페이지 계산
$prev = max(1, $block_start - 1);
$next = min($total_page, $block_end + 1);



// SQL 쿼리 작성
$sql = "SELECT board.*, 
  (SELECT COUNT(*) FROM board_reply WHERE board_reply.b_pid = board.pid) AS comment_count
  FROM board
  WHERE 1=1 
  " . ($category !== 'all' ? " AND category = '$category'" : '') . "
  $search_where 
  ORDER BY pid DESC 
  LIMIT $start_num, $list
";
$result = $mysqli->query($sql);

?>

<div class="search_box">
  <div class="container">
    <h2><?= $title ?></h2>
    <form action="" class="d-flex">
      <input type="text" class="form-control" id="search_box" placeholder="검색어를 입력하세요.">
      <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>  
</div>

<div class="community container">
  <div class="row">
    <aside class="col-2 d-flex flex-column">
      <h6>커뮤니티</h6>
      <hr>
      <ul>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/notice.php"><li>공지사항<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/faq.php"><li>FAQ<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/qna.php"><li>QnA<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/free.php" class="active"><li>자유게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/questions.php"><li>질문게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/study.php"><li>스터디 모집<i class="fa-solid fa-chevron-right"></i></li></a>
      </ul>
    </aside>
  
    <div class="free content col-10">
      <div class="top">
        <h6>수강생들의 자유로운 소통구간입니다.</h6>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/community_new.php" class="btn btn-primary">글쓰기</a>
      </div>
      <hr>
      <table class="table table-hover text-center">
      <thead>
        <tr>
          <th scope="col" class="num">No</th>
          <th scope="col">제목</th>
          <th scope="col" class="view">조회</th>
          <th scope="col" class="recommend">UP</th>
          <th scope="col" class="answer">댓글</th>
          <th scope="col" class="date">게시일</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // 게시글 출력
      $num = $total_count - $start_num;
      while($data = $result->fetch_object()){
        $post_date = date("Y-m-d", strtotime($data->date)); // date 컬럼의 타임스탬프를 Y-m-d 형식으로 변환
        $current_date = date("Y-m-d");
        
        $content = $data->content;
        $title_cut = $data->title;
        
        // 제목이 길 경우 10글자로 자르기
        if(iconv_strlen($title_cut) > 10){
          $title_cut = iconv_substr($title_cut, 0, 10) . '...';
        }
        if(iconv_strlen($content) > 10){
          $content = iconv_substr($content, 0, 10) . '...';
        }
        ?>
        <tr>
          <th scope="row"><?= $num ?></th>
          <td class="post">
            <a href="community_view.php?pid=<?=$data->pid?>">
              <?= $data->title ?>
            </a>
          </td>
          <td><?=$data->hit ? $data->hit : 0 ?></td>
          <td><?=$data->likes ? $data->likes : 0 ?></td>
          <td><?= $data->comment_count ? $data->comment_count : 0 ?></td>
          <td><?=$post_date ?></td>
        </tr>
        <?php
        $num--;
        }
        ?>
      </tbody>
      </table>

      <nav aria-label="Page navigation">
    <ul class="pagination">
      <?php
        if ($block_num > 1) { //prev 버튼
          $prev = $block_start - $block_ct;
          echo "<li class=\"page-item prev\">
              <a class=\"page-link\" href=\"free.php?category={$category}&page={$prev}\">
                  <img src=\"http://{$_SERVER['HTTP_HOST']}/qc/img/icon-img/CaretLeft.svg\" alt=\"페이지네이션 prev\">
              </a>
          </li>";
        }
      ?>
        
      <?php
        // 페이지 번호 표시
        for ($i = $block_start; $i <= $block_end; $i++) {                
          $active = ($page == $i) ? 'active' : '';
      ?>
      <li class="page-item <?= $active; ?>"><a class="page-link" href="free.php?category=<?=$category?>&page=<?= $i; ?>&search_keyword=<?=$search_keyword?>"><?= $i; ?></a></li>
      <?php
        }
        $next = $block_end + 1;
        if($total_block >  $block_num){ //next 버튼
      ?>
      <li class="page-item next">
        <a class="page-link" href="free.php?category=<?=$category?>&page=<?= $next;?>">
          <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/img/icon-img/CaretRight.svg" alt="페이지네이션 next">
        </a>
      </li>
      <?php
      }         
      ?>
    </ul>
  </nav>


    </div>
  </div>

</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>