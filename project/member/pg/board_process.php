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
    $file_cnt=4;
    if(isset($_FILES['files'])){

        $file_list_str = $board->file_attach($_FILES['files'],$file_cnt);

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
else if($mode == 'file_attach'){
    //수정에서 개별파일 첨부하기
    $file_list_str = '';
    if(isset($_FILES['files'])){
        $file_cnt = 1;
        $file_list_str = $board->file_attach($_FILES['files'],$file_cnt);

    }else{
        $arr=["result"=>"empty_files"];
        die(json_encode($arr));
    }

    $row =$board->view($idx);
    if($row['files']!=''){
        $files = $row['files'].'?'.$file_list_str;
    }else{

        $files =$file_list_str;
    }

    if($row['downhit']!=''){
        $downs = $row['downhit'].'?0';
    }else{
        $downs ='';
    }

    $board->updateFileList($idx, $files, $downs);

    $arr=["result"=>"success"];
    die(json_encode($arr));
}else if($mode=='edit'){

    $row=$board->view($idx);
    if($row['id']!=$ses_id){
        die(json_encode(["result"=>"permission_denied"]));
    }

    $old_img_arr = $board->extract_image($row['content']);


    //이미지 변환하여 저장
    preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",$content,$matches);

    $current_img_arr=[];
    foreach($matches[1] AS $key =>$row){
        if(substr($row,0,5) != 'data:'){
            $current_img_arr[] = $row;
            continue;
        }   //이미 업로드된 이미지는 처리하지 않는다.

        list($type, $data) = explode(';',$row);
        list(,$data) = explode(',',$data);
        $data = base64_decode($data);
        list(,$ext) = explode('/',$type);
        $ext = ($ext == 'jpeg')?'jpg':$ext;

        $filename = date('YmdHis').'_'.$key.'.'.$ext;

        file_put_contents(BOARD_DIR."/".$filename, $data);  //파일 업로드 수행

        $content =str_replace($row, BOARD_WEB_DIR."/".$filename,$content);//base64 인코딩된 이미지가 서버 업로드 이름으로 변경

        
    }

    $diff_img_arr = array_diff($old_img_arr,$current_img_arr);
    foreach($diff_img_arr AS $value){
        unlink("../".$value);
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
    $arr = [
        'idx'=>$idx,
        'subject'=>$subject,
        'content'=>$content
    ];

    $board ->edit($arr);
    die(json_encode(["result"=>"success"]));

}else if($mode=='delete'){
    $row = $board->view($idx);
    //본문 이미지 삭제
    $img_arr =$board->extract_image($row['content']);
    foreach($img_arr AS $value){
        unlink("../".$value);
    }

    //첨부파일 삭제
    if($row['files']!=''){
    $filelist = explode('?', $row['files']);
        foreach($filelist AS $value){
            list($file_src, ) = explode('|',$value);
            if(file_exists("../".$value)){
                unlink(BOARD_DIR.'/'.$file_src);
            }
        }
    }

    $board->delete($idx);

    die(json_encode(["result"=>"success"]));
}