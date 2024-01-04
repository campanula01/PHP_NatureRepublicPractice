function getUrlParams(){
    const params = {};

    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(str, key, value){
            params[key] = value;
        }
    );

    return params;
}

function getExtensionOfFilename(filename){
    const filelen = filename.length; //문자열의 길이
    const lastdot = filename.lastIndexOf('.');
    return filename.substring(lastdot+1,filelen).toLowerCase();

}

function updateLiElement(data){
    const el = document.getElementById('id_filelist');
    el.innerHTML="";
    for(i=0;i<data.file_list.length;i++){
        const li = document.createElement("li")     //<li></li>
        const span = document.createElement("span");    //<SPAN></span>
        span.innerHTML = data.file_list[i]; //<span>8888.png<span>
        li.appendChild(span) //<li><span>8888.png</span></li>

        const button = document.createElement("button");
        button.innerHTML ='삭제'    //<button>삭제</button>
        button.classList.add('btn', 'btn-sm', 'btn-danger', 'mb-2', 'btn_file_del', 'py-0', 'mx-2')  //btn btn-sm btn-danger mb-2 btn_file_del py-0
        button.setAttribute('data-th',i);

        li.appendChild(button);
        el.appendChild(li);

        
    }
    const btn_file_dels =document.querySelectorAll(".btn_file_del")
        btn_file_dels.forEach((box)=>{
            box.addEventListener("click",()=>{
                fileDeleteFunction(box.dataset.th)
            })
        })

        if(btn_file_dels.length > 3){
            document.getElementById('div_attach').style.display='none';
        }else {
            document.getElementById('div_attach').style.display='block';
        }
}

function fileDeleteFunction(th){
    if(!confirm('해당 첨부파일을 삭제하시겠습니까?')){
        return false;
    }
    const params = getUrlParams();

    const f = new FormData();

    f.append("th",th); //게시물 제목
    f.append("bcode",params['bcode']);//bcode
    f.append("idx",params['idx']);  //게시물 번호
    f.append("mode","each_file_del");   // 개별 파일 삭제

    const xhr = new XMLHttpRequest();
    xhr.open("POST","pg/board_process.php",true);
    xhr.send(f);

    xhr.onload=()=>{
        if(xhr.status==200){
            const data = JSON.parse(xhr.responseText);
            if(data.result=='empty_idx'){
                alert("게시물 번호가 빠졌습니다.")
                return false;
            }                
            if(data.result=='empty_th'){
                alert("몇번째 첨부파일인지 알 수가 없습니다.")
                return false;
            }      
            else if(data.result=='success'){
                //self.location.reload();
                updateLiElement(data);
            }             
        }else if(xhr.status==404){
            alert("파일이 존재하지 않습니다.")
        }
    }

}


document.addEventListener("DOMContentLoaded",()=>{
    
    const params = getUrlParams()

    const btn_file_dels = document.querySelectorAll(".btn_file_del");
    btn_file_dels.forEach((box)=>{
        box.addEventListener("click",()=>{
            fileDeleteFunction(box.dataset.th)


        })
    })

    const id_attach = document.querySelector("#id_attach")
    if(id_attach){
        id_attach.addEventListener("change",()=>{
            const f = new FormData();
            f.append("bcode",params['bcode']);//bcode
            f.append("mode","file_attach");   //모드 파일만 첨부
            f.append("idx",params['idx']); //게시물 번호
            //console.log(id_attach.files)

            if(id_attach.files[0].size > 40 *1024*1024){
                alert('파일 용량이 40메가보다 큰 파일이 첨부되었습니다. 확인 바랍니다.')
                id_attach.value=''
                return false
            }

            ext = getExtensionOfFilename(id_attach.files[0].name);
            if(ext=='exe'||ext=='xls'||ext=='php'||ext=='js'){
                alert('첨부할 수 없는 포맷의 파일이 첨부되었습니다.(exe,xls,php,js,...)');
                id_attach.value='';
                return false;
            }
            f.append("files[]",id_attach.files[0]);   // 파일


            const xhr = new XMLHttpRequest();
            xhr.open("POST", "./pg/board_process.php",true)
            xhr.send(f)

            xhr.onload=()=>{
                if(xhr.status==200){
                    const data = JSON.parse(xhr.responseText);
                    if(data.result=='success'){
                        //self.location.reload();
                        updateLiElement(data);
                        document.getElementById('id_attach').value = '';
                    }else if(data.result=='empty_files'){
                        alert('파일이 첨부되지 않았습니다.');
                    }
                }else if(xhr.status==404){
                    alert('통신실패')
                }
            }

        })

    }

    const btn_board_list = document.querySelector("#btn_board_list");
    btn_board_list.addEventListener("click",()=>{
        self.location.href='./board.php?bcode='+params['bcode'];
    })

    //수정 확인 버튼
    const btn_edit_submit = document.querySelector("#btn_edit_submit");
    btn_edit_submit.addEventListener("click",()=>{
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


        const params =getUrlParams();

        const f = new FormData();
        f.append("subject",id_subject.value); //게시물 제목
        f.append("content",markupStr);  //게시물 내용
        f.append("bcode",params['bcode']);//bcode
        f.append("idx",params['idx']);
        f.append("mode","edit");   //board_process.php를 쪼개서 쓸거기 때문에 구분할 것이 필요.
 
        const xhr = new XMLHttpRequest()
        xhr.open("POST","./pg/board_process.php",true)
        xhr.send(f);

        xhr.onload=()=>{
            if(xhr.status==200){
                const data = JSON.parse(xhr.responseText);
                if(data.result=='success'){
                    alert('글 수정이 성공했습니다.');
                    self.location.href='./board.php?bcode='+params['bcode'];
                }else if(data.result=='permission_denied'){
                    alert('수정권한이 없는 게시물입니다.');
                    self.location.href='./board.php?bcode='+params['bcode'];
                }
            }else{
                alert('통신실패')
            }
        }
    })
})