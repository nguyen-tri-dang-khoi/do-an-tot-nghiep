<?php
    include_once("../lib/database_v2.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
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
              <h3 class="card-title">Quản lý loại sản phẩm</h3>
              <div class="card-tools">
                <div class="input-group">
                  <div class="input-group-append">
                    <button id="btn-them-loai-san-pham" class="btn btn-success">
                      Thêm loại sản phẩm
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-left">
                  <li class="breadcrumb-item"><a style="cursor:pointer" href="category_manage.php">Quản lý menu</a></li>
                  <?php
                    if(empty($_SESSION["count_lvl"])){
                      $_SESSION["count_lvl"] = 1;
                    }
                    if(isset($_GET["parent_id"])) {
                      if(empty($_SESSION["lvl"])){
                        $_SESSION["lvl"] = array($_GET["parent_id"]);
                      }
                      for($i = 0 ; $i < count($_SESSION["lvl"]) ; $i++) {
                        $sql = "select id, name from product_type where id = ?";
                        $result = fetch_row($sql, [$_SESSION["lvl"][$i]]);
                  
                  ?>
                      <li class="breadcrumb-item"><a style="cursor:pointer" href="category_manage.php?parent_id=<?php echo $result["id"];?>&count=<?php echo $i;?>"><?php echo $result["name"];?></a></li>
                  <?php 
                        if($result !== "") {
                          if(!in_array($_GET["parent_id"],$_SESSION["lvl"])) {
                            array_push($_SESSION["lvl"],$_GET["parent_id"]);
                          } else {
                            if(isset($_GET["count"])) {
                              array_splice($_SESSION["lvl"],intval($_GET["count"]) + 1);
                            }
                          }
                          $_SESSION["count_lvl"] = count($_SESSION["lvl"]);
                        }
                      }
                    } else {
                      if(!isset($_GET["count"]) && isset($_SESSION["lvl"],$_SESSION["count_lvl"])) {
                        array_splice($_SESSION["lvl"],0);
                        $_SESSION["count_lvl"] = 1;
                      }
                    }
                  ?>
                  </ol>
                </div>
              </div>
			  <div id="btn-file" class="row">
				<div class="col-12">
					<div class="col-12" style="padding-right:0px;padding-left:0px;">
					</div>
					 <table id="m-product-type" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Số thứ tự</th>
							<th>Tên loại sản phẩm</th>
							<th>Ngày thêm</th>
							<th>Thao tác</th>
						  </tr>
						</thead>
						<?php
							$get = $_GET;
							unset($get['page']);
							$str_get = http_build_query($get);
							$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
							$parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : null;
							$arr_paras = [];
							$where = "where 1 = 1 and is_delete = 0";
							if($page){
							  $where = "where 1 = 1 and parent_id is null and is_delete = 0";
							} 
							if($parent_id) {
							  $where = "where 1 = 1 and parent_id = ? and is_delete = 0";
							  array_push($arr_paras,$_REQUEST['parent_id']);
							}
							$limit = $_SESSION['paging'];
							$start_page = $limit * ($page - 1);
							
							$sql_get_total = "select count(*) as 'countt' from product_type $where";
							$total = fetch_row($sql_get_total,$arr_paras)['countt'];
							$cnt = 0;
							array_push($arr_paras,$start_page);
							array_push($arr_paras,$limit);
							$sql_get_product_type = "select * from product_type $where limit ?,?";
							$product_types = db_query($sql_get_product_type,$arr_paras);
							// print_r($arr_paras);
							// print_r($sql_get_product_type);
						  ?>
						<tbody id="list-loai-san-pham">
						<?php foreach($product_types as $product_type) {?>
						  <tr class="parent-type" style="cursor:pointer;" id="loai-san-pham<?=$product_type["id"];?>">
                <td onclick="location.href='category_manage.php?parent_id=<?php echo $product_type['id'];?>&count=<?php echo $_SESSION['count_lvl'];?>'"><?php echo $total - ($start_page + $cnt);?></td>
                <td onclick="location.href='category_manage.php?parent_id=<?php echo $product_type['id'];?>&count=<?php echo $_SESSION['count_lvl'];?>'"><?php echo $product_type["name"];?></td>
                <td onclick="location.href='category_manage.php?parent_id=<?php echo $product_type['id'];?>&count=<?php echo $_SESSION['count_lvl'];?>'"><?=Date("d-m-Y H:i:s",strtotime($product_type["created_at"]));?></td>
                <td>
                  <button class="btn-sua-loai-san-pham btn btn-primary"
                  data-id="<?php echo $product_type["id"];?>" data-number="<?php echo $total - ($start_page + $cnt);?>">
                  Sửa
                  </button>
                  <button class="btn-xoa-loai-san-pham btn btn-danger" data-id="<?php echo $product_type["id"];?>">
                  Xoá
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
							<th>Tên loại sản phẩm</th>
							<th>Ngày thêm</th>
							<th>Thao tác</th>
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
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--html & css section end-->
<?php
    // js lib
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
      });
      dt_pt.buttons().container().appendTo('#m-product-type_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
   $(document).ready(function(){
      // thêm loại sản phẩm
      var max_number = "<?=$total - ($start_page - 1);?>";
      $(document).on('click','#btn-them-loai-san-pham',function(event){
        let number = max_number;
        max_number++;
        console.log(number);
        $('#form-loai-san-pham').load("ajax_category_manage.php?parent_id=<?=$parent_id;?>" + "&status=Insert&number=" + number,() => {
          $('#modal-xl').modal('show');
        });
      });
      // sửa loại sản phẩm
	    var click_number;
      $(document).on('click','.btn-sua-loai-san-pham',function(event){
        number = $(event.currentTarget).attr("data-number");
        console.log(number);
        click_number = $(this).closest('tr');
        let id = $(event.currentTarget).attr('data-id');
        $('#form-loai-san-pham').load("ajax_category_manage.php?id=" + id + "&status=Update&number=" + number,() => {
            $('#modal-xl').modal('show');
        });
      });
      // xoá loại sản phẩm
      $(document).on('click','.btn-xoa-loai-san-pham',function(event){
		    console.log($(event.currentTarget).attr('data-id'));
        click_number = $(this).closest('tr');
		    let id = $(event.currentTarget).attr('data-id');
        $.confirm({
          title: 'Thông báo',
          content: 'Bạn có chắc chắn muốn xoá loại sản phẩm này ?',
          buttons: {
            Có: function () {
              $.ajax({
                url:window.location.href,
                type:"POST",
                cache:false,
                data:{
                  token: "<?php echo_token();?>",
                  id: id,
                  status: "Delete",
                },
                success:function(res){
                  res_json = JSON.parse(res);
                  if(res_json.msg == "ok") {
                  $.alert({
                    title: "Thông báo",
                    content: res_json.success
                  });
                  //$('#loai-san-pham' + res_json.id).remove();
                  dt_pt.row(click_number).remove().draw();
                  } else {
                    $.alert({
                    title: "Thông báo",
                    content: res.error
                  });
                  
                  }
                }
              });
            },
            Không: function () {
            },
          }
        });
      });
      // xử lý thao tác thêm sửa
      $(document).on('click','#btn-luu-loai-san-pham',function(event){
         event.preventDefault();
         if(!$('input[name=ten_loai_san_pham]').val()) {
            $.alert({
              title: "Thông báo",
              content: "Vui lòng không để trống tên loại sản phẩm"
            });
            return;
         }
         $.ajax({
            url:window.location.href,
            type:"POST",
            cache:false,
            data:{
              token: "<?php echo_token();?>",
              id: $('input[name=id]').val(),
              parent_id: "<?php echo $parent_id?>",
              name:$('input[name=ten_loai_san_pham]').val(),
              status: $('#btn-luu-loai-san-pham').text(),
              number: $('input[name=number]').val(),
            },
            success:function(res){
              let res_json = JSON.parse(res);
              console.log(res_json);
              $('#form-loai-san-pham').trigger('reset');
              $('#modal-xl').modal('hide');

              if(res_json.msg == "ok"){
                let status = $('#btn-luu-loai-san-pham').text();
                let record = `
                  <tr style="cursor:pointer;" id="loai-san-pham${res_json.id}" role="row" class="odd">
                    <td onclick="location.href='category_manage.php?parent_id=${res_json.id}&amp;count=1'" class="dtr-control sorting_1" tabindex="0">${res_json.number}</td>
                    <td onclick="location.href='category_manage.php?parent_id=${res_json.id}&amp;count=1'">${res_json.name}</td>
                    <td onclick="location.href='category_manage.php?parent_id=${res_json.id}&amp;count=1'">${res_json.created_at}</td>
                    <td>
                      <button class="btn-sua-loai-san-pham btn btn-primary" data-id="${res_json.id}" data-number="${res_json.number}">
                        Sửa
                      </button>
                      <button class="btn-xoa-loai-san-pham btn btn-danger" data-id="${res_json.id}" data-number="${res_json.number}">
                        Xoá
                      </button>
                    </td>
                  </tr>
                `;
                record = $(record);
                if(status == "Insert"){
                  $.alert({
                    title: "Thông báo",
                    content: "Thêm loại sản phẩm thành công"
                  });
                  dt_pt.row.add(record[0]).draw();
                } else if(status == "Update") {
                  $.alert({
                    title: "Thông báo",
                    content: "Sửa loại sản phẩm thành công"
                  });
                  let one_row = dt_pt.row(click_number).data();
                  one_row[0] = `${res_json.number}`;
                  one_row[1] = `${res_json.name}`;
                  dt_pt.row(click_number).data(one_row).draw();
                  
                }
              } else {
                $.alert({
                  title: "Thông báo",
                  content: res_json.error,
                });
              }
            }
          });
      });
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
        //echo "a";
		    $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
        $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
        if($status == "Delete") {
           $success = "Xoá dữ liệu thành công.";
           $error = "Network has problem. Please reload this page.";
           ajax_db_update_by_id('product_type',['is_delete'=>1], [$id],['id' => $id,"number"=>$number,'success' => $success],['error' => $error]);
        } else if($status == "Update") {
            $sql_check_exist = "Select count(*) as 'countt' from product_type where name = ? and id <> ?";
            $row = fetch_row($sql_check_exist,[$name,$id]);
            if(1 == 2) { //$row['countt'] > 0
                $error = "Tên loại sản phẩm này đã tồn tại.";
                echo_json(['msg' => 'not_ok','error' => $error]);
            } else if(1 == 1) { //$row['countt'] == 0
                ajax_db_update_by_id('product_type', ['name' => $name],[$id],["id" => $id,"name"=>$name,"number"=>$number]);
            }
        } else if($status == "Insert") {
            $sql_check_exist = "Select count(*) as 'countt' from product_type where name = ?";
            $row = fetch_row($sql_check_exist,[$name]);
            if(1 == 2) {
                $error = "Tên loại sản phẩm này đã tồn tại.";
                echo_json(['msg' => 'not_ok','error' => $error]);
            } else if(1 == 1){
                if($parent_id) {
                    ajax_db_insert_id('product_type',['name'=>$name,'parent_id' => $parent_id],['id' => $id,"number"=>$number,'name'=>$name,'created_at'=>date('d-m-Y H:i:s',time())]);
                } else {
                    ajax_db_insert_id('product_type',['name'=>$name],['id' => $id,"number"=>$number,'name'=>$name,'created_at'=>date('d-m-Y H:i:s',time())]);
                }
            }
        }
    }
    
?>