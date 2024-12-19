<?php
$title = "강의 목록";
// $teacherOverView_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/teacherOverView.css\" rel=\"stylesheet\">";
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
$slick_css = "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css\" integrity=\"sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />";
$slick_js = "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js\" integrity=\"sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

if (!isset($_SESSION['AUID'])) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../index.php';
    </script>
  ";
}

//인기 강의
$sql = "SELECT * FROM lecture_list WHERE ispopular = 1 ORDER BY regist_day ASC";
$result = $mysqli->query($sql);
$dataArr = [];
while ($data = $result->fetch_object()) {
  $dataArr[] = $data;
}


//프리미엄 강의
$sql2 = "SELECT * FROM lecture_list WHERE ispremium = 1  ORDER BY regist_day ASC";
$result2 = $mysqli->query($sql2);
$dataArr2 = [];
while ($data2 = $result2->fetch_object()) {
  $dataArr2[] = $data2;
}


//추천강의
$sql3 = "SELECT * FROM lecture_list WHERE isrecom = 1  ORDER BY regist_day ASC";
$result3 = $mysqli->query($sql3);
$dataArr3 = [];
while ($data3 = $result3->fetch_object()) {
  $dataArr3[] = $data3;
}



//무료강의
$sql4 = "SELECT * FROM lecture_list WHERE isfree = 1  ORDER BY regist_day ASC";
$result4 = $mysqli->query($sql4);
$dataArr4 = [];
while ($data4 = $result4->fetch_object()) {
  $dataArr4[] = $data4;
}







?>



<h4 class="mt-3">인기강의</h4>
<div class="mb-3"> <!-- Flex 컨테이너 -->
  <div class="popular">
    <?php
    foreach ($dataArr as $item) {
      $tuition = '';
      if ($item->dis_tuition > 0) {
        $tui_val = number_format($item->tuition);
        $distui_val = number_format($item->dis_tuition);
        $tuition .= "<p class=\"text-decoration-line-through \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
      } else {
        $tui_val = number_format($item->tuition);
        $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p>";
      }
    ?>
      <section class="slide">
        <div>
          <div class="cover mb-2">
            <img src="<?= $item->cover_image ?>" alt="">
          </div>
          <div class="title mb-2">
            <h5 class="small-font mb-0"><a href="lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <p class="name text-decoration-underline"><?= $item->t_id ?></p>
          </div>
          <div>
            <?= $tuition ?>
          </div>
        </div>
        <ul>
          <li class="d-flex align-items-center gap-2"> <img src="../img/icon-img/review.svg" alt=""> 5점 </li>
          <li class="like d-flex align-items-center"><img src="../img/icon-img/Heart.svg" width="10" height="10" alt="">500+</li>
          <li class="tag"><?= !empty($item->lecture_tag) ? "<span> {$item->lecture_tag}</span>" : '' ?> </li>
        </ul>
      </section>
    <?php
    }
    ?>
  </div>
</div>

<hr>
<h4 class="mt-3">추천강의</h4>
<div class="mb-3"> <!-- Flex 컨테이너 -->
  <div class="recom">
    <?php
    foreach ($dataArr3 as $item) {
      $tuition = '';
      if ($item->dis_tuition > 0) {
        $tui_val = number_format($item->tuition);
        $distui_val = number_format($item->dis_tuition);
        $tuition .= "<p class=\"text-decoration-line-through \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
      } else {
        $tui_val = number_format($item->tuition);
        $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p>";
      }
    ?>
      <section class="slide">
        <div>
          <div class="cover mb-2">
            <img src="<?= $item->cover_image ?>" alt="">
          </div>
          <div class="title mb-2">
            <h5 class="small-font mb-0"><a href="lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <p class="name text-decoration-underline"><?= $item->t_id ?></p>
          </div>
          <div>
            <?= $tuition ?>
          </div>
        </div>
        <ul>
          <li class="d-flex align-items-center gap-2"> <img src="../img/icon-img/review.svg" alt=""> 5점 </li>
          <li class="like d-flex align-items-center"><img src="../img/icon-img/Heart.svg" width="10" height="10" alt="">500+</li>
          <li class="tag"><?= !empty($item->lecture_tag) ? "<span> {$item->lecture_tag}</span>" : '' ?> </li>
        </ul>
      </section>
    <?php
    }
    ?>
  </div>
</div>
<hr>
<h4 class="mt-3">프리미엄 강의</h4>
<div class="mb-3"> <!-- Flex 컨테이너 -->
  <div class="premium">
    <?php
    foreach ($dataArr2 as $item) {
      $tuition = '';
      if ($item->dis_tuition > 0) {
        $tui_val = number_format($item->tuition);
        $distui_val = number_format($item->dis_tuition);
        $tuition .= "<p class=\"text-decoration-line-through \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
      } else {
        $tui_val = number_format($item->tuition);
        $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p>";
      }
    ?>
      <section class="slide">
        <div>
          <div class="cover mb-2">
            <img src="<?= $item->cover_image ?>" alt="">
          </div>
          <div class="title mb-2">
            <h5 class="small-font mb-0"><a href="lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <p class="name text-decoration-underline"><?= $item->t_id ?></p>
          </div>
          <div>
            <?= $tuition ?>
          </div>
        </div>
        <ul>
          <li class="d-flex align-items-center gap-2"> <img src="../img/icon-img/review.svg" alt=""> 5점 </li>
          <li class="like d-flex align-items-center"><img src="../img/icon-img/Heart.svg" width="10" height="10" alt="">500+</li>
          <li class="tag"><?= !empty($item->lecture_tag) ? "<span> {$item->lecture_tag}</span>" : '' ?> </li>
        </ul>
      </section>
    <?php
    }
    ?>
  </div>
</div>
<hr>
<h4 class="mt-3">무료강의</h4>
<div class="mb-3"> <!-- Flex 컨테이너 -->
  <div class="free">
    <?php
    foreach ($dataArr4 as $item) {
      $tuition = '';
      if ($item->dis_tuition > 0) {
        $tui_val = number_format($item->tuition);
        $distui_val = number_format($item->dis_tuition);
        $tuition .= "<p class=\"text-decoration-line-through \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
      } else {
        $tui_val = number_format($item->tuition);
        $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p>";
      }
    ?>
      <section class="slide">
        <div>
          <div class="cover mb-2">
            <img src="<?= $item->cover_image ?>" alt="">
          </div>
          <div class="title mb-2">
            <h5 class="small-font mb-0"><a href="lecture_view.php?lid=<?= $item->lid ?>"><?= $item->title ?></a></h5>
            <p class="name text-decoration-underline"><?= $item->t_id ?></p>
          </div>
          <div>
            <?= $tuition ?>
          </div>
        </div>
        <ul>
          <li class="d-flex align-items-center gap-2"> <img src="../img/icon-img/review.svg" alt=""> 5점 </li>
          <li class="like d-flex align-items-center"><img src="../img/icon-img/Heart.svg" width="10" height="10" alt="">500+</li>
          <li class="tag"><?= !empty($item->lecture_tag) ? "<span> {$item->lecture_tag}</span>" : '' ?> </li>
        </ul>
      </section>
    <?php
    }
    ?>
  </div>
</div>



<script>
  $('.popular').slick({

    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
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

  $('.recom').slick({

    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
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
    centerMode: false,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
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

  $('.free').slick({

    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
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
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>