<?php
$title = '대시보드';
$admin_index_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/admin_index.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

//강사 관련. 모든 강사 수
$teacher_count_sql = "SELECT COUNT(*) AS total_teachers FROM teachers";
$teacher_count = $mysqli->query($teacher_count_sql);
$t_count = $teacher_count->fetch_object();
//2024에 가입한 강사 수
$teacher_2024_register = "SELECT COUNT(*) AS total_2024_teachers FROM teachers WHERE YEAR(reg_date) = 2024";
$teacher_2024_count = $mysqli->query($teacher_2024_register);
$teacher_2024 = $teacher_2024_count->fetch_object();
//2023에 가입한 강사 수
$teacher_2023_register = "SELECT COUNT(*) AS total_2023_teachers FROM teachers WHERE YEAR(reg_date) = 2023";
$teacher_2023_count = $mysqli->query($teacher_2023_register);
$teacher_2023 = $teacher_2023_count->fetch_object();

$reg2023_TNumber = intval(($teacher_2023->total_2023_teachers)); //9
$reg2024_TNumber = intval(($teacher_2024->total_2024_teachers)); //13

if ($reg2023_TNumber > 0) {
  $increasePercentage = (($reg2024_TNumber - $reg2023_TNumber) / $reg2023_TNumber) * 100;
  $increasePercentage = round($increasePercentage, 2); // 소수점 두 자리까지 반올림
} else {
  $increasePercentage = 0; // 2023년 값이 0일 경우 증가율은 정의할 수 없음
}

//회원 관련. 모든 회원 수
$member_count_sql = "SELECT COUNT(*) AS total_members FROM members";
$member_count = $mysqli->query($member_count_sql);
$m_count = $member_count->fetch_object();

//2024에 가입한 회원 수
$member_2024_register = "SELECT COUNT(*) AS total_2024_members FROM members WHERE YEAR(reg_date) = 2024";
$member_2024_count = $mysqli->query($member_2024_register);
$member_2024 = $member_2024_count->fetch_object();

//2023에 가입한 회원 수
$member_2023_register = "SELECT COUNT(*) AS total_2023_members FROM members WHERE YEAR(reg_date) = 2023";
$member_2023_count = $mysqli->query($member_2023_register);
$member_2023 = $member_2023_count->fetch_object();

//회원수 증가율
$reg2023_M_Number = intval(($member_2023->total_2023_members));
$reg2024_M_Number = intval(($member_2024->total_2024_members));

if ($reg2023_M_Number > 0) {
  $M_increasePercentage = (($reg2024_M_Number - $reg2023_M_Number) / $reg2023_M_Number) * 100;
  $M_increasePercentage = round($M_increasePercentage, 2);
} else {
  $M_increasePercentage = 0;
}

//매출 상위 5명 강사 
$sql = "SELECT * 
        FROM teachers
        ORDER BY year_sales DESC
        LIMIT 5;"; // 상위 5명 제한

$result = $mysqli->query($sql);

$name = [];
$sale = [];

// 상위 5명 데이터 추출
while ($data = $result->fetch_object()) {
  $name[] = $data->name; // x축에 사용할 강사 이름
  $sale[] = $data->year_sales; // y축에 사용할 매출 데이터
}
//print_r($name);Array ( [0] => 권도형 [1] => 이기상 [2] => 장윤정 [3] => 이지영 [4] => 이동진 )
//print_r($sale)Array ( [0] => 54000000 [1] => 23400000 [2] => 16780000 [3] => 15600000 [4] => 15430000 )

?>



<div class="dashboard container m-0">
  <!-- Summary Section -->
  <div class="row">
    <div class="col-md-4 amount_teacher">
      <div class="card border-0 shadow-sm p-3">
        <div class="card-header bg-white border-0 pb-2 d-flex">
          <h6 class="mb-0 fw-bold text-primary">강사</h6>
          <small class="ms-2">2024년 11월 기준 전년 대비 증감량</small>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-4">
              <h6 class="text-primary">전체 강사</h6>
              <p class="mb-0">
                <span class="text-primary d-block">↑ <?= $increasePercentage ?>%</span>
                <span class="text-dark d-block">총 <?= $t_count->total_teachers ?> 명</span>
              </p>
            </div>
            <div class="col-4">
              <h6 class="text-primary">신규 강사</h6>
              <?php
              if ($teacher_2024->total_2024_teachers) {

              ?>
                <p class="mb-0">
                  <span class="text-primary">↑</span>
                  <span class="text-dark"><?= $teacher_2024->total_2024_teachers ?>명</span>
                </p>
              <?php
              }
              ?>
            </div>
            <div class="col-4">
              <h6 class="text-danger">탈퇴 강사</h6>
              <p class="mb-0">
                <span class="text-danger"></span>
                <span class="text-dark">0명</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 amount_member">
      <div class="card border-0 shadow-sm p-3">
        <div class="card-header bg-white border-0 pb-2 d-flex">
          <h6 class="mb-0 fw-bold text-primary">회원</h6>
          <small class="ms-2">2024년 11월 기준 10월 달 대비 증감량</small>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-4">
              <h6 class="sub_tt text-primary">전체 회원</h6>
              <p class="mb-0">
                <span class="text-primary d-block">↑ <?= $M_increasePercentage ?>%</span>
                <span class="text-dark d-block">총 <?= $m_count->total_members ?> 명</span>
              </p>
            </div>
            <div class="col-4">
              <h6 class="text-primary">신규 회원</h6>
              <?php
              if ($member_2024->total_2024_members) {

              ?>
                <p class="mb-0">
                  <span class="text-primary">↑</span>
                  <span class="text-dark"><?= $member_2024->total_2024_members ?>명</span>
                </p>
              <?php
              }
              ?>
            </div>
            <div class="col-4">
              <h6 class="text-danger">탈퇴 회원</h6>
              <p class="mb-0">
                <span class="text-dark">0명</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 amount_sales">
      <div class="card border-0 shadow-sm p-3">
        <div class="card-header bg-white border-0 pb-2 d-flex">
          <h6 class="mb-0 fw-bold  text-primary">강사 매출 누적 순위</h6>
        </div>
        <div class="card-body">
          <canvas id="top5TeachersChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- <div class="col-md-4 amount_sales">
  <div class="card border-0 shadow-sm p-3">
    <div class="card-header bg-white border-0 pb-2 d-flex">
      <h6 class="mb-0 fw-bold  text-primary">강사 매출 누적 순위</h6>
    </div>
    <div class="card-body">
      <canvas id="salesChart" height="150"></canvas>
    </div>
  </div>
</div>
</div> -->

<!-- Popular Courses -->
<div class="row mt-4 ms-0 me-0 d-flex justify-content-between">
  <div class="row col-md-8">
    <div class="mb-4 card p-3 border-0 bg-light">
      <h6>인기 강의</h6>
      <div class="chart-container">
        <canvas id="popularCoursesChart"></canvas>
      </div>
    </div>

    <div class="QnA card p-3 border-0 bg-light">
      <h6>Q&A</h6>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>제목</th>
            <th>작성자</th>
            <th>등록일</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>CSS 테이블 세로 간격 줄이는 방법이 뭐죠?</td>
            <td>CodeRookie21</td>
            <td>2024-11-01</td>
            <td>
              <a href="">
                <img src="img/icon-img/Edit.svg" alt="" style="width: 20px;">
              </a>
            </td>
          </tr>
          <tr>
            <td>JavaScript 함수 최적화 관련 문의</td>
            <td>DevGuru</td>
            <td>2024-11-02</td>
            <td>
              <a href="">
                <img src="img/icon-img/Edit.svg" alt="" style="width: 20px;">
              </a>
            </td>
          </tr>
          <tr>
            <td>React에서 상태 관리 효율적으로 구현하는 법?</td>
            <td>JS_Ninja</td>
            <td>2024-11-03</td>
            <td>
              <a href="">
                <img src="img/icon-img/Edit.svg" alt="" style="width: 20px;">
              </a>
            </td>
          </tr>
          <tr>
            <td>Python 웹 스크래핑 중 특정 태그 가져오기 문제</td>
            <td>PythonLover</td>
            <td>2024-11-04</td>
            <td>
              <a href="">
                <img src="img/icon-img/Edit.svg" alt="" style="width: 20px;">
              </a>
            </td>
          </tr>
        </tbody>
      </table>

      <nav aria-label="Page navigation example">
        <ul class="pagination pagination-sm">
          <li class="page-item"><a class="page-link" href="#"><img src="img/icon-img/CaretLeft.svg" alt=""></a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item"><a class="page-link" href="#">5</a></li>
          <li class="page-item"><a class="page-link" href="#"><img src="img/icon-img/CaretRight.svg" alt=""></a></li>
        </ul>
      </nav>
    </div>
  </div>

  <!-- Revenue Section -->
  <div class="Revenue col-md-4 card p-3 border-0 bg-light">
    <h6>월별 매출</h6>
    <h3 class="text-center mt-3">12,020,000원</h3>
    <h6 class="text-center text-primary">1,091,000원(+10%)↑</h6>
    <canvas id="monthlyChart" height="400"></canvas>
  </div>
</div>
</div>



<!-- Chart.js Scripts -->
<script>
  // // Sales Chart
  // const salesCtx = document.getElementById('salesChart').getContext('2d');
  // const salesChart = new Chart(salesCtx, {
  //   type: 'bar',
  //   data: {
  //     labels: ['강사1', '강사2', '강사3', '강사4', '강사5'],
  //     datasets: [{
  //       label: '강사 매출',
  //       data: [7000000, 6000000, 5500000, 5000000, 4500000],
  //       backgroundColor: 'rgba(54, 162, 235, 0.5)'
  //     }]
  //   },
  //   options: {
  //     responsive: true,
  //     plugins: {
  //       legend: {
  //         display: false
  //       }
  //     }
  //   }
  // });

  fetch('sales/sales_data.php')
    .then(response => response.json())
    .then(data => {
      const months = data.map(item => item.month);
      const sales = data.map(item => item.sales);
      const monthly_data = document.getElementById('monthlyChart');
      new Chart(monthly_data, {
        type: 'bar', // 막대 차트
        data: {
          labels: months, // x축 레이블
          datasets: [{
            label: '월 별 매출',
            data: sales, // y축 데이터
            backgroundColor: 'rgba(112, 134, 253, 1)',

          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          }
          // scales: {
          //   y: {
          //     beginAtZero: true
          //   }
          // }
        }
      });
    }).catch(error => console.error('Error fetching data:', error));


  //most popular chart
  const ctx = document.getElementById('popularCoursesChart').getContext('2d');

  const popularCoursesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        '프론트엔드를 위한 자바스크립트 첫 걸음',
        '고농축 프론트엔드 풀코스',
        '코어 자바스크립트',
        'A-Z부터 따라하며 배우는 리액트',
        'Vue.js 시작하기'
      ],
      datasets: [{
        label: '인기 강의',
        data: [3102, 2497, 2459, 2270, 1989],
        backgroundColor: [
          '#FF6B6B', // 첫 번째 막대 색상
          '#6BCBFF', // 두 번째 막대 색상
          '#A28DFF', // 세 번째 막대 색상
          '#6BD1FF', // 네 번째 막대 색상
          '#3666FF' // 다섯 번째 막대 색상
        ],
        borderWidth: 0,
        borderRadius: 10 // 막대 끝을 둥글게
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }, // 범례 비활성화
        tooltip: {
          enabled: true
        } // 툴팁 활성화
      },
      scales: {
        x: {
          grid: {
            display: false,
            drawBorder: false
          },
          ticks: {
            display: false
          }
        },
        y: {
          grid: {
            display: false
          },
          ticks: {
            align: 'start',
            padding: 20,
            font: {
              size: 14
            }, // Y축 폰트 크기
            color: '#333'
          } // Y축 텍스트 색상
        }
      }
    }
  });
  document.addEventListener('DOMContentLoaded', function() {
    // top5 강사 매출 차트
    const teacherNames = <?php echo json_encode($name); ?>; // 강사 이름 배열
    const teacherSales = <?php echo json_encode($sale); ?>; // 매출 데이터 배열

    const ctx = document.getElementById('top5TeachersChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar', // 차트 유형: 막대형
      data: {
        labels: teacherNames, // x축 레이블 (강사 이름)
        datasets: [{
          label: '매출 (원)', // 범례
          data: teacherSales, // y축 데이터 (매출)
          backgroundColor: [
            '#FF6B6B', // 첫 번째 막대 색상
            '#6BCBFF', // 두 번째 막대 색상
            '#A28DFF', // 세 번째 막대 색상
            '#6BD1FF', // 네 번째 막대 색상
            '#3666FF' // 다섯 번째 막대 색상
          ],
          borderWidth: 0, // 테두리 두께 없음
          borderRadius: 10 // 막대 끝을 둥글게
        }]
      },
      options: {
        responsive: true, // 반응형
        maintainAspectRatio: false, // 그래프 비율 유지 해제
        plugins: {
          legend: {
            display: false // 범례 비활성화
          },
          title: {
            display: true,
            text: '강사 매출 상위 5명',
            font: {
              size: 14, // 제목 폰트 크기
              weight: 'bold' // 제목 폰트 굵기
            },
            padding: {
              top: 10,
              bottom: 30 // 제목과 그래프 간격
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                // 툴팁에 콤마(,)를 추가하여 읽기 쉽게 표시
                return `매출: ${context.raw.toLocaleString()} 원`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false // x축 격자선 비활성화
            },
            ticks: {
              font: {
                size: 14 // x축 폰트 크기
              },
              color: '#333' // x축 텍스트 색상
            }
          },
          y: {
            beginAtZero: true, // y축 0부터 시작
            grid: {
              borderDash: [7, 7], // 점선 형태의 격자선
              color: '#ccc' // y축 격자선 색상
            },
            ticks: {
              font: {
                size: 12 // y축 폰트 크기
              },
              color: '#333', // y축 텍스트 색상
              callback: function(value) {
                // y축 숫자에 콤마(,) 추가
                return `${value.toLocaleString()} 원`;
              }
            }
          }
        }
      }
    });

    // most popular chart
    const popularCtx = document.getElementById('popularCoursesChart').getContext('2d');
    new Chart(popularCtx, {
      type: 'bar',
      data: {
        labels: [
          '프론트엔드를 위한 자바스크립트 첫 걸음',
          '고농축 프론트엔드 풀코스',
          '코어 자바스크립트',
          'A-Z부터 따라하며 배우는 리액트',
          'Vue.js 시작하기'
        ],
        datasets: [{
          label: '인기 강의',
          data: [3102, 2497, 2459, 2270, 1989],
          backgroundColor: ['#FF6B6B', '#6BCBFF', '#A28DFF', '#6BD1FF', '#3666FF'],
          borderWidth: 0,
          borderRadius: 10
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: true
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              display: false
            }
          },
          y: {
            grid: {
              display: false
            },
            ticks: {
              align: 'start',
              padding: 20,
              font: {
                size: 14
              },
              color: '#333'
            }
          }
        }
      }
    });

    // Monthly Revenue Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
      type: 'bar',
      data: {
        labels: ['7월', '8월', '9월', '10월', '11월', '12월'],
        datasets: [{
          label: '월별 매출',
          data: [8000000, 8500000, 9000000, 9500000, 10000000, 12020000],
          backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: '월별 매출 데이터'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: '매출 (원)'
            }
          },
          x: {
            title: {
              display: true,
              text: '월'
            }
          }
        }
      }
    });
  });




  // Monthly Revenue Chart
  // const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
  // const monthlyChart = new Chart(monthlyCtx, {
  //   type: 'bar',
  //   data: {
  //     labels: ['7월', '8월', '9월', '10월', '11월', '12월'],
  //     datasets: [{
  //       label: '월별 매출',
  //       data: [8000000, 8500000, 9000000, 9500000, 10000000, 12020000],
  //       backgroundColor: 'rgba(54, 162, 235, 0.5)',
  //     }]
  //   },
  //   options: {
  //     responsive: true,
  //     plugins: {
  //       legend: {
  //         display: false
  //       }
  //     }
  //   }
  // });
</script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>