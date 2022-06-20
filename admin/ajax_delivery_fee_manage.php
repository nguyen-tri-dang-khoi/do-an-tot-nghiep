<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Update") {
        $sql_get_all = "select * from delivery_fee where id = '$id' and is_delete = 0 limit 1";
        $row = fetch(sql_query($sql_get_all));
        log_a($row);
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group" >
            <label for="province_id">Tỉnh / thành phố :</label>
            <select name="province_id" class="form-control select-address" onchange="loadDistricts()">
                <option value="no">Chọn tỉnh / thành phố</option>
                <?php
                    $sql_list_provinces = "select id,full_name from provinces";
                    $provinces = fetch_all(sql_query($sql_list_provinces));
                    foreach($provinces as $province) {
                ?>
                    <option value="<?=$province['id']?>" <?=$province['id'] == $row['province_id'] ? "selected" : "";?>><?=$province['full_name']?></option>
                <?php
                    }
                ?>
            </select>
            <div id="province_id_err" class="text-danger"></div>
        </div>
        <div class="col-md-4 form-group select-districts">
            <label for="district_id">Quận / huyện / xã</label>
            <select name="district_id" class="form-control select-address" onchange="loadWards()">
                <option value="no">Chọn quận / huyện / xã</option>
                <?php
                    $sql_list_districts = "select id,full_name from districts where province_id = " . $row['province_id'];
                    $districts = fetch_all(sql_query($sql_list_districts));
                    foreach($districts as $district) {
                ?>
                    <option value="<?=$district['id']?>" <?=$district['id'] == $row['district_id'] ? "selected" : "";?>><?=$district['full_name']?></option>
                <?php
                    }
                ?>
            </select>
            <div id="district_id_err" class="text-danger"></div>
        </div>
        <div class="col-md-4 form-group select-wards">
            <label for="ward_id">Phường / thị trấn</label>
            <select name="ward_id" class="form-control select-address">
                <option value="">Chọn phường / thị trấn</option>
                <?php
                    $sql_list_wards = "select id,full_name from wards where district_id = " . $row['district_id'];
                    $wards = fetch_all(sql_query($sql_list_wards));
                    foreach($wards as $ward) {
                ?>
                    <option value="<?=$ward['id']?>" <?=$ward['id'] == $row['ward_id'] ? "selected" : "";?>><?=$ward['full_name']?></option>
                <?php
                    }
                ?>
            </select>
            <div id="ward_id_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="delivery_fee">Phí vận chuyển:</label>
            <input type="text" id="fee" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="delivery_fee" value="<?=number_format($row['fee'],0,".",".");?>" class="form-control" placeholder="Nhập phí vận chuyển...">
            <div id="delivery_fee_err" class="text-danger"></div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button onclick="processModalUpdate()" id="btn-update" type="button" class="dt-button button-purple">Sửa</button>
    <input type="hidden" name="id" value="<?=$row['id'];?>">      
</div>
<?php
    } else if($status == "Insert") {
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group ">
            <label for="province_id">Tỉnh / thành phố :</label>
            <select name="province_id" class="form-control select-address" onchange="loadDistricts()">
                <option value="no">Chọn tỉnh / thành phố</option>
                <?php
                    $sql_list_provinces = "select id,full_name from provinces";
                    $provinces = fetch_all(sql_query($sql_list_provinces));
                    foreach($provinces as $province) {
                ?>
                    <option value="<?=$province['id']?>"><?=$province['full_name']?></option>
                <?php
                    }
                ?>
            </select>
            <div id="province_id_err" class="text-danger"></div>
        </div>
        <div class="col-md-4 form-group select-districts" >
            <label for="district_id">Quận / huyện / xã</label>
            <select name="district_id" class="form-control select-address" onchange="loadWards()">
                <option value="no">Chọn quận / huyện / xã</option>
            </select>
            <div id="district_id_err" class="text-danger"></div>
        </div>
        <div class="col-md-4 form-group select-wards">
            <label for="ward_id">Phường / thị trấn</label>
            <select name="ward_id" class="form-control select-address">
                <option value="no">Chọn phường / thị trấn</option>
            </select>
            <div id="ward_id_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="delivery_fee">Phí vận chuyển:</label>
            <input type="text" id="fee" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="delivery_fee" class="form-control" placeholder="Nhập phí vận chuyển...">
            <div id="delivery_fee_err" class="text-danger"></div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button onclick="processModalInsert()" id="btn-insert" type="button" class="dt-button button-purple">Thêm</button>    
</div>
<?php } else if($id && $status == "Read") {?>
    <?php
        $sql_get_all = "select df.id as 'df_id',df.fee as 'df_fee',df.created_at as 'df_created_at',pr.full_name as 'pr_full_name',di.full_name as 'di_full_name',wa.full_name as 'wa_full_name' from delivery_fee df inner join provinces pr on df.province_id = pr.id inner join districts di on df.district_id = di.id inner join wards wa on df.ward_id = wa.id where df.id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));    
    ?>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th class='w-200'>Tỉnh / thành phố</th>
                <td><?=$result['pr_full_name'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Quận / huyện / xã</th>
                <td><?=$result['di_full_name'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Phường / thị trấn</th>
                <td><?=$result['wa_full_name'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Phí vận chuyển</th>
                <td><?=number_format($result['df_fee'],0,".",".");?></td>
            </tr>
            <tr>
                <th class='w-200'>Ngày tạo</th>
                <td><?=Date("d-m-Y",strtotime($result['df_created_at']));?></td>
            </tr>
        </table>
    </div>
<?php } else if($status == "read_more") {
    $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
    $html = "";
    if($str_arr_upt) {
        $sql = "select df.id as 'df_id',df.fee as 'df_fee',df.created_at as 'df_created_at',pr.full_name as 'pr_full_name',di.full_name as 'di_full_name',wa.full_name as 'wa_full_name' from delivery_fee df inner join provinces pr on df.province_id = pr.id inner join districts di on df.district_id = di.id inner join wards wa on df.ward_id = wa.id where df.id in ($str_arr_upt)";
        $result2 = fetch_all(sql_query($sql));
        $i = 1;
        foreach($result2 as $res) {
            $html .= "<tbody style='display:none;' class='t-bd-read t-bd-read-$i'>
            <tr>
                <th class='w-200'>Tỉnh / thành phố<</th>
                <td>" . $res['pr_full_name'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Quận / huyện / xã</th>
                <td>" . $res['di_full_name'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Phường / thị trấn</th>
                <td>" . $res['wa_full_name'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Phí vận chuyển</th>
                <td>" . number_format($res['df_fee'],0,".",".") . "</td>
            </tr>
            <tr>
                <th class='w-200'>Ngày tạo</th>
                <td>" . Date("d-m-Y H:i:s",strtotime($res['df_created_at'])) . "</td>
            </tr>
            </tbody>";
            $i++;
        }
        $html = "<div class='card-body'>
        <table class='table table-bordered'>
            $html
        </table></div>";
        print_r($html);
        
    }
} else if($status == "load_districts") {
    $province_id = isset($_REQUEST["province_id"]) ? $_REQUEST["province_id"] : null;    
?>
    <label for="district_id">Quận / huyện / xã</label>
    <select name="district_id" class="form-control select-address" onchange="loadWards()">
        <option value="no">Chọn quận / huyện / xã</option>
        <?php
            if($province_id != "no") {
                $sql_list_districts = "select id,full_name from districts where province_id = '$province_id'";
                $districts = fetch_all(sql_query($sql_list_districts));
                foreach($districts as $district) {
            ?>
                    <option value="<?=$district['id']?>"><?=$district['full_name']?></option>
        <?php
                }
            } else {
        ?>
        <?php } ?>
    </select>
    <div id="district_id_err" class="text-danger"></div>
<?php } else if($status == "load_wards") {
    $district_id = isset($_REQUEST["district_id"]) ? $_REQUEST["district_id"] : null;
?>
    <label for="ward_id">Phường / thị trấn</label>
    <select name="ward_id" class="form-control select-address">
        <option value="no">Chọn phường / thị trấn</option>
        <?php
            if($district_id != "no") {
                $sql_list_wards = "select id,full_name from wards where district_id = '$district_id'";
                $wards = fetch_all(sql_query($sql_list_wards));
                foreach($wards as $ward) {
        ?>
                    <option value="<?=$ward['id']?>"><?=$ward['full_name']?></option>
        <?php
                }
            } else {
        ?>
        <?php } ?>
    </select>
    <div id="ward_id_err" class="text-danger"></div>
<?php } else if($status == "load_districts2") {
    $choose_province_id = isset($_REQUEST["choose_province_id"]) ? $_REQUEST["choose_province_id"] : null;    
?>
    <select name="choose_district_id" class="form-control select-address2" onchange="loadWards2()">
        <option value="">Chọn quận / huyện / xã</option>
        <?php
            if($choose_province_id != "") {
                $sql_list_districts = "select id,full_name from districts where province_id = '$choose_province_id'";
                $districts = fetch_all(sql_query($sql_list_districts));
                foreach($districts as $district) {
            ?>
                    <option value="<?=$district['id']?>"><?=$district['full_name']?></option>
        <?php
                }
            } else {
        ?>
        <?php } ?>
    </select>
<?php } else if($status == "load_wards2") {
    $choose_district_id = isset($_REQUEST["choose_district_id"]) ? $_REQUEST["choose_district_id"] : null;
?>
    <select name="choose_ward_id" class="form-control select-address2">
        <option value="">Chọn phường / thị trấn</option>
        <?php
            if($choose_district_id != "") {
                $sql_list_wards = "select id,full_name from wards where district_id = '$choose_district_id'";
                $wards = fetch_all(sql_query($sql_list_wards));
                foreach($wards as $ward) {
        ?>
                    <option value="<?=$ward['id']?>"><?=$ward['full_name']?></option>
        <?php
                }
            } else {
        ?>
        <?php } ?>
    </select>
<?php }?>
