<?php
include 'inc/common.php';
include 'inc/dbconfig.php';
include 'inc/board.php';
include 'inc/comment.php';
include 'inc/lib.php';//페이지네이션


$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
$idx = (isset($_GET['idx']) && $_GET['idx']!='' && is_numeric($_GET['idx']))?$_GET['idx']:'';


if($bcode == ''){
    die("<script>alert('게시판 코드가 빠졌습니다.');history.go(-1);</script>");
}
if($idx == ''){
    die("<script>alert('게시물 번호가 빠졌습니다.');history.go(-1);</script>");
}

//게시판 목록
include 'inc/board_manage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list1();
$board_name = $boardm->getBoardName($bcode);


$board = new Board($db);//게시판 클래스
$menu_code='board';
$js_array=['js/board_view.js'];
$g_title = $board_name;


$boardRow = $board->hitInc($idx);
$boardRow = $board->view($idx);

if($boardRow == null){
    die("<script>alert('존재하지 않는 게시물입니다.');history.go(-1);</script>");
}

//댓글 목록
$comment = new Comment($db);

$commentRs = $comment->list($idx);
//다운로드 횟수 배열
$downhit_arr = explode('?', $boardRow['downhit']);

include 'inc_header.php';

?>

<main class="w-100 mx-auto border rounded-2 p-5" style="height: calc(100vh-200px);">

<h3 class="text-center"><?=$board_name ?></h3>

    <div class="vstack w-75 mx-auto">
        <div class="p-3">
            <span class="h4 fw-bolder"><?=$boardRow['subject'];?></span>
        </div>
        <div class="d-flex border border-top-0 border-start-0 border-end-0 border-bottom-1">
            <span><?=$boardRow['name']; ?></span>
            <span class="ms-5 me-auto"><?=$boardRow['hit']; ?></span>
            <span><?=$boardRow['create_at']; ?></span>
        </div>
        <div class="p-3">
            <?=$boardRow['content'];?>
            <?php
            //첨부파일 출력

                if($boardRow['files']!=''){
                    $filelist = explode('?',$boardRow['files']);
                    $th=0;

                    //[배열명] = array_fill([시작번호],[제품 항목수], "[값]");
                    if($boardRow['downhit']==''){
                        $downhit_arr = array_fill(0,count($filelist),0);
                    }
                    foreach($filelist AS $file){
                        list($file_source, $file_name) = explode('|',$file);
                        echo "<a href=\"./pg/board_download.php?idx=$idx&th=$th\">$file_name</a>(다운로드: ".$downhit_arr[$th].")<br>";
                        $th++;
                    }
                }
            ?>
        </div>
        <div class="d-flex gap-2 p-3">
            <button class="btn btn-secondary me-auto" id="btn_list">목록</button>

            <?php if($boardRow['id']==$ses_id){?>
            <button class="btn btn-green1" id="btn_edit">수정</button>
            <button class="btn btn-danger" id="btn_delete">삭제</button>
            <?php } ?>
        </div>

        <div class="d-flex gap-2 mt-3">
            <textarea name="" rows="3" class="form-control" id="comment_content"></textarea>
            <button class="btn btn-secondary" id="btn_comment" data-comment-idx="0">등록</button>
        </div>  
        
        <div class="mt-3">
            <table class="table ">
                <colgroup>
                    <col width="50%"  />
                    <col width="10%"  />
                    <col width="10%"  />
                </colgroup>
                <?php 
                    foreach($commentRs AS $comRow){
                ?>
                <tr>
                    <td>
                        <span><?php echo nl2br($comRow['content']); ?></span>

                        <?php
                        if($comRow['id']==$ses_id){
                            echo '
                            <button class="btn btn-info btn-sm p-0 ms-2 btn_comment_edit" data-comment-idx="'.$comRow['idx'].'">수정</button>
                            <button class="btn btn-danger btn-sm p-0 ms-2 btn_comment_delete" data-comment-idx="'.$comRow['idx'].'">삭제</button>
                            ';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $comRow['id']; ?>
                    </td>
                    <td>
                        <?php echo $comRow['create_at']; ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

</main>




<?php
include 'inc_footer.php';
?>