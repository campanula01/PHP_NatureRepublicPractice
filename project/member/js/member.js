document.addEventListener("DOMContentLoaded",()=>{
    //querySelector 하나만 선택
    const btn_member = document.querySelector("#btn_member")

    btn_member.addEventListener("click",()=>{
        const chk_member1 = document.querySelector("#chk_member1")
        if(chk_member1.checked !==true){
            alert('회원 약관에 동의해 주세요.')
            return false
        }

        const chk_member2 = document.querySelector("#chk_member2")

        if(chk_member2.checked !== true){
            alert('개인 정보 취급방침에 동의해 주세요.')
            return false
        }

        //반드시 회원약관을 눌러야만 이동이 되게
        const f = document.stipulation_form
        f.chk.value=1
        f.submit()
        

    })


})