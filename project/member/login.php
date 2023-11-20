<?php

$g_title="로그인";
$js_array=['js/login.js'];

include 'inc_header.php';
?>
<main class="mx-auto border rounded-5 p-5 d-flex gap-5" style="height: calc(100vh-200px);">
    <form method="post" class="w-25 mt-5 m-auto" action="">
        <img src="./images/logo.png" width="200" alt="">
        <h3 class="h3 mb-3">로그인</h3>
        <div class="form-floating mt-2">
            <input type="text" class="form-control" id="f_id" placeholder="ID" autocomplete="off">
            <label for="f_id">아이디</label>
        </div>

        <div class="form-floating mt-2">
            <input type="password" class="form-control" id="f_pw" placeholder="PW" autocomplete="off">
            <label for="f_pw">비밀번호</label>
        </div>

        <button type="button" id="btn_login" class="w-100 mt-2 btn btn-lg btn-green1">확인</button>
    </form>
</main>


<?php
include 'inc_footer.php';
?>
