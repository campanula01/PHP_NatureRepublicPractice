<?php
include '../inc_common.php';
include '../../inc/dbconfig.php';
include '../../inc/member.php';

$member = new Member($db);

//값이 존재하고 ''가 아닐 때 post값으로 설정.
$idx = (isset($_POST['idx']) && $_POST['idx'] !='') ? $_POST['idx'] : '' ;
$id = (isset($_POST['id']) && $_POST['id'] !='') ? $_POST['id'] : '' ;
$password = (isset($_POST['password']) && $_POST['password'] !='') ? $_POST['password'] : '' ;
$email = (isset($_POST['email']) && $_POST['email'] !='') ? $_POST['email'] : '' ;
$mode = (isset($_POST['mode']) && $_POST['mode'] !='') ? $_POST['mode'] : '' ;

$name = (isset($_POST['name']) && $_POST['name'] !='') ? $_POST['name'] : '' ;
$zipcode = (isset($_POST['zipcode']) && $_POST['zipcode'] !='') ? $_POST['zipcode'] : '' ;
$addr1 = (isset($_POST['addr1']) && $_POST['addr1'] !='') ? $_POST['addr1'] : '' ;
$addr2 = (isset($_POST['addr2']) && $_POST['addr2'] !='') ? $_POST['addr2'] : '' ;

$level = (isset($_POST['level']) && $_POST['level'] !='') ? $_POST['level'] : '' ;
$old_photo = (isset($_POST['old_photo']) && $_POST['old_photo'] !='') ? $_POST['old_photo'] : '' ;




if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {

    $new_photo = $_FILES['photo'];
    $old_photo  = $member->profile_upload($id, $new_photo, $old_photo);
}


$arr = [
    'idx'=>$idx,
    'id' =>$id,
    'password'=>$password,
    'email' =>$email,
    'name'=>$name,
    'zipcode'=>$zipcode,
    'addr1'=>$addr1,
    'addr2'=>$addr2,
    'photo'=>$old_photo,
    'level'=>$level
];

$member->edit($arr);

echo "
<script>
    alert('수정되었습니다.');
    self.location.href='../admin_main.php'
</script>
";



