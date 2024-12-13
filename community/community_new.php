<?php 
$title = "게시판 글 등록";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";
$summernote_css = "<link href=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css\" rel=\"stylesheet\">";
$summernote_js = "<script src=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

$selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
$placeholder = '';
$value = '';

if (in_array($selectedCategory, ['qna', 'free', 'questions'])) {
    $placeholder = '글 작성 전, 비방이나 무례한 언행은 삼가주세요! 함께 쾌적한 공간을 만들어가요.';
} elseif ($selectedCategory === 'study') {
    $value = "[스터디 모집 내용 예시]\n스터디 주제 :\n스터디 목표 :\n스터디 규칙 :\n스터디 지원 방식 :";
}

?>

<div class="write container">
<form action="community_new_ok.php" method="POST" enctype="multipart/form-data">
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

  <input type="text" name="title" class="form-control title" placeholder="제목을 입력하세요">
  <input type="text" name="tag" class="form-control tag" placeholder="태그를 입력하세요">

  <textarea id="summernote" name="content" class ="content" placeholder="<?php echo htmlspecialchars($placeholder); ?>"><?php echo htmlspecialchars($value); ?></textarea>

  <div class="send">
    <button class="btn" id="goback">취소</button>
    <button type="submit" class="btn btn-primary">등록</button>
  </div>
</form>
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