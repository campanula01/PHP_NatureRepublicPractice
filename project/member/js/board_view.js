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
    const params = getUrlParams();
    //목록 이동
    const btn_list = document.querySelector("#btn_list");
    btn_list.addEventListener("click",()=>{
        self.location.href='./board.php?bcode='+params["bcode"]
    })

    //수정버튼
    const btn_edit = document.querySelector("#btn_edit");
    if(btn_edit){
        btn_edit.addEventListener("click",()=>{
            self.location.href ='./board_edit.php?bcode='+params['bcode']+'&idx='+params['idx']
        })
    }

    
    //삭제버튼
    const btn_delete = document.querySelector("#btn_delete");
    if(btn_delete){
        btn_delete.addEventListener("click",()=>{
            if(confirm('삭제하시겠습니까?')){

                const f = new FormData()
                f.append('idx',params['idx']);
                f.append('mode','delete');
                f.append('bcode',params['bcode'])

                const xhr = new XMLHttpRequest();
                xhr.open("POST",'pg/board_process.php',true)
                xhr.send(f)
                xhr.onload=()=>{
                    if(xhr.status==200){
                        const data = JSON.parse(xhr.responseText);
                        if(data.result=='success'){
                            alert('게시물이 삭제되었습니다.');
                            self.location.href='./board.php?bcode='+params['bcode'];
                        }
                    }else{
                        alert('통신실패')
                    }
                }
            }
        })
    }

    //댓글 등록 버튼 클릭
    const btn_comment = document.querySelector("#btn_comment");
    btn_comment.addEventListener("click",()=>{
        const comment_content = document.querySelector("#comment_content")
        if(comment_content.value==''){
            comment_content.focus();
            alert('댓글 내용을 입력바랍니다.');
            return false;
        }
        const f1 = new FormData();
        f1.append('pidx',params['idx']);
        f1.append('content',comment_content.value);
        f1.append('idx',btn_comment.dataset.commentIdx);
        if(btn_comment.dataset.commentIdx > 0){
            f1.append('mode','edit');
        }else{
            f1.append('mode','input');
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST","./pg/comment_process.php", true)
        xhr.send(f1);
        xhr.onload=()=>{
            if(xhr.status==200){
                const data = JSON.parse(xhr.responseText)
                if(data.result == 'empty pidx'){
                    alert('게시물 번호가 빠졌습니다.')
                    return false;
                }else if(data.result == 'empty content'){
                    alert('댓글 내용이 빠졌습니다.')
                    return false;
                }else if(data.result == 'success'){
                    self.location.reload();
                }
            }else if(xhr.status==404){
                alert('통신실패')
            }
        }
    })

    //댓글 삭제 버튼 클릭시
    const btn_comment_deletes = document.querySelectorAll(".btn_comment_delete");
    btn_comment_deletes.forEach((box)=>{
        box.addEventListener("click",()=>{

            if(!confirm('이 댓글을 삭제 하시겠습니까?')){
                return false;
            }
            //alert(box.dataset.commentIdx);
            const f1 = new FormData();
            f1.append('pidx',params['idx']);
            f1.append('idx', box.dataset.commentIdx);
            f1.append('mode','delete');


            const xhr = new XMLHttpRequest();
            xhr.open("POST","pg/comment_process.php",true);
            xhr.send(f1);
            xhr.onload = ()=>{
                if(xhr.status==200){
                    const data = JSON.parse(xhr.responseText);
                    if(data.result=='success'){
                        self.location.reload();
                    }
                }else if(xhr.status==404){
                    alert('통신실패')
                }
            }
        })
    })

    //댓글 수정 버튼
    const btn_comment_edits = document.querySelectorAll(".btn_comment_edit");
    btn_comment_edits.forEach((box)=>{
        box.addEventListener("click",()=>{
            const comment_content = document.querySelector("#comment_content");
            comment_content.value = box.parentNode.childNodes[1].textContent;
            comment_content.focus();

            btn_comment.dataset.commentIdx = box.dataset.commentIdx;
            btn_comment.textContent='수정'

        })
    })
})