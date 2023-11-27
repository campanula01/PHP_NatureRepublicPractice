<?php
include 'inc/common.php';
include 'inc/dbconfig.php';
include 'inc/board.php';
include 'inc/lib.php';//페이지네이션


$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
$page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page']:1 ;
$sn = (isset($_GET['sn']) && $_GET['sn']!='')?$_GET['sn']:'';
$sf = (isset($_GET['sf']) && $_GET['sf']!='')?$_GET['sf']:'';


if($bcode == ''){
    die("<script>alert('게시판 코드가 빠졌습니다.');history.go(-1);</script>");
}


//게시판 목록
include 'inc/board_manage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list1();
$board_name = $boardm->getBoardName($bcode);


$board = new Board($db);//게시판 클래스
$menu_code='board';
$js_array=['js/board.js'];
$g_title = $board_name;

$limit =10;
$page_limit=5;
$paramArr=['sn'=>$sn, 'sf'=>$sf];
$boardRs = $board->list($bcode,$page,$limit, $paramArr);
$total = $board->total($bcode,$paramArr);

include 'inc_header.php';
?>
<style>
    .tr{cursor: pointer;}
</style>
<main class="w-100 mx-auto border rounded-2 p-5" style="height: calc(100vh-200px);">

<h3 class="text-center"><?=$board_name ?></h3>

<table class="table striped mt-5 table-hover">
    <colgroup>
        <col width="10%">
        <col width="45%">
        <col width="10%">
        <col width="15%">
        <col width="10%">
    </colgroup>
    <tr>
        <th>번호</th>
        <th>제목</th>
        <th>이름</th>
        <th>날짜</th>
        <th>조회 수</th>
    </tr>
        <!-- 게시판 목록 출력 부분 -->
<?php 
    $cnt = 0;
    $ntotal = $total-($page-1)*$limit;
    foreach ($boardRs as $boardrow): 
    $number =$ntotal-$cnt;
    $cnt++;
    ?>
    <tr class="tr" data-idx="<?=$boardrow['idx']; ?>">
        <td><?= $number ?></td>
        <td><?= $boardrow['subject'] ?></></td>
        <td><?= $boardrow['name'] ?></td>
        <td><?= $boardrow['create_at'] ?></td>
        <td><?= $boardrow['hit'] ?></td>
    </tr>
<?php endforeach; ?>

</table>
<div class="container mt-3 w-50 d-flex gap-2">
    <select name="" id="sn" class="form-select w-25">
        <option value="1" <?php if($sn==1) echo ' selected'; ?>>제목, 내용</option>
        <option value="2" <?php if($sn==2) echo ' selected'; ?>>제목</option>
        <option value="3" <?php if($sn==3) echo ' selected'; ?>>내용</option>
        <option value="4" <?php if($sn==4) echo ' selected'; ?>>글쓴이</option>
    </select>
    <input type="text" class="form-control w-25" id="sf" value="<?=$sf ?>">
    <button class="btn btn-green1 w-25" id="btn_search">검색</button>
    <button class="btn btn-primary w-25" id="btn_all">전체목록</button>
</div>

<div class="d-flex justify-content-between align-items-start">
    <!-- 페이징 처리 부분 -->
    <?php
    $param='&bcode='.$bcode;
        if(isset($sn) && $sn !='' && isset($sf) && $sf !=''){
            $param .= '&sn='.$sn.'&sf='.$sf;            
        }
        $pagenation = my_pagination($total, $limit, $page_limit, $page, $param);

        echo $pagenation;
    ?>

    <button type="button" class="btn btn-green1" id="btn_write">글쓰기</button>
</div>
</main>




<?php
include 'inc_footer.php';
?>