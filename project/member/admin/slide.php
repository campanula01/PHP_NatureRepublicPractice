<?php

$js_array=['js/slide.js'];

include 'inc_common.php';
include 'inc_header.php';
include '../inc/dbconfig.php';
include '../inc/slide.php';  //게시판관리 class 



$slide = new Slide($db);
$slideArr =$slide->list();

?>
<main class="border rounded-5 p-5" style="height: calc(100vh-200px);">

    <div class="container">
        <h3>슬라이드 관리</h3>
        <p>최신 3개의 슬라이드만 반영됩니다.</p>
    </div>

    <table class="table table-border">
        <tr>
            <th>번호</th>
            <th>슬라이드 이름</th>
            <th>슬라이드 이미지</th>
            <th>등록일시</th>
            <th>관리</th>
        </tr>
        <?php
            foreach($slideArr AS $row){
                //$row['create_at']=substr($row['create_at'],0,16);
                $fileParts = explode('|', $row['file']);
                $imageName = $fileParts[0];
        ?>
        <tr>
            <td><?= $row['idx']; ?></td>
            <td><?= $row['name']; ?></td>
            <td> <?php
                 $imagePath = '../data/slide/' . $imageName;
                 if (file_exists($imagePath)) {
                     ?>
                     <img src="<?= $imagePath; ?>" alt="<?= $row['name']; ?>" style="max-width: 300px; max-height: 300px;">
                     <?php
                 } else {
                     echo "Image not found";
                 }
                 ?></td>
            <td><?= $row['create_at']; ?></td>
            <td>
                <button type="button" class="btn btn-success btn-sm btn_slide_view " data-idx="<?=$row['idx']; ?>">보기</button>
                <button type="button" class="btn btn-green1 btn-sm btn_slide_edit " data-bs-toggle="modal" data-bs-target="#slide_create_modal" data-idx="<?=$row['idx']; ?>">수정</button>
                <button type="button" class="btn btn-danger btn-sm btn_slide_delete" data-idx="<?=$row['idx']; ?>">삭제</button>
            </td>

        </tr>

        <?php
            }
        ?>
    </table>

    <div class="d-flex mt-3 justify-content-between align-items-start">


            <button type="button" class="btn btn-success" id="btn_create_modal" data-bs-toggle="modal" data-bs-target="#slide_create_modal">슬라이드 생성</button>
    </div>

</main>
<style>
  .close{
    width: 11px; height: 11px; background-color: white; margin-top: 7px; margin-right: 7px;
    clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%);}
    #pop1{
      border: 1px solid #999; left: 5px; top: 5px; position: absolute; z-index: 1000;
      display: none;
    }
</style>


<!-- Modal -->
<div class="modal fade" id="slide_create_modal" tabindex="-1" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal_title">슬라이드 생성</h1>
        <input type="hidden" name="mode" id="slide_mode" value="">
        <input type="hidden" name="idx" id="slide_idx" value="">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ">
        <div class="d-flex gap-2">
        <input type="text" id="slide_title" class="form-control" placeholder="슬라이드 이름">

        </div>
        <div class="mt-2 d-flex gap-2">
        <input type="file" id="file" class="form-control" placeholder="슬라이드 파일">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-green3" id="btn_slide_create">확인</button>
      </div>
    </div>
  </div>
</div>

<?php
include 'inc_footer.php';
?>
