<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Update") {
        $sql_get_all = "select * from coupon where id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));
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
            <label for="coupon_discount_percent">Khuyến mãi (đơn vị %):</label>
            <input min="1" max="99" placeholder="Nhập khuyến mãi (1 - 100%)..." type="number" name="coupon_discount_percent" value="<?=$result['coupon_discount_percent'] ? $result['coupon_discount_percent'] : "";?>" class="form-control">
            <div id="coupon_discount_percent_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_min">Số tiền tối thiểu:</label>
            <input onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" type="text" name="coupon_if_subtotal_min" placeholder="Nhập điều kiện tổng tiền hoá đơn tối thiểu..." value="<?=$result['coupon_if_subtotal_min'] ? number_format($result['coupon_if_subtotal_min'],0,".",".") : "";?>" class="form-control">
            <div id="coupon_if_subtotal_min_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_max">Số tiền tối đa:</label>
            <input onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" type="text" name="coupon_if_subtotal_max" placeholder="Nhập điều kiện tổng tiền hoá đơn tối đa..." value="<?=$result['coupon_if_subtotal_max'] ? number_format($result['coupon_if_subtotal_max'],0,".",".") : "";?>" class="form-control">
            <div id="coupon_if_subtotal_max_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="coupon_date_start">Thời gian bắt đầu:</label>
            <input type="text" placeholder="Nhập thời gian bắt dầu khuyến mãi..." name="coupon_date_start" value="<?=$result['coupon_date_start'] ? Date('d-m-Y',strtotime($result['coupon_date_start'])) : "";?>" class="form-control is-date-coupon-start">
            <div id="coupon_date_start_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_date_end">Thời gian kết thúc:</label>
            <input placeholder="Nhập thời gian kết thúc khuyến mãi..." type="text" name="coupon_date_end"  value="<?=$result['coupon_date_end'] ? Date('d-m-Y',strtotime($result['coupon_date_end'])) : "";?>" class="form-control is-date-coupon-end">
            <div id="coupon_date_end_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button id="btn-update" onclick="processModalUpdate()" type="button" class="dt-button button-purple">Sửa</button>
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
            <input min="1" max="99" type="number" name="coupon_discount_percent" class="form-control" placeholder="Nhập khuyến mãi (1 - 100%)...">
            <div id="coupon_discount_percent_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_min">Số tiền tối thiểu:</label>
            <input onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" type="text" name="coupon_if_subtotal_min" class="form-control" placeholder="Nhập điều kiện tổng tiền hoá đơn tối thiểu...">
            <div id="coupon_if_subtotal_min_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_if_subtotal_max">Số tiền tối đa:</label>
            <input onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" type="text" name="coupon_if_subtotal_max" class="form-control" placeholder="Nhập điều kiện tổng tiền hoá đơn tối đa...">
            <div id="coupon_if_subtotal_max_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="coupon_date_start">Thời gian bắt đầu:</label>
            <input type="text" name="coupon_date_start" class="form-control is-date-coupon-begin" placeholder="Nhập thời gian bắt dầu khuyến mãi...">
            <div id="coupon_date_start_err" class="coupon-validate text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="coupon_date_end">Thời gian kết thúc:</label>
            <input type="text" name="coupon_date_end" class="form-control is-date-coupon-end" placeholder="Nhập thời gian kết thúc khuyến mãi...">
            <div id="coupon_date_end_err" class="coupon-validate text-danger"></div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button id="btn-insert" onclick="processModalInsert()" type="button" class="dt-button button-purple">Thêm</button>    
</div>
<?php } if($id && $status == "Read") {?>
    <?php
        $sql_get_all = "select * from coupon where id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));    
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
                <td><?=number_format($result['coupon_if_subtotal_min'],0,".",".");?></td>
            </tr>
            <tr>
                <th class='w-200'>Số tiền tối đa</th>
                <td><?=number_format($result['coupon_if_subtotal_max'],0,".",".");?></td>
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
                <td>" . number_format($res['coupon_if_subtotal_min'],0,".",".") . "</td>
            </tr>
            <tr>
                <th class='w-200'>Số tiền tối đa</th>
                <td>" . number_format($res['coupon_if_subtotal_max'],0,".",".") . "</td>
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
