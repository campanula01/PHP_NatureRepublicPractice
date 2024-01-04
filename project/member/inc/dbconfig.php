<?php
$servername='localhost';    //웹서버와 db서버가 같은곳에 있음
$dbuser = 'root';
$dbpassword='';
$dbname='nature';

try{
//db인스턴스 생성
$db = new PDO("mysql:host={$servername};dbname={$dbname}",$dbuser,$dbpassword);

//Prepared Statement를 지원하지 않는 경우 데이터베이스의 기능을 사용하도록 해줌.
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true); //쿼리 버퍼링을 활성화
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);    //PDO객체가 에러를 처리하는 방식 설정


}catch(PDOException $e){
    //꼭 해줘야 오류가 어디서 일어났는지 알 수 있다.
    echo $e->getMessage();
}

define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT'].'/project/member');
define('ADMIN_DIR', DOCUMENT_ROOT.'/admin');
define('DATA_DIR', DOCUMENT_ROOT.'/data');
define('PROFILE_DIR', DATA_DIR.'/profile');
define('BOARD_DIR',DATA_DIR.'/board');  //파일이 저장될 절대 경로
define('BOARD_WEB_DIR','data/board');  //웹에서 사용하는  경로
define('POPUP_DIR',DATA_DIR.'/popup');  //파일이 저장될 절대 경로
define('POPUP_WEB_DIR','data/popup');  //웹에서 사용하는  경로
define('SLIDE_DIR',DATA_DIR.'/slide');  //파일이 저장될 절대 경로
define('SLIDE_WEB_DIR','data/slide');  //웹에서 사용하는  경로
