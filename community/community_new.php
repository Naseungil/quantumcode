<?php 
$title = "게시판 글 등록";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";
$summernote_css = "<link href=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css\" rel=\"stylesheet\">";
$summernote_js = "<script src=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

?>

<div class="write container">
  <div class="row select d-flex">
    <input type="radio" class="btn-check" name="category" id="qna" autocomplete="off" checked>
    <label class="btn btn-outline-primary col-2" for="qna" value="qna">QnA</label>

    <input type="radio" class="btn-check" name="category" id="free" autocomplete="off">
    <label class="btn btn-outline-primary col-2" for="free" value="free">자유</label>

    <input type="radio" class="btn-check" name="category" id="questions" autocomplete="off">
    <label class="btn btn-outline-primary col-2" for="questions" value="questions">질문</label>

    <input type="radio" class="btn-check" name="category" id="study" autocomplete="off">
    <label class="btn btn-outline-primary col-2" for="study" value="study">스터디 모집</label>
  </div>

<input type="text" class="form-control title" placeholder="제목을 입력하세요">
<input type="text" class="form-control tag" placeholder="태그를 입력하세요">

<textarea id="summernote" class ="content" name="content"></textarea>

<div class="send">
  <a href="" class="btn" id="goback">취소</a>
  <a href="community_write_ok.php" class="btn btn-primary">작성</a>
</div>
</div>

<script>
  $(document).ready(function() {
  $('#summernote').summernote({
    height: 700, // 에디터 높이
    lang: 'ko-KR', // 한글 언어 설정
    toolbar: [
      // 한 줄에 배치할 버튼 목록
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['fontname', ['fontname']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['codeview']]
    ],
  });
});

$('#goback').click(function(){
    history.back();
  }); 
</script>