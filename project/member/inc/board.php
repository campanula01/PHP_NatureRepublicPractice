<?php

//게시판 클래스
class Board{
    //db연결 객제를 가져와야함. 멤버변수
    private $conn;

    //생성자
    public function __construct($db)
    {
        $this->conn =$db;
    }

    //글등록
    public function input($arr){
        $sql = "INSERT INTO board(bcode, id, name, subject, content,files, ip, create_at) VALUES (
            :bcode, :id, :name, :subject,:content,:files, :ip, NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":bcode",$arr['bcode']);
            $stmt->bindValue(":id",$arr['id']);
            $stmt->bindValue(":name",$arr['name']);
            $stmt->bindValue(":subject",$arr['subject']);
            $stmt->bindValue(":content",$arr['content']);
            $stmt->bindValue(":files",$arr['files']);
            $stmt->bindValue(":ip",$arr['ip']);
            $stmt->execute();
    }
}