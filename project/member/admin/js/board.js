document.addEventListener("DOMContentLoaded",()=>{
    const btn_search = document.querySelector('#btn_search');
    btn_search.addEventListener("click",()=>{
        const sf= document.querySelector("#sf")
        if(sf.value == ''){
            alert('검색어를 입력해 주세요.');
            sf.focus();
            return false
        }

        const sn = document.querySelector("#sn");

        self.location.href = './board.php?sn='+sn.value+'&sf='+sf.value;
    })

    const btn_all = document.querySelector("#btn_all");
    btn_all.addEventListener("click",()=>{
        self.location.href="./board.php";
    })


    const board_title = document.querySelector("#board_title");

    //게시판 생성 버튼 클릭
    const btn_create_modal = document.querySelector("#btn_create_modal");
    btn_create_modal.addEventListener("click",()=>{
        board_title.value='';

        const board_mode = document.querySelector("#board_mode");
        board_mode.value="input";

        document.querySelector("#modal_title").textContent="게시판 생성";
    })

    const btn_board_create = document.querySelector("#btn_board_create");

    btn_board_create.addEventListener("click",()=>{
        if(board_title.value==""){
            alert('게시판 이름을 입력해 주세요.');
            board_title.focus()
            return false
        }

        btn_board_create.disabled = true;

        const board_mode = document.querySelector("#board_mode");
        const board_idx = document.querySelector("#board_idx");


        const xhr = new XMLHttpRequest()

        const f = new FormData()
        f.append('board_title',board_title.value)
        f.append('board_type',document.querySelector("#board_type").value)
        f.append('mode',board_mode.value)
        f.append('idx',board_idx.value)

        xhr.open("POST", "./pg/board_process.php", true);
        xhr.send(f);

        xhr.onload = ()=>{
            if(xhr.status == 200){
                const data = JSON.parse(xhr.responseText);
                if(data.result == 'mode_empty'){
                    alert('mode값이 누락되었습니다.');
                    btn_board_create.disabled = false;

                    return false;
                }else if(data.result=='title_empty'){
                    alert('게시판 명이 누락되었습니다.');
                    btn_board_create.disabled = false;
                    board_title.focus();
                    return false;
                    
                }else if(data.result=='btype_empty'){
                    alert('게시판 타입이 누락되었습니다.');
                    btn_board_create.disabled = false;
                    return false;
                }else if(data.result == 'success'){
                    alert('게시판이 생성되었습니다.');
                    self.location.reload();
                }else if(data.result == 'edit_success'){
                    alert('게시판이 수정되었습니다.');
                    self.location.reload();
                }
            }else{
                    btn_board_create.disabled = false;
                    alert('통신 실패'+xhr.status);
            }
        }
    })

    //수정버튼 클릭
     const btn_mem_edit = document.querySelectorAll(".btn_mem_edit");
     btn_mem_edit.forEach((box)=>{
         box.addEventListener("click",()=>{
            document.querySelector("#modal_title").textContent="게시판 수정";

            const board_mode = document.querySelector("#board_mode");
            const board_idx = document.querySelector("#board_idx");

            board_mode.value="edit";

            const idx = box.dataset.idx;

            board_idx.value = idx;

            const f = new FormData();
            f.append("idx",idx);
            f.append("mode","getInfo");

            const xhr = new XMLHttpRequest()
            xhr.open("POST","./pg/board_process.php",true);
            xhr.send(f);

            xhr.onload=()=>{
                if(xhr.status==200){
                    const data = JSON.parse(xhr.responseText);
                    if(data.result == 'empty_idx'){
                        alert('idx값이 누락되었습니다.');
                        return false;
                    }else if(data.result=='success'){
                        document.querySelector("#board_title").value=data.list.name
                        document.querySelector("#board_type").value = data.list.btype;
                    }

                }else{
                    alert('통신 실패'+xhr.status);
                }
            }
    
         })
     })


  

    //삭제 버튼 클릭
    const btn_mem_delete = document.querySelectorAll(".btn_mem_delete");
    btn_mem_delete.forEach((box)=>{
        box.addEventListener("click",()=>{

            if(!confirm('게시판을 삭제하시겠습니까?')){
                return false;
            }
            const idx = box.dataset.idx;

            const f = new FormData();
            f.append("idx",idx);
            f.append("mode","delete");

            const xhr = new XMLHttpRequest();
            xhr.open("POST","./pg/board_process.php",true);
            xhr.send(f);

            xhr.onload=()=>{
                if(xhr.status==200){
                    const data =JSON.parse(xhr.responseText);
                    if(data.result == 'success'){
                        self.location.reload();
                    }
                }else{
                    alert('통신에 실패했습니다.');
                }
            }
        })
    })
})