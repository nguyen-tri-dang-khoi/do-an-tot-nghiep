<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($status == "changeCompanyInfo") {
        $sql_get_all = "select * from company_info where id = 1 limit 1";
        $result = fetch(sql_query($sql_get_all));
?>
<div class="card-body">
    <div class="row">
        <div class="col-xl-4 col-md-6 col-sm-12 form-group">
            <label for="full_name">Tên công ty</label>
            <input type="text" class="form-control" name="company_name" placeholder="Nhập tên công ty" value="<?=$result['company_name']?>">
            <p id="company_name_err" class="text-danger"></p>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12 form-group">
            <label for="email">Email công ty</label>
            <input type="email" class="form-control" name="company_email" placeholder="Nhập email công ty" value="<?=$result['company_email']?>">
            <p id="company_email_err" class="text-danger"></p>
        </div>
        <div class="col-xl-4 col-md-12 form-group">
            <label for="phone">Số điện thoại công ty</label>
            <input type="number" min="1" class="form-control" name="company_phone" placeholder="Nhập số điện thoại công ty" value="<?=$result['company_phone']?>">
            <p id="company_phone_err" class="text-danger"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 form-group">
            <label for="email">Mã số thuể</label>
            <input type="number" min="1" class="form-control" name="company_tax_code" placeholder="Nhập mã số thuế công ty" value="<?=$result['company_tax_code']?>">
            <p id="company_tax_code_err" class="text-danger"></p>
        </div>
        <div class="col-md-6 col-sm-12 form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" class="form-control" name="company_address" placeholder="Nhập địa chỉ thường trú" value="<?=$result['company_address']?>">
            <p id="company_address_err" class="text-danger"></p>
        </div>
    </div>
</div>
<div class="card-footer">
    <button id="btn-update" type="button" onclick="processChangeCompanyInfo()" class="dt-button button-purple">Sửa dữ liệu</button>
</div>
<?php } ?>