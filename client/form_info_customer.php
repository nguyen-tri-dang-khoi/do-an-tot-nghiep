<?php 
    include_once 'db.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>
    <?php
        $_SESSION['customer_id'] = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
        if($_SESSION['customer_id']){
            $customer_id = $_SESSION['customer_id'];
            $conn = connect();
            $name = "";
            $sql_customer = "select * from user where type = 'customer' and id = '$customer_id' limit 1";
            $result = mysqli_query($conn, $sql_customer);
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                $name = $row['full_name'];
    ?>
            <div class="col-10 m-auto p-0 mt-0 mb-4 row d-flex">
                <form action="form_info_customer_process.php" method="post" onsubmit="return validate()" class="row d-flex col-6 mt-4 m-auto p-0">
                    <h3 class="p-0">Thông tin cá nhân</h3>    
                    <div class="col-12 mb-1 m-auto p-0">
                        <label for="full_name2" class="form-label">Họ và Tên</label>
                        <input name="full_name" type="text" value="<?php echo $row['full_name']; ?>" class="form-control" placeholder="Họ và tên ">
                        <p id="full_name_err" class="text-danger"></p>
                    </div>
                    <div class="col-md-12 mb-1 m-auto p-0">
                        <label for="inputEmail4" class="form-label">Email</label>
                        <input name="email" type="email" value="<?php echo $row['email']; ?>" class="form-control"  placeholder="abc@email.com">
                        <p id="email_err" class="text-danger"></p>
                    </div>
                    <div class="col-md-12 mb-1  m-auto p-0">
                        <label for="inputcontact" class="form-label">Số điện thoại</label>
                        <input name="phone" type="text" value="<?php echo ($row['phone'] ? $row['phone'] : "");?>" class="form-control"  placeholder="0123456xxx">
                        <p id="phone_err" class="text-danger"></p>
                    </div>
                    <div class="col-12 m-auto mb-1 p-0 d-flex flex-column">
                        <label for="inputBirth" class="form-label">Ngày sinh</label>
                        <input placeholder="dd-mm-yyyy" value="<?php echo ($row['birthday'] ? Date("Y-m-d",strtotime($row['birthday'])) : ""); ?>" disabled  style="border-radius: 5px;border: 1px solid #ced4da;padding: 2px 5px;" type="date" id="birthday" name="birthday" />
                        <span id="register_birthday_err" class="text-danger"></span> 
                    </div>
                    <div class="col-12 m-auto mb-1 p-0">
                        <label for="inputAddress" class="form-label">Địa chỉ</label>
                        <input name="address" type="text" value="<?php echo ($row['address'] ? $row['address'] : ""); ?>" class="form-control" placeholder="xxx Trần Xuân Soạn - Tân Thuận Tây - Quận 7 - HCM">
                        <p id="address_err" class="text-danger"></p>
                    </div>
                    <input type="hidden" name="thao_tac" value="updateInfo">
                    <div class="col-12 m-auto p-0">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
                <div class="col-6 mt-4">
                    <h3 class="p-0">Lịch sử mua hàng</h3>    
                    <?php
                        $sql_order = "select o.id as 'id_don_hang',o.delivery_status_id as 'o_delivery_status_id' ,o.is_cancel,pm.payment_name as 'pm_payment_name', orders_code,note,o.created_at,total,ps.payment_status_name as 'trang_thai_thanh_toan' from orders o inner join payment_status ps on o.payment_status_id = ps.id inner join payment_method pm on o.payment_method_id =  pm.id where o.customer_id = '$customer_id'";
                       // print_r($sql_order);
                        $result = mysqli_query($conn,$sql_order);
                        while($row11 = mysqli_fetch_assoc($result)) {
                           
                    ?>
                    <div class="history_order">
                        <table class="table table_order">
                            <thead class="theadd" style="cursor:pointer;">
                                <tr>
                                    <th scope="col">Mã đơn hàng</th>
                                    <th scope="col"><?php echo $row11['orders_code'];?></th>
                                </tr>
                            </thead>
                            <tbody class="hidden_table">
                                <tr>
                                    <th scope="row">Tổng tiền thanh toán</th>
                                    <td><?php echo number_format($row11['total'],0,".",".");?>đ</td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <th scope="row">Trạng thái thanh toán</th>
                                    <td><?php echo $row11['trang_thai_thanh_toan'];?></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <th scope="row">Phương thức thanh toán</th>
                                    <td><?php echo $row11['pm_payment_name'];?></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <th scope="row">Ghi chú đơn hàng</th>
                                    <td><?php echo $row11['note'] ? $row11['note'] : "Không có ghi chú";?></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                <th scope="row">Trạng thái đơn hàng mới nhất</th>
                                    <td>
                                        <?php
                                            $order_id_delivery = $row11['o_delivery_status_id'];
                                            if($row11['is_cancel'] == 1) {
                                                echo "Đã huỷ";
                                            } else if($row11['is_cancel'] == 0) {
                                                $sql_order_delivery = "select * from delivery_status where id = $order_id_delivery limit 1";
                                                $row_delivery = mysqli_query($conn,$sql_order_delivery);
                                                $row_delivery = mysqli_fetch_array($row_delivery);
                                                echo $row_delivery['delivery_status_name'];
                                            }
                                        ?>
                                       
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <th scope="row">Ngày đặt hàng</th>
                                    <td><?php echo Date("d-m-Y",strtotime($row11['created_at']));?></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <th scope="row">Người mua hàng</th>
                                    <td><?php echo $name;?></td>
                                    <!-- <td></td> -->
                                </tr>
                                <?php
                                    if($row11['is_cancel'] == 0) {
                                ?>
                                <tr><th scope="col"><button class="w-100" onclick="cancelOrder('<?php echo $row11['id_don_hang'];?>')">Hủy đơn hàng</button></th></tr>
                                <?php
                                    }
                                ?>
                                <tr>
                                    <tr style="color:red;">
                                        <th style="width:400px;">Tên sản phẩm</th>
                                        <th style="width:100px;">Ảnh sản phẩm</th>
                                        <th style="width:100px;">Số lượng</th>
                                        <th style="width:100px;">Đơn giá</th>
                                        <th style="width:100px;">Số tiền</td>
                                    </tr>
                                    <?php
                                        $customer_id = $_SESSION['customer_id'];
                                        $order_id = $row11['id_don_hang'];
                                        $sql_order_history = "Select pi.name as 'ten_san_pham',pi.img_name as 'pi_img', od.count as 'so_luong_mua', od.price as 'gia_mua' from orders o inner join order_detail od on o.id = od.order_id inner join product_info pi on od.product_info_id = pi.id where o.customer_id = '$customer_id' and o.id = $order_id";
                                        $result2 = mysqli_query($conn,$sql_order_history);
                                        while($row = mysqli_fetch_array($result2)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['ten_san_pham']?></td>
                                            <td>
                                                <img width="100" height="100" src="../admin/<?php echo $row['pi_img'];?>" alt="">
                                            </td>
                                            <td><?php echo $row['so_luong_mua']?></td>
                                            <td><?php echo number_format($row['gia_mua'],0,".",".");?>đ</td>
                                            <td><?php echo number_format(($row['so_luong_mua'] * $row['gia_mua']),0,".",".");?>đ</td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                </tr>
                               
                            </tbody>
                        </table>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
    <?php
            }
        }
    ?>
    
    <?php include_once ('include/footer.php'); ?>
    <script type = "text/javascript" src = "//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"> </script>
    <script>
        function validate(){
            let test = true;
            $('p.text-danger').text("");
            let full_name = $('input[name="full_name"]').val();
            let phone_reg = /^\d{10}$/;
            let email_reg = /^[A-Za-z0-9+_.-]+@(.+)/;
            let phone = $('input[name="phone"]').val();
            let email = $('input[name="email"]').val();
            let address = $('input[name="address"]').val();
            if(full_name == ""){
                $('#full_name_err').text("Tên đầy đủ không được để trống");
                test = false;
            } 
            if(phone == "") {
                $('#phone_err').text("Số điện thoại không được để trống");
                test = false;
            } else if(!phone.match(phone_reg)) {
                $('#phone_err').text("Số điện thoại không đúng định dạng");
                test = false;
            }

            if(email == "") {
                $('#email_err').text("Email không được để trống");
                test = false;
            } else if(!email.match(email_reg)) {
                $('#email_err').text("Email không đúng định dạng");
                test = false;
            }

            if(address == "") {
                $('#address_err').text("Địa chỉ không được để trống");
                test = false;
            }
            return test;
        }
        function cancelOrder(id){
            $.ajax({
                url:"cancel_order.php",
                type:"POST",
                data:{
                    thao_tac:"huy_don_hang",
                    "order_id": id,
                },success:function(data){
                    data = JSON.parse(data);
                    if(data.msg == "ok") {
                        location.reload();
                    }
                }
            })
        }
    </script>
    <?php include_once ('js/js_customIndex.php'); ?>
</body>
</html>
