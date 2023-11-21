document.addEventListener("DOMContentLoaded",()=>{

    //이메일 중복 확인
    const btn_email_check = document.querySelector("#btn_email_check")
    btn_email_check.addEventListener("click",()=>{
        const f = document.input_form
        if(f.old_email.value == f.email.value){
            alert('이메일 중복확인이 필요없습니다. 새로운 이메일로 변경시 이메일 중복확인을 진행해 주세요.')
            return false;
        }

        if(f.email.value ==''){
            alert('이메일을 입력해 주세요.');
            f.email.focus();
            return false;
        }

                //AJAX
                const f1 = new FormData()
                f1.append('email', f.email.value)
                f1.append('mode','email_chk')
        
        
                const xhr = new XMLHttpRequest()
                xhr.open("POST", "../pg/member_process.php", "true");
                xhr.send(f1);
                xhr.onload = () =>{
                    if(xhr.status == 200){
                        //단순한 문자형태를 json타입으로 변환
                        const data = JSON.parse(xhr.responseText);
                        if(data.result=='success'){
                            document.input_form.email_chk.value="0";
                            alert('사용이 가능한 이메일입니다.');
                            document.input_form.email_chk.value="1";
                        }else if(data.result=='fail'){
                            document.input_form.email_chk.value="0";
                            alert('이미 사용중인 이메일입니다. 다른 이메일을 입력해 주세요.');
        
                            f_email.value='';
                            f_email.focus();
                        }else if(data.result == 'empty_email'){
                            alert('이메일이 비어있습니다.');
                            f_email.focus();
                        }else if(data.result =='email_format_wrong'){
                            alert('이메일이 형식에 맞지 않습니다.');
                            f_email.value='';
                            f_email.focus();
                        }
                    }
        
                }
            
        
    })

    //프로필 이미지 
    const f_photo = document.querySelector("#f_photo");
    f_photo.addEventListener("change",(e)=>{
        const reader = new FileReader()

        reader.readAsDataURL(e.target.files[0])

        reader.onload = function(event){
            console.log(event)
            
            const f_preview = document.querySelector("#f_preview");
            f_preview.setAttribute("src",event.target.result);

        }
    })

    
    //우편 번호 검색
    const btn_zipcode = document.querySelector("#btn_zipcode");

    btn_zipcode.addEventListener("click",()=>{

        new daum.Postcode({
        oncomplete: function(data) {
            let addr ='';

           
          

            console.log(data);
            if(data.userSelectedType =='J'){
                addr = data.jibunAddress
            }else if(data.userSelectedType == 'R'){
                addr = data.roadAddress
            }

            if(data.bname !=''){
                extra_addr = data.bname
            }else if(data.buildingName == ''){
                if(extra_addr == ''){
                    extra_addr = data.buildingName;
                }else{
                    extra_addr += ', '+data.buildingName;
                }
            }

            if(extra_addr != ''){
                extra_addr = ' ('+extra_addr+')';
            }

            const f_addr1 = document.querySelector("#f_addr1");
            f_addr1.value = addr+extra_addr;


            const f_zipcode = document.querySelector("#f_zipcode");
            f_zipcode.value = data.zonecode;

            const f_addr2 = document.querySelector("#f_addr2");
            f_addr2.focus()
        }
        }).open();
        document.input_form.zipcode_chk.value="1";

    })
    

//수정버튼 클릭 시
const btn_submit = document.querySelector("#btn_submit");
btn_submit.addEventListener("click", ()=>{
    const f = document.input_form;

    if(f.name.value == ''){
        alert('이름을 입력해 주세요.');
        f.name.focus();
        return false;
    }

    //비밀번호 일치여부 확인
    if(f.password.value !== f.password2.value){
        alert('비밀번호가 서로 일치하지 않습니다.');
        f.password2.value='';
        f.password2.focus();
        return false;
    }


    //이메일 입력 부분 확인
    if(f.email.value ==''){
        alert('이메일을 입력해 주세요.')
        f.email.focus()
        return false;
    }

    //이메일 중복 체크 여부 확인
    if(f.email.value != f.old_email.value){
        if(f.email_chk.value ==0){
            alert('이메일 중복 확인을 해주세요.')
            return false;
        }
    
    }
    

    //우편 번호 입력 확인
    if(f.zipcode.value ==''){
        alert('우편번호를 입력하여 주세요.')
        return false;
    }

    if(f.addr1.value==''){
        alert('주소를 입력해 주세요.')
        f.addr1.focus();
        return false;
    }

    if(f.addr2.value==''){
        alert('상세 주소를 입력해 주세요.')
        f.addr2.focus();
        
        return false;

    }

    f.submit();
})


})