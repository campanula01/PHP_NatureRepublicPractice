<?php
//db연결
include 'inc/dbconfig.php';
include 'inc/member.php';
//아이디 중복 테스트
$id='king';
$email='email@email.com';
$mem = new Member($db);

if($mem->id_exists($id)){
    echo "아이디가 중복됩니다";

}else{
    echo "아이디를 사용할 수 있습니다.";
}


if($mem->email_exists($email)){
    echo "email 중복됩니다";

}else{
    echo "email을 사용할 수 있습니다.";
}