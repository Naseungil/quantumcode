<?php
$title = '강의 등록';
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
$summernote_css = "<link href=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css\" rel=\"stylesheet\">";
$summernote_js = "<script src=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');


$id = isset($_SESSION['AUID']) ? $_SESSION['AUID']  : $_SESSION['TUID'];
// $id = $_SESSION['AUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('강사로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

// echo $id;

// $sql = "SELECT MAX(lid) AS last_lid FROM lecture_list";
// if ($result = $mysqli->query($sql)) {
//   $data = $result->fetch_object();
// }


$cate_sql = "SELECT * FROM lecture_category WHERE step = 1 ";
$cate_result = $mysqli->query($cate_sql);
while ($cate_data = $cate_result->fetch_object()) { //조회된 값들 마다 할일, 값이 있으면 $data할당
  $cate[] = $cate_data; //$cate1배열에 $data할당
}


?>
<div class="container">
  <Form action="lecture_insert_ok.php" id="lecture_submit" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="lecture_description" name="lecture_description" value="">
    <input type="hidden" name="lecture_video" id="lecture_videoId" value="">

    <div class="row lecture">
      <div class="col-4 mb-5">
        <h6>커버 이미지 등록</h6>
        <div class="lecture_coverImg mb-3">
          <img src="../img/icon-img/no-image.png" id="coverImg" alt="">
        </div>
        <div class="input-group">
          <input type="file" class="form-control" accept="image/*" name="cover_image" id="cover_image" required>
        </div>
      </div>
      <div class="col-8 mt-3">
        <table class="table">
          <thead class="visually-hidden">
            <tr>
              <th scope="col">구분</th>
              <th scope="col">내용</th>
            </tr>
          </thead>
          <tbody>
            <tr scope="row">
              <th scope="row" class="insert_name">강사명</th>
              <td colspan="3">
                <input type="text" class="form-control" name="userid" id="userid" value="<?= $id ?>" disabled>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_name">강의명</th>
              <td colspan="3">
                <input type=" text" class="form-control" name="title" id="title" placeholder="강의명을 입력해주세요" required>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row">카테고리 선택</th>
              <td colspan="3">
                <div class="d-flex gap-3">
                  <select class="form-select plat" name="platforms" required>
                    <option value="" selected>Platforms</option>
                    <?php
                    if (!empty($cate)) {
                      foreach ($cate as $plat) {
                    ?>
                        <option value="<?= $plat->code; ?>"><?= $plat->name; ?></option>
                    <?php
                      }
                    }
                    ?>
                    <!-- <option value="A0001">Web</option> -->
                  </select>
                  <select class="form-select dev" name="development" required>
                    <option value="" selected>Development</option>
                    <!-- <option value="B0001">Front-End</option> -->
                  </select>
                  <select class="form-select tech" name="technologies" required>
                    <option value="" selected>Technologies</option>
                    <!-- <option value="C0001">React</option> -->
                  </select>
                </div>
              </td>
            </tr>
            <tr>
              <th scope="row">수강료</th>
              <td class="twoculumn_table">
                <input type="text" class="form-control" name="tuition" id="tuition" required>
                <span></span>
              </td>
              <th scope="row" class="insert_name">할인 수강료</th>
              <td>
                <input type="text" class="form-control" name="dis_tuition" id="dis_tuition" value="">
              </td>
            </tr>
            <tr>
              <th scope="row">등록일</th>
              <td class="twoculumn_table">
                <input type="text" class="form-control" name="regist_day" id="regist_day" placeholder="" required>
                <span></span>
              </td>
              <th scope="row" class="insert_name">난이도</th>
              <td>
                <select class="form-select " name="difficult" required>
                  <option value="" selected>난이도</option>
                  <option value="1">입문</option>
                  <option value="2">초급</option>
                  <option value="3">중급</option>
                  <option value="4">고급</option>
                  <option value="5">전문</option>
                </select>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_name">노출옵션</th>
              <td colspan="3">
                <div class="d-flex justify-content-between">
                  <div class="d-flex align-items-center flex-grow-1 justify-content-start">
                    <input class="form-check-input me-2" type="checkbox" name="ispremium" value="1" id="ispremium">
                    <label class="form-check-label" for="ispremium">프리미엄</label>
                  </div>
                  <div class="d-flex align-items-center flex-grow-1 justify-content-start">
                    <input class="form-check-input me-2" type="checkbox" name="ispopular" value="1" id="ispopular">
                    <label class="form-check-label" for="ispopular">인기 강의</label>
                  </div>
                  <div class="d-flex align-items-center flex-grow-1 justify-content-start">
                    <input class="form-check-input me-2" type="checkbox" name="isrecom" value="1" id="isrecom">
                    <label class="form-check-label" for="isrecom">추천 강의</label>
                  </div>
                  <div class="d-flex align-items-center flex-grow-1 justify-content-start">
                    <input class="form-check-input me-2" type="checkbox" name="isfree" value="1" id="isfree">
                    <label class="form-check-label" for="isfree">무료 강의</label>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-4 ">
        <h6>홍보영상 등록</h6>
        <div class="lecture_prVideo mb-3">
          <video src="" id="pr_video"></video>
          <select class="form-select w-25" name="prVideo_type" id="prVideo_type">
            <option value="1" selected>파일</option>
            <option value="2">URL</option>
          </select>
        </div>
        <input type="file" class="form-control" accept="video/*" name="pr_video" id="pr_videoFile">
        <div class="input-group mb-3">
          <span class="input-group-text" id="pr_videoAddon">URL</span>
          <input type="url" class="form-control" name="pr_videoUrl" id="pr_videoUrl">
        </div>
      </div>
      <div class="col-8 ">
        <div class="d-flex flex-column gap-2">
          <label for="sub_title" class="bold">강의 요약</label>
          <textarea class="form-control" placeholder="강의 요약" name="sub_title" id="sub_title" required></textarea>
        </div>
      </div>
      <div>
        <h6>강의 상세 설명</h6>
        <div id="desc"></div>
      </div>
      <div class="col-4 ">
        <h6>강의 영상 등록</h6>
        <div class="lecture_video mb-3 d-flex">
          <!-- <video src="" id="lecture_addVideo"></video> -->

        </div>
        <input type="file" class="form-control visually-hidden" accept="video/*" name="add_videos[]" id="add_videos" multiple>
        <button type="button" class="btn btn-primary btn-sm" id="addVideo">영상 추가</button>
        <div id="addVideos" class="d-flex gap-3"></div>

      </div>
      <div class="col-8 ">
        <div class="d-flex flex-column gap-2">
          <label for="objectives" class="bold">강의 목표</label>
          <textarea class="form-control" placeholder="강의 목표" name="objectives" id="objectives" required></textarea>
        </div>
        <div class="d-flex flex-column gap-2">
          <label for="tag" class="bold">강의 태그</label>
          <textarea class="form-control" placeholder="강의 태그" name="tag" id="tag" required></textarea>
        </div>
      </div>
    </div>
    <div class="mt-3 d-flex justify-content-end">
      <button type="submit" class="btn btn-primary">등록</button>
    </div>
  </Form>
</div>
<script src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/js/common.js"></script>
<script>
  function addCover(file, cover) {
    let coverImage = file;
    coverImage.on('change', (e) => {
      let file = e.target.files[0];
      let target = cover;
      if (file) {
        const reader = new FileReader();
        reader.onloadend = (e) => {
          let attachment = e.target.result;
          if (attachment) {
            target.attr('src', attachment);
          }
        }
        reader.readAsDataURL(file);
      } else {
        target.attr('src', '../img/icon-img/no-image.png');
      }
    });
  }

  $(document).on('change', '.plat', function() {
    let platValue = $(this).val();
    makeOption($(this), 2, $('.dev'), '').then(() => {
      $('.dev').trigger('change'); // dev의 change 이벤트 실행
    })
  });

  $(document).on('change', '.dev', function() {
    //console.log('platValue received in dev change:', platValue);
    makeOption($(this), 3, $('.tech'), $('.plat').val());
  });


  addCover($('#cover_image'), $('#coverImg'));
  addCover($('#pr_videoFile'), $('#pr_video'));

  function videoToggle(select, target1, target2) {
    target2.prop('disabled', true);
    select.change(function() {
      let value = $(this).val();
      target1.prop('disabled', true);
      target2.prop('disabled', true);
      if (value == 1) {
        target1.prop('disabled', true).prop('disabled', false);
      } else {
        target2.prop('disabled', false);
      }
    });
  }
  videoToggle($('#prVideo_type'), $('#pr_videoFile'), $('#pr_videoUrl'));
  videoToggle($('#addVideo_type'), $('#add_videos'), $('#add_videosUrl'));



  function attachFile(file) {

    let formData = new FormData(); //페이지전환 없이, 폼전송없이(submit 이벤트 없이) 파일 전송, 빈폼을 생성
    formData.append('savefile', file); //<input type="file" name="savefile" value="file"> 이미지 첨부

    $.ajax({
      url: 'lecture_add_video.php',
      data: formData,
      cache: false, //이미지 정보를 브라우저 저장, 안한다
      contentType: false, //전송되는 데이터 타입지정, 안한다.
      processData: false, //전송되는 데이터 처리(해석), 안한다.
      dataType: 'json', //lecture_addVideo.php이 반환하는 값의 타입
      type: 'POST', //파일 정보를 전달하는 방법
      success: function(returned_data) { //lecture_addVideo.php과 연결(성공)되면 할일


        if (returned_data.result === 'size') {
          alert('10MB 이하만 첨부할 수 있습니다.');
          return;
        } else if (returned_data.result === 'image') {
          alert('이미지만 첨부할 수 있습니다.');
          return;
        } else if (returned_data.result === 'error') {
          alert('첨부실패, 관리자에게 문의하세요');
          return;
        } else { //파일 첨부가 성공하면
          let vidids = $('#lecture_videoId').val() + returned_data.vidid + ',';
          $('#lecture_videoId').val(vidids);
          let html = `
            <div class="card" style="width: 9rem;" id="${returned_data.vidid}">
              <video src="${returned_data.savefile}" class="card-img-top" alt="..."> </video>
              <div class="card-body">                
                <button type="button" class="btn btn-danger btn-sm">삭제</button>
              </div>
            </div>
          `;
          $('.lecture_video').append(html);
        }
      }
    })
  } //Attachfile

  function file_delete(lvid) {

    if (!confirm('정말 삭제할까요?')) { //조건이 false일때
      return false; //거짓 반환,종료      
    }

    let data = {
      lvid: lvid
    }
    $.ajax({
      async: false, //동기방식, image_delete.php의 결과를 받으면 진행      
      url: 'video_delete.php',
      data: data, //삭제할 번호 data 객체를 전달
      type: 'post', //data를 전달할 방식
      dataType: 'json', //json형식이용해서, 객체로 받겠다.
      error: function() {
        //연결실패시 할일
      },
      success: function(returned_data) {
        //연결성공시 할일, image_delete.php가 echo 출력해준 값을 매배견수 returend_data 받자
        if (returned_data.result == 'mine') {
          alert('본인이 작성한 제품의 이미지만 삭제할 수 있습니다.');
          return;
        } else if (returned_data.result == 'error') {
          alert('삭제 실패!');
          return;
        } else {
          $('#' + lvid).remove(); //요소(tag)를 삭제
        }
      }
    })
  }


  $('#addVideo').click(function() {
    $('#add_videos').trigger('click');
  });

  $('#add_videos').change(function() {
    let files = $(this).prop('files');

    for (let i = 0; i < files.length; i++) {
      attachFile(files[i]);
    }

  });

  let lecture_desc = $('#desc');
  lecture_desc.summernote({
    height: 500,
    popover: {
      image: [
        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
        ['float', ['floatLeft', 'floatRight', 'floatNone']],
        ['remove', ['removeMedia']]
      ],
      link: [
        ['link', ['linkDialogShow', 'unlink']]
      ],
      table: [
        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
      ],
      air: [
        ['color', ['color']],
        ['font', ['bold', 'underline', 'clear']],
        ['para', ['ul', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture']]
      ]
    }
  });

  $("#regist_day").datepicker({
    format: "yyyy-mm-dd"
  });

  $('#lecture_submit').submit(function(e) {
    console.log($('#lecture_submit'));
    if (lecture_desc.summernote('isEmpty')) {
      e.preventDefault();
      alert('상품 설명을 작성해주세요');
      lecture_desc.summernote('focus');
    }

    var markup = lecture_desc.summernote('code');
    let content = encodeURIComponent(markup);
    $('#lecture_description').val(markup);
  });

  $('.lecture_video').on('click', 'button', function() {
    let lvid = $(this).closest('.card').attr('id');
    //console.log(imgid);
    file_delete(lvid);
  });
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>d