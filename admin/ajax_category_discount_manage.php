<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Update") {
        $sql_get_all = "select * from product_type_discount where id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));
?>
<div class="card-body">
    <div class="row" style="margin-left:0px;flex-direction:column;">
        <label for="danh_muc">Danh mục sản phẩm</label>
        <div style="display:flex;flex-direction:row;align-items:center;">
            <ul tabindex="1" class="col-md-6" style="padding-left:0px;height: 65px;" id="menu">
                <li class="parent" style="border: 1px solid #dce1e5;position:relative;">
                    <a href="#">Chọn danh mục</a>
                    <ul class="child" >
                        <?php echo show_menu();?>
                    </ul>
                </li>
            </ul>
            <nav id="breadcrumb-menu" class="col-md-6" aria-label="breadcrumb">
                <?=generate_breadcrumb_menus($result['product_type_id']);?>
            </nav>
        </div>
        <input type="hidden" name="product_type_id" value="<?=$result['product_type_id'];?>">
        <div id="product_type_id_err" class="text-danger" style="margin-top:-10px;"></div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="discount_percent">Khuyến mãi (đơn vị %) :</label>
            <input min="1" max="99" type="number" name="discount_percent" placeholder="Nhập giá trị khuyến mãi..." value="<?=$result['discount_percent'] ? $result['discount_percent'] : "";?>" class="form-control">
            <div id="discount_percent_err" class="text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="discount_content">Nội dung mô tả:</label>
            <textarea class="form-control" name="discount_content" placeholder="Nội dung mô tả..."><?=$result['discount_content'] ? $result['discount_content'] : "";?></textarea>
            <div id="discount_content_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="date_start">Thời gian bắt đầu :</label>
            <input type="text" name="date_start" placeholder="Nhập ngày bắt đầu..."  value="<?=$result['date_start'] ? Date('d-m-Y',strtotime($result['date_start'])) : "";?>" class="form-control is-date-coupon-start">
            <div id="date_start_err" class="text-danger"></div>
        </div>
        <div class="col-md-3 form-group">
            <label for="date_end">Thời gian kết thúc :</label>
            <input type="text" name="date_end" placeholder="Nhập ngày kết thúc..." value="<?=$result['date_end'] ? Date('d-m-Y',strtotime($result['date_end'])) : "";?>" class="form-control is-date-coupon-end">
            <div id="date_end_err" class="text-danger"></div>
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
    <div class="row" style="margin-left:0px;flex-direction:column;">
        <label for="danh_muc">Danh mục sản phẩm</label>
        <div style="display:flex;flex-direction:row;">
            <ul tabindex="1" class="col-md-6" style="padding-left:0px;height:auto;" id="menu">
                <li class="parent" style="border: 1px solid #dce1e5;position:relative">
                    <a href="#">Chọn danh mục</a>
                    <ul class="child" style="">
                        <?php echo show_menu();?>
                    </ul>
                </li>
            </ul>
            <nav id="breadcrumb-menu" class="col-md-6" aria-label="breadcrumb"></nav>
        </div>
        <input type="hidden" name="product_type_id" value="">
        <input type="hidden" name="category_name" value="">
        <div id="product_type_id_err" class="text-danger"></div>
    </div>
    <div class="row mt-15">
        <div class="col-md-6 form-group">
            <label for="discount_percent">Khuyến mãi (đơn vị %) :</label>
            <input min="1" max="99" type="number" name="discount_percent" class="form-control" placeholder="Nhập giá trị khuyến mãi...">
            <div id="discount_percent_err" class="text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="discount_content">Nội dung mô tả:</label>
            <textarea class="form-control" name="discount_content" placeholder="Nội dung mô tả..."></textarea>
            <div id="discount_content_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="date_start">Thời gian bắt đầu :</label>
            <input type="text" name="date_start" class="form-control is-date-coupon-start" placeholder="Nhập ngày bắt đầu...">
            <div id="date_start_err" class="text-danger"></div>
        </div>
        <div class="col-md-3 form-group">
            <label for="date_end">Thời gian kết thúc :</label>
            <input type="text" name="date_end" class="form-control is-date-coupon-end" placeholder="Nhập ngày kết thúc...">
            <div id="date_end_err" class="text-danger"></div>
        </div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<div class="card-footer">
    <button id="btn-insert" type="button" class="dt-button button-purple">Thêm</button>    
</div>
<?php } if($id && $status == "Read") {?>
    <?php
        $sql_get_all = "select * from product_type_discount where id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));
    ?>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th class='w-200'>Danh mục khuyến mãi</th>
                <td class="width-auto">
                    <nav id="breadcrumb-menu" class="" aria-label="breadcrumb">
                        <?=generate_breadcrumb_menus($result['product_type_id']);?>
                    </nav>
                </td>
            </tr>
            <tr>
                <th class='w-200'>Nội dung mô tả</th>
                <td><?=$result['discount_content'];?></td>
            </tr>
            <tr>
                <th class='w-200'>Khuyến mãi (đơn vị %)</th>
                <td><?=$result['discount_percent']."%";?></td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian bắt đầu</th>
                <td><?=Date("d-m-Y",strtotime($result['date_start']));?></td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian kết thúc</th>
                <td><?=Date("d-m-Y",strtotime($result['date_end']));?></td>
            </tr>
            <tr>
                <th class='w-200'>Tình trạng</th>
                <td><?=$result['is_active'] == 1 ? "Đang kích hoạt" : "Chưa kích hoạt";?></td>
            </tr>
            <tr>
                <th class='w-200'>Ngày tạo</th>
                <td><?=Date("d-m-Y",strtotime($result['created_at']));?></td>
            </tr>
        </table>
    </div>
<?php } if($status == "read_more") {
    $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
    $html = "";
    if($str_arr_upt) {
        $sql = "select * from product_type_discount where id in ($str_arr_upt)";
        $result2 = fetch_all(sql_query($sql));
        $i = 1;
        foreach($result2 as $res) {
            $html .= "<tbody style='display:none;' class='t-bd-read t-bd-read-$i'>
            <tr>
                <th class='w-200'>Danh mục khuyến mãi</th>
                <td><nav id='breadcrumb-menu' class='' aria-label='breadcrumb'>" .  generate_breadcrumb_menus($res['product_type_id']) . "</nav></td>
            </tr>
            <tr>
                <th class='w-200'>Nội dung mô tả</th>
                <td>" . $res['discount_content'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Khuyến mãi (đơn vị %)</th>
                <td>" . $res['discount_percent'] . "</td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian bắt đầu</th>
                <td>" . Date('d-m-Y',strtotime($res['date_start'])) . "</td>
            </tr>
            <tr>
                <th class='w-200'>Thời gian kết thúc</th>
                <td>" . Date('d-m-Y',strtotime($res['date_end'])) . "</td>
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
