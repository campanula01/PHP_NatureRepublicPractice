<?php
include 'inc/common.php';
include 'inc/dbconfig.php';
include 'inc/board.php';


$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
if($bcode == ''){
    die("<script>alert('게시판 코드가 빠졌습니다.');history.go(-1);</script>");
}


//게시판 목록
include 'inc/board_manage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list1();
$board_name = $boardm->getBoardName($bcode);


$board = new Board($db);


$js_array=['js/board.js'];
$g_title = $board_name;
include 'inc_header.php';
?>
<main class="w-75 mx-auto border rounded-2 p-5" style="height: calc(100vh-200px);">

<h3 class="text-center"><?=$board_name ?></h3>

<table class="table striped mt-5">
    <tr>
        <th>번호</th>
        <th>제목</th>
        <th>이름</th>
        <th>날짜</th>
        <th>조회 수</th>
    </tr>
    <tr>
        
    </tr>
</table>

<div class="d-flex justify-content-between align-items-start">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        </ul>
    </nav>
    <button type="button" class="btn btn-green1" id="btn_write">글쓰기</button>
</div>
</main>




<?php
include 'inc_footer.php';
?>