<?php
    include_once("../lib/database.php");
    $id = isset($REQUEST["id"]) ? $_REQUEST["id"] : null;
    if($id) {
        $sql_get_customer_info = "select id,full_name,email,phone,address,birthday,img_name,cmnd,username,count(*) as 'countt' from customer where id = ? and is_delete = 0 and is_lock = 0 limit 1";
        $result = fetch_row($sql_get_customer_info,[$id]);
?>
<?php
    if($result['countt'] == 1) { 
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="full_name">Tên đầy đủ</label>
            <input type="text" class="form-control" id="full_name" value="<?=$result['full_name']?>">
        </div>
        <div class="col-md-4 form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" value="<?=$result['email']?>">
        </div>
        <div class="col-md-4 form-group">
            <label for="phone">Số điện thoại</label>
            <input type="email" class="form-control" id="phone" value="<?=$result['phone']?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="email">Số chứng minh nhân dân</label>
            <input type="email" class="form-control" id="cmnd" value="<?=$result['cmnd']?>">
        </div>
        <div class="col-md-6 form-group">
            <label for="image">Ảnh đại diện</label>
            <div class="img-fluid" id="where-replace">
                <img src="<?php echo "upload/profile/{$result['img_cmnd']}";?>" class="img-fluid" id="display-image"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="birthday">Ngày sinh</label>
            <input type="text" class="form-control" id="birthday" value="<?=$result['birthday']?>">
        </div>
        <div class="col-md-6 form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" class="form-control" id="address" value="<?=$result['address']?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" value="<?=$result['username']?>">
        </div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST[id];?>">
<!-- /.card-body -->
<div class="card-footer">
    <button id="btn-insert" type="submit" class="btn btn-primary">Sửa dữ liệu</button>
</div>
<?php 
    } 
    exit();
}

?>