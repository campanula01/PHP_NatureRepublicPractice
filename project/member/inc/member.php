<?php
//Member Class file

class Member{
    //db연결 객제를 가져와야함. 멤버변수
    private $conn;

    //생성자
    public function __construct($db)
    {
        $this->conn =$db;
    }

    //아이디 중복체크용 멤버 함수
    public function id_exists($id){
        $sql = "select * from member where id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(':id', $id);
        $stmt->execute();

        //true면 이미 존재
        return $stmt->rowCount() ? true:false;
    }


    //이메일 형식 체크
    public function email_format_check($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
        //이메일 중복체크용 멤버 함수
    public function email_exists($email){
        $sql = "select * from member where email=:email";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(':email', $email);
        $stmt->execute();

        //true면 이미 존재
        return $stmt->rowCount() ? true:false;
    }

    //회원 정보 입력
    public function input($marr){

        //단방향 암호화
        $new_hash_password = password_hash($marr['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO member(id, name, password, email, zipcode, addr1, addr2,photo, create_at, ip) VALUES
        (:id, :name, :password, :email, :zipcode, :addr1, :addr2,:photo, NOW() ,:ip)";

        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(':email', $marr['email']);
        $stmt -> bindParam(':id', $marr['id']);
        $stmt -> bindParam(':name', $marr['name']);
        $stmt -> bindParam(':zipcode', $marr['zipcode']);
        $stmt -> bindParam(':password', $new_hash_password);
        $stmt -> bindParam(':addr1', $marr['addr1']);
        $stmt -> bindParam(':addr2', $marr['addr2']);
        $stmt -> bindParam(':photo', $marr['photo']);
        $stmt -> bindParam(':ip', $_SERVER['REMOTE_ADDR']);
        

        $stmt->execute();

    }

    //로그인
    public function login($id, $pw){

        //password_verify($password, $new_password);

        $sql = "SELECT password FROM member WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        if($stmt->rowCount()){
            $row = $stmt->fetch();

            if(password_verify($pw, $row['password'])){
                $sql = "UPDATE member set login_dt=NOW() where id=:id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id',$id);
                $stmt->execute();
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

        return $stmt->rowCount() ? true:false;

    }

    //로그아웃
    public function logout(){
        session_start();
        session_destroy();

        die('<script>self.location.href="../main.php"</script>');
    }

    //회원 정보 가져오기
    public function getInfo($id){
        $sql = "SELECT * from member where id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute();
        return $stmt->fetch();
    }

    public function edit($marr){
        $sql ="UPDATE member SET name=:name, email=:email, zipcode=:zipcode, addr1=:addr1, addr2=:addr2, photo=:photo";

        $params=[
            ':name'=>$marr['name'],
            ':email'=>$marr['email'],
            ':zipcode'=>$marr['zipcode'],
            ':addr1'=>$marr['addr1'],
            ':addr2'=>$marr['addr2'],
            ':photo'=>$marr['photo'],
            ':id'=>$marr['id']
        ];

        if($marr['password']!=''){
            //단방향 암호화
            $new_hash_password = password_hash($marr['password'], PASSWORD_DEFAULT);

            $params[':password'] = $new_hash_password;

            $sql .=",password=:password";
        }

        $sql .=" WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        //프로필 이미지를 업로드 했다면

    }








    //회원관리
    public function list($page, $limit){
        $start = ($page-1)*$limit;
        $sql = "SELECT idx, id, name, email, Date_Format(create_at,'%Y-%m-%d %H:%i') as create_at 
        FROM member 
        order by idx desc LIMIT ".$start.",".$limit;

        $stmt = $this->conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute();

        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $stmt->fetchAll();
    }

    public function total(){
        $sql = "SELECT COUNT(*) cnt FROM member";
        $stmt = $this->conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute();

        $row = $stmt->fetch();
        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $row['cnt'];
    }

}