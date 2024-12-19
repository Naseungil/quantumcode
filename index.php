<?php

$main_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/main.css\" rel=\"stylesheet\">";
$slick_css = "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css\" integrity=\"sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />";
$slick_js = "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js\" integrity=\"sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');
//$email = $_SESSION['MemEmail']; $memId = $_SESSION['MemId']; 로 되어있다.

//추천강의
// 사용자 카테고리 기반 맞춤 추천 강의 데이터 가져오기
$query = "
    SELECT 
        lc.code, 
        IFNULL(lc.pcode, NULL) AS pcode, 
        IFNULL(lc.ppcode, NULL) AS ppcode
    FROM 
        user_categories AS uc
    INNER JOIN 
        lecture_category AS lc
    ON 
        JSON_CONTAINS(uc.category, JSON_QUOTE(lc.name))
    WHERE 
        uc.user_email = ?
";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
  die('카테고리 쿼리 준비 실패: ' . $mysqli->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$customRecommendedLectures = []; // 맞춤 추천 강의 배열

while ($row = $result->fetch_assoc()) {
  $combinedCustomOrder = ($row['ppcode'] !== null ? $row['ppcode'] : 'NULL')
    . ($row['pcode'] !== null ? $row['pcode'] : 'NULL')
    . $row['code'];

  $lectureQuery = "
        SELECT 
            lid, category, title, cover_image, t_id, isfree, ispremium, ispopular, isrecom, 
            tuition, dis_tuition, regist_day, expiration_day, sub_title, difficult 
        FROM 
            lecture_list 
        WHERE 
            category = ?
        ORDER BY 
            student_count DESC 
        LIMIT 10
    ";

  $lectureStmt = $mysqli->prepare($lectureQuery);
  if (!$lectureStmt) {
    die('강의 쿼리 준비 실패: ' . $mysqli->error);
  }

  $lectureStmt->bind_param("s", $combinedCustomOrder);
  $lectureStmt->execute();
  $lectureResult = $lectureStmt->get_result();

  while ($lectureRow = $lectureResult->fetch_assoc()) {
    $customRecommendedLectures[] = $lectureRow;
  }

  $lectureStmt->close();
}

//인기 강의
$sql = "SELECT * FROM lecture_list WHERE ispopular = 1";
$result = $mysqli->query($sql);
$dataArr = [];
while ($data = $result->fetch_object()) {
  $dataArr[] = $data;
}

//프리미엄 강의
$sql2 = "SELECT * FROM lecture_list WHERE ispremium = 1";
$result2 = $mysqli->query($sql2);
$dataArr2 = [];
while ($data2 = $result2->fetch_object()) {
  $dataArr2[] = $data2;
}

//추천강의
$sql3 = "SELECT * FROM lecture_list WHERE isrecom = 1";
$result3 = $mysqli->query($sql3);
$dataArr3 = [];
while ($data3 = $result3->fetch_object()) {
  $dataArr3[] = $data3;
}

//무료강의
$sql4 = "SELECT * FROM lecture_list WHERE isfree = 1";
$result4 = $mysqli->query($sql4);
$dataArr4 = [];
while ($data4 = $result4->fetch_object()) {
  $dataArr4[] = $data4;
}

$review_sql = "SELECT lr.*, l.title 
FROM lecture_review lr
LEFT JOIN lecture_list l
ON lr.lid = l.lid
";
$review_result = $mysqli->query($review_sql);
$reviewArr = [];
while ($review_row = $review_result->fetch_object()) {
  $reviewArr[] = $review_row;
}

$notice_sql = "SELECT * FROM board WHERE category = 'notice'";
$notice_result = $mysqli->query($notice_sql);
$noticeArr = [];
while ($notice_row = $notice_result->fetch_object()) {
  $noticeArr[] = $notice_row;
}

?>

<!-- Placeholder for Main Content -->
<main>
  <section class="banner">
    <div class="main_slides">
      <div class="slide">
        <img src="img/core-img/001.png" height="400" alt="배너 이미지">
      </div>
      <div class="slide">
        <img src="img/core-img/002.png" height="400" alt="배너 이미지">
      </div>
      <div class="slide">
        <img src="img/core-img/003.png" height="400" alt="배너 이미지">
      </div>
      <div class="slide">
        <img src="img/core-img/004.png" height="400" alt="배너 이미지">
      </div>
    </div>

    <div class="custom container">
      <div class="banner_controls">
        <button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>
        <span>1/4</span>
        <button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
      <div class="custom-pagination "></div>
    </div>



    <div class="main_notice container d-flex">
      <h2 class=" "><i class="fa-solid fa-triangle-exclamation"></i> 공지</h2>
      <div class="notice_slides ">
        <?php
        if (!empty($noticeArr)) {
          $today = date("Y.m.d", time());
          if (count($noticeArr) > 0) {
            foreach ($noticeArr as $notice) {
              $date = date_create($notice->date);
        ?>
              <div class="notice_text d-flex justify-content-between">
                <span><?= $notice->title ?></span>
                <span><?= date_format($date, 'Y.m.d') ?></span>
              </div>
            <?php
            }
          } else {

            ?>
            <div class="notice_text d-flex justify-content-between">
              <span>[공지사항] 신규 업데이트가 없습니다</span>
              <span><?= $today ?></span>
            </div>
            <div class="notice_text d-flex justify-content-between">
              <span>[공지사항] 신규 업데이트가 없습니다</span>
              <span><?= $today ?></span>
            </div>
        <?php

          }
        }
        ?>
      </div>
      <div class="notice_controls">
        <button class="slick-prev"><i class="fa-solid fa-angle-up"></i></button>
        <button class="slick-next"><i class="fa-solid fa-angle-down"></i></button>
      </div>
    </div>
  </section>
  <section class="skill_tag container ">
    <div>
      <figure>
        <div><img src="./img/icon-img/html_icon.svg" alt=""></div>
        <figcaption>HTML</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/css_icon.svg" alt=""></div>
        <figcaption>CSS</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/javascript_icon.svg" alt=""></div>
        <figcaption>JavaScript</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/react_icon.svg" alt=""></div>
        <figcaption>React</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/java_icon.svg" alt=""></div>
        <figcaption>Java</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/jquery_icon.svg" alt=""></div>
        <figcaption>Jqeury</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/python_icon.svg" alt=""></div>
        <figcaption>Python</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/php_icon.svg" alt=""></div>
        <figcaption>PHP</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/node_js_icon.svg" alt=""></div>
        <figcaption>Node.js</figcaption>
      </figure>
      <figure>
        <div><img src="./img/icon-img/vue_icon.svg" alt=""></div>
        <figcaption>Vue.js</figcaption>
      </figure>
    </div>
    <p class="skill_cnt"></p>
    <p id="skill_filter" class="d-flex">

    </p>
  </section>



  <!-- user_categories 테이블에서 user_email이 $_SESSION['MemEmail']과 같은 경우에만 조건문을 보여주도록  -->
  <?php
  if (isset($_SESSION['MemEmail'])) {
    $email = $_SESSION['MemEmail'];

    // Check if the user_email exists in the user_categories table
    $checkUserQuery = "SELECT 1 FROM user_categories WHERE user_email = ?";
    $checkUserStmt = $mysqli->prepare($checkUserQuery);
    if (!$checkUserStmt) {
      die('사용자 확인 쿼리 준비 실패: ' . $mysqli->error);
    }

    $checkUserStmt->bind_param("s", $email);
    $checkUserStmt->execute();
    $userExists = $checkUserStmt->get_result()->fetch_row();

    $checkUserStmt->close();

    // Display the content only if the user_email exists in the user_categories table
    if ($userExists) {
  ?>
      <div class="recom_algorithm container"> <!-- Flex 컨테이너 -->
        <h6><?php echo htmlspecialchars($_SESSION['MUNAME']); ?>님을 위한 맞춤별 추천 강의</h6>
        <h3 class="mb-3">맞춤별 추천 강의</h3>
        <p>관심있는 강의를 추천알고리즘을 통해 만나보세요!</p>
        <div class="algorithm">
          <?php
          foreach ($customRecommendedLectures as $item) {
            $tuition = '';
            if ($item['dis_tuition'] > 0) {
              $tui_val = number_format($item['tuition']);
              $distui_val = number_format($item['dis_tuition']);
              $tuition .= "<p class=\"active-font\"> $distui_val 원 </p><p class=\"text-decoration-line-through small-font\"> $tui_val 원 </p>";
            } else {
              $tui_val = number_format($item['tuition']);
              $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p><p class=\"small-font\"> &nbsp; </p>";
            }
          ?>
            <section class="slide d-flex flex-column justify-content-between">
              <div>
                <div class="cover mb-2">
                  <img src="<?= htmlspecialchars($item['cover_image']) ?>" alt="강의 이미지">
                </div>
                <div class="info">
                  <div class="title">
                    <h5 class="small-font mb-0"><a href="lecture/lecture_view.php?lid=<?= htmlspecialchars($item['lid']) ?>"><?= htmlspecialchars($item['title']) ?></a></h5>
                  </div>
                  <div class="tuition">
                    <?= $tuition ?>
                  </div>
                  <ul class="tags">
                    <?php if ($item['ispopular']): ?>
                      <li class="tag"><span> 인기 </span></li>
                    <?php endif; ?>
                    <?php if ($item['isrecom']): ?>
                      <li class="tag"><span> 추천 </span></li>
                    <?php endif; ?>
                    <?php if ($item['ispremium']): ?>
                      <li class="tag"><span> 프리미엄 </span></li>
                    <?php endif; ?>
                    <?php if ($item['isfree']): ?>
                      <li class="tag"><span> 무료 </span></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </section>
          <?php
          }
          ?>
        </div>
        <div class="algorithm_controls">
          <button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>
          <button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
      </div>
  <?php
    } else {
      // echo "<p>추천 강의가 없습니다. 관심 카테고리를 설정해주세요!</p>";
    }
  }
  ?>

  <div class="main_popular container"> <!-- Flex 컨테이너 -->
    <h6>BEST</h6>
    <h3 class="mb-3">인기 강의</h3>
    <p>수강생 PICK! 지금 가장 뜨거운 강의, 당신의 성공을 위한 필수 선택! </p>
    <div class="popular">
      <?php
      foreach ($dataArr as $item) {
        $tuition = '';
        if ($item->dis_tuition > 0) {
          $tui_val = number_format($item->tuition);
          $distui_val = number_format($item->dis_tuition);
          $tuition .= "<p class=\"active-font\"> $distui_val 원 </p><p class=\"text-decoration-line-through small-font\"> $tui_val 원 </p>";
        } else {
          $tui_val = number_format($item->tuition);
          $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p><p class=\"small-font\"> &nbsp; </p>";
        }
      ?>
        <section class="slide d-flex flex-column justify-content-between">
          <div>
            <div class="cover mb-2">
              <img src="<?= $item->cover_image ?>" alt="">
            </div>
            <div class="info">
              <div class="title ">
                <h5 class="small-font mb-0"><a href="lecture/lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
              </div>
              <div class="tuition ">
                <?= $tuition ?>
              </div>
              <ul class="">
                <!-- <li class="d-flex align-items-center gap-2"> <img src="../img/icon-img/review.svg" alt=""> 5점 </li>
                <li class="like d-flex align-items-center"><img src="../img/icon-img/Heart.svg" width="10" height="10" alt="">500+</li> -->
                <li class="tag"><span> 인기 </span> </li>
              </ul>
            </div>
          </div>
        </section>

      <?php
      }
      ?>
    </div>
    <div class="porpular_controls">
      <button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>
      <button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>

  <section class="main_premium container">
    <h6>Premium</h6>
    <h3 class="mb-3">프리미엄 강의</h3>
    <p>최고를 꿈꾸는 당신을 위한 차별화된 교육, 프리미엄 강의로 도약하세요! </p>
    <div class="premium">
      <?php
      foreach ($dataArr2 as $item) {
        $tuition = '';
        if ($item->dis_tuition > 0) {
          $tui_val = number_format($item->tuition);
          $distui_val = number_format($item->dis_tuition);
          $tuition .= "<p class=\"active-font\"> $distui_val 원 </p><p class=\"text-decoration-line-through small-font\"> $tui_val 원 </p>";
        } else {
          $tui_val = number_format($item->tuition);
          $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p><p class=\"small-font\"> &nbsp; </p>";
        }
        $title = $item->title;
        if (iconv_strlen($title) > 35) {
          $title = iconv_substr($title, 0, 35) . '...';
        }
      ?>

        <div class="slide mx-3">
          <img src="<?= $item->cover_image ?>" alt="">
          <div class="info d-flex flex-column  justify-content-evenly">
            <h5><a href="lecture/lecture_view.php?lid=<?= $item->lid ?>"><?= $title ?></a></h5>
            <p><?= $item->learning_obj ?></p>
            <div class="tuition">
              <?= $tuition ?>
            </div>
            <ul>
              <li><span>프리미엄</span></li>
            </ul>
          </div>
        </div>
        <div class="slide mx-3">
          <img src="<?= $item->cover_image ?>" alt="">
          <div class="info d-flex flex-column  justify-content-evenly">
            <h5><a href="lecture/lecture_view.php?lid=<?= $item->lid ?>"><?= $title ?></a></h5>
            <p><?= $item->learning_obj ?></p>
            <div class="tuition">
              <?= $tuition ?>
            </div>
            <ul>
              <li><span>프리미엄</span></li>
            </ul>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
    <div class="premium_controls">
      <button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>
      <button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </section>

  <section class="main_info text-center">
    <div>
      <h3>퀀텀코드: 코드로 미래를 뛰어넘다</h3>
      <h4>최신 기술을 실무 중심으로 배우며, 스스로의 가능성을 퀀텀 점프 시킬 수 있는 최고의 코드 강의 플랫폼 "오늘의 배움이 내일의 기술이 됩니다. 퀀텀코드와 함께 도전하세요."</h4>
      <ul class="">
        <li>
          <h6>강의 만족도 99%</h6>
          <p>하루 20분의 기적 <br>수강생 만족도</p>
          <img src="./img/icon-img/info_img1.svg" alt="">
        </li>
        <li>
          <h6>320만 회원돌파</h6>
          <p>코딩 공부를 위해 <br>퀀텀코드를 선택한 회원</p>
          <img src="./img/icon-img/info_img2.svg" alt="">
        </li>
        <li>
          <h6>24년의 코딩노하우</h6>
          <p>퀀텀코드 만의 <br>코딩교육 노하우</p>
          <img src="./img/icon-img/info_img3.svg" alt="">
        </li>
        <li>
          <h6>10명 중 9명 완강 실적</h6>
          <p>수강생이 선택한 <br>학습효과</p>
          <img src="./img/icon-img/info_img4.svg" alt="">
        </li>
      </ul>
      <button>퀀텀코드 자세히 알기</button>
    </div>
  </section>
  <!-- 수강평 3개 이하면 임시 더미 텍스트 -->
  <section class="container main_review">
    <h3 class="d-flex justify-content-between"><b>수강생 후기</b><a href="#">더보기</a> </h3>
    <div class="review_content d-flex gap-3">
      <?php
      if (!empty($reviewArr)) {
        if (count($reviewArr) > 2) {
          foreach ($reviewArr as $review) {
      ?>
            <figure class="d-flex align-items-start">
              <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc<?= $review->profile_image ?>" alt="회원 프로필 이미지">
              <figcaption>
                <div class="d-flex gap-3">
                  <b><?= $review->username ?></b>
                  <span><?= $review->title ?></span>
                </div>
                <p><?= $review->comment ?> </p>
              </figcaption>
            </figure>
          <?php
          }
        } else {
          ?>
          <figure class="d-flex align-items-start">
            <img src="./img/core-img/어드민_이미지.png" alt="">
            <figcaption>
              <div>
                <b>김민준</b>
                <span>만들면서 배우는 리액트</span>
              </div>
              <p>리액트를 처음 접했는데, 퀀텀코드 강의 덕분에 프로젝트를 직접 만들며 빠르게 배울 수 있었습니다. 강의가 체계적이고 실습 위주라서 이해가 정말 잘 돼요. </p>
            </figcaption>
          </figure>
          <figure class="d-flex align-items-start">
            <img src="./img/core-img/어드민_이미지.png" alt="">
            <figcaption>
              <div>
                <b>김민준</b>
                <span>만들면서 배우는 리액트</span>
              </div>
              <p>리액트를 처음 접했는데, 퀀텀코드 강의 덕분에 프로젝트를 직접 만들며 빠르게 배울 수 있었습니다. 강의가 체계적이고 실습 위주라서 이해가 정말 잘 돼요. </p>
            </figcaption>
          </figure>
          <figure class="d-flex align-items-start">
            <img src="./img/core-img/어드민_이미지.png" alt="">
            <figcaption>
              <div>
                <b>김민준</b>
                <span>만들면서 배우는 리액트</span>
              </div>
              <p>리액트를 처음 접했는데, 퀀텀코드 강의 덕분에 프로젝트를 직접 만들며 빠르게 배울 수 있었습니다. 강의가 체계적이고 실습 위주라서 이해가 정말 잘 돼요. </p>
            </figcaption>
          </figure>
      <?php
        }
      }
      ?>
    </div>
    <div class="review_controls">
      <button type="button" class="slick-prev"><img src="./img/icon-img/left_arrow.svg" alt="" width="20"></button>
      <button type="button" class="slick-next"><img src="./img/icon-img/right_arrow.svg" alt="" width="20"></button>
    </div>
  </section>

  <section class="main_recom container">
    <h6>Recommend</h6>
    <h3 class="mb-3">추천 강의</h3>
    <p>전문가가 추천하는 검증된 강의, 이제 당신의 차례입니다. </p>
    <div class="recom">
      <?php
      foreach ($dataArr3 as $item) {
        $tuition = '';
        if ($item->dis_tuition > 0) {
          $tui_val = number_format($item->tuition);
          $distui_val = number_format($item->dis_tuition);
          $tuition .= "<p class=\"active-font\"> $distui_val 원 </p><p class=\"text-decoration-line-through small-font\"> $tui_val 원 </p>";
        } else {
          $tui_val = number_format($item->tuition);
          $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p><p class=\"small-font\"> &nbsp; </p>";
        }
      ?>
        <div class="slide mx-3">
          <img src="<?= $item->cover_image ?>" alt="">
          <div class="info d-flex flex-column gap-3 justify-content-between">
            <h5><a href="lecture/lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <div class="tuition">
              <?= $tuition ?>
            </div>
            <ul>
              <li><span>추천</span></li>
            </ul>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
    <div class="recom_controls">
      <button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>
      <button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </section>

  <section class="main_free container">
    <h6>free</h6>
    <h3 class="mb-3">무료 강의</h3>
    <p>무료로 시작하는 배움의 여정, 지금 바로 도전하세요!</p>
    <div class="free ">
      <?php
      foreach ($dataArr4 as $item) {
      ?>
        <div class="image_container mx-2">
          <img src="<?= $item->cover_image ?>" alt="">
          <div class="box"></div>
          <div class="info d-flex flex-column justify-content-between">
            <h5><a href="lecture/lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <ul>
              <li><span>무료</span></li>
            </ul>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </section>
  <!-- 검색 메뉴 ? 출력 ? -->
  <section class="main_category container">
    <h3>그래도 관심가는 강의를 찾지 못했다면</h3>
    <p>아래 키워드를 검색해보세요 </p>
    <div class="keywords">
      <div class="tech">
        <span>Javascript</span>
        <span>Python</span>
        <span>React</span>
        <span>Vue</span>
        <span>Angular</span>
        <span>Node.js</span>
        <span>HTML</span>
        <span>CSS</span>
        <span>Swift</span>
        <span>Ruby on Rails</span>
      </div>
      <div class="tech">
        <span>Kotlin</span>
        <span>TypeScript</span>
        <span>Django</span>
        <span>Flask</span>
        <span>Firebase</span>
        <span>AWS</span>
        <span>Blockchain</span>
        <span>Docker</span>
      </div>
    </div>
    <div id="tech_filter"></div>
  </section>
</main>


<script>
  const $pagination = $(".custom-pagination");

  //slick 슬라이드
  $(".main_slides").on("init reInit afterChange", function(event, slick, currentSlide) {
    const totalSlides = slick.slideCount;
    const current = (currentSlide || 0) + 1;

    // 페이지네이션 텍스트 갱신
    $(".banner_controls span").text(`${current}/${totalSlides}`);

    // 페이지네이션 상태 갱신
    $pagination.find(".custom-dot").removeClass("active");
    $pagination.find(`.custom-dot:nth-child(${current})`).addClass("active");
  });
  $('.main_slides').slick({
    speed: 300,
    autoplay: true,
    autoplaySpeed: 3000,
    prevArrow: $('.banner .slick-prev'),
    nextArrow: $('.banner .slick-next'),
    dots: false, // 기본 dots 비활성화
    arrows: true,
    slidesToShow: 1,
    slidesToScroll: 1,
  });
  $('.notice_slides').slick({
    speed: 300,
    vertical: true,
    autoplay: true,
    autoplaySpeed: 4000,
    prevArrow: $('.main_notice .slick-prev'),
    nextArrow: $('.main_notice .slick-next'),
  });
  $('#skill_filter').slick({

    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
  })
  $('.algorithm').slick({
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    prevArrow: $('.recom_algorithm .slick-prev'),
    nextArrow: $('.recom_algorithm .slick-next'),
  });
  $('.popular').slick({

    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    prevArrow: $('.main_popular .slick-prev'),
    nextArrow: $('.main_popular .slick-next'),
    responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });
  $('.premium').slick({
    rows: 2, // 슬라이드의 행 수
    slidesPerRow: 3, // 각 행에 표시할 슬라이드 개수
    infinite: true, // 무한 반복
    arrows: true, // 화살표 표시
    prevArrow: $('.main_premium .slick-prev'),
    nextArrow: $('.main_premium .slick-next'),
  });
  $('.review_content').slick({
    infinite: false,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 1,

    infinite: true, // 무한 반복
    arrows: true, // 화살표 표시
    prevArrow: $('.main_review .slick-prev'),
    nextArrow: $('.main_review .slick-next'),
  });
  $('.recom').slick({
    rows: 2, // 슬라이드의 행 수
    slidesPerRow: 4, // 각 행에 표시할 슬라이드 개수
    infinite: true, // 무한 반복
    arrows: true, // 화살표 표시

    prevArrow: $('.main_recom .slick-prev'),
    nextArrow: $('.main_recom .slick-next'),
  });
  $('.free').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    infinite: true, // 무한 반복

  });

  // Skill 필터 기능
  const skillTags = document.querySelectorAll('.skill_tag figure');
  const skillFilter = document.getElementById('skill_filter');
  const skillCnt = document.querySelector('.skill_cnt');

  skillFilter.innerHTML = '<p>카테고리를 선택하면 강의를 바로 확인할 수 있습니다.</p>'
  skillTags.forEach(skillTag => {
    skillTag.addEventListener('click', (e) => {
      skillTags.forEach(tag => {
        const activeDiv = tag.querySelector('div.active');
        if (activeDiv) {
          activeDiv.classList.remove('active');
        }
      });

      const skill = e.currentTarget.querySelector('figcaption').textContent;
      console.log(skill);
      const data = new URLSearchParams({
        skill: skill,
      });
      fetch('./main/skill_filter.php', {
          method: 'POST',
          body: data,
        })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          skillFilter.innerHTML = data.skill;
          skillCnt.innerHTML = data.cnt;
        }).catch(error => console.error("Error:", error));
      const clickedDiv = e.currentTarget.querySelector('div');
      if (clickedDiv) {
        clickedDiv.classList.add('active');
      }
    })
  })

  // 커스텀 페이지네이션 생성
  const slideCount = $(".main_slides").slick("getSlick").slideCount;
  for (let i = 0; i < slideCount; i++) {
    $pagination.append(`<div class="custom-dot"></div>`);
  }

  //카테고리 검색
  const cateKeywords = document.querySelectorAll('.keywords .tech');
  cateKeywords.forEach(keyword => {
    keyword.addEventListener('click', (e) => {
      const keywordText = e.target.innerText;
      location.href = `lecture/lecture_list.php?search_keyword=${keywordText}`;
    })
  })

  // 첫 번째 dot 활성화
  $pagination.find(".custom-dot:first-child").addClass("active");
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');

?>