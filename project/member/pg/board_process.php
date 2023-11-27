<?php

$err_array = error_get_last();

$a =(int) ini_get("post_max_size")*1024*1024;

if(isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > $a){

    $arr =['result'=>'post_size_exceed'];
    die(json_encode($arr));
}


include '../inc/common.php';
include '../inc/dbconfig.php';
include '../inc/board.php'; //게시판 class
include '../inc/member.php';

$mode = (isset($_POST['mode']) && $_POST['mode'] !='')?$_POST['mode']:'';
$bcode = (isset($_POST['bcode']) && $_POST['bcode'] !='')?$_POST['bcode']:'';
$subject = (isset($_POST['subject']) && $_POST['subject'] !='')?$_POST['subject']:'';
$content = (isset($_POST['content']) && $_POST['content'] !='')?$_POST['content']:'';
$idx = (isset($_POST['idx']) && $_POST['idx'] !='' && is_numeric($_POST['idx']))?$_POST['idx']:'';
$th = (isset($_POST['th']) && $_POST['th'] !='' && is_numeric($_POST['th']))?$_POST['th']:'';

if($mode ==''){
    $arr=["result"=>"empty_mode"];
    $json_str = json_encode($arr);  //배열=>json문자열
    die($json_str);
}
if($bcode ==''){
    $arr=["result"=>"empty_bcode"];
    $json_str = json_encode($arr);  //배열=>json문자열
    die($json_str);
}

$board = new Board($db);
$member = new Member($db);

if($mode=='input'){

    //이미지 변환하여 저장
    preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",$content,$matches);

    $img_array=[];
    foreach($matches[1] AS $key =>$row){
        if(substr($row,0,5) != 'data:'){
            continue;
        }

        list($type, $data) = explode(';',$row);
        list(,$data) = explode(',',$data);
        $data = base64_decode($data);
        list(,$ext) = explode('/',$type);
        $ext = ($ext == 'jpeg')?'jpg':$ext;

        $filename = date('YmdHis').'_'.$key.'.'.$ext;

        file_put_contents(BOARD_DIR."/".$filename, $data);

        $content =str_replace($row, BOARD_WEB_DIR."/".$filename,$content);
        $img_array[] = BOARD_WEB_DIR."/".$filename;

        
    }

    
    if($subject ==''){
    $arr=["result"=>"empty_subject"];
        $json_str = json_encode($arr);  //배열=>json문자열
        die($json_str);
    }
    if($content =='' || $content=='<p><br></p>'){
            $arr=["result"=>"empty_content"];
            $json_str = json_encode($arr);  //배열=>json문자열
            die($json_str);
    }

    //다중 파일 첨부

    //파일첨부
    $file_list_str = '';
    if(isset($_FILES['files'])){

        if(sizeof($_FILES['files']['name'])>4){
            $arr = ["result"=>"file_upload_count_exceed"];
            die(json_encode($arr));
        }

        $tmp_arr =[];

        foreach($_FILES['files']['name'] AS $key =>$val){
            $_FILES['files']['name'][$key];

            $full_str ='';
            $tmparr = explode('.', $_FILES['files']['name'][$key]);
            $ext = end($tmparr);
            //exe파일 제어
            $not_allowed_file_ext =['exe','xls'];

            if(in_array($ext, $not_allowed_file_ext)){
                $arr = ['result'=>'not_allowed_file'];
                die(json_encode($arr));
            }
            $flag = rand(1000,9999);
            $filename='a'.date('YmdHis').$flag.'.'.$ext;
            $file_ori =$_FILES['files']['name'][$key];

            //copy() move_uploaded_file()
            copy($_FILES['files']['tmp_name'][$key], BOARD_DIR.'/'.$filename);

            //파일명으로 할 수 없는 걸고 |
            $full_str = $filename.'|'.$file_ori;
            $tmp_arr[] = $full_str;

        }
        $file_list_str = implode('?',$tmp_arr);
        
    }

    $memArr = $member->getInfo($ses_id);
    if($ses_id==''){

    }


    $name = $memArr['name'];

    $arr = [
        'bcode'=>$bcode,
        'id'=>$ses_id,
        'name'=>$name,
        'subject'=>$subject,
        'content'=>$content,
        'files'=>$file_list_str,
        'ip'=>$_SERVER['REMOTE_ADDR']
    ];

    $board ->input($arr);
    $arr=["result"=>"success"];
    $json_str = json_encode($arr);  //배열=>json문자열
    header('Content-Type: application/json');  // Content-Type을 JSON으로 설정
    die($json_str);

}else if($mode == 'each_file_del'){
    if($idx==''){
        $arr=["result"=>"empty_idx"];
        die(json_encode($arr));
    }
    if($th==''){
        $arr=["result"=>"empty_th"];
        die(json_encode($arr));
    }

    $file = $board->getAttachFile($idx, $th);
    
    $each_files = explode('|',$file);
    if(file_exists(BOARD_DIR.'/'.$each_files[0])){
        unlink(BOARD_DIR.'/'.$each_files[0]);

    }

    $row =$board->view($idx);
    $files = explode('?', $row['files']);
    $tmp_arr = [];
    foreach($files AS $key=>$val){
        if($key==$th){
            continue;
        }   //삭제된 번호만 빼고 합치기
        $tmp_arr[] = $val;
    }

    $files = implode('?', $tmp_arr);    //새로 조합된 파일리스트 문자열

    $tmp_arr=[];
    $downs = explode('?',$row['downhit']);
    foreach($downs AS $key=>$val){
        if($key==$th){
            continue;
        }   //삭제된 번호만 빼고 합치기
        $tmp_arr[] = $val;
    }
    $downs = implode('?', $tmp_arr);    //새로 조합된 다운로드수 문자열

    $board->updateFileList($idx, $files, $downs);



    $arr=["result"=>"success"];
    die(json_encode($arr));
}