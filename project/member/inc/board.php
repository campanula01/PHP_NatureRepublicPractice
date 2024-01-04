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

    //글 수정
    public function edit($arr){
        $sql = "UPDATE board SET subject=:subject, content=:content WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [':subject'=> $arr['subject'], ':content'=>$arr['content'], ':idx'=>$arr['idx']];
        $stmt->execute($params);
    }

    //글 목록
    public function list($bcode,$page, $limit, $paramArr){
        $start = ($page-1)*$limit;

        $where = " WHERE bcode=:bcode ";
        $params=[':bcode'=>$bcode];
        if(isset($paramArr['sn']) && $paramArr['sn'] != '' && isset($paramArr['sf']) && $paramArr['sf'] !=''){
            switch($paramArr['sn']){
                case 1: $where .= "AND (subject LIKE CONCAT('%', :sf, '%') or (content like concat('%',:sf2,'%'))) ";
                        $params=[':bcode'=>$bcode,':sf'=>$paramArr['sf'], ':sf2'=>$paramArr['sf'] ];
                         break; //제목, 내용
                case 2: $where .= "AND (subject LIKE CONCAT('%', :sf, '%')) ";
                $params=[':bcode'=>$bcode,':sf'=>$paramArr['sf'] ];
                 break;//제목
                 
                case 3: $where .= "AND (content like concat('%',:sf2,'%')) ";
                $params=[':bcode'=>$bcode,':sf2'=>$paramArr['sf']];
                break;//내용

                case 4: $where .= "AND (name=:sf) ";
                $params=[':bcode'=>$bcode,':sf'=>$paramArr['sf']];
                break;//내용
            }
        }

        $sql = "SELECT idx, id, subject, name,hit,comment_cnt, Date_Format(create_at,'%Y-%m-%d %H:%i') as create_at , files
        FROM board ".$where."
        order by idx desc LIMIT ".$start.",".$limit;

        $stmt = $this->conn->prepare($sql);


        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute($params);

        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $stmt->fetchAll();
    }

    //전체 글 수 구하기
    public function total($bcode,$paramArr){
        $where = " WHERE bcode=:bcode ";
        $params=[':bcode'=>$bcode];
        if(isset($paramArr['sn']) && $paramArr['sn'] != '' && isset($paramArr['sf']) && $paramArr['sf'] !=''){
            switch($paramArr['sn']){
                case 1: $where .= "AND (subject LIKE CONCAT('%', :sf, '%') or (content like concat('%',:sf2,'%'))) ";
                        $params=[':bcode'=>$bcode,':sf'=>$paramArr['sf'], ':sf2'=>$paramArr['sf'] ];
                         break; //제목, 내용
                case 2: $where .= "AND (subject LIKE CONCAT('%', :sf, '%')) ";
                $params=[':bcode'=>$bcode,':sf'=>$paramArr['sf'] ];
                 break;//제목
                 
                case 3: $where .= "AND (content like concat('%',:sf2,'%')) ";
                $params=[':bcode'=>$bcode,':sf2'=>$paramArr['sf']];
                break;//내용

                case 4: $where .= "AND (name=:sf) ";
                $params=[':bcode'=>$bcode,':sf'=>$paramArr['sf']];
                break;//내용
            }
        }

        $sql = "SELECT COUNT(*) as cnt 
        FROM board ".$where;

        $stmt = $this->conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);  //필드명으로 된 형태만 나옴.
        $stmt->execute($params);

        $row = $stmt->fetch();
        //fetch는 하나만 가져오고 fetchAll은 여러개를 가져옴.
        return $row['cnt'];
    }

    //글보기
    public function view($idx){
        $sql = "SELECT * FROM board WHERE idx=:idx";
        $stmt =$this->conn->prepare($sql);
        $params = [":idx"=>$idx];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    //글조회수 +1
    public function hitInc($idx){
        $sql = "UPDATE board SET hit=hit+1 WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params=[":idx"=>$idx];
        $stmt->execute($params);
    }

    //첨부파일 구하기
    public function getAttachFile($idx, $th){
        $sql = "SELECT files FROM board WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [":idx"=>$idx];
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row= $stmt->fetch();

        $filelist = explode('?',$row['files']);

        return $filelist[$th].'|'.count($filelist); //첨부파일의 갯수까지
    }

    //다운로드 횟수 구하기
    public function getDownhit($idx){
        $sql = "SELECT downhit FROM board WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [":idx"=>$idx];
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row= $stmt->fetch();

        return $row['downhit'];

    }

    //다운로드 수 업데이트
    public function increaseDownhit($idx,$downhit){
        $sql = "UPDATE board SET downhit =:downhit WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [ ":downhit"=>$downhit, ":idx"=>$idx,];
        $stmt->execute($params);

    }

    //파일 목록 업데이트
    public function updateFileList($idx, $files, $downs){
        $sql = "UPDATE board SET files=:files, downhit = :downhit WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [":idx"=>$idx,":files"=>$files, ":downhit"=>$downs];
        $stmt->execute($params);
    }

    //파일 첨부
    public function file_attach($files, $file_cnt){
        if(sizeof($files['name'])>$file_cnt){
            $arr = ["result"=>"file_upload_count_exceed"];
            die(json_encode($arr));
        }

        $tmp_arr =[];

        foreach($files['name'] AS $key =>$val){
            $files['name'][$key];

            $full_str ='';
            $tmparr = explode('.', $files['name'][$key]);
            $ext = end($tmparr);
            //exe파일 제어
            $not_allowed_file_ext =['exe','xls'];

            if(in_array($ext, $not_allowed_file_ext)){
                $arr = ['result'=>'not_allowed_file'];
                die(json_encode($arr));
            }
            $flag = rand(1000,9999);
            $filename='a'.date('YmdHis').$flag.'.'.$ext;
            $file_ori =$files['name'][$key];

            //copy() move_uploaded_file()
            copy($files['tmp_name'][$key], BOARD_DIR.'/'.$filename);

            //파일명으로 할 수 없는 걸고 |
            $full_str = $filename.'|'.$file_ori;
            $tmp_arr[] = $full_str;

        }
        return implode('?',$tmp_arr);
        
    }

    public function extract_image($content){
            //이미지 변환하여 저장
    preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",$content,$matches);

    $img_array=[];
    foreach($matches[1] AS $key =>$row){
        $img_array[]=$row;

        }   //이미 업로드된 이미지는 처리하지 않는다.
        return $img_array;
    }

    public function delete($idx){
        $sql = "DELETE FROM board WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [":idx"=>$idx];
        $stmt->execute($params);
    }

}