<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($g_title) &&$g_title='')?$g_title:'빡독 관리자' ?></title>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<style>

  
@font-face {
    font-family: 'SOYOMapleBoldTTF';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2310@1.0/SOYOMapleBoldTTF.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
}


body{
  font-family: 'SOYOMapleBoldTTF';
}

    .form-control:focus {
        border-color: #20B2AA	; /* 녹색 코드 */
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); /* 주변에 그림자 효과를 주어 강조 */
    }

    .textarea:focus{
        border-color: #20B2AA	; /* 녹색 코드 */
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); /* 주변에 그림자 효과를 주어 강조 */
    
    }

   

/* 체크박스 배경 및 테두리 색상 변경 */
.form-check-input:checked {
            background-color: 	#20B2AA; /* 부트스트랩 성공 색상 */
            border-color: 	#20B2AA; /* 같은 색상으로 테두리 설정 */
        }
        
        /* 체크 표시(✔) 색상 변경 */
        .form-check-input {
            accent-color: 	#20B2AA; /* 부트스트랩 성공 색상 */
        }
          /* 포커스 시 체크박스 테두리 색상 변경 */
          .form-check-input:focus {
            border-color: 	#20B2AA; /* 녹색 테두리 색상 */
            box-shadow: 0 0 0 0.25rem 		#20B2AA	; /* 녹색 그림자 테두리 */
        }

        .btn-green1 {
          background-color: #20B2AA	;
          color: white;
          transition: background-color 0.3s; /* 부드러운 색상 전환 효과 */
        }

        .btn-green2 {
          color: rgba(1, 155, 1, 0.767);
        }

        .btn-green1:active {
        background-color: white; /* 배경색을 흰색으로 */
        color: rgba(1, 155, 1, 0.767); /* 글자 색상을 원래의 배경색으로 */
        }
        
        .btn-green1:hover {
          background-color: #008B8B;
          color: white; /* 마우스를 올렸을 때의 배경 색 */
          /* 추가적인 스타일 변경을 원한다면 여기에 추가 */
        }

        .btn-green3{
          background-color: rgba(1, 155, 1, 0.767) !important; /* 배경색 변경 */
    color: white !important; /* 글자 색 변경 */
        }

        /* 자신의 CSS 파일이나 <style> 태그 내부에 추가 */
.pagination .page-link {
  
  background-color: white !important; /* 배경색을 흰색으로 */
    color: rgba(1, 155, 1, 0.767) !important; /* 글자 색을 녹색으로 */
}

/* 활성 상태의 페이지 링크 */
.pagination .page-item.active .page-link {
  background-color: rgba(1, 155, 1, 0.767) !important; /* 배경색 변경 */
    color: white !important; /* 글자 색 변경 */
}

/* 마우스 오버 효과 */
.pagination .page-link:hover {
  background-color: rgba(1, 155, 1, 0.767) !important; /* 배경색 변경 */
    color: white !important; /* 글자 색 변경 */
}

</style>

<?php 


if(isset($js_array)){
    foreach($js_array AS $var){
        echo '<script src="'.$var.'?v='.date('YmdHis').'"></script>'.PHP_EOL;
    }    
}

?>

<script>
  document.addEventListener("DOMContentLoaded",()=>{

    const login = document.querySelector("#login");
    if(login) {
  login.addEventListener("click",()=>{
    self.location.href="../login.php"
  })
}


  const stipulation = document.querySelector("#stipulation");
  if(stipulation) {
  stipulation.addEventListener("click",()=>{
    self.location.href="../stipulation.php"
  })
}

  const logout = document.querySelector("#logout");
  if(logout) {
  logout.addEventListener("click",()=>{
    self.location.href="../pg/logout.php"
  })
}
  
  const mypage = document.querySelector("#mypage");
  if(mypage) {
  mypage.addEventListener("click",()=>{
    self.location.href="../mypage.php"
  })
  }

  const adminpage = document.querySelector("#adminpage");
  if(adminpage) {
    adminpage.addEventListener("click",()=>{
    self.location.href="../admin/admin_main.php"
  })
  }
})

</script>
</head>
<body>
    <div class="container">
        <header class="p-3 mb-3 border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="../main.php" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
              <img src="../images/logo.png" style="width:7rem" class="me-2 p-3">
            </a>
    
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
    
              <li><a href="member.php" class="nav-link px-2 link-body-emphasis">회원 관리</a></li>
              <li><a href="board.php" class="nav-link px-2 link-body-emphasis">게시판 관리</a></li>
              <li><a href="popup.php" class="nav-link px-2 link-body-emphasis">팝업 관리</a></li>
              <li><a href="slide.php" class="nav-link px-2 link-body-emphasis">슬라이드 관리</a></li>
            </ul>
    
            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
              <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>
    

            <div class="text-end">
              <?php if(isset($ses_id)&& $ses_id !=''){
              //로그인이 된 상태
              ?>
              <button type="button" class="btn btn-light  me-2" id="logout">로그아웃</button>
              <?php 
                if($ses_level ==10){
              ?>
                <button type="button" class="btn btn-green1 " id="adminpage">관리 페이지</button>
              <?php
                }else{
                ?>
              <button type="button" class="btn btn-dark " id="mypage">Profile</button>

              <?php
                }
              ?>
            
              <?php  
              }else{
                ?>
                <button type="button" class="btn btn-light  me-2" id="login">Login</button>
                <button type="button" class="btn btn-dark " id="stipulation">Sign-up</button>
            <?php
              }
              ?>
                </div>


          </div>
          </header>