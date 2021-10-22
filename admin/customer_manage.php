<?php
    include_once("../lib/database_v2.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $where = "where 1=1 ";
        if($keyword || $keyword == 0 ) 
        {
            if($search_option == "fullname") {
                $where .= "and lower(full_name) like lower('%$keyword%')";
            } else if($search_option == "email") {
                $where .= "and lower(email) like lower('%$keyword%')";
            } else if($search_option == "phone") {
                $where .= "and lower(phone) like lower('%$keyword%')";
            } else if($search_option == "address") {
                $where .= "and lower(address) like lower('%$keyword%')";
            } else if($search_option == "all") {
                $where .= "and lower(full_name) like lower('%$keyword%') or ";
                $where .= "lower(email) like lower('%$keyword%') or ";
                $where .= "lower(phone) like lower('%$keyword%') or ";
                $where .= "lower(address) like lower('%$keyword%') ";
            }
        }
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
<div class="container-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Quản lý khách hàng</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="col-12" style="padding-right:0px;padding-left:0px;">
                            <form style="margin-bottom: 17px;display:flex;" action="<?php echo get_url_current_page();?>" method="get">
                                <div class="">
                                    <select class="form-control" name="type">
                                        <option value="">Chọn cột tìm kiếm</option>
                                        <option value="fullname" <?=$search_option == 'fullname' ? 'selected="selected"' : '' ?>>Tên đầy đủ</option>
                                        <option value="address" <?=$search_option == 'address' ? 'selected="selected"' : '' ?>>Địa chỉ</option>
                                        <option value="email" <?=$search_option == 'email' ? 'selected="selected"' : '' ?>>Email</option>
                                        <option value="phone" <?=$search_option == 'phone' ? 'selected="selected"' : '' ?>>Số điện thoại</option>
                                        <option value="all" <?=$search_option == 'all' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                    </select>
                                </div>
                                <div class="ml-10" style="display:flex;">
                                    <input type="text" name="keyword" placeholder="Nhập từ khoá..." class="form-control" value="<?=$keyword;?>">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    <?php
							// set get
							$get = $_GET;
							unset($get['page']);
							$str_get = http_build_query($get);
							// query
                            $arr_paras = [];
                            $where = "where 1 = 1 and is_delete = 0 and is_lock = 0";
                            $keyword = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : null;
                            if($keyword) {
                                $where .= "";
                            }
                            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                            $limit = $_SESSION['paging'];
                            $start_page = $limit * ($page - 1);
                            $sql_get_total = "select count(*) as 'countt' from customer $where";
                            $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                            array_push($arr_paras,$start_page);
                            array_push($arr_paras,$limit);
                            $sql_get_customer = "select * from customer $where limit ?,?";
                            //print_r($sql_get_customer);
                            //print_r($arr_paras);
                            $rows = db_query($sql_get_customer,$arr_paras);
							$cnt = 0;
                        ?>
                        <table id="m-customer-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Số thứ tự</th>
                                    <th>Tên đầy đủ</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày sinh</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($rows as $row) { ?>
                                <tr id="customer-<?=$row["id"]?>">
                                    <th><?=$total - ($start_page + $cnt);?></th>
                                    <td><?=$row["full_name"]?></td>
                                    <td><?=$row["email"]?></td>
                                    <td><?=$row["phone"]?></td>
                                    <td><?=$row["address"]?></td>
                                    <td><?=Date("d-m-Y",strtotime($row["birthday"]));?></td>
                                    <td><?=$row["username"]?></td>
                                    <td><?=Date("d-m-Y h:i:s",strtotime($row["created_at"]));?></td>
                                    <td>
                                        <button class="btn-update-user btn btn-primary"
                                        data-id="<?=$row["id"];?>">Xem thông tin khách hàng</button>
                                        <!--<button class="btn-send-notify btn btn-secondary" data-id="<?=$row["id"];?>">Gửi thông báo
                                        </button>-->
                                        <button class="btn-lock-user btn btn-danger" data-id="<?=$row["id"];?>">Khoá tài khoản
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                        $cnt++;
                                    } 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Số thứ tự</th>
                                    <th>Tên đầy đủ</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày sinh</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="justify-content:center;" class="row">
                            <ul id="pagination" class="pagination">
                            </ul>
                        </div>
                    </div>
                </div>
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
        $("#m-customer-table").DataTable({
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
            "buttons": [
            {
                "extend": "copy",
                "text": "Sao chép bảng",
            },{
                "extend": "excel",
            },{
                "extend": "pdf",
            },{
                "extend": "csv",
            },{
                "extend": "print",
                "text": "In bảng",
            },{
                "extend": "colvis",
                "text": "Ẩn / Hiện cột",
            }
        ]
        }).buttons().container().appendTo('#m-customer-table_wrapper .col-md-6:eq(0)');
    });
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
        echo "post";
    }
?>