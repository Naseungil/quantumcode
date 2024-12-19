<?php
$title = "회원 목록";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');


$id = $_SESSION['AUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}


//검색
$search_where = ''; //초기화
$search_keyword = $_GET['search_keyword'] ?? '';

if($search_keyword){ 
  // $search_where .= " and (name LIKE '%$search_keyword%' OR content LIKE '%$search_keyword%')";
  $search_where .= " and (name LIKE '%$search_keyword%')";
}


//데이터의 개수 조회
$page_sql = "SELECT COUNT(*) AS cnt FROM memberskakao WHERE 1=1 $search_where";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();
$row_num = $page_data['cnt'];


//페이지네이션 
if(isset($_GET['page'])){
  $page = $_GET['page'];
}else{
  $page = 1;
}

$list = 10;
$start_num=($page-1)*$list;
$block_ct = 5;
$block_num = ceil($page/$block_ct); //$page1/5 0.2 = 1

$block_start = (($block_num-1)*$block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num/$list); //총75개 10개씩, 8
$total_block = ceil($total_page/$block_ct);

if($block_end > $total_page ) $block_end = $total_page;

//목적에 맞게 목록 가져오기
$sql = "SELECT * FROM memberskakao WHERE 1=1 $search_where ORDER BY memid ASC LIMIT $start_num, $list"; //teachers 테이블에서 모든 데이터를 조회

$result = $mysqli->query($sql); //쿼리 실행 결과
while($data = $result->fetch_object()){
  $dataArr[] = $data;
}

?>

<div class="container">
  <form action="">
    <div class="d-flex gap-3 w-30 mt-3 align-items-center">
      <h3>현재 회원 수 : <?= $row_num; ?> 명</h3>
      <input type="text" class="form-control w-25 ms-auto" name="search_keyword" id="search">
      <button class="btn btn-primary btn-sm w-20">검색</button>
    </div>     
    


    <hr> 
    <!-- <form action="plist_update.php" method="GET"> -->
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No. </th>
          <th scope="col">이름</th>
          <th scope="col">아이디(이메일)</th>
          <th scope="col">가입날짜</th>
          <th scope="col">회원 등급</th>
          <th scope="col">상세보기</th>
          <!-- <th scope="col">수정하기</th> -->
          <th scope="col">쪽지보내기</th>

        </tr>
      </thead>
      <tbody>
          <?php
            if(isset($dataArr)){
              foreach($dataArr as $item){
          ?> 
          <tr>
            <th scope="row"><?= $item->memId; ?></th>
              <td><?= $item->memName; ?></td>
              <td><?= $item->memEmail; ?></td>
              <td><?= $item->memCreatedAt; ?></td>
              <td><?= $item->grade; ?></td>
              <td><a href="member_view.php?memId=<?= $item->memId;?>" class="btn btn-primary btn-sm">상세보기</a></td>
              <td>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#messageModal" data-mid="<?= $item->memId; ?>" >쪽지보내기</button>
              </td>
          </tr>
          <?php
              }
            }
          ?> 
      </tbody>
    </table>
  </form>
  <nav aria-label="Page navigation">
    <ul class="pagination d-flex justify-content-center">
      <?php
        if($block_num > 1){
          $prev = $block_start - $block_ct;
          echo "<li class=\"page-item\"><a class=\"page-link\" href=\"member_list.php?&search_keyword={$search_keyword}&page={$prev}\">Previous</a></li>";
        }
      ?>
      
      <?php
        for($i=$block_start; $i<=$block_end; $i++){                
          $page == $i ? $active = 'active': $active = '';
      ?>
      <li class="page-item <?= $active; ?>"><a class="page-link" href="member_list.php?&search_keyword=<?= $search_keyword;?>&page=<?= $i;?>"><?= $i;?></a></li>
      <?php
        }
        $next = $block_end + 1;
        if($total_block >  $block_num){
      ?>
      <li class="page-item"><a class="page-link" href="member_list.php?&search_keyword=<?= $search_keyword;?>&page=<?= $next;?>">Next</a></li>
      <?php
      }         
      ?>
    </ul>
  </nav>
</div>
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">쪽지 보내기</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="sendMessageForm" method="post">
          <!-- 수정: sender_idx는 관리자 정보를 세션에서 가져오므로 유지 -->
          <input type="hidden" id="sender_idx" name="sender_idx" value="<?= $_SESSION['AUIDX'] ?>"> 
          <!-- 수정: receiver_mid는 JavaScript로 설정되므로 기본값을 제거 -->
          <input type="hidden" id="receiver_mid" name="receiver_mid" value=""> 
          <div class="mb-3">
            <label for="message" class="form-label">메시지 내용</label>
            <textarea id="message" name="message" class="form-control" placeholder="쪽지 내용을 입력하세요" rows="10" maxlength="2000" required></textarea>
            <small class="form-text text-muted">최대 2000자까지 입력할 수 있습니다.</small>
          </div>
          <button type="submit" class="btn btn-primary">보내기</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>

document.getElementById("messageModal").addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget; // 버튼에서 data-mid 가져오기
    const receiverMid = button.getAttribute("data-mid"); // data-mid 값 가져오기
    document.getElementById("receiver_mid").value = receiverMid; // 숨겨진 input에 설정
});

// 메시지 전송 처리
document.getElementById("sendMessageForm").addEventListener("submit", function (e) {
    e.preventDefault(); // 기본 폼 전송 막기

    // 폼 데이터 가져오기
    const sender_idx = document.getElementById("sender_idx").value;
    const receiver_mid = document.getElementById("receiver_mid").value;
    const message = document.getElementById("message").value;

    // POST 요청 보내기
    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ 
        sender_idx: sender_idx, 
        receiver_mid: receiver_mid, 
        message: message 
        })
    })
    .then(response => response.json()
    )
    .then(data => {
      console.log(data);
        if (data.status === "success") {
            alert(data.message); // 성공 메시지 표시
            const modal = bootstrap.Modal.getInstance(document.getElementById("messageModal"));
            modal.hide(); // 모달 닫기
        } else {
            alert(data.message); // 오류 메시지 표시
        }
    })
    .catch(error => console.error("Error:", error));
});
  
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>