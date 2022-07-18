<?php
    include_once("../lib/database.php");
    $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : null;
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $shipper_id = isset($_SESSION['shipper_id']) ? $_SESSION['shipper_id'] : null;
    if($shipper_id) {
        if($status == "load_order") {
            $sql_get_order_info = "select ods.orders_code as 'ods_orders_code',ods.delivery_complete_date as 'ods_delivery_complete_date',ods.delivery_date as 'ods_delivery_date',ods.total as 'ods_total',u.phone as 'u_phone',u.full_name as 'u_full_name',ods.address as 'ods_address' from orders ods inner join user u on ods.customer_id = u.id where ods.id = ? and ods.shipper_id = ? and u.type = 'customer' limit 1";
            $row = fetch(sql_query($sql_get_order_info,[$order_id,$shipper_id]));
            
            $sql_get_order_detail = "select pi.name as 'pi_name',od.count as 'od_count',od.price as 'od_price' from orders ods inner join order_detail od on ods.id = od.order_id inner join product_info pi on od.product_info_id = pi.id where ods.id = ? and ods.shipper_id = ?";
            $rows_order_detail = fetch_all(sql_query($sql_get_order_detail,[$order_id,$shipper_id]));

            $sql_get_history_shipping = "select ds.delivery_status_name,odss.reason as 'odss_reason' ,ds.created_at as 'ds_created_at' from orders ods inner join orders_delivery_status odss on ods.id = odss.order_id  inner join delivery_status ds on odss.delivery_status_id = ds.id where ods.id = ? and ods.shipper_id = ?";
            $rows_history_shipping = fetch_all(sql_query($sql_get_history_shipping,[$order_id,$shipper_id]));
           
?>
<div class="row">
    <div class="col-12">
        <h3>Thông tin tổng quan</h3>
        <table class='table table-bordered'>
            <tbody>
                <tr>
                    <th class='w-170'>Mã đơn hàng</th>
                    <td><?=$row['ods_orders_code'];?></td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td><?=number_format($row['ods_total'],0,".",".")."đ";?></td>
                </tr>
                <tr>
                    <th>Tên khách hàng</th>
                    <td><?=$row['u_full_name'];?></td>
                </tr>
                <tr>
                    <th>Địa chỉ giao hàng</th>
                    <td><?=$row['ods_address'];?></td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td><?=$row['u_phone'];?></td>
                </tr>
                <tr>
                    <th>Ngày giao hàng</th>
                    <td><?=Date('d-m-Y',strtotime($row['ods_delivery_date']));?></td>
                </tr>
                <tr>
                    <th>Ngày hoàn tất đơn hàng</th>
                    <td><?=$row['ods_delivery_complete_date'] ? Date('d-m-Y',strtotime($row['ods_delivery_complete_date'])) : "Chưa có thông tin"; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <h3>Thông tin chi tiết đơn hàng</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên sản phấm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Số tiền</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($rows_order_detail as $row){
            ?>
                <tr>
                    <td><?=$row['pi_name']?></td>
                    <td><?=number_format($row['od_price'],0,".",".")."đ"?></td>
                    <td><?=number_format($row['od_count'],0,".",".")?></td>
                    <td><?=number_format($row['od_price'] * $row['od_count'],0,".",".")."đ"?></td>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>    
    </div>
    <div class="col-12">
        <h3>Lịch sử giao hàng</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Trạng thái đơn hàng</th>
                    <th>Ghi chú đơn hàng</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($rows_history_shipping as $row){
            ?>
                <tr>
                    <td><?=$row['delivery_status_name']?></td>
                    <td><?=$row['odss_reason']?></td>
                    <td><?=Date("d-m-Y h:i:s",strtotime($row['ds_created_at']))?></td>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    } else if($status == "update_delivery_status"){ 
?>
    <div class="card-body">
        <?php
            $sql_get_order_code = "select orders_code from orders where orders.id = $order_id limit 1";
           
        ?>
        <h3>Cập nhật trạng thái đơn hàng <?php echo fetch(sql_query($sql_get_order_code))['orders_code']; ?></h3>
        <hr>
        <div class="row">
           
            <div class="col-xl-4 col-md-6 col-sm-12 form-group">
                <label for="reason">Trạng thái vận chuyển</label>
                <select name="delivery_status_id" class="form-control">
                    <option value="">Chọn trạng thái vận chuyển</option>
                    <?php
                        $sql_delivery_status = "select delivery_status_id from orders where orders.id = $order_id limit 1";
                        $result_delivery_status = fetch(sql_query($sql_delivery_status))['delivery_status_id'];
                        if($result_delivery_status == "2") {
                            $sql_ds = "select ds.id as 'ds_id',ds.delivery_status_name from delivery_status ds where ds.id = 3 limit 1";
                            $rows_22 = fetch_all(sql_query($sql_ds));
                        } else if($result_delivery_status == "3") {
                            $sql_ds = "select ds.id as 'ds_id',ds.delivery_status_name from delivery_status ds where ds.id in (5,7) limit 2";
                            $rows_22 = fetch_all(sql_query($sql_ds));
                        } else if($result_delivery_status == "5") {
                            $sql_ds = "select ds.id as 'ds_id',ds.delivery_status_name from delivery_status ds where ds.id in (6,7) limit 2";
                            $rows_22 = fetch_all(sql_query($sql_ds));
                        } else if($result_delivery_status == "6") {
                            $sql_ds = "select ds.id as 'ds_id',ds.delivery_status_name from delivery_status ds where ds.id in (7,8) limit 2";
                            $rows_22 = fetch_all(sql_query($sql_ds));
                        }
                        foreach($rows_22 as $row) {
                    ?>
                    <option value="<?=$row['ds_id']?>"><?=$row['delivery_status_name']?></option>
                    <?php
                        }
                    ?>
                </select>
                <p id="delivery_status_id_err" class="text-danger"></p>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 form-group">
                <label for="reason">Ghi chú thông tin</label>
                <textarea class="form-control" name="reason"></textarea>
                <p id="reason_err" class="text-danger"></p>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button onclick="updateOrderStatus('<?php echo $order_id;?>')" id="btn-update" type="button" class="dt-button button-purple">Cập nhật trạng thái vận chuyển</button>
    </div>
<?php
    }
?>
<?php
    } else {
        header("Location:login.php");
    }
?>