<?php
/**die() 함수는 PHP에서 현재 스크립트의 실행을 즉시 종료하는 데 사용됩니다.
 *  die() 함수가 호출되면 스크립트는 즉시 실행을 멈추고, 
 * 선택적으로 종료하기 전에 메시지를 출력할 수 있습니다(인수로 문자열이 전달된 경우).
*/
session_start();


$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] !='') ? $_SESSION['ses_id']:'';

if($ses_id==''){
    echo "
    <script>
        alert('로그인 후 접근이 가능한 페이지입니다.');
        self.location.href='./main.php';
    </script>
    ";
    exit;
}

include 'inc/dbconfig.php';
include 'inc/member.php';
$member = new Member($db);

$memArr = $member->getInfo($ses_id);

//print_r($memArr);

$js_array=['js/mypage.js'];
$g_title = '회원 정보 수정';
include 'inc_header.php'; ?>


<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>



<main class="w-50 mx-auto border rounded-5 p-5">
    <h1 class="text-center">회원정보 수정</h1>

    <form name="input_form" method="post" enctype="multipart/form-data" autocomplete="off" action="pg/member_process.php">
        <input type="hidden" name="mode" value="edit">
        <input type="hidden" name="email_chk" value="0">
        <input type="hidden" name="zipcode_chk" value="0">
        <input type="hidden" name="old_email" value="<?= $memArr['email']; ?>">
        <input type="hidden" name="old_photo" value="<?= $memArr['photo']; ?>">


    <div class="d-flex gap-2 align-items-end" >
    <div>
        <label for="f_name" class="form-label">이름</label>
        <input type="text" name="name" class="form-control" id="f_name" readonly value="<?=$memArr['name'];?>" >
    </div>
    </div>


    <div class="d-flex mt-3 gap-2 align-items-end" >
    <div>
        <label for="f_id" class="form-label">아이디</label>
        <input type="text" name="id" class="form-control" readonly id="f_id" placeholder="ID" value="<?=$memArr['id'];?>">
    </div>
    
    </div>


    <div class="d-flex mt-3 gap-2 justify-content-between" >
    <div class="w-50">
        <label for="f_password" class="form-label">비밀번호</label>
        <input type="password" name="password" class="form-control" id="f_password" placeholder="PassWord">
    </div>
    <div class="w-50">
        <label for="f_password2" class="form-label">비밀번호 확인</label>
        <input type="password" name="password2" class="form-control" id="f_password2" placeholder="PassWord Check">
    </div>
    </div>



    <div class="d-flex gap-2 align-items-end" >
    <div class="flex-grow-1">
        <label for="f_email" class="form-label">이메일</label>
        <input type="text" name="email" class="form-control" id="f_email" placeholder="Email" value="<?=$memArr['email'];?>">
    </div>
    <button type="button" id="btn_email_check" class="btn btn-secondary">이메일 중복 확인</button>
    </div>

    <div class="d-flex mt-3  gap-2 align-items-end">
        <div >
            <label for="f_zipcode">우편번호</label>
            <input type="text" name="zipcode" id="f_zipcode" readonly value="<?=$memArr['zipcode'];?>" class="form-control" maxlength="5" minlength="5" placeholder="우편번호 찾기를 이용해주세요.">

        </div>
        <button type="button" id="btn_zipcode" class="btn btn-secondary">우편번호 찾기</button>
    </div>


    <div class="d-flex mt-3 gap-2 justify-content-between" >
    <div class="w-50">
        <label for="f_addr1" class="form-label">주소</label>
        <input type="text" class="form-control" name="addr1" id="f_addr1" value="<?=$memArr['addr1'];?>"placeholder="">
    </div>
    <div class="w-50">
        <label for="f_addr2" class="form-label">상세주소</label>
        <input type="text" class="form-control" name="addr2" id="f_addr2" value="<?=$memArr['addr2'];?>" placeholder="상세 주소를 입력해주세요.">
    </div>
    </div>

    <div class="mt-3 d-flex gap-5">

        <div>
            <label for="f_photo" class="form-label">프로필 이미지</label>
            <input type="file" name="photo" id="f_photo" class="form-control">
        </div>
        <?php 
        if($memArr['photo']){
            echo '<img src="data/profile/'.$memArr['photo'].'" id="f_preview" class="w-25 border rounded-5" alt="profile image">';
        }else{
            echo '<img src="images/person.png" id="f_preview" class="w-25 border rounded-5" alt="profile image">';
        }
        ?>
        
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="button" class="btn btn-green1 w-50" style="height: 50px;" id="btn_submit">수정확인</button>
        <button type="button" class="btn btn-secondary w-50">수정취소</button>

    </div>

    </form>

</main>
<?php include 'inc_footer.php'; ?>