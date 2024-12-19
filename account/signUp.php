<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .signup-container {
            max-width: 480px;
            margin: 80px auto;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
        }
        .btn-submit:hover {
          background-color: #0056b3; /* 호버 시 어두운 파란색 */
          color: white;

        }
        .form-check-label {
            font-size: 14px;
        }
        .text-link {
            color: #007bff;
            text-decoration: none;
        }
        .text-link:hover {
            text-decoration: underline;
        }
        .form-text {
            font-size: 12px;
            color: #6c757d;
        }
        .modal-dialog {
        max-width: 40%; /* 모달의 최대 너비를 80%로 설정 */
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container text-center">
            <!-- 로고 -->
            <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/index2.php">
             <img src="../img/main_logo1.png" alt="Logo" class="mb-4">
            </a>
            
            <!-- 제목 -->
            <h4 class="mb-3">인생을 바꾸는 교육,</h4>
            <h5 class="mb-4">퀀텀 코드 회원가입</h5>

            <!-- 회원가입 양식 -->
            <form action="signUp_ok.php" method="POST" id="signUp_ok" enctype="multipart/form-data">
                <!-- 이름 -->
                <div class="form-group mb-3">
                    <input type="text" class="form-control" name="name" id="name" placeholder="이름(닉네임)을 입력해 주세요." required maxlength="10" style="border-radius: 8px;">
                </div>
                
                <!-- 이메일 입력과 인증메일 보내기 버튼 -->
                <div class="form-group mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" id="email" placeholder="이메일 입력해 주세요." required maxlength="40" style="border-radius: 8px;">
                        <button class="btn btn-outline-secondary ms-2" type="button" style="border-radius: 8px;" id="emailCheck">중복체크</button>
                    </div>
                </div>

                <!-- 휴대폰 번호 입력과 중복체크 버튼 -->
                <div class="form-group mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="number" id="number" placeholder="휴대폰 번호를 입력해주세요" required maxlength="13" style="border-radius: 8px;">
                        <button class="btn btn-outline-secondary ms-2" type="button" style="border-radius: 8px;" id="numberCheck">중복체크</button>
                    </div>
                </div>

                <!-- 비밀번호 -->
                <div class="form-group mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호" required style="border-radius: 8px;">
                    <!-- 비밀번호 안내 문구 -->
                    <div class="form-text">8자 이상, 숫자와 특수문자 포함을 권장합니다.</div>
                </div>

                <!-- 비밀번호 확인 -->
                <div class="form-group mb-3">
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="비밀번호 확인" required style="border-radius: 8px;">
                </div>
                <input type="hidden" id="memCreatedAt" name="memCreatedAt">
                

                <!-- 약관 동의 -->
                <div class="form-check mb-2 text-start">
                    <input class="form-check-input" type="checkbox" id="agree_terms" required>
                    <label class="form-check-label" for="agree_terms">
                        서비스 이용약관 동의 (필수) <a href="#" class="text-link">보기</a>
                    </label>
                </div>
                <div class="form-check mb-2 text-start">
                    <input class="form-check-input" type="checkbox" id="agree_privacy" required>
                    <label class="form-check-label" for="agree_privacy">
                        개인정보 수집 및 이용 동의 (필수) <a href="#" class="text-link">보기</a>
                    </label>
                </div>
                <div class="form-check mb-4 text-start">
                    <input class="form-check-input" type="checkbox" id="agree_marketing">
                    <label class="form-check-label" for="agree_marketing">
                        마케팅 수신 동의 (선택) <a href="#" class="text-link">보기</a>
                    </label>
                </div>
                

                <!-- 회원가입 버튼 -->
                <button type="submit" class="btn btn-submit w-100" style="border-radius: 8px;">회원가입하기</button>
                <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/logintest2.php" class="btn btn-light w-100 mt-3" style="border-radius: 8px;">로그인 페이지로 돌아가기</a>
            </form>
        </div>
    </div>

    <!-- 약관 모달 -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">서비스 이용약관</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- 여기에 약관 내용이 동적으로 들어갑니다. -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>

    <script>
    //지금 시간을 디비에 저장하도록 설정.
    document.addEventListener('DOMContentLoaded', function () {
    const now = new Date();
    const formattedTime = now.toISOString().slice(0, 19).replace('T', ' '); // MySQL DATETIME 형식으로 변환
    document.getElementById('memCreatedAt').value = formattedTime; // 숨겨진 input에 시간 설정

     // 약관 모달을 표시하는 함수
     function showModal(title, content) {
        const modalTitle = document.getElementById('termsModalLabel');
        const modalBody = document.getElementById('modalContent');

        modalTitle.textContent = title;
        modalBody.innerHTML = content;

        const modal = new bootstrap.Modal(document.getElementById('termsModal'));
        modal.show();
    }

    // 각 "보기" 링크 클릭 이벤트
    document.querySelector('label[for="agree_terms"] .text-link').addEventListener('click', function (e) {
        e.preventDefault();
        const termsContent = `
        <p>이용약관 내용입니다. 이곳에 이용약관 전문을 넣어주세요.</p>
        <ul>
            <li>제1조: 서비스 이용 목적</li>
            <li>제2조: 사용자의 의무</li>
            <li>제3조: 개인정보 보호</li>
            <li>제4조: 서비스 제공 및 변경</li>
            <li>제5조: 서비스 이용의 제한 및 정지</li>
            <li>제6조: 계약 해지 및 손해배상</li>
            <li>제7조: 면책 조항</li>
            <li>제8조: 준거법 및 관할법원</li>
        </ul>
        <h6>제1조 서비스 이용 목적</h6>
        <p>이 약관은 사용자가 회사에서 제공하는 서비스를 올바르게 이용할 수 있도록 하는 데 목적이 있습니다.</p>
        <h6>제2조 사용자의 의무</h6>
        <p>사용자는 다음과 같은 행위를 해서는 안 됩니다:</p>
        <ul>
            <li>타인의 개인정보 도용</li>
            <li>서비스를 이용한 불법 행위</li>
            <li>회사 및 타인의 명예 훼손</li>
        </ul>
        <h6>제3조 개인정보 보호</h6>
        <p>회사는 관련 법령에 따라 사용자의 개인정보를 보호합니다. 자세한 내용은 개인정보 처리방침을 참고하시기 바랍니다.</p>
        <h6>제4조 서비스 제공 및 변경</h6>
        <p>회사는 서비스의 내용 및 제공 방식을 변경할 수 있으며, 변경 시 사전 공지합니다.</p>
        <h6>제5조 서비스 이용의 제한 및 정지</h6>
        <p>다음의 경우 서비스 이용이 제한될 수 있습니다:</p>
        <ul>
            <li>서비스 운영을 방해하는 경우</li>
            <li>약관을 위반한 경우</li>
        </ul>
        <h6>제6조 계약 해지 및 손해배상</h6>
        <p>계약 해지 및 손해배상에 대한 사항은 별도로 정한 규정을 따릅니다.</p>
        <h6>제7조 면책 조항</h6>
        <p>회사는 다음과 같은 사유로 발생한 손해에 대해 책임을 지지 않습니다:</p>
        <ul>
            <li>천재지변 또는 불가항력</li>
            <li>사용자의 귀책사유</li>
        </ul>
        <h6>제8조 준거법 및 관할법원</h6>
        <p>이 약관은 대한민국 법률에 따라 해석되며, 분쟁 발생 시 관할법원은 회사의 본사 소재지를 기준으로 합니다.</p>
    `;
    showModal('서비스 이용약관', termsContent);
    });

    document.querySelector('label[for="agree_privacy"] .text-link').addEventListener('click', function (e) {
        e.preventDefault();
        const privacyContent = `
        <p>개인정보 수집 및 이용에 대한 동의 내용입니다. 본 정책은 개인정보 보호법에 근거하여 작성되었습니다.</p>
        <ul>
            <li><strong>수집항목:</strong> 이름, 이메일, 연락처, 서비스 이용 기록</li>
            <li><strong>수집목적:</strong> 
                <ul>
                    <li>회원 관리 및 본인 확인</li>
                    <li>서비스 제공 및 이용 분석</li>
                    <li>고객 문의 및 불만 처리</li>
                    <li>마케팅 및 이벤트 정보 제공</li>
                </ul>
            </li>
            <li><strong>수집방법:</strong> 회원가입 시 입력, 서비스 이용 시 자동 수집</li>
            <li><strong>보유기간:</strong> 
                <ul>
                    <li>회원 탈퇴 시 즉시 삭제</li>
                    <li>단, 법령에 따라 보존이 필요한 경우에는 관련 법령에 따름</li>
                </ul>
            </li>
            <li><strong>동의 거부 권리:</strong> 개인정보 수집에 동의하지 않을 수 있으나, 이 경우 서비스 이용이 제한될 수 있습니다.</li>
        </ul>
        <h6>개인정보의 제3자 제공</h6>
        <p>회사는 원칙적으로 사용자의 동의 없이 개인정보를 제공하지 않으며, 다음의 경우에만 제3자에게 제공할 수 있습니다:</p>
        <ul>
            <li>법령에 의거하여 제공이 요구되는 경우</li>
            <li>사용자가 사전에 동의한 경우</li>
        </ul>
        <h6>개인정보 보호를 위한 조치</h6>
        <p>회사는 개인정보 보호를 위해 다음과 같은 조치를 취합니다:</p>
        <ul>
            <li>개인정보 암호화 및 보안 시스템 강화</li>
            <li>접근 권한 관리 및 최소화</li>
            <li>정기적인 보안 점검 및 교육</li>
        </ul>
    `;
        showModal('개인정보 수집 및 이용 동의', privacyContent);
    });

    document.querySelector('label[for="agree_marketing"] .text-link').addEventListener('click', function (e) {
        e.preventDefault();
        const marketingContent = `
            <p>마케팅 수신 동의에 대한 내용입니다. 선택사항이며, 동의하지 않으셔도 서비스 이용에는 제한이 없습니다.</p>
            <ul>
                <li><strong>목적:</strong> 광고 및 이벤트 안내, 맞춤형 서비스 제공</li>
                <li><strong>수신 채널:</strong> 
                    <ul>
                        <li>이메일</li>
                        <li>SMS (문자 메시지)</li>
                        <li>앱 푸시 알림</li>
                    </ul>
                </li>
                <li><strong>보유 및 이용 기간:</strong> 
                    <ul>
                        <li>동의 철회 또는 회원 탈퇴 시까지</li>
                        <li>단, 법령에 따라 보존이 필요한 경우는 예외</li>
                    </ul>
                </li>
                <li><strong>동의 철회:</strong> 
                    <ul>
                        <li>수신 거부를 원하실 경우 설정 페이지에서 언제든 철회할 수 있습니다.</li>
                        <li>고객센터를 통해서도 철회 요청이 가능합니다.</li>
                    </ul>
                </li>
            </ul>
            <h6>유의사항</h6>
            <p>마케팅 수신 동의를 철회하시더라도, 법적 고지나 거래 관련 정보는 계속 발송될 수 있습니다.</p>
        `;
        showModal('마케팅 수신 동의', marketingContent);
    });
});

    //중복 체크
    let emailChecked = false;
    let numberChecked = false;

    $('#emailCheck').click(function(){
        let value = $('#email').val();
        if(value == ''){
        alert('이메일을 입력해주세요.');
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
          emailChecked = true;
          numberChecked = true;
        }
        }
      }
    )
  }

  $('#signUp_ok').submit(function(e) {
    // 필수 체크박스 상태 확인
    const agreeTermsChecked = $('#agree_terms').is(':checked');
    const agreePrivacyChecked = $('#agree_privacy').is(':checked');

    if (!emailChecked || !numberChecked) {
        e.preventDefault(); // 제출 막기
        alert('이메일과 전화번호 중복 체크에 문제가 있습니다. 다시 확인해주세요.');
    } else if (!agreeTermsChecked || !agreePrivacyChecked) {
        e.preventDefault(); // 제출 막기
        alert('필수 약관에 동의해주세요.');
    } else {
        // 모든 조건이 만족되었을 때만 폼 제출
        $(this).unbind('submit').submit(); // 기본 제출 이벤트를 다시 연결하여 실행
    }
});
    </script>
</body>
</html>
