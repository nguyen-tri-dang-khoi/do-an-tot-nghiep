<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    $order_id = isset($_REQUEST["order_id"]) ? $_REQUEST["order_id"] : null;
    $str_arr_upt = isset($_REQUEST["str_arr_upt"]) ? $_REQUEST["str_arr_upt"] : null;
    if($status == "show_list_payment") {
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Số thứ tự</th>
            <th>Tên phương thức thanh toán</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = "select * from payment_method";
            $sql_get_count = "select count(*) as 'cnt' from payment_method";
            $count = fetch(sql_query($sql_get_count))['cnt'];
            $rows = fetch_all(sql_query($sql));
            $cnt = 0;
            foreach($rows as $row) {
        ?>
        <tr>
            <td><?=$count - $cnt;?></td>
            <td><?=$row['payment_name']?></td>
            <td><?=$row['is_active'] == 1 ? "Hoạt động" : "Ngưng hoạt động";?></td>
            <td>
                <?php if($row['is_active'] == 1) {?>
                <button onclick="changeActivePayment()" data-active="y" data-id="<?=$row['id'];?>" class="dt-button button-red">Inactive</button>
                <?php } else {?>
                <button onclick="changeActivePayment()" data-active="n" data-id="<?=$row['id'];?>" class="dt-button button-green">Active</button>
                <?php }?>
            </td>
        </tr>
        <?php $cnt++; } ?>
    </tbody>
</table>
<?php
    } else if($status == "show_order_detail") {
        $sql_get_client_order = "select c.full_name,c.phone,c.email,c.birthday, c.address as 'c_address',o.orders_code,o.address as 'o_address', o.total,o.payment_status,o.note,o.created_at as 'o_created_at',pm.payment_name from orders o inner join customer c on
        c.id = o.customer_id inner join payment_method pm on o.payment_method_id = pm.id where o.id = '$order_id' limit 1";
        $sql_get_detail_order = "select pi.name as 'pi_name', od.count as 'od_count', od.price as 'od_price' from order_detail od inner join product_info pi on od.product_info_id = pi.id where od.order_id = '$order_id'";
        $client_order = fetch(sql_query($sql_get_client_order));
        $detail_order = fetch_all(sql_query($sql_get_detail_order));
?>
    <div class="row">
        <div class="col-md-6">
            <h4>Thông tin khách hàng</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Họ tên:</th>
                    <td><?=$client_order['full_name'];?></td>
                </tr>
                <tr>
                    <th>Email: </th>
                    <td><?=$client_order['email'];?></td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td><?=$client_order['phone'];?></td>
                </tr>
                <tr>
                    <th>Ngày sinh:</th>
                    <td><?=$client_order['birthday'] ? Date("d-m-Y",strtotime($client_order['birthday'])) : "Chưa có thông tin";?></td>
                </tr>
                <tr>
                    <th>Địa chỉ:</th>
                    <td><?=$client_order['c_address'];?></td>
                </tr>
                
            </table>
        </div>
        <div class="col-md-6">
            <h4>Thông tin đơn hàng</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Mã hoá đơn</th>
                    <td><?=$client_order['orders_code'];?></td>
                </tr>
                <tr>
                    <th>Địa chỉ giao hàng</th>
                    <td><?=$client_order['o_address'] == $client_order['c_address'] ? "Trùng với địa chỉ khách hàng" : $client_order['o_address'];?></td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td><?=number_format($client_order['total'],0,"",".") . "đ";?></td>
                </tr>
                <tr>
                    <th>Tình trạng thanh toán</th>
                    <td><?=$client_order['payment_status'] == 1 ? "Đã thanh toán" : "Chưa thanh toán";?></td>
                </tr>
                <tr>
                    <th>Phương thức thanh toán</th>
                    <td><?=$client_order['payment_name']?></td>
                </tr>
                <tr>
                    <th>Ghi chú:</th>
                    <td><?=$client_order['note'] ? $client_order['note'] : "Không có";?></td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?=$client_order['o_created_at']?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>Chi tiết đơn hàng</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Số tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($detail_order as $row) {
                    ?>
                    <tr>
                        <td><?=$row['pi_name']?></td>
                        <td><?=$row['od_count']?></td>
                        <td><?=number_format($row['od_price'],0,"",".") . "đ";?></td>
                        <td><?=number_format($row['od_count'] * $row['od_price'],0,"",".") . "đ"?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
    } else if($status == "show_order_detail_fast") {
        $arr = explode(",",$str_arr_upt);
        $i = 1;
        foreach($arr as $order_id) {
            $sql_get_client_order = "select c.full_name,c.phone,c.email,c.birthday, c.address as 'c_address',o.orders_code,o.address as 'o_address', o.total,o.payment_status,o.note,o.created_at as 'o_created_at',pm.payment_name from orders o inner join customer c on
            c.id = o.customer_id inner join payment_method pm on o.payment_method_id = pm.id where o.id = '$order_id' limit 1";
            $sql_get_detail_order = "select pi.name as 'pi_name', od.count as 'od_count', od.price as 'od_price' from order_detail od inner join product_info pi on od.product_info_id = pi.id where od.order_id = '$order_id'";
            $client_order = fetch(sql_query($sql_get_client_order));
            $detail_order = fetch_all(sql_query($sql_get_detail_order));
?>
    <div class="t-row t-row-<?=$i;?>" style="display:none;">
        <div class="row">
            <div class="col-md-6">
                <h4>Thông tin khách hàng</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Họ tên:</th>
                        <td><?=$client_order['full_name'];?></td>
                    </tr>
                    <tr>
                        <th>Email: </th>
                        <td><?=$client_order['email'];?></td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td><?=$client_order['phone'];?></td>
                    </tr>
                    <tr>
                        <th>Ngày sinh:</th>
                        <td><?=$client_order['birthday'] ? Date("d-m-Y",strtotime($client_order['birthday'])) : "Chưa có thông tin";?></td>
                    </tr>
                    <tr>
                        <th>Địa chỉ:</th>
                        <td><?=$client_order['c_address'];?></td>
                    </tr>
                    
                </table>
            </div>
            <div class="col-md-6">
                <h4>Thông tin đơn hàng</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Mã hoá đơn</th>
                        <td><?=$client_order['orders_code'];?></td>
                    </tr>
                    <tr>
                        <th>Địa chỉ giao hàng</th>
                        <td><?=$client_order['o_address'] == $client_order['c_address'] ? "Trùng với địa chỉ khách hàng" : $client_order['o_address'];?></td>
                    </tr>
                    <tr>
                        <th>Tổng tiền</th>
                        <td><?=number_format($client_order['total'],0,"",".") . "đ";?></td>
                    </tr>
                    <tr>
                        <th>Tình trạng thanh toán</th>
                        <td><?=$client_order['payment_status'] == 1 ? "Đã thanh toán" : "Chưa thanh toán";?></td>
                    </tr>
                    <tr>
                        <th>Phương thức thanh toán</th>
                        <td><?=$client_order['payment_name']?></td>
                    </tr>
                    <tr>
                        <th>Ghi chú:</th>
                        <td><?=$client_order['note'] ? $client_order['note'] : "Không có";?></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td><?=$client_order['o_created_at']?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Chi tiết đơn hàng</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Số tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($detail_order as $row) {
                        ?>
                        <tr>
                            <td><?=$row['pi_name']?></td>
                            <td><?=$row['od_count']?></td>
                            <td><?=number_format($row['od_price'],0,"",".") . "đ";?></td>
                            <td><?=number_format($row['od_count'] * $row['od_price'],0,"",".") . "đ"?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
        $i++;
    ?>
<?php   }?>
<?php }?>