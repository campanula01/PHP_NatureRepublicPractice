function getUrlParams(){
    const params = {};

    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(str, key, value){
            params[key] = value;
        }
    );

    return params;
}

document.addEventListener("DOMContentLoaded",()=>{

    //게시판 목록으로 이동하기
    const btn_board_list = document.querySelector("#btn_board_list");
    btn_board_list.addEventListener("click",()=>{
        const params =getUrlParams();

        self.location.href='./board.php?bcode='+params['bcode'];
    })

    //게시물 작성 후 확인버튼 클릭
    const btn_write_submit = document.querySelector("#btn_write_submit");
    btn_write_submit.addEventListener("click",()=>{
        const id_subject = document.querySelector("#id_subject");

        if(id_subject.value==''){
            alert('게시물의 제목을 입력해 주세요');
            id_subject.focus();
            return false;
        }

        const markupStr = $('#summernote').summernote('code');
        if(markupStr =='<p><br></p>'){
            alert('내용을 입력해주세요.');
            return false;
        }

        //파일 첨부
        const id_attach = document.querySelector("#id_attach")
        //const file = id_attach.files[0]
        if(id_attach.files.length > 4){
            alert('최대 첨부 가능 파일의 개수는 4개입니다.');
            id_attach.value='';
            return false;
        }

        const params =getUrlParams();

        const f = new FormData();
        f.append("subject",id_subject.value); //게시물 제목
        f.append("content",markupStr);  //게시물 내용
        f.append("bcode",params['bcode']);//bcode
        f.append("mode","input");   //board_process.php를 쪼개서 쓸거기 때문에 구분할 것이 필요.
        //f.append("files",file); //파일첨부

        for(const file of id_attach.files){
            f.append("files[]",file);   // 파일
        }
        const xhr = new XMLHttpRequest()
        xhr.open("POST","./pg/board_process.php",true)
        xhr.send(f);

        xhr.onload=()=>{
            if(xhr.status==200){
                const data = JSON.parse(xhr.responseText);
                if(data.result=='success'){
                    alert('글 등록이 성공했습니다.');
                    self.location.href='./board.php?bcode='+params['bcode'];
                }else if(data.result=='file_upload_count_exceed'){
                    alert('파일 업로드 개수를 초과했습니다.');
                    id_attach.value='';
                    return false;
                }else if(data.result=='post_size_exceed'){
                    alert('첨부파일의 용량이 큽니다.');
                    id_attach.value=''
                    return false;
                }
            }else{
                alert('통신실패')
            }
        }


    })


    const id_attach = document.querySelector("#id_attach");
    id_attach.addEventListener("change",()=>{
        if(id_attach.files.length >4 ){
            alert('파일은 4개까지만 첨부가 가능합니다.');
            id_attach.value=''
            return false;
        }
    })
})