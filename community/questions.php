<?php 
$title = "질문게시판";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

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
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/free.php"><li>자유게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/questions.php" class="active"><li>질문게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/study.php"><li>스터디 모집<i class="fa-solid fa-chevron-right"></i></li></a>
      </ul>
    </aside>
  
    <div class="questions content col-10">
      <div class="top">
        <h6>궁금한 점을 수강생에게 물어보세요.</h6>
        <a href="#" class="btn btn-primary">글쓰기</a>
      </div>
      <hr>
      <table class="table table-hover text-center">
      <thead>
        <tr>
          <th scope="col" class="num">No</th>
          <th scope="col">제목</th>
          <th scope="col" class="view">조회</th>
          <th scope="col" class="recommend">UP</th>
          <th scope="col" class="answer">답변</th>
          <th scope="col" class="date">게시일</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td class="post">
            <a href=""><span>미해결</span> 게시글 제목</a>
          </td>
          <td>133</td>
          <td>0</td>
          <td>1</td>
          <td>2024.12.09</td>
        </tr>
      </tbody>
      </table>

      <nav aria-label="Page navigation">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="#"><img src="../img/icon-img/CaretLeft.svg" alt=""></a></li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#"><img src="../img/icon-img/CaretRight.svg" alt=""></a></li>
        </ul>
      </nav>

    </div>
  </div>

</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>