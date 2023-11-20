<?php
$js_array=['js/member.js'];
$g_title = '약관';
include 'inc_header.php'; ?>


          <main class="p-5 border rounded-5">
            <h2 class="text-center ">회원 약관 및 개인 정보 취급방침 동의</h2>
            <h5>회원 약관</h5>
            <textarea name="" id="" cols="30" rows="10" class="form-control">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa explicabo minima laboriosam maxime consequatur voluptatum exercitationem numquam repellat error, rem tempore dolor sequi dolores quas impedit quis hic officiis totam amet ex laborum pariatur! Quia id exercitationem cumque molestiae eius ducimus, rem nulla temporibus recusandae veritatis numquam laborum itaque, voluptatibus tempore atque nihil. Accusamus officia aliquid facilis possimus dolorem voluptas excepturi iusto, asperiores tempore modi quaerat repellendus non molestias odit voluptatibus rerum iure? Voluptate libero officiis doloremque animi in. Commodi fugiat necessitatibus non ducimus iusto, facere dolor alias facilis? Totam, perferendis dignissimos sapiente libero voluptatum aut illo ex atque sit nesciunt eveniet doloribus suscipit incidunt consectetur. Sequi recusandae reiciendis sapiente fuga laborum eum iure officia. Labore, quisquam tempore iste excepturi asperiores enim, nulla amet accusantium ipsam consectetur commodi porro, delectus ad deserunt? Mollitia dignissimos sit explicabo. Dicta, praesentium explicabo ab esse iure magnam molestiae repudiandae quod sunt perspiciatis! Voluptatibus error ducimus, odio voluptate officiis quos similique animi beatae qui, numquam accusamus et facere ab ipsa, saepe distinctio provident minima consequuntur alias mollitia molestiae soluta! Officia totam minus ipsum deserunt quia, nulla ea suscipit reprehenderit at qui ex ipsam, quo numquam optio quidem pariatur aspernatur fugit recusandae ratione iste. Dolorem nostrum quam rerum quaerat tempore excepturi aliquam aspernatur unde laborum quia odio, impedit tenetur, placeat corrupti dolore sunt sed perferendis? Ducimus molestiae veritatis aliquam, voluptas ex corporis, totam quidem odit illum ad quibusdam esse sed error deleniti. Aliquid nam accusamus assumenda ipsum ex, nemo, repellendus iste eos ea quae id aperiam tempora enim magnam saepe architecto. Porro asperiores cupiditate dolorum enim veritatis totam expedita obcaecati id reprehenderit, tempore praesentium? Fugiat ipsum similique illum est eveniet aliquid error nam sit enim omnis, placeat veniam, deleniti eaque praesentium, vero culpa cumque minus modi aspernatur commodi sequi iusto in accusamus. Vitae animi natus provident!
            </textarea>

            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="" id="chk_member1">
                <label class="form-check-label" for="chk_member1">
                    위 약관에 동의 하시겠습니까?
                </label>
                
              </div>


            <h5 class="mt-4">개인정보 취급방침</h5>
            <textarea name="" id="" cols="30" rows="10" class="form-control">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed facere cum fugit ab veritatis quisquam dolor, voluptatum quo explicabo natus, aliquam porro harum amet eum reprehenderit a pariatur ipsam odio commodi quaerat dolorum ex exercitationem laudantium fugiat? Voluptas itaque dolor temporibus accusantium corporis quae, reiciendis quaerat enim! Repudiandae, doloribus quasi. Earum ullam quam velit iusto facere minima, omnis ipsam iste incidunt aliquam aperiam delectus obcaecati. Aperiam, tempora dolorem officiis adipisci repellat nostrum velit fuga quo non vel at excepturi dolorum. Quae cumque veritatis unde, sed magnam accusantium esse! Accusamus esse quam natus velit nobis sit, magnam harum cum libero? Veritatis dicta doloribus obcaecati provident ut minima! Cupiditate itaque delectus, voluptatum voluptatem quae eum blanditiis labore voluptates porro officia atque magnam ipsum nemo, aliquam cumque reiciendis consequatur nisi veniam. Laudantium commodi ducimus blanditiis facere quidem praesentium pariatur consectetur, iusto eos, assumenda minima aliquam omnis dicta aut similique, sapiente repellendus? Vitae doloribus qui id autem assumenda eligendi maiores incidunt repellat minus repudiandae nobis est reprehenderit animi delectus consectetur, quasi adipisci ipsam, quod sit. Tempora beatae reprehenderit assumenda, iste, sapiente aliquid ratione numquam quasi exercitationem aperiam, quaerat soluta ea. Doloribus doloremque minus aut aspernatur veniam libero, officia tenetur rerum quibusdam, reiciendis temporibus ex.
            </textarea>


            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="" id="chk_member2">
                <label class="form-check-label" for="chk_member2">
                    위 개인 정보 취급방침에 동의 하시겠습니까?
                </label>
                
              </div>

              <div class="mt-4 d-flex justify-content-center gap-2">

                <button class="btn btn-green1 w-75" id="btn_member">회원가입</button>
                <button class="btn btn-secondary w-25" style="height: 60px;">가입취소</button>

              </div>

              <form method="post" name="stipulation_form" action="member_input.php" style="display: none;">
                <input type="hidden" name="chk" value="0">
              </form>
          </main>
    </div>

  <?php include 'inc_footer.php'; ?>
