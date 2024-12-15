<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$skill = $_POST['skill'] ?? '';

$skill_html = '';
if($skill){

$sql = "SELECT l.*, c.*
FROM lecture_list l 
JOIN lecture_category c 
ON l.lcid = c.lcid
WHERE c.name = '$skill'";

$result = $mysqli->query($sql);

 
if($result){
  while ($item = $result->fetch_object()) {
    $tuition = '';
        if ($item->dis_tuition > 0) {
          $tui_val = number_format($item->tuition);
          $distui_val = number_format($item->dis_tuition);
          $tuition .= "<p class=\"active-font\"> $distui_val 원 </p><p class=\"text-decoration-line-through small-font\"> $tui_val 원 </p>";
        } else {
          $tui_val = number_format($item->tuition);
          $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p><p class=\"small-font\"> &nbsp; </p>";
        }

    $skill_html .= "
    <div class=\"slide mx-3\">
          <img src=\"$item->cover_image\" alt=\"\">
          <div class=\"info d-flex flex-column gap-3 justify-content-between\">
            <h5><a href=\"lecture/lecture_view.php?lid=$item->lid\">$item->title</a></h5>
            <div class=\"tuition\">
              $tuition
            </div>
            <ul>
              <li><span>$item->lecture_tag</span></li>
            </ul>
          </div>
        </div>
    ";
  }
}else{
  $skill_html .= "<p>검색 결과가 없습니다</p>";
}
}
else{
  $skill_html .= "skill이 없습니다";
}
echo json_encode([
  'skill' => $skill_html
]);
exit;
