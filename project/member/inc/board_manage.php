<?php

//게시판 관리 클래스
class BoardManage{
     //db연결 객제를 가져와야함. 멤버변수
     private $conn;

     //생성자
     public function __construct($db)
     {
         $this->conn =$db;
     }

     
    //게시판관리 목록
    public function list($page, $limit, $paramArr){
        $start = ($page-1)*$limit;

        $where = "";
        if($paramArr['sn'] != '' && $paramArr['sf'] !=''){
            switch($paramArr['sn']){
                case 1: $sn_str = 'name'; break;
                case 2: $sn_str = 'btype'; break;
                case 3: $sn_str = 'cnt'; break;
            }
            $where = "WHERE ".$sn_str."=:sf ";
        }

        $sql = "SELECT idx, name, bcode, btype, cnt, Date_Format(create_at,'%Y-%m-%d %H:%i') as create_at 
        FROM board_manage ".$where."
        order by idx desc LIMIT ".$start.",".$limit;


        $stmt = $this->conn->prepare($sql);

        if($where !=''){
            $stmt->bindParam(':sf', $paramArr['sf']);
        }

        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute();

        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $stmt->fetchAll();
    }


    public function list1(){  

        $sql = "SELECT idx, name, bcode, btype, cnt, Date_Format(create_at,'%Y-%m-%d %H:%i') as create_at 
        FROM board_manage";

        $stmt = $this->conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute();

        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $stmt->fetchAll();
    }


    public function total($paramArr){
        $where = "";
        if($paramArr['sn'] != '' && $paramArr['sf'] !=''){
            switch($paramArr['sn']){
                case 1: $sn_str = 'name'; break;
                case 2: $sn_str = 'btype'; break;
                case 3: $sn_str = 'cnt'; break;
            }
            $where = "WHERE ".$sn_str."=:sf ";
        }

        $sql = "SELECT COUNT(*) cnt FROM board_manage ".$where;
        $stmt = $this->conn->prepare($sql);

        
        if($where !=''){
            $stmt->bindParam(':sf', $paramArr['sf']);
        }

        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute();

        $row = $stmt->fetch();
        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $row['cnt'];
    }


    //게시판 생성
    public function create($arr){
        $sql = "insert into board_manage(name, bcode, btype, create_at) values
        (:name, :bcode, :btype, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $arr['name']);
        $stmt->bindParam(':bcode', $arr['bcode']);
        $stmt->bindParam(':btype', $arr['btype']);
        $stmt->execute();

    }

        //게시판 수정
        public function update($arr){
            $sql = "UPDATE board_manage SET name=:name, btype=:btype WHERE idx=:idx";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $arr['name']);
            $stmt->bindParam(':idx', $arr['idx']);
            $stmt->bindParam(':btype', $arr['btype']);
            $stmt->execute();
    
        }

    //게시판 idx로 정보 가져오기
    public function getBcode($idx){
        $sql = "SELECT bcode FROM board_manage WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idx',$idx);
        $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);  //필드명으로 된 형태만 나옴.
        $stmt->execute();
        return $row = $stmt->fetch();   //$row['bcode']
    }


    //게시판 삭제 
    public function delete($idx){
        //bcode
        $bcode = $this->getBcode($idx);

        $sql = "DELETE FROM board_manage WHERE idx =:idx";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idx',$idx);
        $stmt->execute();

        $sql = "DELETE FROM board WHERE bcode =:bcode";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':bcode',$bcode);
        $stmt->execute();
    }

    //게시판 코드 생성
    public function bcode_create(){
        $letter = range('a','z');
        $bcode = '';
        for($i = 0; $i<6;$i++){
            $r = rand(0,25);
            $bcode .=$letter[$r];
        }

        return $bcode;
    }

    //게시판 정보 불러오기 
    public function getInfo($idx){
        $sql = "SELECT * FROM board_manage WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":idx",$idx);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        return $stmt->fetch();
    }


    //게시판 코드로 게시판명 가져오기
    public function getBoardName($bcode){
        $sql= "SELECT name FROM board_manage WHERE bcode=:bcode";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":bcode",$bcode);
        $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);  //필드명으로 된 형태만 나옴.
        $stmt->execute();
        return $row = $stmt->fetch();   //$row['bcode']
    }


}