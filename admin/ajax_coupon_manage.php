<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Update") {
        $sql_get_all = "select * from coupon where id = ? limit 1";
        $result = fetch_row($sql_get_all,[$id]);
?>

<div class="card-body">
<div class="row">
        <div class="col-md-6 form-group">
            <label for="coupon_code">Nhập mã khuyến mãi:</label>
            <input type="text" name="coupon_code" class="form-control" value="<?=$result['coupon_code'] ? $result['coupon_code'] : "";?>" placeholder="Nhập mã khuyến mãi...">
            <div id="coupon_code_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="coupon_content">Nội dung mô tả:</label>
            <textarea class="form-control" name="coupon_content" placeholder="Nội dung mô tả..."><?=$result['coupon_content'] ? $result['coupon_content'] : "";?></textarea>
            <div id="coupon_content_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="coupon_discount_percent">Khuyến mãi (đơn vị %) :</label>
            <input min="1" max="99" type="number" name="coupon_discount_percent" value="<?=$result['coupon_discount_percent'] ? $result['coupon_discount_percent'] : "";?>" class="form-control">
            <div id="coupon_discount_percent_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_min">Số tiền tối thiểu :</label>
            <input type="number" name="coupon_if_subtotal_min" value="<?=$result['coupon_if_subtotal_min'] ? $result['coupon_if_subtotal_min'] : "";?>" class="form-control">
            <div id="coupon_if_subtotal_min_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_max">Số tiền tối đa :</label>
            <input type="number" name="coupon_if_subtotal_max" value="<?=$result['coupon_if_subtotal_max'] ? $result['coupon_if_subtotal_max'] : "";?>" class="form-control">
            <div id="coupon_if_subtotal_max_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="coupon_date_start">Thời gian bắt đầu :</label>
            <input type="text" name="coupon_date_start" data-coupon-date="<?=$result['coupon_date_start'] ? Date("Y-m-d",strtotime($result['coupon_date_start'])) : ""?>" value="<?=$result['coupon_date_start'] ? Date('d-m-Y',strtotime($result['coupon_date_start'])) : "";?>" class="form-control is-date-coupon-start">
            <div id="coupon_date_start_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-3 form-group">
            <label for="coupon_date_end">Thời gian kết thúc :</label>
            <input type="text" name="coupon_date_end" data-coupon-date="<?=$result['coupon_date_end'] ? Date("Y-m-d",strtotime($result['coupon_date_end'])) : ""?>" value="<?=$result['coupon_date_end'] ? Date('d-m-Y',strtotime($result['coupon_date_end'])) : "";?>" class="form-control is-date-coupon-end">
            <div id="coupon_date_end_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<div class="card-footer">
    <button id="btn-update" type="button" class="dt-button button-purple">Sửa</button>
    <input type="hidden" name="id" value="<?=$result['id'];?>">      
</div>
<?php
    } if($status == "Insert") {
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="coupon_code">Nhập mã khuyến mãi:</label>
            <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã khuyến mãi...">
            <div id="coupon_code_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="coupon_content">Nội dung mô tả:</label>
            <textarea class="form-control" name="coupon_content" placeholder="Nội dung mô tả..."></textarea>
            <div id="coupon_content_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="coupon_discount_percent">Khuyến mãi (đơn vị %) :</label>
            <input min="1" max="99" type="number" name="coupon_discount_percent" class="form-control">
            <div id="coupon_discount_percent_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_min">Số tiền tối thiểu:</label>
            <input type="number" name="coupon_if_subtotal_min" class="form-control">
            <div id="coupon_if_subtotal_min_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_max">Số tiền tối đa:</label>
            <input type="number" name="coupon_if_subtotal_max" class="form-control">
            <div id="coupon_if_subtotal_max_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="coupon_date_start">Thời gian bắt đầu :</label>
            <input type="text" name="coupon_date_start" class="form-control is-date-coupon-begin">
            <div id="coupon_date_start_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-3 form-group">
            <label for="coupon_date_end">Thời gian kết thúc :</label>
            <input type="text" name="coupon_date_end" class="form-control is-date-coupon-end">
            <div id="coupon_date_end_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<div class="card-footer">
    <button id="btn-insert" type="button" class="dt-button button-purple">Thêm</button>    
</div>
<?php } if($id && $status == "Read") {?>
    <?php
        $sql_get_all = "select * from coupon where id = ? limit 1";
        $result = fetch_row($sql_get_all,[$id]);    
    ?>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th class='w-200'>Mã khuyến mãi</th>
                <td><?=$result['coupon_code'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Nội dung mô tả</th>
                <td><?=$result['coupon_content'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Khuyến mãi (đơn vị %)</th>
                <td><?=$result['coupon_discount_percent']."%";?></td>
            </tr>
            <tr>
                <th class='w-200'>Số tiền tối thiểu</th>
                <td><?=$result['coupon_if_subtotal_min'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Số tiền tối đa</th>
                <td><?=$result['coupon_if_subtotal_max'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian bắt đầu</th>
                <td><?=Date("d-m-Y",strtotime($result['coupon_date_start']));?></td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian kết thúc</th>
                <td><?=Date("d-m-Y",strtotime($result['coupon_date_end']));?></td>
            </tr>
            <tr>
                <th class='w-200'>Tình trạng</th>
                <td><?=$result['is_active'] == 1 ? "Đang kích hoạt" : "Chưa kích hoạt";?></td>
            </tr>
            <tr>
                <th class='w-200'>Ngày tạo</th>
                <td><?=Date("d-m-Y",strtotime($result['coupon_date_end']));?></td>
            </tr>
        </table>
    </div>
<?php } if($status == "read_more") {
    $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
    $html = "";
    if($str_arr_upt) {
        $sql = "select * from coupon where id in ($str_arr_upt)";
        $result2 = fetch_all(sql_query($sql));
        $i = 1;
        foreach($result2 as $res) {
            $html .= "<tbody style='display:none;' class='t-bd-read t-bd-read-$i'>
            <tr>
                <th class='w-200'>Mã khuyến mãi</th>
                <td>" . $res['coupon_code'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Nội dung mô tả</th>
                <td>" . $res['coupon_content'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Khuyến mãi (đơn vị %)</th>
                <td>" . $res['coupon_discount_percent'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Số tiền tối thiểu</th>
                <td>" . $res['coupon_if_subtotal_min'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Số tiền tối đa</th>
                <td>" . $res['coupon_if_subtotal_max'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian bắt đầu</th>
                <td>" . Date('d-m-Y',strtotime($res['coupon_date_start'])) . "</td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian kết thúc</th>
                <td>" . Date('d-m-Y',strtotime($res['coupon_date_end'])) . "</td>
            </tr>
            <tr>
                <th class='w-200'>Tình trạng</th>
                <td>" . ($res['is_active'] == 1 ? "Đang kích hoạt" : "Chưa kích hoạt") . "</td>
            </tr>
            <tr>
                <th class='w-200'>Ngày tạo</th>
                <td>" . Date("d-m-Y H:i:s",strtotime($res['created_at'])) . "</td>
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
}

?>
