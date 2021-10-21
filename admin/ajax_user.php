<?php
    include_once("../lib/database.php");
    
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
    if($id) {
        $sql_get_user_info = "select id,full_name,email,phone,address,birthday,img_name,cmnd,username,count(*) as 'countt' from user where id = ? and is_delete = 0 and is_lock = 0 limit 1";
        $result = fetch_row($sql_get_user_info,[$id]);
?>
<?php
    if($result['countt'] == 1) { 
?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="full_name">Tên đầy đủ</label>
                <input type="text" class="form-control" id="full_name" placeholder="Nhập họ tên đầy đủ" value="<?=$result['full_name']?>">
            </div>
            <div class="col-md-4 form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Nhập email" value="<?=$result['email']?>">
            </div>
            <div class="col-md-4 form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" placeholder="Nhập số điện thoại" value="<?=$result['phone']?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email">Số chứng minh nhân dân</label>
                <input type="text" class="form-control" id="cmnd" placeholder="Nhập số chứng minh nhân dân" value="<?=$result['cmnd']?>">
            </div>
            <div class="col-md-6 form-group">
                <label for="image">Ảnh đại diện</label>
                <div class="custom-file">
                    <input name="img_name" type="file" class="custom-file-input" id="fileInput">
                    <label class="custom-file-label" for="fileInput">Chọn ảnh đại diện</label>
                </div>
                <!--_DIR_["IMG"]["ADMINS"];?>info/img-cmnd/echo $result["img_cmnd"]-->
                <div class="img-fluid" id="where-replace">
                    <img src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="birthday">Ngày sinh</label>
                <input type="text" date-date="<?=$result['birthday']?>" class="form-control" id="birthday" placeholder="Nhập ngày tháng năm sinh" value="<?=Date('d-m-Y',strtotime($result['birthday']));?>">
            </div>
            <div class="col-md-6 form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ thường trú" value="<?=$result['address']?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" placeholder="Nhập tên đăng nhập" value="<?=$result['username']?>">
            </div>
            <div class="col-md-6 form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" class="form-control" value="1234" id="password" placeholder="Nhập mật khẩu đăng nhập">
            </div>
        </div>
    </div>
    <input type="hidden" name="token" value="<?php echo_token();?>">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <input type="hidden" name="number" value="<?php echo $number;?>">
    <div class="card-footer">
        <button id="btn-update" type="submit" class="btn btn-primary">Sửa dữ liệu</button>
    </div>
<?php 
        } 
        exit();
    }
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="full_name">Tên đầy đủ</label>
            <input type="text" class="form-control" id="full_name" placeholder="Nhập họ tên đầy đủ" >
        </div>
        <div class="col-md-4 form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Nhập email">
        </div>
        <div class="col-md-4 form-group">
            <label for="phone">Số điện thoại</label>
            <input type="email" class="form-control" id="phone" placeholder="Nhập số điện thoại">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="email">Số chứng minh nhân dân</label>
            <input type="email" class="form-control" id="cmnd" placeholder="Nhập số chứng minh nhân dân">
        </div>
        <div class="col-md-6 form-group">
            <label for="image">Ảnh đại diện</label>
            <div class="custom-file">
                <input name="img_name" type="file" class="custom-file-input" id="fileInput">
                <label class="custom-file-label" for="fileInput">Chọn ảnh đại diện</label>
            </div>
            <div class="img-fluid" id="where-replace">
                <span></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="birthday">Ngày sinh</label>
            <input date-date="" type="text" class="form-control" id="birthday" placeholder="Nhập ngày tháng năm sinh">
        </div>
        <div class="col-md-6 form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ thường trú">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" placeholder="Nhập tên đăng nhập">
        </div>
        <div class="col-md-6 form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" class="form-control" value="1234" id="password" placeholder="Nhập mật khẩu đăng nhập">
        </div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<input type="hidden" name="number" value="<?php echo $number;?>">
<!-- /.card-body -->
<div class="card-footer">
    <button id="btn-insert" type="submit" class="btn btn-primary">Thêm dữ liệu</button>
</div>