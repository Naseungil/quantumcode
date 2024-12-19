<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

// 세션에서 사용자 이메일 가져오기
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/loginTest2.php';</script>";
    exit;
}

$userEmail = $_SESSION['MemEmail'];
$memId = $_SESSION['MemId'];

// SQL 쿼리 준비
$sql = "SELECT * FROM membersKakao WHERE memEmail = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();

// 결과 가져오기
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>내 정보 수정하기</title>
  <link rel="stylesheet" href="/qc/css/core-style.css?v=<?= time(); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
</head>
<body>
  <div class="container mt-3">
    <h3>나의 정보 수정하기</h3>
    <form id="user_update" action="user_update_ok.php" method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- 프로필 섹션 -->
        <div class="col-md-4 text-center">
          <?php $profileImage = $data['memProfilePath'] ?? '../img/icon-img/no-image.png'; ?>
          <img src="<?= htmlspecialchars($profileImage); ?>" class="mb-3" id="coverImg" style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%; border: 2px solid #ccc;">
          <input type="file" class="form-control mt-2" accept="image/*" name="cover_image" id="cover_image">
        </div>

        <!-- 정보 섹션 -->
        <div class="col-md-8">
          <table class="table table-bordered">
            <tr>
              <th>이름</th>
              <td><input type="text" class="form-control" name="memName" value="<?= htmlspecialchars($data['memName']); ?>" required></td>
            </tr>
            <tr>
              <th>이메일</th>
              <td><input type="text" class="form-control" value="<?= htmlspecialchars($data['memEmail']); ?>" disabled></td>
            </tr>
            <tr>
              <th>생년월일</th>
              <td>
                <input type="date" class="form-control" name="birth" id="birth" 
                      value="<?= htmlspecialchars($data['birth']); ?>" >
              </td>
            </tr>
            <tr>
              <th>주소</th>
              <td>
                <div class="input-group">
                  <input type="text" class="form-control" name="memAddr" id="memAddr" value="<?= htmlspecialchars($data['memAddr']); ?>" >
                  <button type="button" class="btn btn-secondary" id="findAddressBtn">주소 찾기</button>
                </div>
              </td>
            </tr>
            <tr>
              <th>가입일</th>
              <td><input type="text" class="form-control" value="<?= htmlspecialchars($data['memCreatedAt']); ?>" disabled></td>
            </tr>
            <tr>
              <th>마지막 로그인</th>
              <td><input type="text" class="form-control" value="<?= htmlspecialchars($data['lastLoginAt']); ?>" disabled></td>
            </tr>
            <tr>
              <th>로그인 횟수</th>
              <td><input type="text" class="form-control" value="<?= htmlspecialchars($data['login_count']); ?> 회" disabled></td>
            </tr>
            <tr>
              <th>전화번호</th>
              <td><input type="text" class="form-control" name="number" value="<?= htmlspecialchars($data['number']); ?>" ></td>
            </tr>
            <tr>
              <th>등급</th>
              <td><input type="text" class="form-control" value="<?= htmlspecialchars($data['grade']); ?>" disabled></td>
            </tr>
          </table>
          <div class="text-end">
            <button type="button" class="btn btn-danger">탈퇴하기</button>
            <button type="button" class="btn btn-primary">저장하기</button>
          </div>
        </div>
      </div>
    </form>
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
    
// 카카오 주소 API 활용. 
document.getElementById("findAddressBtn").addEventListener("click", function() {
  new daum.Postcode({
    oncomplete: function(data) {
      let fullAddress = data.roadAddress;
      let extraAddress = '';

      if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
        extraAddress += data.bname;
      }
      if (data.buildingName !== '' && data.apartment === 'Y') {
        extraAddress += (extraAddress !== '' ? ', ' + data.buildingName : data.buildingName);
      }

      fullAddress += (extraAddress !== '' ? ' (' + extraAddress + ')' : '');
      document.getElementById("memAddr").value = fullAddress;
    }
  }).open();
});

$('#user_update .btn-primary').click(function(e) {
    e.preventDefault(); // 폼의 기본 submit 동작을 중단합니다.

    // FormData 객체를 사용하여 폼 데이터 수집
    const formData = new FormData($('#user_update')[0]);

    // FormData 내용 확인
    console.log("FormData 내용 확인:");
    for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }

    $.ajax({
        url: 'user_update_ok.php', // 데이터 전송 대상 URL
        type: 'POST', // HTTP 요청 방식
        data: formData,
        processData: false, // 파일 업로드 시 필수: 데이터 처리를 하지 않음
        contentType: false, // 파일 업로드 시 필수: 컨텐츠 타입 설정을 자동 처리
        success: function(response) {
            // 서버에서 응답을 성공적으로 받았을 때 실행
            if (response.status === 'success') {
                alert('정보가 성공적으로 수정되었습니다.');
                location.reload(); // 페이지 새로고침
            } else {
                alert('정보 수정에 실패했습니다: ' + response.message);
            }
        },
        error: function() {
            // 서버 연결 실패 시 실행
            alert('서버와의 연결에 실패했습니다. 다시 시도해주세요.');
        }
    });
});

  </script>
</body>
</html>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php'); ?>