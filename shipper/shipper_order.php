<?php
    include_once("../lib/database.php");
    include_once("include/login_fail_redirect.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $shipper_id = $_SESSION['shipper_id'];
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : "";
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : "";
        $shipping_status = isset($_REQUEST['shipping_status']) ? $_REQUEST['shipping_status'] : "";
        $delivery_date = isset($_REQUEST['delivery_date']) ? $_REQUEST['delivery_date'] : "";
        $delivery_complete_date = isset($_REQUEST['delivery_complete_date']) ? $_REQUEST['delivery_complete_date'] : "";
        $where = "where 1=1 and o.shipper_id = '$shipper_id'";
        if($keyword) {
            if($search_option) {
                if($search_option == 'orders_code') {
                    $where .= " and (lower(o.orders_code) like lower('%$keyword%'))";
                } else if($search_option == 'address') {
                    $where .= " and (lower(o.address) like lower('%$keyword%'))";
                } else if($search_option == 'full_name') {
                    $where .= " and (lower(u.full_name) like lower('%$keyword%'))";    
                } else if($search_option == 'phone') {
                    $where .= " and (lower(u.phone) like lower('%$keyword%'))";
                } else if($search_option == 'all') {
                    $where .= " and (lower(o.orders_code) like lower('%$keyword%') or lower(o.address) like lower('%$keyword%') or lower(u.full_name) like lower('%$keyword%') or lower(u.phone) like lower('%$keyword%'))";
                }
            }
        }
        if($shipping_status) {
            $where .= " and o.delivery_status_id = '$shipping_status'";
        }
        if($delivery_date) {
            $delivery_date = Date("Y-m-d",strtotime($delivery_date));
            $where .= " and (o.delivery_date >= '$delivery_date 00:00:00' and o.delivery_date <= '$delivery_date 23:59:59')";
        }
        if($delivery_complete_date) {
            $delivery_complete_date = Date("Y-m-d",strtotime($delivery_complete_date));
            $where .= " and (o.delivery_complete_date >= '$delivery_complete_date 00:00:00' and o.delivery_complete_date <= '$delivery_complete_date 23:59:59')";
        }
        log_v($where);
?>
<!--html & css section start-->
<div class="content-wrapper" style="margin-left:290px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 kh-padding">
                <div class="col-sm-6">
                    <h1 style="font-weight:bold;color:#d9585c;" class="m-0">Trang danh sách đơn giao hàng</h1>
                </div>
            </div>
            <hr style="">
        </div> 
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row kh-padding">
                <div class="col-12 search">
                    <form action="shipper_order.php" method="get" class="d-flex a-center">
                        <label for="" style="margin-bottom:0;" class="">Tìm kiếm theo cột:</label>
                        <select name="search_option" class="form-control ml-10" style="width:180px;">
                            <option value="">Chọn cột tìm kiếm</option>
                            <option value="orders_code" <?=$search_option == 'orders_code' ? "selected" : "";?>>Mã hoá đơn</option>
                            <option value="address" <?=$search_option == 'address' ? "selected" : "";?>>Địa chỉ giao hàng</option>
                            <option value="full_name" <?=$search_option == 'utomer_name' ? "selected" : "";?>>Khách hàng</option>
                            <option value="phone" <?=$search_option == 'utomer_phone' ? "selected" : "";?>>Số điện thoại khách hàng</option>
                            <option value="all" <?=$search_option == 'all' ? "selected" : "";?>>Tất cả</option>
                        </select>
                        <input type="text" name="keyword" class="form-control ml-10" placeholder="Nhập từ khoá tìm kiếm" value="<?=$keyword;?>" style="width:200px;">
                        <label for="" class="ml-15 " style="margin-bottom:0;">Ngày bắt đầu giao hàng:</label>
                        <input type="text" class="form-control ml-10 kh-datepicker" name="delivery_date" placeholder="Ngày bắt đầu" style="width:120px;" value="<?=$delivery_date ? Date("d-m-Y",strtotime($delivery_date)) : "";?>">

                        <label for="" class="ml-15" style="margin-bottom:0;">Ngày hoàn tất giao hàng:</label>
                        <input type="text" class="form-control ml-10 kh-datepicker" name="delivery_complete_date" placeholder="Ngày kết thúc" style="width:120px;" value="<?=$delivery_complete_date ? Date("d-m-Y",strtotime($delivery_complete_date)) : "";?>">

                        <label for="" class="ml-30" style="margin-bottom:0;">Theo trạng thái đơn hàng:</label>
                        <select name="shipping_status" class="form-control ml-10" style="width:240px;">
                            <option value="">Chọn trạng thái đơn hàng</option>
                            <?php
                                $sql_delivery_status = "select * from delivery_status where id > 1";
                                $rows_delivery = fetch_all(sql_query($sql_delivery_status));
                                foreach($rows_delivery as $row) {
                            ?>
                                    <option value="<?=$row['id']?>" <?=$shipping_status == $row['id'] ? "selected" : "";?>><?=$row['delivery_status_name'];?></option>
                            <?php 
                                }
                            ?>
                        </select>
                        <button class="btn btn-default ml-10">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-12 mt-15">
                    <?php
                        // set get
                        $get = $_GET;
                        unset($get['page']);
                        $str_get = http_build_query($get);
                        // query
                        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                        $limit = $_SESSION['shipper_paging'];
                        $start_page = $limit * ($page - 1);
                        $sql_get_total = "select count(*) as 'countt' from orders o inner join user u on o.customer_id = u.id $where";
                        $total = fetch(sql_query($sql_get_total))['countt'];
                        $sql_get_shipping_orders = "select o.id as 'o_id',o.delivery_status_id as 'o_delivery_status_id', o.orders_code as 'o_orders_code',o.address as 'o_address',o.delivery_date as 'o_delivery_date',o.delivery_complete_date as 'o_delivery_complete_date',u.full_name as 'u_full_name',u.phone as 'u_phone',o.created_at as 'o_created_at' from orders o inner join user u on o.customer_id = u.id $where limit $start_page,$limit";
                        $cnt=0;
                        $rows = fetch_all(sql_query($sql_get_shipping_orders));
                    ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="w-300">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(count($rows) > 0) {
                                foreach($rows as $row) {
                            ?>
                                <tr>
                                    <td><?=$total - ($cnt + $start_page);?></td>
                                    <td><?=$row['o_orders_code'];?></td>
                                    <td>
                                        <?=$row['o_address'];?>
                                    </td>
                                    <td>
                                    <?php
                                        $delivery_status_id = $row['o_delivery_status_id'];
                                        $sql_get_delivery_status = "select * from delivery_status where id = $delivery_status_id limit 1";
                                        $delivery_status = fetch(sql_query($sql_get_delivery_status));
                                        echo $delivery_status['delivery_status_name'];
                                        if($delivery_status['id'] < 7) {
                                    ?>
                                    <button class="ml-10 dt-button" onclick="openModalDeliveryStatus('<?=$row['o_id'];?>')">Cập nhật</button>
                                    <?php
                                        }
                                    ?>
                                    </td>
                                    <td><?=Date("d-m-Y",strtotime($row['o_created_at']));?></td>
                                    <td>
                                        <button onclick="openModalRead('<?=$row['o_id'];?>')" class="dt-button button-grey"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            <?php
                                    $cnt++;
                                } 
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="d-flex j-center" class="mt-15">
                        <ul id="pagination" class="pagination">
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="modal-xl" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thông tin đơn hàng</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12 ship">

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Cập nhật trạng thái vận chuyển</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12 ship-status">

        </div>
      </div>
    </div>
  </div>
</div>
<!--html & css section end-->


<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script>
    function openModalRead(id){
        $('.ship').load(`ajax_shipper_order.php?status=load_order&order_id=${id}`,function(){
            $('#modal-xl').modal('show');
        })
    }
    function openModalDeliveryStatus(id) {
        $('.ship-status').load(`ajax_shipper_order.php?status=update_delivery_status&order_id=${id}`,function(){
            $('#modal-xl2').modal('show');
        })
    }
    function updateOrderStatus(id){
        $('p.text-danger').text("");
        let test = true;
        let delivery_status_id = $('select[name="delivery_status_id"] > option:selected').val();
        let reason = $('textarea[name="reason"]').val();
        if(delivery_status_id == "") {
            $('#delivery_status_id_err').text("Không để trống trạng thái vận chuyển");
            test = false;
        } 
        if(reason == "") {
            $('#reason_err').text("Không để trống ghi chú đơn hàng");
            test = false;
        }
        if(test) {
            $.ajax({
                url:window.location.href,
                type:"POST",
                data:{
                    status:"update_delivery_status",
                    order_id:id,
                    reason:reason,
                    delivery_status_id:delivery_status_id,
                },success:function(data){
                    console.log(data);
                    data = JSON.parse(data);
                    
                    if(data.msg == "ok") {
                        $.alert({
                            title:"Thông báo",
                            content:"Xử lý thành công",
                            "buttons":{
                                "Ok":function(){
                                    location.reload();
                                }
                            }
                        })
                    }
                }
            })
        }
    }
</script>
<script>
    $(".kh-datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
    })
</script>
<script>
   $(function() {
    $('#pagination').pagination({
        items: <?=$total;?>,
        itemsOnPage: <?=$limit;?>,
		currentPage: <?=$page;?>,
		hrefTextPrefix: "<?php echo '?page='; ?>",
		hrefTextSuffix: "<?php echo '&' . $str_get;?>",
        prevText: "<",
        nextText: ">",
		onPageClick: function(){
			//window.location.href=""
		},
        cssStyle: 'light-theme'
    });
  });
</script>

<!--js section end-->
<?php
    include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        $order_id = isset($_REQUEST["order_id"]) ? $_REQUEST["order_id"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $delivery_status_id = isset($_REQUEST["delivery_status_id"]) ? $_REQUEST["delivery_status_id"] : null;
        $reason = isset($_REQUEST["reason"]) ? $_REQUEST["reason"] : null;
        if($status == "update_delivery_status") {
            //7: thanh cong,8:huy don
            if($delivery_status_id == 7) {
                $delivery_complete_date = Date('Y-m-d h:i:s',time());
                $sql_upt_complete_date = "Update orders set delivery_complete_date = ?,payment_status_id = ? where id = $order_id";
                sql_query($sql_upt_complete_date,[$delivery_complete_date,1]);
            } else if($delivery_status_id == 8) {

            }
            $sql_ins_history = "Insert into orders_delivery_status(order_id,delivery_status_id,reason) values(?,?,?)";
            sql_query($sql_ins_history,[$order_id,$delivery_status_id,$reason]);
            //
            $sql_upt_order = "Update orders set delivery_status_id = ? where id = ?";
            sql_query($sql_upt_order,[$delivery_status_id,$order_id]);
            //
            echo_json(["msg" => "ok"]);
        }
        // code to be executed post method
    }
?>