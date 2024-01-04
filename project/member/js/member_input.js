document.addEventListener("DOMContentLoaded",()=>{

    //아이디 중복 체크
    const btn_id_check = document.querySelector("#btn_id_check");
    btn_id_check.addEventListener("click",()=>{
        const f_id = document.querySelector("#f_id");

        if(f_id.value==''){
            alert('아이디를 입력해 주세요.');
            return false;
        }

        //AJAX
        const f1 = new FormData()
        f1.append('id', f_id.value)
        f1.append('mode','id_chk')


        const xhr = new XMLHttpRequest()
        xhr.open("POST", "./pg/member_process.php", "true");
        xhr.send(f1);
        xhr.onload = () =>{
            if(xhr.status == 200){
                //단순한 문자형태를 json타입으로 변환
                const data = JSON.parse(xhr.responseText);
                if(data.result=='success'){
                    document.input_form.id_chk.value="0";
                    alert('사용이 가능한 아이디입니다.');
                    document.input_form.id_chk.value="1";
                }else if(data.result=='fail'){
                    document.input_form.id_chk.value="0";
                    alert('이미 사용중인 아이디입니다. 다른 아이디를 입력해 주세요.');

                    f_id.value='';
                    f_id.focus();
                }else if(data.result == 'empty_id'){
                    alert('아이디가 비어있습니다.');
                    f_id.focus();
                }
            }

        }
    })


    //이메일 중복 체크
    const btn_email_check = document.querySelector("#btn_email_check");
    btn_email_check.addEventListener("click",()=>{
        const f_email = document.querySelector("#f_email");

        if(f_email.value==''){
            alert('이메일을 입력해 주세요.');
            f_email.focus();
            return false;
        }

        //AJAX
        const f1 = new FormData()
        f1.append('email', f_email.value)
        f1.append('mode','email_chk')


        const xhr = new XMLHttpRequest()
        xhr.open("POST", "./pg/member_process.php", "true");
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


    //가입버튼 클릭 시
    const btn_submit = document.querySelector("#btn_submit");
    btn_submit.addEventListener("click", ()=>{
        const f = document.input_form;

        if(f.name.value == ''){
            alert('이름을 입력해 주세요.');
            f.name.focus();
            return false;
        }

        if(f.id.value == ''){
            alert('아이디를 입력해 주세요.');
            f.id.focus();
            return false;
        }

        //아이디 중복 확인 여부 체크 
        if(f.id_chk.value == 0){
            alert('아이디 중복 확인을 해주세요.');
            return false;
        }

        //비밀번호 입력 여부
        if(f.password.value == ''){
            alert('비밀번호를 입력하여 주세요.');
            f.password.focus();
            return false;
        }


        //비밀번호 입력 여부
        if(f.password2.value == ''){
            alert('비밀번호를 확인하여 주세요.');
            f.password2.focus();
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
        if(f.email_chk.value ==0){
            alert('이메일 중복 확인을 해주세요.')
            return false;
        }

        //우편 번호 찾기 확인
        if(f.zipcode_chk.value ==0){
            alert('우편 번호 찾기를 이용하여 주세요.')
            f.zipcode.value='';
            f.zipcode.focus();
            f.addr1.value='';
            f.addr2.value='';
            return false;
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
    



    const f_photo = document.querySelector("#f_photo");
    f_photo.addEventListener("change",(e)=>{
        const reader = new FileReader()

        //이미지 로딩 시작
        reader.readAsDataURL(e.target.files[0])

        //이미지 로딩이 끝나면
        reader.onload = function(event){
            console.log(event)
            //img요소를 만듦.
            //const img = document.createElement("img");
            //img.setAttribute("src",event.target.result);

            const f_preview = document.querySelector("#f_preview");
            f_preview.setAttribute("src",event.target.result);

        }
    })
})
