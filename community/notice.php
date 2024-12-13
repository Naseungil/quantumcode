<?php 
$title = "공지사항";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');



?>

<div class="title_box">
  <div class="container">
    <h2><?= $title ?></h2>  
  </div>  
</div>

<div class="community container">
  <div class="row">
    <aside class="col-2 d-flex flex-column">
      <h6>커뮤니티</h6>
      <hr>
      <ul>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/notice.php" class="active"><li>공지사항<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/faq.php"><li>FAQ<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/qna.php"><li>QnA<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/free.php"><li>자유게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/questions.php"><li>질문게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/study.php"><li>스터디 모집<i class="fa-solid fa-chevron-right"></i></li></a>
      </ul>
    </aside>
  
    <div class="notice content col-10">
      <h6>퀀텀코드의 공지사항입니다.</h6>
      <hr>
      <table class="table table-hover text-center">
      <thead>
        <tr>
          <th scope="col" class="num">No</th>
          <th scope="col">제목</th>
          <th scope="col" class="writer">글쓴이</th>
          <th scope="col" class="date">게시일</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td class="post"><a href="">게시글 제목</a></td>
          <td>퀀텀코드</td>
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