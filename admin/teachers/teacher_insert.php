<?php
$title = '강사 등록';
$teacher_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/teacher.css\" rel=\"stylesheet\">";
$summernote_css = "<link href=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css\" rel=\"stylesheet\">";
$summernote_js = "<script src=\"https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

if(!isset($_SESSION['AUID'])){
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../index.php';
    </script>
  ";
}



?>

<div class="container">
  <Form action="teacher_insert_ok.php" id="teacher_save" method="POST" enctype="multipart/form-data">
    <!-- <input type="hidden" id="teacher_description" name="teacher_description" value="">
    <input type="hidden" name="lid" id="lid" value=""> -->
    <div class="row teacher">
      <div class="col-4 mb-5">
        <h6>강사 이미지 등록</h6>
        <div class="teacher_coverImg mb-3">
          <img src="../img/icon-img/no-image.png" id="coverImg" alt="">
        </div>
        <div class="input-group">
          <input type="file" class="form-control" accept="image/*" name="cover_image" id="cover_image" >
        </div>
        <div class="mt-3">
          <tr>
            <th scope="row">강사등급 선택</th>
            <td colspan="3">
              <div class="d-flex gap-3">
                <select class="form-select mt-3" name="grade" required>
                  <option value="" selected>등급을 선택해주세요</option>
                  <option value="Blonze">Blonze</option>
                  <option value="Silver">Silver</option>
                  <option value="Gold">Gold</option>
                  <option value="Vip">Vip</option>
                </select>
                
              </div>
            </td>
          </tr>
          <p class="mt-3">강사 등급은 강의 결제 수수료에 큰 영향을 미칩니다.</p>
        </div>
      </div>
      <div class="col-8 mb-3">
        <table class="table">
          <thead class="visually-hidden">
            <tr>
              <th scope="col">11111111111111111구분</th>
              <th scope="col">111111내용111111111</th>
            </tr>
          </thead>
          <tbody>
          <tr scope="row">
              <th scope="row" class="insert_name">이름</th>
              <td colspan="3">
                <input type="text" class="form-control" name="name" id="name" placeholder="이름을 입력해주세요" required maxlength="10">
                <div id="nameError" class="mt-2" style="color: red;"></div> <!-- 에러 메시지 위치 -->
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_id">아이디</th>
              <td colspan="3">
                <input type="text" class="form-control" name="id" id="id" placeholder="아이디를 입력해주세요(영어숫자 합 최대20자)" required maxlength="20">
                <div id="idError" class="mt-2" style="color: red;"></div> <!-- 에러 메시지 위치 -->
                <button type="button" id="idCheck" class="btn btn-secondary btn-sm">중복체크</button>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_birth">생년월일</th>
              <td colspan="3">
                <input type="text" class="form-control" name="birth" id="birth" placeholder="생년월일을 숫자로 6자리 입력해주세요.(ex.19901010)" required>
                <span id="error-message" style="color: red; display: none;">8자리 숫자만 입력 가능합니다.</span>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_password">비밀번호</th>
              <td colspan="3">
                <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호를 입력해주세요" required>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_passwordCheck">비밀번호 확인</th>
              <td colspan="3">
                <input type="password" class="form-control" name="passwordCheck" id="passwordCheck" placeholder="비밀번호를 한번 더 입력해주세요" required>
                <div id="passwordError" class="mt-2"></div> <!-- 에러 메시지 표시 위치 -->
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_email">이메일</th>
              <td colspan="3">
                <input type="text" class="form-control" name="email" id="email" placeholder="이메일을 입력해주세요" required>
                <span id="email-error" style="color: red; display: none;">올바른 이메일 형식이 아닙니다.</span>
                <button type="button" id="emailCheck" class="btn btn-secondary btn-sm mt-2">중복체크</button>

              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_number">전화번호</th>
              <td colspan="3">
                <input type="text" class="form-control" name="number" id="number" placeholder="전화번호를 입력해주세요" required>
                <span id="number-error" style="color: red; display: none;">올바른 전화번호 형식이 아닙니다. 숫자 최대 15자리로 입력해주세요.</span>
                <button type="button" id="numberCheck" class="btn btn-secondary btn-sm mt-2">중복체크</button>

              </td>
            </tr>
            <tr>
              <th scope="row">가입일</th>
              <td class="twoculumn_table">
                <input type="date" class="form-control" name="reg_date" id="reg_date" placeholder="" required>
                <span></span>
              </td>
            </tr>
            <tr>
              <th scope="row">강사 요약</th>
              <td class="twoculumn_table">
                <label for="teacher_detail" class="bold"></label>
                <textarea class="form-control" placeholder="강사 요약" name="teacher_detail" id="teacher_detail"></textarea>
              </td>
            </tr>
            
          </tbody>
        </table>
      </div>
    </div>
    <div class="mt-3 d-flex justify-content-end">
      <button class="btn btn-primary">등록</button>
    </div>
  </Form>
</div>

<script>
//이미지 구현창
function addCover(file, cover) {
    let coverImage = file;
    coverImage.on('change', (e) => {
      let file = e.target.files[0];
      let target = cover;
      if (file) {
        const reader = new FileReader();
        reader.onloadend = (e) => {
          let attachment = e.target.result;
          console.log(attachment);
          if (attachment) {
            target.attr('src', attachment);
          }
        }
        reader.readAsDataURL(file);
      } else {
        target.attr('src', '');
      }
    });
  }

  addCover($('#cover_image'), $('#coverImg'));

  //비밀번호 검증
  function validatePassword() {
    const password = document.getElementById('password').value;
    const passwordCheck = document.getElementById('passwordCheck').value;
    const message = document.getElementById('passwordError');

    if (password && passwordCheck) {
      if (password !== passwordCheck) {
        message.textContent = '비밀번호가 일치하지 않습니다.';
        message.style.color = 'red';
      } else {
        message.textContent = '비밀번호가 일치합니다.';
        message.style.color = 'green';
      }
    } else {
      message.textContent = ''; // 비어있는 경우 메시지 지우기
    }
  }
  // 비밀번호 필드에 이벤트 리스너 추가
  document.getElementById('password').addEventListener('input', validatePassword);
  document.getElementById('passwordCheck').addEventListener('input', validatePassword);

  document.getElementById("birth").addEventListener("blur", function() {
    const birthInput = this.value;
    const errorMessage = document.getElementById("error-message");
    
    // 숫자로만 구성된 8자리인지 검사
    if (!/^\d{8}$/.test(birthInput)) {
      errorMessage.style.display = "inline";
      this.style.borderColor = "red"; // 경고 시 입력창 테두리 색상 변경
    } else {
      errorMessage.style.display = "none";
      this.style.borderColor = ""; // 유효한 경우 테두리 색상 초기화
    }
  });

  //이름이름
  document.getElementById('name').addEventListener('input', validateName);
  function validateName() {
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    const nameValue = nameInput.value;
    // 한글만 포함되어 있는지, 최대 10자 이내인지 확인하는 정규 표현식
    const namePattern = /^[가-힣]{1,10}$/;

    if (!namePattern.test(nameValue)) {
      nameError.textContent = "이름은 한글로만 최대 10자까지 입력 가능합니다.";
    } else {
      nameError.textContent = ""; // 조건 만족 시 오류 메시지 지우기
    }
  }

  // 아이디 검증 함수
  document.getElementById('id').addEventListener('input', validateId);
   function validateId() {
    const idInput = document.getElementById('id');
    const idError = document.getElementById('idError');
    const idValue = idInput.value;

    // 영어와 숫자만 포함되어 있는지, 최대 20자 이내인지 확인하는 정규 표현식
    const idPattern = /^[A-Za-z0-9]{1,20}$/;

    if (!idPattern.test(idValue)) {
      idError.textContent = "아이디는 영어와 숫자로 최대 20자까지 입력 가능합니다.";
    } else {
      idError.textContent = ""; // 조건 만족 시 오류 메시지 지우기
    }
  }

  //전화번호
  document.getElementById('number').addEventListener('input', validateNumber);

  function validateNumber() {
  const numberInput = document.getElementById('number');
  const numberError = document.getElementById('number-error');
  const numberValue = numberInput.value;

  // 숫자로만 구성된 1~15자리인지 확인하는 정규 표현식
  const numberPattern = /^\d{1,15}$/;

  if (!numberPattern.test(numberValue)) {
    numberError.textContent = "전화번호는 숫자로만 최대 15자리까지 입력 가능합니다.";
    numberError.style.display = "block"; // 오류 메시지 표시
    numberInput.style.borderColor = "red"; // 경고 시 입력창 테두리 색상 변경
  } else {
    numberError.textContent = ""; // 조건 만족 시 오류 메시지 지우기
    numberError.style.display = "none"; // 오류 메시지 숨김
    numberInput.style.borderColor = ""; // 유효한 경우 테두리 색상 초기화
  }
}

  //이메일 형식
  document.getElementById("email").addEventListener("blur", function() {
    const emailInput = this.value;
    const errorMessage = document.getElementById("email-error");
    
    // 이메일 형식 검사 (간단한 정규 표현식)
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!emailPattern.test(emailInput)) {
      errorMessage.style.display = "block";
      this.style.borderColor = "red"; // 경고 시 입력창 테두리 색상 변경
    } else {
      errorMessage.style.display = "none";
      this.style.borderColor = ""; // 유효한 경우 테두리 색상 초기화
    }
  });






  //아이디 중복 체크
  let idChecked = false;
  let emailChecked = false;
  let numberChecked = false;

  $('#idCheck').click(function(){
    let value = $('#id').val();
    if(value == ''){
      alert('아이디를 입력해주세요');
      $('#id').focus();
    } else{
      Check_func('id', value);
      console.log('아이디 보내지는 것도됌')
    }
  });

  $('#emailCheck').click(function(){
    let value = $('#email').val();
    if(value == ''){
      alert('email을 입력해주세요');
      $('#email').focus();
    } else{
      Check_func('email', value);
      console.log('이메일 보내지는 것도됌')

    }
  });

  $('#numberCheck').click(function(){
    let value = $('#number').val();
    console.log(value);
    if(value == ''){
      alert('전화번호를 입력해주세요');
      $('#number').focus();
    } else{
      Check_func('number', value);
      console.log('번호 보내지는 것도됌')
    }
  });

  function Check_func(name, value){
    let data = {
      name:name,
      value:value
    }
    console.log(data);
    $.ajax({
      async:false,      
      url:'Check_func.php',
      data:data,
      type:'post',
      dataType:'json',
      error:function(e){
        //연결실패시 할일
        console.log(e);
        alert('서버 연결에 실패했습니다')
      },
      success:function(returend_data){
        //연결성공시 할일, image_delete.php가 echo 출력해준 값을 매배견수 returend_data 받자
        // console.log(returend_data);


        if(Number(returend_data.result) > 0){
          alert('중복됩니다, 다시 시도해주세요.');
        }else{
          alert('사용할 수 있습니다.');
          idChecked = true
        }
        }
      }
    )
  }


  $('#teacher_save').submit(function(e){
  if (!idChecked) {
    e.preventDefault();
    alert('아이디 중복체크를 해주세요');
  } else{
    $('#teacher_save').submit();
  }
  });

</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>