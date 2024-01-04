document.addEventListener("DOMContentLoaded",()=>{
    const btn_popup_create = document.querySelector("#btn_slide_create");
    btn_popup_create.addEventListener("click",()=>{
        const slide_title = document.querySelector("#slide_title");
        if(slide_title.value==''){
            alert('슬라이드 이름을 입력해 주시기 바랍니다.');
            popup_title.focus();
            return false;
        }


        if(document.querySelector("#slide_mode").value !='edit'){
            const file = document.querySelector("#file");
                if(file.value==''){
                    alert('이미지 파일을 첨부해 주시기 바랍니다.');
                    file.focus();
                    return false;
                }
        }
        

        const mode =(document.querySelector('#slide_mode').value !='')?document.querySelector("#slide_mode").value :'input'
        const idx = document.querySelector("#slide_idx");

        const f1 =new FormData();
        
        f1.append('name',slide_title.value);
        f1.append('file',file.files[0]);
        f1.append('mode',mode);
        f1.append('idx',idx.value);



        const xhr =new XMLHttpRequest();
        xhr.open("POST",'./pg/slide_process.php',true);
        xhr.send(f1);

        xhr.onload = ()=>{
            if(xhr.status==200){
                const data = JSON.parse(xhr.responseText);
                if(data.result=='success'){
                    alert('등록을 성공했습니다.');
                    self.location.reload();
                }else if(data.result=='empty_name'){
                    alert('팝업이름이 비었습니다.');
                    return false;
                }
            }else if(xhr.status==404){
                alert('통신실패')
            }
        }
    })


    //팝업보기 버튼 클릭
    const btn_slide_views = document.querySelectorAll(".btn_slide_view")
    btn_slide_views.forEach((box)=>{
        box.addEventListener("click",()=>{
            getInfo(box.dataset.idx, "view");
        })
    })

    //팝업 수정버튼
    const btn_slide_edits = document.querySelectorAll(".btn_slide_edit");
    btn_slide_edits.forEach((box)=>{
        box.addEventListener("click",()=>{
            getInfo(box.dataset.idx, "edit");

            //모달 윈도
            document.querySelector("#modal_title").textContent = '팝업 수정';
            document.querySelector("#slide_mode").value = 'edit';
            document.querySelector("#slide_idx").value = box.dataset.idx;
        })
    })

    //팝업 삭제 버튼
    const btn_slide_deletes = document.querySelectorAll(".btn_slide_delete")
    btn_slide_deletes.forEach((box)=>{
        box.addEventListener("click",()=>{

            if(!confirm('이 슬라이드 삭제하시겠습니까?')){
                return false;
            }
            const f1 = new FormData();
            f1.append('idx',box.dataset.idx);
            f1.append('mode','delete');

            const xhr = new XMLHttpRequest();
            xhr.open("POST","pg/slide_process.php",true);
            xhr.send(f1);
            xhr.onload = ()=>{
                if(xhr.status==200){
                    self.location.reload();
                }else if(xhr.status==404){
                    alert('통신 실패')
                }
            }

        })
    })


})

//공통 사용
function getInfo(idx, mode){
    const f1 = new FormData();
    f1.append("idx",idx);
    f1.append("mode","getInfo");

    const xhr = new XMLHttpRequest();
    xhr.open("POST","pg/slide_process.php", true);
    xhr.send(f1);
    xhr.onload = ()=>{
        if(xhr.status==200){
            const data = JSON.parse(xhr.responseText);

            if(mode=='view'){
                const pop1 = document.querySelector("#pop1");
                pop1.style.display = 'block';
                pop1.style.left = data.pop_x+'px';
                pop1.style.top = data.pop_y+'px';
                document.querySelector("#pop1 img").src =data.file;
    
                const cookie_term = document.querySelector("#cookie_term");
                if(data.cookie=='day'){
                    cookie_term.textContent='하루 동안 이 창을 다시 열지 않음';
                }else if(data.cookie=='week'){
                    cookie_term.textContent='일주일 동안 이 창을 다시 열지 않음';
                }else if(data.cookie=='month'){
                    cookie_term.textContent='한달 동안 이 창을 다시 열지 않음';
                }
            }else if(mode=='edit'){
                document.querySelector("#slide_title").value = data.name;
            }
        }else if(xhr.status==404){
            alert('통신실패')
        }
            
    }
}