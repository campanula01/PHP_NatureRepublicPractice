<?php
//게시판 목록
include 'inc/dbconfig.php';
include 'inc/board_manage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list1();

$js_array=['js/member_success.js'];
include 'inc/common.php';
include 'inc_header.php';
?>
<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5" style="height: calc(100vh-200px);">
    <img src="images/logo1.jpeg" class="w-50" alt="">

    <div>
        <h3>main.</h3>
        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit vitae ullam eligendi numquam id inventore corrupti esse pariatur, reiciendis nisi debitis exercitationem dignissimos iusto impedit totam officia illo dolor quisquam!
        </p>
        <button type="button" id="btn_login" class="btn btn-green1">로그인 하기</button>
    </div>

</main>
<?php
include 'inc_footer.php';
?>
