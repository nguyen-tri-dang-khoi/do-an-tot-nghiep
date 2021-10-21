<?php
    include_once("../lib/database_v2.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
      // code to be executed get method
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $where = "where 1=1 ";
      if($keyword || $keyword == 0) {
        $where .= "and lower(keyword) like lower('%$keyword%')";
      }
?>
<?php
		
?>
<!--html & css section start-->
<style>
    table.dataTable span.highlight {
      background-color: #17a2b8;
      border-radius: 5px;
      text-align: center;
      color: white;
    }
    .card-header::after{
      display:none;
    }

</style>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<!-- /.row -->
<div class="container-wrapper">
  <div class="container-fluid">
    <section class="content content-wrapper">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header" style="display: flex;justify-content: space-between;">
              <h3 class="card-title">Quản lý lịch sử tìm kiếm</h3>
            </div>
            <div class="card-body">
			  <div id="btn-file" class="row">
				<div class="col-12">
					<div class="col-12" style="padding-right:0px;padding-left:0px;">
            <form style="margin-bottom: 17px;display:flex;" action="history_manage.php" method="get">
                <div class="" style="display:flex;">
                    <input type="text" name="keyword" placeholder="Nhập từ khoá..." class="form-control" value="<?=$keyword;?>">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
            </form>
					</div>
					 <table id="m-product-type" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Số thứ tự</th>
							<th>Từ khoá</th>
							<th>Ngày thêm</th>
						  </tr>
						</thead>
						<?php
							$get = $_GET;
							unset($get['page']);
							$str_get = http_build_query($get);
							$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
							$parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : null;
							$arr_paras = [];
							$where = "where 1 = 1";
							$limit = $_SESSION['paging'];
							$start_page = $limit * ($page - 1);
							$sql_get_total = "select count(*) as 'countt' from keyword_history $where";
							$total = fetch_row($sql_get_total,$arr_paras)['countt'];
							$cnt = 0;
							array_push($arr_paras,$start_page);
							array_push($arr_paras,$limit);
							$sql_get_keyword_history = "select * from keyword_history $where limit ?,?";
							$keyword_historys = db_query($sql_get_keyword_history,$arr_paras);
							// print_r($arr_paras);
							// print_r($sql_get_keyword_history);
						  ?>
						<tbody id="list-loai-san-pham">
						<?php foreach($keyword_historys as $keyword_history) {?>
						  <tr>
                  <td><?php echo $total - ($start_page + $cnt);?></td>
                  <td><?php echo $keyword_history["keyword"];?></td>
                  <td><?=Date("d-m-Y",strtotime($keyword_history["created_at"]));?></td>
						  </tr>
						  <?php 
								$cnt++;
							  } 
						  ?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Số thứ tự</th>
							<th>Từ khoá</th>
							<th>Ngày thêm</th>
						  </tr>
						</tfoot>
					 </table>
					 <div style="justify-content:center;" class="row">
					  <nav id="pagination" aria-label="Page navigation example">

					  </nav>
					</div>
				</div>
			  </div>
             
            </div>
            
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<!-- /.modal load-->
<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Thông tin loại sản phẩm</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="form-product-type" class="modal-body">
        <form id="form-loai-san-pham" action="" method="post">

        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    var dt_pt;
    $(document).ready(function (e) {
      dt_pt = $("#m-product-type").DataTable({
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
        "searchHighlight": true,
        "paging":false,
        "order": [[ 0, "desc" ]],
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
      dt_pt.buttons().container().appendTo('#m-product-type_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
   $(document).ready(function(){
      
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
        
    }
    
?>