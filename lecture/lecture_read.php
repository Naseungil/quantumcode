<?php
$title = "학습하기";
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/lecture.css\" rel=\"stylesheet\">";
$video_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/video.css\" rel=\"stylesheet\">";
$libVideo_css = "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/video.js/8.21.1/video-js.min.css\" integrity=\"sha512-eAxdlYVdHHB5//MPUJMimVOM0OoKa3I1RWCnrqvzwri3u5+RBdB6d0fdBsEOj78PyomrHR3+E3vy0ovoVU9hMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />";
$libVideo_js = "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/video.js/8.21.1/video.min.js\" integrity=\"sha512-4ojVomDWDnx2FZyOK/eVZCTut+02zggocT1Cj8S7Y/E31ozUWlU0vZ5+rzVyy+hKZCG6Gt9RJ9elOMS70LBRtQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

$lid = $_GET['lid'];
$vidArr = [];
$sql = "SELECT * FROM lecture_video WHERE lid = $lid ORDER BY lvid";
$result = $mysqli->query($sql);
if (isset($result)) {
  while ($data = $result->fetch_object()) {
    $vidArr[] = $data->video_lecture;
  }
} else {
  echo
  "<script>
  alert('영상이 제공되지 않는 강의 입니다');
  location.href = './lecture_view.php?lid=<?= $lid ?>';
  </script> ";
}
$vidArrJson = json_encode($vidArr);
?>

<div class="hidden_hover"></div>
<div class="video">
  <div class="video_wrapper d-flex">
    <video id="lecture-video" class="video-js" width="100%" height="100%" controls>
      <source src="<?= $vidArr[0] ?>" type="video/mp4">
    </video>
    <div class="video_info">
      <h3>목차입니다</h3>
      <p>설명입니다</p>
    </div>
  </div>
  <div class="controls">
    <button id="prev-btn"><i class="fa-solid fa-backward"></i><span>이전 수업</span></button>
    <button id="next-btn"><span>다음 수업</span> <i class="fa-solid fa-forward"></i></button>
  </div>
  <aside>
    <div class="index" data-target="index">
      <img src="../img/icon-img/st_book.svg" width="40" alt="">
      <h5>강의 목차</h5>
    </div>
    <div class="desc" data-target="desc">
      <img src="../img/icon-img/ChatText.svg" width="40" alt="">
      <h5>강의 설명</h5>
    </div>
  </aside>

</div>


<script>
  // 강의 영상 배열 (PHP에서 전달)
  const vidArr = <?= $vidArrJson; ?>;
  let currentIndex = 0; // 현재 영상 인덱스

  // 영상 요소 참조
  const video = document.getElementById('lecture-video');
  const prevBtn = document.getElementById('prev-btn');
  const nextBtn = document.getElementById('next-btn');
  const navBar = document.querySelector('.navbar');
  const hoverBar = document.querySelector('.hidden_hover');
  const asideBtn = document.querySelectorAll('aside > div');
  const menuItems = document.querySelectorAll(".menu-item");

  let excuted = false;
  //헤더 호버 이벤트
  hoverBar.addEventListener('mouseenter', () => {
    setTimeout(() => {
      navBar.classList.add('active')
    }, 2000);
  })
  hoverBar.addEventListener('mouseleave', () => {
    setTimeout(() => {
      navBar.classList.remove('active')
    }, 2000);
  })

  // 영상 재생 함수
  const loadVideo = (index) => {
    if (index >= 0 && index < vidArr.length) {
      video.src = vidArr[index]; // 비디오 URL 설정
      video.play(); // 자동 재생
      currentIndex = index; // 현재 인덱스 업데이트
    } else {
      alert('강의가 더 이상 없습니다.');
    }
  };

  // 버튼 이벤트 핸들러
  prevBtn.addEventListener('click', () => {
    loadVideo(currentIndex - 1); // 이전 영상 로드
  });

  nextBtn.addEventListener('click', () => {
    loadVideo(currentIndex + 1); // 다음 영상 로드
  });

  // 페이지 로드 시 첫 번째 영상 자동 재생
  if (vidArr.length > 0) {
    loadVideo(0);
  } else {
    alert('영상이 제공되지 않는 강의입니다.');
  }

  //aside 메뉴를 열기
  let currentTarget = null;
  asideBtn.forEach(btn => {
    btn.addEventListener('click', (e) => {

      e.currentTarget.classList.add('active');
      const target = btn.getAttribute("data-target");
      if (currentTarget === target) {
        // 같은 메뉴를 클릭했을 때 닫기
        e.currentTarget.classList.remove('active');

        btn.closest('.video').firstElementChild.classList.remove('open');
        currentTarget = null;
      } else {
        // 다른 메뉴를 클릭했을 때
        currentTarget = target;
        asideBtn.forEach(btn => {
          btn.classList.remove('active');
        })
        e.currentTarget.classList.add('active');
        btn.closest('.video').firstElementChild.classList.add('open')
      }

    })
  })

  videojs(video, {
    controls: true,
    autoplay: false,
    preload: 'auto',
    muted: true,
  });
</script>