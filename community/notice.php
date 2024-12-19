<?php
$title = "공지사항";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php'); // DB 연결

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지

if ($current_page < 1) $current_page = 1; // 페이지 범위 제한

// 총 게시물 수 계산
$total_items_sql = "SELECT COUNT(*) AS total FROM board WHERE category = 'notice'";
$total_items_result = $mysqli->query($total_items_sql);
$total_items_row = $total_items_result->fetch_assoc();
$total_items = $total_items_row['total'];

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);
if ($current_page > $total_pages) $current_page = $total_pages;

// 데이터 가져오기
$start_index = ($current_page - 1) * $items_per_page;
$sql = "SELECT pid, title, content, user_id, date FROM board WHERE category = 'notice' ORDER BY date DESC LIMIT ?, ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $start_index, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();
$notices = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- 스타일 -->
<style>

  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }

  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }


</style>

<div class="title_box">
  <div class="container">
    <h2><?= $title ?></h2>  
  </div>  
</div>

<div class="community container">
  <div class="row">
    <aside class="col-2 d-flex flex-column">
      <h6>커뮤니티</h6>
      <hr>
      <ul>
        <a href="notice.php" class="active"><li>공지사항<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="faq.php"><li>FAQ<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="qna.php"><li>QnA<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="board.php"><li>자유게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="study.php"><li>스터디 모집<i class="fa-solid fa-chevron-right"></i></li></a>
      </ul>
    </aside>
  
    <div class="notice content col-10">
      <div class="d-flex justify-content-between align-items-center">
        <h6>퀀텀코드 공지사항</h6>
      </div>
      <hr>
      <table class="table table-hover text-center">
        <thead>
          <tr>
            <th scope="col" class="num" style="width: 5%;">No</th>
            <th scope="col" style="width: 50%;">제목</th>
            <th scope="col" style="width: 15%;">작성자</th>
            <th scope="col" style="width: 30%;">게시일</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($notices as $index => $notice): ?>
            <tr>
              <th scope="row"><?= $total_items - $start_index - $index ?></th>
              <td class="post"><a href="#" class="view-content" data-title="<?= htmlspecialchars($notice['title']) ?>" data-content="<?= htmlspecialchars($notice['content']) ?>"><?= htmlspecialchars($notice['title']) ?></a></td>
              <td><?= htmlspecialchars($notice['user_id']) ?></td>
              <td><?= htmlspecialchars($notice['date']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- 페이지네이션 -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <!-- 이전 버튼 -->
          <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $current_page - 1 ?>">&lt;</a>
          </li>
          <!-- 페이지 번호 -->
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($current_page == $i) ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <!-- 다음 버튼 -->
          <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $current_page + 1 ?>">&gt;</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>

<!-- 모달 -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- 넓이를 크게 하기 위해 modal-lg 클래스 추가 -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contentModalLabel">게시물 내용</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 id="modalTitle"></h5>
        <p id="modalContent"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // 제목 클릭 시 모달 표시
    const links = document.querySelectorAll(".view-content");
    links.forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const title = this.getAttribute("data-title");
        const content = this.getAttribute("data-content");

        // 모달에 데이터 삽입
        document.getElementById("modalTitle").textContent = title;
        document.getElementById("modalContent").textContent = content;

        // 모달 표시
        const contentModal = new bootstrap.Modal(document.getElementById("contentModal"));
        contentModal.show();
      });
    });
  });
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>
