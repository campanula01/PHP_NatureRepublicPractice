<?php

$js_array=['js/admin_main.js'];

include 'inc_common.php';
include 'inc_header.php';
?>





       

    <div  class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5 mt-5" style="height: calc(100vh-200px);">
    <img src="../images/logo-sns.png" class="w-25" alt="">

    <div>
        <h3>빡독 관리페이지 입니다.</h3>
        <p>
        이곳에서 회원, 게시판, 팝업, 슬라이드 관리를 할 수 있습니다.
        </p>
        <button type="button" id="btn_main" class="btn btn-green1">사용자 메인페이지로 가기</button>
    </div>
    </div>
</main>
<?php
include 'inc_footer.php';
?>