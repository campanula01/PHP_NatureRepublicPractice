<?php
include '../inc/dbconfig.php';
include '../inc/member.php';

$member = new Member($db);

//값이 존재하고 ''가 아닐 때 post값으로 설정.
$id = (isset($_POST['id']) && $_POST['id'] !='') ? $_POST['id'] : '' ;
$password = (isset($_POST['password']) && $_POST['password'] !='') ? $_POST['password'] : '' ;
$email = (isset($_POST['email']) && $_POST['email'] !='') ? $_POST['email'] : '' ;
$mode = (isset($_POST['mode']) && $_POST['mode'] !='') ? $_POST['mode'] : '' ;

$name = (isset($_POST['name']) && $_POST['name'] !='') ? $_POST['name'] : '' ;
$zipcode = (isset($_POST['zipcode']) && $_POST['zipcode'] !='') ? $_POST['zipcode'] : '' ;
$addr1 = (isset($_POST['addr1']) && $_POST['addr1'] !='') ? $_POST['addr1'] : '' ;
$addr2 = (isset($_POST['addr2']) && $_POST['addr2'] !='') ? $_POST['addr2'] : '' ;
$old_photo = (isset($_POST['old_photo']) && $_POST['old_photo'] !='')?$_POST['old_photo']:'';

//중복체크
if($mode=='id_chk'){

    if($id == ''){
        die(json_encode(['result'=>'empty_id']));
    }
    if($member->id_exists($id)){
        $arr = ['result'=>'fail'];  //배열
        $json = json_encode($arr);  //json 타입 {"result":"fail"}

        die($json);
    }else{
        die(json_encode(['result'=>'success']));
    }
}else if($mode=='email_chk'){

    if($email == ''){
        die(json_encode(['result'=>'empty_email']));
    }

    //이메일 형식 체크
    if($member->email_format_check($email)===false){
        die(json_encode(['result'=>'email_format_wrong']));
    }

    if($member->email_exists($email)){
        $arr = ['result'=>'fail'];  //배열
        $json = json_encode($arr);  //json 타입 {"result":"fail"}

        die($json);
    }else{
        die(json_encode(['result'=>'success']));
    }
}else if($mode == 'input'){

    //profile image처리
    //print_r($_FILES);
    // exit;
    // $tmparr = explode('.', $_FILES['photo']['name']);
    // $ext = end($tmparr);
    // $photo = $id. '.' .$ext;
    // //리눅스에서 쉘에 들어가서
    //chmod 777 data/profile

    
// Array
// (
//     [photo] => Array
//         (
//             [name] => 2.jpg
//             [full_path] => 2.jpg
//             [type] => image/jpeg
//             [tmp_name] => C:\xampp\tmp\php4898.tmp
//             [error] => 0
//             [size] => 76850
//         )

// )
    //photo로 복사


     //copy($_FILES['photo']['tmp_name'], "../data/profile/".$photo);
  


     if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
        // 파일이 존재하고, 에러가 없는 경우에만 처리
        $tmparr = explode('.', $_FILES['photo']['name']);
        $ext = end($tmparr);
        $photo = $id . '.' . $ext;
        copy($_FILES['photo']['tmp_name'], "../data/profile/" . $photo);
    }
     

    $arr = [
        'id' =>$id,
        'password'=>$password,
        'email' =>$email,
        'name'=>$name,
        'zipcode'=>$zipcode,
        'addr1'=>$addr1,
        'addr2'=>$addr2,
        'photo'=>$photo
    ];

    $member->input($arr);

    echo "
    <script>
        self.location.href='../member_success.php'
    </script>
    ";
}else if($mode == 'edit'){

    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
        $new_photo = $_FILES['photo'];
    
        $old_photo  = $member->profile_upload($id, $new_photo, $old_photo);
    }

    session_start();
    $arr = [
        'id' =>$_SESSION['ses_id'],
        'password'=>$password,
        'email' =>$email,
        'name'=>$name,
        'zipcode'=>$zipcode,
        'addr1'=>$addr1,
        'addr2'=>$addr2,
        'photo'=>$old_photo
    ];

    $member->edit($arr);

    echo "
    <script>
        alert('수정되었습니다.');
        self.location.href='../main.php'
    </script>
    ";

}


