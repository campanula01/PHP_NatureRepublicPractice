<?php

//json형식으로 제공해야함.
session_start();


$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] !='') ? $_SESSION['ses_id']:'';
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] !='') ? $_SESSION['ses_level']:'';

if($ses_id =='' || $ses_level != 10){
    $arr = ["result"=>"success_denied"];
    die(json_encode($arr)); //{"result":"empty_idx"}
}


include '../inc/dbconfig.php';
include '../inc/member.php';

$idx =(isset($_POST['idx']) && $_POST['idx'] !='' && is_numeric($_POST['idx']))? $_POST['idx']:'';

if($idx ==''){
    $arr = ["result"=>"empty_idx"];
    die(json_encode($arr)); //{"result":"empty_idx"}
}

$mem = new Member($db);
$mem->member_del($idx);

$arr = ["result"=>"success"];
die(json_encode($arr)); 


?>