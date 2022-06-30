<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "changeInfo") {
        $sql_get_all = "select * from user where id = '$id' and (type = 'admin' or type = 'officer') limit 1";
        $result = fetch(sql_query($sql_get_all));
?>
<div class="card-body">
    <div class="row">
        <div class="col-xl-4 col-md-6 col-sm-12 form-group">
            <label for="full_name">Tên đầy đủ</label>
            <input type="text" class="form-control" name="full_name" placeholder="Nhập họ tên đầy đủ" value="<?=$result['full_name']?>">
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12 form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Nhập email" value="<?=$result['email']?>">
        </div>
        <div class="col-xl-4 col-md-12 form-group">
            <label for="phone">Số điện thoại</label>
            <input type="number" min="1" class="form-control" name="phone" placeholder="Nhập số điện thoại" value="<?=$result['phone']?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 form-group">
            <label for="email">Số chứng minh nhân dân</label>
            <input type="number" min="1" class="form-control" name="cmnd" placeholder="Nhập số chứng minh nhân dân" value="<?=$result['cmnd']?>">
        </div>
        <div class="col-md-6 col-sm-12 form-group">
            <label for="image">Ảnh đại diện</label>
            <div class="custom-file">
                <input name="img_admin_file" type="file" class="custom-file-input" id="fileInput" onchange="readURL(this)">
                <label class="custom-file-label" for="fileInput">Chọn ảnh</label>
            </div>
            <div class="img-fluid" id="where-replace">
                <img src="<?=$result['img_name'] ? "../admin/".$result['img_name'] : "upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 form-group" >
            <label for="birthday">Ngày sinh</label>
            <input type="text" class="form-control kh-datepicker" name="birthday" placeholder="Nhập ngày tháng năm sinh" value="<?=Date('d-m-Y',strtotime($result['birthday']));?>">
        </div>
        <div class="col-md-6 col-sm-12 form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" class="form-control" name="address" placeholder="Nhập địa chỉ thường trú" value="<?=$result['address']?>">
        </div>
    </div>
</div>
<input type="hidden" name="id" value="<?php echo $id;?>">
<div class="card-footer">
    <button id="btn-update" type="button" onclick="processChangeInfo()" class="dt-button button-purple">Sửa dữ liệu</button>
</div>
<?php } else if($id && $status == "ChangePass") {?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 col-md-12 form-group">
                <label for="">Mật khẩu cũ:</label>
                <input type="password" class="form-control" name="old_pass" placeholder="Nhập mật khẩu cũ..." value="">
            </div>
            <div class="col-lg-4 col-md-12 form-group">
                <label for="">Mật khẩu mới:</label>
                <input type="password" class="form-control" name="new_pass" placeholder="Nhập mật khẩu mới..." value="">
            </div>
            <div class="col-lg-4 col-md-12 form-group">
                <label for="">Xác nhận mật khẩu mới:</label>
                <input type="password" class="form-control" name="confirm_new_pass" placeholder="Nhập xác nhận mật khẩu mới..." value="">
            </div>
        </div>
    </div>
<input type="hidden" name="id" value="<?php echo $id;?>">
<div class="card-footer">
    <button id="btn-change-pass" type="button" onclick="processChangePass()" class="dt-button button-purple">Sửa dữ liệu</button>
</div>
<?php }?>