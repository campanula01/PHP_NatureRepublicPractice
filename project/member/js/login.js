document.addEventListener("DOMContentLoaded",()=>{
    const btn_login = document.querySelector("#btn_login");
    btn_login.addEventListener("click",()=>{
        const f_id = document.querySelector("#f_id");
        if(f_id.value==''){
            alert("아이디를 입력해주세요.");
            f_id.focus();
            return false;
        }

        const f_pw = document.querySelector("#f_pw");
        if(f_pw.value==''){
            alert("비밀번호를 입력해주세요.");
            f_pw.focus();
            return false;
        }

        //Ajax
        const xhr = new XMLHttpRequest();
        xhr.open("POST","./pg/login_process.php","true");

        const f1 = new FormData();
        f1.append("id", f_id.value);
        f1.append("pw", f_pw.value);

        xhr.send(f1);

        xhr.onload = ()=>{
            if(xhr.status==200){

                //alert(xhr.responseText);
                const data = JSON.parse(xhr.responseText);
                if(data.result=='login_fail'){
                    alert('등록되지 않은 아이디이거나 아이디 또는 비밀번호가 일치하지 않습니다.');
                    f_id.value='';
                    f_pw.value='';
                    f_id.focus();
                    return false;

                }else if(data.result =='login_success'){
                    self.location.href='./main.php' //로그인이 성공했을 때 가는 페이지
                }

            }else{
                alert("통신에 실패했습니다. 다음에 다시 시도하여 주시길 바랍니다.");
            }
        }
    })
})