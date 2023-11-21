<?php

$js_array=['js/board.js'];

include 'inc_common.php';
include 'inc_header.php';
include '../inc/dbconfig.php';
include '../inc/board.php'; 
include '../inc/lib.php'; //페이지 처리

$sn = (isset($_GET['sn'])&& $_GET['sn'] !='' && is_numeric($_GET['sn']))? $_GET['sn']:'';
$sf = (isset($_GET['sf'])&& $_GET['sf'] !='')? $_GET['sf']:'';


$paramArr = ['sn'=>$sn, 'sf'=>$sf];

$board = new Board($db);
$total = $board->total($paramArr);
$limit=5;
$page_limit=5;
$page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page']:1 ;


$param="";


$boardArr = $board->list($page, $limit, $paramArr);



?>
<main class="border rounded-5 p-5" style="height: calc(100vh-200px);">

    <div class="container">
        <h3>게시판 관리</h3>
    </div>

    <table class="table table-border">
        <tr>
            <th>번호</th>
            <th>게시판 이름</th>
            <th>게시판 코드</th>
            <th>게시판 타입</th>
            <th>게시물 수</th>
            <th>등록일시</th>
            <th>관리</th>
        </tr>
        <?php
            foreach($boardArr AS $row){
                //$row['create_at']=substr($row['create_at'],0,16);
        ?>
        <tr>
            <td><?= $row['idx']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['bcode']; ?></td>
            <td><?= $row['btype']; ?></td>
            <td><?= $row['cnt']; ?></td>
            <td><?= $row['create_at']; ?></td>
            <td>
                <button type="button" class="btn btn-green1 btn-sm btn_mem_edit " data-bs-toggle="modal" data-bs-target="#board_create_modal" data-idx="<?=$row['idx']; ?>">수정</button>
                <button type="button" class="btn btn-danger btn-sm btn_mem_delete" data-idx="<?=$row['idx']; ?>">삭제</button>
            </td>

        </tr>

        <?php
            }
        ?>
    </table>

    <div class="container mt-3 d-flex gap-2 w-75">
        <select class="form-select w-25" name="sn" id="sn">
            <option value="1">이름</option>
            <option value="2">게시판 타입</option>
            <option value="3">게시물 수</option>
        </select>

        <input type="text" class="form-control w-50" id="sf" name="sf">
        <button class="btn btn-green1" id="btn_search">검색</button>
        <button class="btn btn-primary" id="btn_all">전체목록</button>


    </div>
    

    <div class="d-flex mt-3 justify-content-between align-items-start">
    <?php
    $param = '&sn='.$sn.'&sf='.$sf;
        $pagenation = my_pagination($total, $limit, $page_limit, $page, $param);

        echo $pagenation;
    ?>

            <button type="button" class="btn btn-primary" id="btn_create_modal" data-bs-toggle="modal" data-bs-target="#board_create_modal">게시판 생성</button>
    </div>

</main>


<!-- Modal -->
<div class="modal fade" id="board_create_modal" tabindex="-1" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal_title">게시판 생성</h1>
        <input type="hidden" name="mode" id="board_mode" value="">
        <input type="hidden" name="idx" id="board_idx" value="">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex gap-2">
        <input type="text" id="board_title" class="form-control" placeholder="게시판 이름">
        <select name="" id="board_type" class="form-select">
            <option value="board">게시판</option>
            <option value="gallery">갤러리</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_board_create">확인</button>
      </div>
    </div>
  </div>
</div>

<?php
include 'inc_footer.php';
?>
