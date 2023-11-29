<?php

//게시판 클래스
class Popup{
    //db연결 객제를 가져와야함. 멤버변수
    private $conn;

    //생성자
    public function __construct($db)
    {
        $this->conn =$db;
    }

    //입력
    public function input($arr){
        $sql = "INSERT INTO popups(name, sdate, edate, pop_x, pop_y, file, link, cookie, `use`, create_at)
         VALUES(:name, :sdate, :edate, :pop_x, :pop_y, :file, :link, :cookie, :use, NOW())";
         $stmt = $this->conn->prepare($sql);
         $params =[':name'=>$arr['name'], ':sdate'=>$arr['sdate'],':edate'=>$arr['edate'],':pop_x'=>$arr['pop_x'],':pop_y'=>$arr['pop_y']
         ,':file'=>$arr['file'],':link'=>$arr['link'],':cookie'=>$arr['cookie'],':use'=>$arr['use']];

         $stmt->execute($params);
    }

    //수정
    public function edit($arr){
        $sql = "UPDATE popups SET name=:name, sdate=:sdate, edate=:edate, pop_x=:pop_x, pop_y=:pop_y, link=:link, cookie=:cookie,
        `use`=:use";
        $params = [':name'=>$arr['name'], ':sdate'=>$arr['sdate'], ':edate'=>$arr['edate'],
         ':link'=>$arr['link'], ':pop_x'=>$arr['pop_x'], ':pop_y'=>$arr['pop_y'], ':cookie'=>$arr['cookie'], ':use'=>$arr['use']];

         if($arr['file'] !=''){
            $sql .=", file=:file";
            $params[':file'] = $arr['file'];
         }

         $sql .=" WHERE idx=:idx";
         $params[':idx']=$arr['idx'];

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params); 
    }

    public function delete($idx){
        $sql ="DELETE FROM popups WHERE idx=:idx";
        $stmt = $this->conn->prepare($sql);
        $params = [':idx'=>$idx];
        $stmt->execute($params); 
    }

    //가져오기
    public function get_info($idx){
        try {
            $sql = "SELECT * FROM popups WHERE idx=:idx";
            $stmt = $this->conn->prepare($sql);
            $params = [':idx'=>$idx];
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Handle the exception, log or return an error response
            // For example:
            return ['error' => $e->getMessage()];
        }
    }

    //목록
    public function list(){
        $sql = "SELECT * FROM popups";
        $stmt = $this->conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    //파일삭제 
    public function file_unlink($file){
        if(file_exists(POPUP_DIR.'/'.$file)){
            unlink(POPUP_DIR.'/'.$file);
        }
    }

    //파일 첨부
    public function file_attach($file){


        $tmp_arr =[];

            $full_str ='';
            $tmparr = explode('.', $file['name']);
            $ext = end($tmparr);
            //exe파일 제어
            $allowed_file_ext =['png','jpg','jpeg','gif'];

            if(!in_array($ext, $allowed_file_ext)){
                $arr = ['result'=>'not_allowed_file'];
                die(json_encode($arr));
            }
            $flag = rand(1000,9999);
            $filename='p'.date('YmdHis').$flag.'.'.$ext;
            $file_ori =$file['name'];

            //copy() move_uploaded_file()
            copy($file['tmp_name'], POPUP_DIR.'/'.$filename);

            //파일명으로 할 수 없는 걸고 |
            return $filename.'|'.$file_ori;

    }
    
}

?>