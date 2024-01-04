<?php
//게시판 목록
include 'inc/dbconfig.php';
include 'inc/board_manage.php';
include 'inc/popup.php';
include 'inc/slide.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list1();

//팝업
$popup = new Popup($db);
$popupArr = $popup->valid_list();

$slide = new Slide($db);
$slideArr = $slide->valid_list();


$js_array=['js/member_success.js'];
include 'inc/common.php';
include 'inc_header.php';
?>
<style>
  .close{
    width: 11px; height: 11px; background-color: white; margin-top: 7px; margin-right: 7px;
    clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%);}

</style>
<?php 
    foreach($popupArr AS $popupRow){
        if(isset($_COOKIE['pop'.$popupRow['idx']]) && $_COOKIE['pop'.$popupRow['idx']]=='1'){
            continue; //팝업창 생성하지 않고 넘어감.
        }
        list($img_src, ) = explode('|', $popupRow['file']);
        $img_src = './data/popup/'.$img_src;

        switch($popupRow['cookie']){
            case 'day' : $cookie_msg = '하루 동안'; break;
            case 'week' : $cookie_msg = '일주일 동안'; break;
            case 'month' : $cookie_msg = '한 달 동안'; break;
        }
?>

<style>
    #pop<?php echo $popupRow['idx']; ?>{
      border: 1px solid #999; left: <?php echo $popupRow['pop_x']; ?>px; top: <?php echo $popupRow['pop_y']; ?>px; position: absolute; z-index: 1000;
      display: block;
    }
</style>
<div id="pop<?php echo $popupRow['idx']; ?>">
  <img src="<?php echo $img_src; ?>" alt="">
  <div class="d-flex gap-2 bg-dark text-white">
    <input type="checkbox" name="chk" value="<?php echo $popupRow['cookie']; ?>" data-idx="<?php echo $popupRow['idx']; ?>" class="ms-auto chk_close">
    <span id="cookie_term"><?php echo $cookie_msg; ?> 이 창을 다시 열지 않음</span>
    <span class="close"></span>
  </div>
  
</div>
<?php
    }
?>

<script>
    function setCookie(name, value, exp){
        let data = new Date();
        data.setTime(data.getTime()+exp*24*60*60*1000)
        //console.log(data);

        document.cookie = name+'='+value+';expires='+data+';path=/';
    }
    setCookie('a',1,1)
    const closes = document.querySelectorAll(".close");
    closes.forEach((box)=>{
        box.addEventListener("click",()=>{
            console.log("Close button clicked!"); // 디버깅 메시지 추가
            box.parentNode.parentNode.style.display ='none';
        })
    })

    const chk_closes = document.querySelectorAll('.chk_close');
    chk_closes.forEach((box)=>{
        box.addEventListener("click",()=>{
            let term = 0;
            switch(box.value){
                case 'day' : term = 1; break;
                case 'week' : term = 7; break;
                case 'month' : term = 30; break;
            }

            setCookie('pop'+box.dataset.idx,'1',term)
            //alert(box.dataset.idx);
            box.parentNode.parentNode.style.display ='none';

        })
    })
</script>

<main>

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <?php 
    foreach($slideArr as $key => $slideRow){
        $activeClass = ($key === 0) ? 'active' : ''; // 첫 번째 슬라이드에 active 클래스 추가
    ?>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $key; ?>" class="<?= $activeClass; ?>" aria-label="Slide <?= $key + 1; ?>"></button>
    <?php
    }
    ?>
  </div>
  <div class="carousel-inner">
    <?php 
    foreach($slideArr as $key => $slideRow){
        $activeClass = ($key === 0) ? 'active' : ''; // 첫 번째 슬라이드에 active 클래스 추가
        list($img_src, ) = explode('|', $slideRow['file']);
        $img_src = './data/slide/'.$img_src;
    ?>
      <div class="carousel-item <?= $activeClass; ?>">
        <img src="<?= $img_src; ?>" class="d-block w-100" alt="">
      </div>
    <?php
    }
    ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>



       

    <div  class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5 mt-5" style="height: calc(100vh-200px);">
    <img src="images/logo-sns.png" class="w-25" alt="">

    <div>
        <h3>빡독</h3>
        <p>
        안녕하세요! 빡세게 독서하자! 독서 모임 '빡독'입니다.😀<br>

        '빡독'은 2017년 7월부터 시작된 수원 인근 지역 거주민들의 독서 모임입니다.<br>

        '빡독'은 "좋은 책, 좋은 이야기, 좋은 사람들"이라는 가치 아래 역사를 이어가고 있어요.<br>

            오늘도 단단하고 알찬 모임으로 성장하고 있는 '빡독'!<br>
            여러분의 많은 관심 부탁드립니다!<br>
        </p>
        <button type="button" id="btn_login" class="btn btn-green1">로그인 하기</button>
    </div>
    </div>
</main>
<?php
include 'inc_footer.php';
?>
