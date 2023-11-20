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