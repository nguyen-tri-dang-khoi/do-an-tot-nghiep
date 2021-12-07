<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    $order_id = isset($_REQUEST["order_id"]) ? $_REQUEST["order_id"] : null;
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
        $sql_get_client_order = "select c.full_name,c.phone, c.address,o.order_code, o.total,o.payment_status,o.note,o.created_at,pm.payment_name from orders inner join customer c on
        c.id = o.customer_id inner join payment_method pm on o.payment_method_id = pm.id where o.id = '$order_id' limit 1";
        $sql_get_detail_order = "select pi.name, od.count, od.price from order_detail inner join product_info pi on od.product_info_id = pi.id";
        $client_order = fetch(sql_query($sql_get_client_order));
        $detail_order = fetch_all(sql_query($sql_get_detail_order));
?>

<?php
    }
?>