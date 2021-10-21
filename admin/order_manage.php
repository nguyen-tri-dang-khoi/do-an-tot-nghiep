<?php
    include_once("../lib/database.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<style>
    table.dataTable span.highlight {
        background-color: #17a2b8;
        border-radius: 5px;
        text-align: center;
        color: white;
    }
</style>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<!-- Main content -->
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid">
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Quản lý đơn hàng</h3>
              </div>
              <div class="card-body">
                <div class="col-12" style="padding-right:0px;padding-left:0px;">
                  <form style="margin-bottom: 17px;display:flex;" action="<?php echo get_url_current_page();?>" method="get">
                      <div class="">
                        <select class="form-control" name="type">
                            <option value="">Loại tìm kiếm</option>
                            <option value="address">Địa chỉ</option>
                            <option value="all">Tất cả</option>
                        </select>
                      </div>
                      <div class="ml-10">
                        <select class="form-control" name="type">
                            <option value="">Tình trạng thanh toán</option>
                            <option value="payment_ok">Đã thanh toán</option>
                            <option value="payment_not_ok">Chưa thanh toán</option>
                        </select>
                      </div>
                      <div class="ml-10" style="display:flex;">
                        <input type="text" name="keyword" placeholder="Nhập từ khoá..." class="form-control">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                  </form>
                </div>
                <table id="m-order" class="table table-bordered table-hover">
                  <thead>
                    <tr>
					            <th>Số thứ tự</th>
                      <th>Mã hoá đơn</th>
                      <th>Tên người dùng</th>
                      <th>Địa chỉ nhận hàng</th>
                      <th>Tổng tiền</th>
                      <th>Tình trạng thanh toán</th>
                      <th>Ngày tạo kiện hàng</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody id="list-san-pham">
                  <?php
                    // set get
                    $get = $_GET;
                    unset($get['page']);
                    $str_get = http_build_query($get);
                    // query
                    $arr_paras = [];
                    $where = "where 1 = 1";
                    $keyword = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : null;
                    if($keyword) {
                        $where .= "";
                    }
                    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                    $limit = $_SESSION['paging'];
                    $start_page = $limit * ($page - 1);
                    $sql_get_total = "select count(*) as 'countt' from orders o inner join order_detail od on o.id = od.order_id inner join customer c on o.customer_id = c.id $where";
                    $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                    array_push($arr_paras,$start_page);
                    array_push($arr_paras,$limit);
                    $sql_get_order = "select * from orders o inner join order_detail od on o.id = od.order_id inner join customer c on o.customer_id = c.id limit ?,?";
                    $rows = db_query($sql_get_order,$arr_paras);
                    $i = 0;
				          	$cnt = 0;
                    foreach($rows as $row) {
                  ?>
                    <tr>
						            <td><?=$total - ($start_page + $cnt);?></td>
                        <td><?=$row['order_id']?></td>
                        <td><?=$row['full_name']?></td>
                        <td><?=$row['address']?></td>
                        <td><?=$row['total']?></td>
                        <?php
                          if($row['payment_status'] == 1) {
                        ?>
                            <td id="status-payment<?php echo $i;?>">Đã thanh toán</td>
                        <?php 
                            } else {
                        ?>
                            <td id="status-payment<?php echo $i;?>">Chưa thanh toán</td>
                        <?php 
                          }
                        ?>
                        <td><?=Date("d-m-Y H:i:s",strtotime($row['created_at']));?></td>
                        <td>
                            <button class="btn btn-secondary btn-xem-chi-tiet-hoa-don"
                            data-bill_id="<?=$row["order_id"];?>"
                            data-sum="<?=$row["total"];?>"
                            data-pay-status="<?=$row["payment_status"];?>"
                            >
                            Xem chi tiết hoá đơn
                            </button><br>
                            <button class="btn btn-info btn-xem-thong-tin-nguoi-dung" data-user_id="<?=$row["customer_id"];?>" data-id="<?=$row["order_id"];?>">
                            Xem thông tin người dùng
                            </button><br>
                            <button class="btn btn-success btn-cap-nhat-thanh-toan" data-pos="<?php echo $i;?>" data-user_id="<?=$row["customer_id"];?>" data-id="<?=$row["order_id"];?>">
                            Cập nhật đã thanh toán
                            </button>
                      </td>
                    </tr>
                  <?php
					            $cnt++;
                      $i++;
                    }
                  ?>
                  </tbody>
                  <tfoot>
                    <tr>
					            <th>Số thứ tự</th>
                      <th>Mã hoá đơn</th>
                      <th>Tên người dùng</th>
                      <th>Địa chỉ nhận hàng</th>
                      <th>Tổng tiền</th>
                      <th>Tình trạng thanh toán</th>
                      <th>Ngày tạo kiện hàng</th>
                      <th>Thao tác</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <ul id="pagination" style="justify-content:center;display:flex;" class="pagination"></ul>
            </div>
          </div>
        </div>   
    </section>
  </div>
</div>

<!-- /.modal -->
<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Extra Large Modal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table table-bordered table-hover">
            <thead id="t_head">
            </thead>
            <tbody id="t_body">
            </tbody>
          </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<!--html & css section end-->
<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/dataTables.responsive.min.js"></script>
<script src="js/responsive.bootstrap4.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>
<script src="js/buttons.colVis.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.25/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
<script src="//bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
<script>
    $(document).ready(function (e) {
        $("#m-order").DataTable({
          "language": {
            "emptyTable": "Không có dữ liệu",
            "sZeroRecords": 'Không tìm thấy kết quả',
            "infoEmpty": "",
            "infoFiltered":"Lọc dữ liệu từ _MAX_ dòng",
            "search":"Tìm kiếm trong bảng này:",   
            "info":"Hiển thị từ dòng _START_ đến dòng _END_ trên tổng số _TOTAL_ dòng",
         },
          "responsive": true, 
          "lengthChange": false, 
          "autoWidth": false,
          "paging":false,
          "order": [[ 0, "desc" ]],
          "searchHighlight": true,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#m-order_wrapper .col-md-6:eq(0)');
    });
</script>

<script>
   $(document).ready(function(){
      /* -1: xem chi tiết hoá đơn
        * 0: xem thông tin người dùng
        * 1: cập nhật trạng thái đã thanh toán.
        */
        // hiển thị thông tin chi tiết đơn hàng khi admin click vào button "Xem chi tiết đơn hàng."
        $(document).on('click','.btn-xem-chi-tiet-hoa-don',function(event){
            let func = -1;
            let id = $(this).attr('data-bill_id');
            let url = window.location.href;
            let token = $('meta[name="token"]').attr('content');
            $.ajax({
                url:url,
                type:"POST",
                data: {
                    token: token,
                    id: id,
                    func: func
                },
                success:function(data){
                    data = JSON.parse(data);
                    let len = data[0].length;

                    $('#modal-xl').modal('show');
                    $('.modal-title').text("Thông tin hoá đơn");
                    if($('th').parents('#t_head').length > 0) {
                        $('#t_head').empty();
                    }
                    if($('tr > td').parents('#t_body').length > 0) {
                        $('#t_body').empty();
                    }
                    $('#t_head').append('<th>Tên sản phẩm</th>');
                    $('#t_head').append('<th>Hình ảnh</th>');
                    $('#t_head').append('<th>Đơn giá</th>');
                    $('#t_head').append('<th>Số lượng</th>');
                    $('#t_head').append('<th>Số tiền</th>');
                    let tr = "";
                    for(let i = 0 ; i < len ; i++) {
                        tr = "<tr id='cthd"+i+"'>";
                        $('#t_body').append(tr);
                        $('#t_body > #cthd' + i).append('<td>' + data[0][i].name + '</td>');
                        $('#t_body > #cthd' + i).append('<td><img width="120" height="120" src="../img/img-admin/product/' + data[0][i].image + '"></td>');
                        $('#t_body > #cthd' + i).append('<td>' + data[0][i].price + '</td>');
                        $('#t_body > #cthd' + i).append('<td>' + data[0][i].count + '</td>');
                        let total = data[0][i].count * data[0][i].price;
                        $('#t_body > #cthd' + i).append('<td>' + total+ '</td>');
                        $('#t_body').append("</tr>");
                    }
                },
                error:function(data){
                    console.log('Error:', data);
                }
            })
        })
        // hiển thị thông tin người dùng đặt hàng khi admin click vào button "Xem thông tin người dùng."
        $(document).on('click','.btn-xem-thong-tin-nguoi-dung',function(event){
            let func = 0;
            let id = $(this).attr('data-user_id');
            let url = window.location.href;
            let token = $('meta[name="token"]').attr('content');
            $.ajax({
                url:url,
                type:"POST",
                data:{
                id: id, 
                func: func,
                token: token,
                },
                success:function(data){
                    data = JSON.parse(data);
                    $('#modal-xl').modal('show');
                    $('.modal-title').text("Thông tin người dùng");
                    if($('th').parents('#t_head').length > 0) {
                        $('#t_head').empty();
                    }
                    if($('tr > td').parents('#t_body').length > 0) {
                        $('#t_body').empty();
                    }
                    $('#t_head').append('<th>Tên</th>');
                    $('#t_head').append('<th>Ảnh đại diện</th>');
                    $('#t_head').append('<th>Email</th>');
                    $('#t_head').append('<th>Ngày sinh</th>');
                    $('#t_head').append('<th>Số điện thoại</th>');
                    $('#t_head').append('<th>Địa chỉ</th>');
                    $('#t_body').append("<tr>");
                    $('#t_body > tr').append('<td>' + data[0].username + '</td>');
                    $('#t_body > tr').append('<td><img width="120" height="120" src="../img/img-user/info/' + data[0].img_name + '"></td>');
                    $('#t_body > tr').append('<td>' + data[0].email + '</td>');
                    $('#t_body > tr').append('<td>' + data[0].birthday + '</td>');
                    $('#t_body > tr').append('<td>' + data[0].phone+ '</td>');
                    $('#t_body > tr').append('<td>' + data[0].address+ '</td>');
                    $('#t_body').append("</tr>");
                },
                error:function(data){
                    console.log('Error:', data);
                }
            })
        })
        // Cập nhật trạng thái đã thanh toán khi admin click vào button "Cập nhật đã thanh toán hoá đơn."
        $(document).on('click','.btn-cap-nhat-thanh-toan',function(event){
            let func = 1;
            let id = $(this).attr('data-id');
            let pos = $(this).attr('data-pos');
            let url = window.location.href;
            let token = $('meta[name="token"]').attr('content');
            $.ajax({
                url:url,
                type:"POST",
                data: {
                    token: token,
                    func: func,
                    id: id,
                },
                success:function(data){
                    data = JSON.parse(data);
                    if(data.msg == "ok") {
                        alert("Cập nhật dữ liệu thành công.");
                        $("#status-payment"+pos).text("Đã thanh toán");
                    } else {
                        alert("Đã có lỗi xảy ra.");
                    }
                },
                error:function(data){
                    console.log('Error:', data);
                }
            })
        })
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
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        if($status == 1) {
           ajax_db_update_by_id('orders',['payment_status'=>1],[$id]);
        } 
        // Nếu $_POST['func'] == -1 : load chi tiết hoá đơn theo hoa_don_id
        else if($status == -1) {
            $sql_load_order_detail = "select pi.name,pi.img_name as 'image',od.count,od.price from product_info pi inner join order_detail od on pi.id = od.product_info_id where od.order_id = ?";
            ajax_db_query($sql_load_order_detail,[$id]);
        } 
        // Nếu $_POST['func'] == 0 : load thông tin cá nhân người dùng
        else if($status == 0) {
           $sql_get_client_info = "select username,email,birthday,phone,address,img_name from customer where id = ? limit 1";
           ajax_fetch_row($sql_get_client_info,[$id]);
        }
    }
?>