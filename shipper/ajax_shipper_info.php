<?php
    include_once("../lib/database.php");
    $shipper_id = isset($_REQUEST["shipper_id"]) ? $_REQUEST["shipper_id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($shipper_id && $status == "Update") {
        $sql_get_all = "select * from user where id = '$shipper_id' and type = 'shipper' limit 1";
        $result = fetch(sql_query($sql_get_all));
?>
<div class="card-body">
    <div class="row">
        <div class="col-xl-4 col-md-6 col-sm-12 form-group">
            <label for="full_name">Tên đầy đủ</label>
            <input type="text" class="form-control" id="full_name" placeholder="Nhập họ tên đầy đủ" value="<?=$result['full_name']?>">
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12 form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Nhập email" value="<?=$result['email']?>">
        </div>
        <div class="col-xl-4 col-md-12 form-group">
            <label for="phone">Số điện thoại</label>
            <input type="number" min="1" class="form-control" id="phone" placeholder="Nhập số điện thoại" value="<?=$result['phone']?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 form-group">
            <label for="image">Ảnh đại diện</label>
            <div class="custom-file">
                <input name="img_name" type="file" class="custom-file-input" id="fileInput" onchange="readURL(this)">
                <label class="custom-file-label" for="fileInput">Chọn ảnh</label>
            </div>
            <div class="img-fluid" id="where-replace">
                <img src="<?=$result['img_name'] ? "../admin/".$result['img_name'] : "../admin/upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 form-group">
            <label for="birthday">Ngày sinh</label>
            <input type="text" date-date="<?=$result['birthday']?>" class="form-control kh-datepicker" id="birthday" placeholder="Nhập ngày tháng năm sinh" value="<?=Date('d-m-Y',strtotime($result['birthday']));?>">
        </div>
        <div class="col-md-6 col-sm-12 form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ thường trú" value="<?=$result['address']?>">
        </div>
    </div>
</div>
<input type="hidden" name="id" value="<?php echo $shipper_id;?>">
<div class="card-footer">
    <button id="btn-update" type="button" class="dt-button button-purple">Sửa dữ liệu</button>
</div>
<?php } else if($shipper_id && $status == "ChangePass") {?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 col-md-12 form-group">
                <label for="">Mật khẩu cũ:</label>
                <input type="password" class="form-control" id="old_pass" placeholder="Nhập mật khẩu cũ..." value="">
            </div>
            <div class="col-lg-4 col-md-12 form-group">
                <label for="">Mật khẩu mới:</label>
                <input type="password" class="form-control" id="new_pass" placeholder="Nhập mật khẩu mới..." value="">
            </div>
            <div class="col-lg-4 col-md-12 form-group">
                <label for="">Xác nhận mật khẩu mới:</label>
                <input type="password" class="form-control" id="confirm_new_pass" placeholder="Nhập xác nhận mật khẩu mới..." value="">
            </div>
        </div>
    </div>
<input type="hidden" name="id" value="<?php echo $shipper_id;?>">
<div class="card-footer">
    <button id="btn-change-pass" type="button" class="dt-button button-purple">Sửa dữ liệu</button>
</div>
<?php }?>