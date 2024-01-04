<?php
include '../inc_common.php';
include '../../inc/dbconfig.php';
include '../../inc/slide.php';

$name = (isset($_POST['name']) && $_POST['name'] !='')?$_POST['name']:'';
$idx = (isset($_POST['idx']) && $_POST['idx'] !='')?$_POST['idx']:'';
$mode = (isset($_POST['mode']) && $_POST['mode'] !='')?$_POST['mode']:'';

if($mode==''){
    $arr = ['result'=>'empty_mode'];
    die(json_encode($arr));
}
if($mode =='getInfo'||$mode=='edit'||$mode=='delete'){
    if($idx==''){
        $arr=['result'=>'empty_idx'];
        die(json_encode($arr));
    }
}

if($mode=='input'||$mode=='edit'){

    if($name==''){
        $arr = ['result'=>'empty_name'];
        die(json_encode($arr));
    }

}
$slide = new Slide($db);

if($mode=='input'){

    if(!isset($_FILES['file']['name']) || $_FILES['file']['name']==''){
        $arr = ['result'=>'empty_file'];
        die(json_encode($arr));
    }

    $filename = $slide->file_attach($_FILES['file']);

    $arr =[
        'name'=>$name, 'file'=>$filename
    ];
    $slide->input($arr);
    $arr = ['result'=>'success'];
    die(json_encode($arr));

}else if($mode == 'edit'){

    $filename='';
    
    if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=''){
        //파일업로드 , 기존 파일 삭제, 새로운 파일이름 db에 업데이트
        $filename = $slide->file_attach($_FILES['file']);
        $row = $slide->get_info($idx);
        if($row['file'] !=''){
            list($file_src,)=explode('|', $row['file']);
            if($file_src != ''){
                $slide->file_unlink($file_src);
            }
        }
    }

    
    $arr =[
        'name'=>$name, 'file'=>$filename, 
        'idx'=>$idx
    ];

    $slide->edit($arr);
    $arr = ['result'=>'success'];
    die(json_encode($arr));

}
else if($mode=='delete'){
    $row = $slide->get_info($idx);
    if($row['file'] !=''){
        list($file_src,)=explode('|', $row['file']);
        if($file_src != ''){
            $slide->file_unlink($file_src);
        }
    }

    $slide -> delete($idx);
}
else if($mode=='getInfo'){


    $row = $slide->get_info($idx);

    list($file, ) = explode('|', $row['file']);
    $file = '../data/slide/'.$file;

    $arr=[
        'result'=>'success',
        'name'=>$row['name'],
        'file'=>$file
    ];


    die(json_encode($arr));
}

?>