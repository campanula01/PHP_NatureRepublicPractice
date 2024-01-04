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



    //글쓰기 버튼
    const btn_write = document.querySelector("#btn_write");

    btn_write.addEventListener("click",()=>{
        self.location.href='./board_write.php?bcode='+params['bcode'];
    })

    //검색버튼
    const btn_search = document.querySelector("#btn_search");
    btn_search.addEventListener("click",()=>{
        const sf = document.querySelector("#sf");
        const sn = document.querySelector("#sn");
        if(sf.value==''){
            alert('검색어를 입력바랍니다.');
            sf.focus();
            return false;
        }

        self.location.href = './board.php?bcode='+params['bcode']+'&sn='+sn.value+'&sf='+sf.value;


    })

    //전체 목록버튼
    const btn_all = document.querySelector("#btn_all");
    btn_all.addEventListener("click",()=>{
        self.location.href='./board.php?bcode='+params['bcode'];
    })

    //글보기
    const trs = document.querySelectorAll(".tr");
    trs.forEach((box)=>{
        box.addEventListener("click",()=>{
            const idx = box.dataset.idx;
            console.log(idx);
            self.location.href='./board_view.php?bcode='+params["bcode"]+'&idx='+ idx
        })
    })


})