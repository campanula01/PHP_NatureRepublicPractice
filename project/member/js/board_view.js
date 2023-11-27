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
                self.location.href ='./board_edit.php?bcode='+params['bcode']+'&idx='+params['idx']
            }
        })
    }
})