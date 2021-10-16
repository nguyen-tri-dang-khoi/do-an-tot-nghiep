<?php
   include_once("../lib/database.php");
   redirect_if_login_status_false();
   if(is_get_method()) {
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/summernote.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<style>
   .img-child {
      position: relative;
      margin: 12px;
      border: 1px solid #b34d4d;
      box-shadow: 2px 2px 14px #f7c5c5c7;
   }
   .img-child .btn-tool {
      margin:unset;
   }
   .icon-x {
		position:absolute;
		top:0px;
		right:0px;
		cursor:pointer;
   }
  .icon-x:hover {
    background-color:red;
    color:white;
   }
   li[data-parent_id_2]:hover {
      cursor:pointer;
   }
   table.dataTable span.highlight {
      background-color: #17a2b8;
      border-radius: 5px;
      text-align: center;
      color: white;
   }
   .card-header::after{
      display:none;
   }
   .parent {
      padding-left:5px;
      display: block;
      position: relative;
      width: 100%;
      z-index: 5;
      float: left;
      line-height: 30px;
      background-color: #ffffff;
      cursor:pointer;
   }
   .parent a{
      margin: 10px;
      color: #495057;
      text-decoration: none;
   }
   .parent:hover > ul {
      display:block;
      position:absolute;
   }
   .child {
      display: none;
      width:220px;
      box-shadow: 2px 3px 13px 1px #ddd;
   }
   .child li {
      background-color: #E4EFF7;
      line-height: 30px;
      width:100%;
   }
   .child li a{
      color: #000000;
   }
   ul{
      list-style: none;
      margin: 0;padding: 0px; 
      min-width:10em;
   }
   ul ul ul{
      left: 100%;
      top: 0;
      margin-left:1px;
   }
   li:hover {
      /*background-color: #95B4CA;*/
   }
   .parent li:hover {
      /*background-color: #F0F0F0;*/
   }
   .expand{
      font-size:12px;
      float:right;
      margin-right:5px;
   }
  /*#m-product-info_wrapper .buttons-html5 {
    margin-right: 5px;
    border-radius: 10px;
    height: 30px;
    font-size: 15px;
    width: 58px;
    /* font-weight: 600; */
    /*padding-top: 3px;*/
    /* padding: 5px; */
</style>
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid">
    <section class="content">
        <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý sản phẩm</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        <div class="input-group-append">
                           <button id="btn-them-san-pham" class="btn btn-success">
                              Thêm sản phẩm
                           </button>
                        </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <form style="margin-bottom: 17px;" action="<?php echo get_url_current_page();?>" method="get">
                        <div class="row">
                           <div class="col-md-3 input-group">
                                 <input type="text" name="keyword" placeholder="Nhập từ khoá..." class="form-control">
                                 <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                       <i class="fas fa-search"></i>
                                    </button>
                                 </div>
                           </div>
                        </div>
                     </form>
                     <table id="m-product-info" class="table table-bordered table-striped">
                     <thead>
                        <tr>
						   <th>Số thứ tự</th>
                           <th>Tên sản phẩm</th>
                           <th>Số lượng</th>
                           <th>Đơn giá</th>
                           <th>Phân loại sản phẩm</th>
                           <th>Hình ảnh</th>
                           <th>Ngày đăng</th>
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
                        $where = "where 1 = 1 and pi.is_delete = 0";
                        $keyword = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : null;
                        if($keyword) {
                           $where .= "";
                        }
                        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                        $limit = 10;
                        $start_page = $limit * ($page - 1);
                        $sql_get_total = "select count(*) as 'countt' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where";
                        $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                        array_push($arr_paras,$start_page);
                        array_push($arr_paras,$limit);
                        $sql_get_product = "select pi.id as 'pi_id', pi.name as 'pi_name',pi.price,pi.count,pi.img_name as 'pi_img_name',pi.created_at,pt.name as 'pt_name' from product_info pi left join product_type pt on pi.product_type_id = pt.id limit ?,?";
                        $rows = db_query($sql_get_product,$arr_paras);
                        foreach($rows as $row) {
                     ?>
                        <tr id="san-pham<?=$row["pi_id"];?>">
						   <td><?=$total - ($start_page + $cnt)?></td>
                           <td><?=$row['pi_name']?></td>
                           <td><?=$row['count']?></td>
                           <td><?=$row['price']?></td>
                           <td><?=$row['pt_name']?></td>
                           <td><img width="100" height="100" src="<?php echo 'upload/product/'.$row['pi_img_name']?>" alt=""></td>
                           <td><?=$row['created_at']?></td>
                           <td>
                              <button class="btn-sua-san-pham btn btn-primary"
                              data-id="<?=$row["pi_id"];?>" >
                              Update
                              </button>
                              <button class="btn-xoa-san-pham btn btn-danger" data-id="<?=$row["pi_id"];?>">
                              Delete
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
                           <th>Tên sản phẩm</th>
                           <th>Số lượng</th>
                           <th>Đơn giá</th>
                           <th>Phân loại sản phẩm</th>
                           <th>Hình ảnh</th>
                           <th>Ngày đăng</th>
                           <th>Thao tác</th>
                        </tr>
                     </tfoot>
                     </table>
                  </div>
                  <ul id="pagination" style="justify-content:center;display:flex;" class="pagination">
                        
                  </ul>
               </div>
            </div>
         </div>
    </section>
  </div>
</div>
<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thông tin sản phẩm</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-san-pham" method="post" enctype='multipart/form-data'>
            
        </form>
      </div>
      <!--<div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>
  </div>
</div>
<!--html & css section end-->
<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script src="js/summernote.min.js"></script>
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
        $("#m-product-info").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
			"searching": false,
            "paging":false,
            "searchHighlight": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#m-product-info_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
   $(document).ready(function(){
      const imagesPreview = (input , parent) => {
         if (input.files) {
               var filesAmount = input.files.length;
               for (i = 0; i < filesAmount; i++) {
                  var reader = new FileReader();
                  reader.onload = (event) => {
                     $(parent).append('<div class="img-child filtr-item col-sm-1">'
                     + '<img src="' + event.target.result + '" class="img-fluid mb-2">'
                     + '<button type="button" class="icon-x btn-xoa-anh-mo-ta-san-pham btn btn-tool"><i class="fas fa-times"></i></button>'
                     +'</div>');
                  }
                  reader.readAsDataURL(input.files[i]);
               }
         }
      };
      const readURL = (input) => {
         if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
               $('#display-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
         }
      };
      // validate
      const validate = () => {
        let test = true
        let name = $('input[name=ten_san_pham]').val();
        let category = $('input[name="category_id"]').val();
        let count = $('input[name=so_luong]').val();
        let price = $('input[name=don_gia]').val();
        let description = $('#summernote').summernote('code');
        if(name.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Tên sản phẩm không được để trống"
			 });
            test = false;
        } else if(category.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Phân loại sản phẩm không được để trống"
			 });
            test = false;
        } else if(count.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Số lượng không được để trống"
			 });
            test = false;
        } else if(price.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Đơn giá không được để trống"
			 });
            test = false;
        } else if(description.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Mô tả sản phẩm không được để trống"
			 });
            test = false;
        }
         console.log($('input[name=id]').val());
         console.log($('input[name=ten_san_pham]').val());
         console.log($('#summernote').summernote('code'));
         console.log($('input[name=so_luong]').val());
         console.log($('input[name=don_gia]').val());
         console.log($('select[name=phan_loai_san_pham] > option:selected').val());
         console.log($('#btn-luu-san-pham').text());
         return test;
      }
      $('#file_input_anh_mo_ta').on('change', function() {
         imagesPreview(this,'#image_preview');
      });
      $(document).on('click','#btn-them-san-pham',function(event){
         $('#form-san-pham').load("ajax_product_info.php",() => {
            $('#modal-xl').modal('show');
            $('#btn-luu-san-pham').text("Insert");
            $(function(){
               setTimeout(() => {
                  $('#summernote').summernote({height: 120});
               },100);
               $(".parent[data-id]").click(function(e){
                  let child = $(e.currentTarget).find('li').length;
                  if(!child){
                     //console.log("nufew");
                     let id = $(e.currentTarget).attr('data-id');
                     let name = $(e.currentTarget).text();
                     name = name.substr(0,name.length - 1);
                     console.log(name);
                     //console.log(id);
                     $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                        $("input[name='category_id']").val(id);
                        $("input[name='category_name']").val(name);
                        $("#breadcrumb-menu").empty();
                        $("#breadcrumb-menu").append(data);
                        $("#breadcrumb-menu").parent().css({"margin-top":"-25px"});
                     });
                  }
               })
            });
            
            $("#fileInput").on("change",function(){
               $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
               readURL(this); 
            });
            $('#file_input_anh_mo_ta').on('change', function() {
               imagesPreview(this,'#image_preview');
            });
         });
      });
      // Update sản phẩm
      $(document).on('click','.btn-sua-san-pham',function(event){
         let id = $(event.currentTarget).attr('data-id');
         $('#form-san-pham').load("ajax_product_info.php?id=" + id,() => {
            $('#modal-xl').modal('show');
            $('#btn-luu-san-pham').text("Update ");
            $(function(){
               setTimeout(() => {
                  $('#summernote').summernote({height: 120});
               },100);
               $(".parent[data-id]").click(function(e){
                  let child = $(e.currentTarget).find('li').length;
                  if(!child){
                     //console.log("nufew");
                     let id = $(e.currentTarget).attr('data-id');
                     let name = $(e.currentTarget).text();
                     name = name.substr(0,name.length - 1);
                     console.log(name);
                     //console.log(id);
                     $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                        $("input[name='category_id']").val(id);
                        $("input[name='category_name']").val(name);
                        $("#breadcrumb-menu").empty();
                        $("#breadcrumb-menu").append(data);
                        $("#breadcrumb-menu").parent().css({"margin-top":"-25px"});
                     });
                  }
               })
            });
            $("#fileInput").on("change",function(){
               $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
               readURL(this); 
            });
            $('#file_input_anh_mo_ta').on('change', function() {
               imagesPreview(this,'#image_preview');
            });
         });
      });
      // Delete sản phẩm
      $(document).on('click','.btn-xoa-san-pham',function(event){
		 let id = $(event.currentTarget).attr('data-id');
		 $.confirm({
			title: 'Thông báo',
			content: 'Bạn có chắc chắn muốn xoá sản phẩm này ?',
			buttons: {
				Có: function () {
					$.ajax({
					   url:window.location.href,
					   type:"POST",
					   cache:false,
					   data:{
						  token: "<?php echo_token(); ?>",
						  id: id,
						  status: "Delete",
					   },
					   success:function(res){
						  console.log(id);
						  res_json = JSON.parse(res);
						  if(res_json.msg == "ok") {
							  $.alert({
								title: "Thông báo",
								content: res_json.success
							 });
							 $('#san-pham' + res_json.id).remove();
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
      // Delete ảnh mô tả sản phẩm
      $(document).on('click','.btn-xoa-anh-mo-ta-san-pham',function(event){
		   let img_id = $(this).attr('data-img_id');
		   let product_image_id = $(event.currentTarget).attr('data-img_id');
		   $.confirm({
				title: 'Thông báo',
				content: 'Bạn có chắc chắn muốn xoá ảnh này ?',
				buttons: {
					Có: function() {
						if (typeof img_id !== 'undefined' && img_id !== false) {
						   $.ajax({
							url:window.location.href,
							type:"POST",
							cache:false,
							data:{
								token: "<?php echo_token(); ?>",
								id: $('input[name=id]').val(),
								product_image_id: product_image_id,
								status: "Delete_img",
							},
							success:function(res){
								res_json = JSON.parse(res);
								if(res_json.msg == "ok") {
									$.alert({
										title: "Thông báo",
										content: res.success
									});
									$("[data-img_id='" + product_image_id + "']").parent().remove();
								} else {
									$.alert({
										title: "Thông báo",
										content: res.error
									});
								}
							}
						   });
						} else {
						   $(this).parent().remove();
						}
					},Không: function() {
						
					}
				}
		   });
      });
      // xử lý thao tác Insert Update
      $(document).on('click','#btn-luu-san-pham',function(event){
         event.preventDefault();
         let formData = new FormData($('#form-san-pham')[0]);
         formData.append('token',"<?php echo_token(); ?>");
         formData.append('id',$('input[name=id]').val());
         formData.append('name',$('input[name=ten_san_pham]').val());
         formData.append('description',$('#summernote').summernote('code'));
         formData.append('count',$('input[name=so_luong]').val());
         formData.append('price',$('input[name=don_gia]').val());
         formData.append('category_id',$("input[name='category_id']").val());
         formData.append('category_name',$("input[name='category_name']").val());
         formData.append('status',$('#btn-luu-san-pham').text().trim());
         // xu ly anh
         let file = $('input[name=img_sanpham_file]')[0].files;
         console.log(file);
         let file_anh_mo_ta = $('input[name="anh_mo_ta[]"]')[0].files;
         let image = ""; 
         if(file.length > 0) {
            formData.append('img_sanpham_file',file[0]); 
         }
         if(file_anh_mo_ta.length > 0) {
            formData.append('anh_mo_ta',file_anh_mo_ta);
         }
         if(validate()) {
            // xử lý ajax
            $.ajax({
               url:window.location.href,
               type:"POST",
               cache:false,
               dataType:"json",
               contentType: false,
               processData: false,
               data:formData,
               success:function(res_json){
                  if(res_json.msg == 'ok'){
                     let status = $('#btn-luu-san-pham').text().trim();
                     let record = "<tr id='san-pham" + res_json.id + "'>";
                     record += "<td>" + res_json.name + "</td>";
                     record += "<td>...</td>";
                     record += "<td id='mo_ta_sp" + res_json.id +  "' style='display:none;'>" + res_json.description + "</td>";
                     record += "<td>" + res_json.count+ "</td>";
                     record += "<td>" + res_json.price + "</td>";
                     record += "<td>" + res_json.category_name + "</td>";
                     record += "<td><img width='100' height='100' src='upload/product/" + res_json.image + "'>" + "</td>";
                     record += "<td>" + res_json.created_at + "</td>";
                     record += "<td>"
                     record += "<button class='btn-sua-san-pham btn btn-primary' ";
                     record +=   "data-name='" + res_json.name + "'" + " data-count='" +res_json.count + "'";
                     record +=   " data-price='" + res_json.price + "' data-name_type='" + res_json.category_id + "' " + " data-image='" +res_json.image + "'";    
                     record += " data-id='" + res_json.id;
                     record += "'>";
                     record +=      "Update"
                     record +  "</button>";
                     record += "<button style='margin-left:3px;' class='btn-xoa-san-pham btn btn-danger' ";
                     record +=   "data-id='" + res_json.id + "'>";
                     record +=      "Delete"
                     record += "</button>";
                     record += "</td>"
                     record +=    "</tr>";
                     let msg ="";
                     if(status == "Insert"){
                           $('#list-san-pham').append(record);
                           setTimeout(() => {
                              $("#san-pham" + res_json.id).css('background-color','#d4efecc2');
                           },1000);
                           msg = "Thêm dữ liệu thành công.";
						   $.alert({
								title: "Thông báo",
								content: msg
						   });
                           //alert(msg);
                           if($('#display-image').length){
                              $('#display-image').replaceWith('<div data-img="" class="img-fluid" id="where-replace">' + "<span></span>" + "</div>");
                           }
                     } else if(status == "Update") {
                           msg = "Xoá dữ liệu thành công.";
						   $.alert({
								title: "Thông báo",
								content: msg
						   });
                           //alert(msg);
                           $('#san-pham' + res_json.id).replaceWith(record);
                           $('#san-pham' + res_json.id).css('background-color','#f7daf4');
                           $('#display-image').replaceWith('<div data-img="" class="img-fluid" id="where-replace">' + "<span></span>" + "</div>");
                     }
                     $('#form-san-pham').trigger('reset');
                     $("#msg_style").removeAttr('style');
                     $("#msg").text(msg);
                     $('#modal-xl').modal('hide');

                  } else if(res_json.msg == 'not_ok') {
                     $.alert({
						title: "Thông báo",
						content: res_json.error
					});
                  }
               },
               error: function (data) {
                  console.log('Error:', data);
               }
            });
         }
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
		onPageClick: function(){
			//window.location.href=""
		},
        cssStyle: 'light-theme'
    });
  });
</script>
<script>
   $(function(){
      $('.breadcrumb-item').click(function(){
         $('.kh-submenu').toggleClass('.kh-submenu-active');
      });
      // load breadcrumb menus 

   });
</script>
<!--js section end-->
<?php
        include_once("include/footer.php");
?>

<?php
    } else if (is_post_method()) {
        $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
        $count = isset($_REQUEST["count"]) ? $_REQUEST["count"] : null;
        $description = isset($_REQUEST["description"]) ? $_REQUEST["description"] : null;
        $category_id = isset($_REQUEST["category_id"]) ? $_REQUEST["category_id"] : null;
        $category_name = isset($_REQUEST["category_name"]) ? $_REQUEST["category_name"] : null;
        $price = isset($_REQUEST["price"]) ? $_REQUEST["price"] : null;
        //$image_str = isset($_REQUEST["image_str"]) ? $_REQUEST["image_str"] : null;
        if($status == 'Delete') {
            $success = "Bạn đã Delete dữ liệu thành công";
            $error = "Network has problem. Please try again.";
            ajax_db_update_by_id('product_info',['is_delete' => 1],[$id],["id" => $id,"success" => $success],['error' => $error]);
        } else if($status == "Insert") {
            $sql_check_exist = "select count(*) as 'countt' from product_info where id = ?";
            $row = fetch_row($sql_check_exist,[$id]);
            if($row['countt'] > 0) {
               $error = "Tên loại sản phẩm này đã tồn tại.";
               echo_json(['msg' => 'not_ok', 'error' => $error]);
            } else {
               $insert = db_insert_id('product_info',['name'=>$name,'user_id'=>$user_id,'product_type_id'=>$category_id,'description'=>$description,'count'=>$count,'price'=>$price,'img_name'=>null]);
               if($insert > 0) {
                  /**
                  * $image = null;file_upload(['file' => 'img_cmnd_file'],'product_info','img_name',"upload/user/identify/",$id,$image,'cmnd_');
                  */
                  $image = null;
                  file_upload(['file' => 'img_sanpham_file'],'product_info','img_name','upload/product/',$insert,$image);
                  if($image) {
                     db_update_by_id('product_info',["img_name" => $image],[$insert]);
                  }
                  
                  $__arr = [];
                  $__arr_ext = [];
                  $sql_get_last_id = "Select id from product_image order by id desc limit 1";
                  $id = fetch_row($sql_get_last_id);
                  $id = ($id) ? $id["id"] : 0;
                  $__arr_ext = multiple_file_upload(['file' => 'anh_mo_ta','dirname' => "upload/product/{$insert}/",'img_id' => $id,'file_name' => 'anh_mo_ta']);
                  if(count($__arr_ext) > 0) {
                     $len = count($__arr_ext) + $id;
                     $j = 0;
                     for($i = $id ; $i < $len ; $i++) {
                        array_push($__arr,[$insert,"anh_mo_ta" . $i . "." . $__arr_ext[$j]]);
                        $j++;
                     }
                     db_insert_more_row('product_image',['product_info_id','img_id'],$__arr);
                  }
                  
                  $success = "Insert dữ liệu thành công.";
                  echo_json(["msg" => "ok","success" => $success,"id"=>$insert,"name"=>$name,"description"=>$description,"category_name"=>$category_name,"category_id" => $category_id,"image"=>$image,"price"=>$price,"count"=>$count,"created_at"=>date('Y-m-d H-i-s',time())]);
               }
            }
        } else if($status == "Update") {
            $image = null;
            // print_r("khoi_dep_trai");
            file_upload(['file' => 'img_sanpham_file'],'product_info','img_name','upload/product/',$id,$image);
            $__arr = [];
            $__arr_ext = [];
            $sql_get_last_id = "Select id from product_image order by id desc limit 1";
            $img_id = fetch_row($sql_get_last_id);
            $img_id = ($img_id) ? $img_id["id"] : 0;
            $__arr_ext = multiple_file_upload(['file' => 'anh_mo_ta','dirname' => "upload/product/{$id}/",'img_id' => $img_id,'file_name' => 'anh_mo_ta'],$__arr_ext);
            if(count($__arr_ext) > 0) {
               $len = count($__arr_ext) + $img_id;
               $j = 0;
               for($i = $img_id ; $i < $len ; $i++) {
                  array_push($__arr,[$id,"anh_mo_ta" . $i . "." . $__arr_ext[$j]]);
                  $j++;
               }
               db_insert_more_row('product_image',['product_info_id','img_id'],$__arr);
            }
            if($image) {
               db_update_by_id('product_info',['name'=>$name,'user_id'=>$user_id,'product_type_id'=>$category_id,'description'=>$description,'count'=>$count,'price'=>$price,'img_name'=>$image],[$id]);
            } else {
               db_update_by_id('product_info',['name'=>$name,'user_id'=>$user_id,'product_type_id'=>$category_id,'description'=>$description,'count'=>$count,'price'=>$price],[$id]);
            }
            $success = "Update dữ liệu thành công.";
            $sql_get_file_name = "select img_name from product_info where id = ?";
            $image = fetch_row($sql_get_file_name,[$id]);
            if($image) {
               $image = $image['img_name'];
            }
            echo_json(["msg" => "ok",'success' => $success,"id" => $id,"name"=>$name,"description"=>$description,"category_name"=>$category_name,"category_id"=>$category_id,"image"=>$image,"price"=>$price,"count"=>$count,"created_at"=>date('Y-m-d H-i-s',time())]);
        } else if($status == "Delete_img") {
            $sql_get_img_id = "select img_id from product_image where id = ?";
            $img_id = isset($_REQUEST["product_image_id"]) ? $_REQUEST["product_image_id"] : null;
            if($img_id) {
               $row = fetch_row($sql_get_img_id,[$img_id]);
               if(count($row) == 1) {
                  $success = "Bạn đã Delete ảnh mô tả thành công";
                  $error = "Đã có lỗi xảy ra. Vui lòng reload lại trang.";
                  $url_image = "upload/product/{$id}/{$row['img_id']}";
                  $bool = file_exists($url_image) ? unlink($url_image) : false;
                  ($bool && db_delete_by_id("product_image",[$_POST["product_image_id"]])) ? echo_json(["msg" => "ok","success" => $success]) : echo_json(["msg" => "not_ok","error" => $error]);
               }
            }
        }
        // code to be executed post method
        // if(isset($_POST["status"],$_POST["id"])) {
            /*$result = php_validate([
               $_POST["status"].'a' => ["required"=>"thao tác","equal"=>"Delete"],
               $_POST["id"].'b' => ["required"=>""],
            ]);*/
            
            // if(!array_key_exists("error",$result)){
               // Thực thi chức năng Delete sản phẩm
               
            //}
            // Ràng buộc dữ liệu đầu vào (chức năng Insert Update sản phẩm cần ràng buộc dữ liệu đầu vào)
            //if(isset($_POST["name"],$_POST["count"],$_POST["name_desc"],$_POST["name_type"],$_POST["price"])) {
               /*$result = php_validate([
                  $_POST["name"].'b' => ["required"=>"Tên sản phẩm","max"=>255],
                  $_POST["count"].'c' => ["required"=>"Số lượng","number_min" => 0],
                  $_POST["name_desc"].'d' => ["required"=>"Mô tả sản phẩm"],
                  $_POST["name_type"].'e' => ["required"=>"Tên loại sản phẩm"],
                  $_POST["price"].'f' => ["required"=>"Đơn giá","number_min" => 0],
               ]);*/
               // print_r($result);
               // if(!array_key_exists('error',$result)){
                  // Upload ảnh mô tả sản phẩm
                  // Admin thực hiện chức năng Insert sản phẩm
                  /*$result = php_validate([
                     $_POST["status"]. 'a' => ["required"=>"","equal"=>"Insert"],
                  ]);*/
                  // print_r($result);
                  //if(!array_key_exists("error",$result)) {
                     // Kiểm tra tên sản phẩm có bị trùng với tên sản phẩm nào đó trong csdl
                     /*$isExist = db_query([
                        's'=>['id'],
                        'f'=>['product_info'],
                        'w'=>['name','=']
                     ],[$_POST["name"]]);*/
                     //if(count($isExist) > 0) {
                        // Báo lỗi nếu tên sản phẩm bị trùng với tên sản phẩm nào đó trong csdl
                        // echo_json(['msg' => 'not_ok', 'error' => 'Tên loại sản phẩm này đã tồn tại.']);
                    // } else {
                        // Kiểm tra admin có upload hình ảnh sản phẩm
                        // insert dữ liệu sản phẩm vào csdl
                       /* $insert = db_insert_id('product_info',[
                           'name'=>$_POST["name"],
                           'user_id'=>(int)$_SESSION['id'],
                           'category_id'=>$_POST["name_type"],
                           'description'=>$_POST["name_desc"],
                           'count'=>$_POST["count"],
                           'price'=>$_POST["price"],
                           'img_name'=>""
                        ]);*/
                        //if($insert > 0){
                           //$image = "";
                           /*file_upload(
                              ['file' => 'img_sanpham_file','not_file' => 'img_sanpham'],
                              'product_info','img_name',_DIR_['IMG']['ADMINS'].'product/',$insert,$image
                           );*/
                           
                           // Sau khi insert thông tin sản phẩm thành công, lấy tên loại sản phẩm của sản phẩm đó
                           //if(db_update_by_id('product_info',['img_name' => $image],[$insert])){
                              /*$ten = fetch_row([
                                 's'=>['product_type.name as "ten"'],
                                 'ij'=>['product_info','product_type'],
                                 'o' =>['product_info.category_id','product_type.id'],
                                 'w_a'=>[['product_info.is_delete','='],['product_info.id','=']],
                                 'lim' => ['?']
                              ],[0,$insert,1])['ten'];*/
                              // Thông báo cho admin biết dữ liệu insert thành công.
                              
                           /*} else {
                              echo_json(["msg" => "not_ok","error" => "Network has problem. Please reload this page."]);
                           }*/
                       /* } else {
                           echo_json(['msg' => 'not_ok','error' => 'Network has problem. Please reload this page']);
                        }
                     }
                  }*/
                  /*$result = php_validate([
                     $_POST["status"].'a' => ["required"=>"","equal"=>"Update"],
                     $_POST["id"].'b' => ["required"=>""],
                  ]);*/
                  // Admin thực hiện chức năng update sản phẩm
                  //if(!array_key_exists("error",$result)){
                     // upload ảnh đại diện của sản phẩm.
                    /* $image = "";
                     // print_r("khoi_dep_trai");
                     file_upload(
                        ['file' => 'img_sanpham_file','not_file' => 'img_sanpham'],
                        'product_info','img_name',_DIR_['IMG']['ADMINS'].'product/',(int)$_POST["id"],$image
                     );
                     // upload ảnh mô tả sản phẩm
                     $__arr = [];
                     $__arr_ext = [];
                     $id = fetch_row_1("select id from product_image order by id desc limit 1")["id"];
                     $num_file_upload = multiple_file_upload(['file' => 'anh_mo_ta','dirname' => _DIR_['IMG']['ADMINS'].'product/'. $_POST["id"] . "/",'img_id' => $id,'file_name' => 'anh_mo_ta_'],$__arr_ext);
                     if(array_key_exists('num_file_success',$num_file_upload)){
                        $len = $num_file_upload['num_file_success'] + $id;
                        $j = 0;
                        for($i = $id ; $i < $len ; $i++) {
                           array_push($__arr,[$_POST["id"],"anh_mo_ta_" . $i . "." . $__arr_ext[$j]]);
                           $j++;
                        }
                        db_insert_more_row('product_image',['product_info_id','img_id'],$__arr);
                     }*/
                     
                     // update dữ liệu sản phẩm vào csdl
                     /*if(db_update_by_id('product_info',[
                        'name'=>$_POST["name"],
                        'user_id'=>(int)$_SESSION['id'],
                        'category_id'=>(int)$_POST["name_type"],
                        'description'=>$_POST["name_desc"],
                        'count'=>(int)$_POST["count"],
                        'price'=>(int)$_POST["price"],
                        'img_name'=>(($image != "") ? $image : NULL)
                     ],[(int)$_POST["id"]])){*/
                        
                        // Sau khi update thông tin sản phẩm thành công, lấy tên loại sản phẩm của sản phẩm đó
                       /* $ten = fetch_row([
                           's'=>['product_type.name'],
                           'ij'=>['product_info','product_type'],
                           'o' =>['product_info.category_id','product_type.id'],
                           'w_a'=>[['product_info.is_delete','='],['product_info.id','=']],
                           'lim' => ['?']
                        ],[0,$_POST["id"],1])['name'];*/
                        // Thông báo cho admin biết dữ liệu Update thành công.
                        //echo_json(["msg" => "ok",'success' => "Update dữ liệu thành công.","id"=>$_POST["id"],"name"=>$_POST["name"],"name_desc"=>$_POST["name_desc"],"name_type"=>$ten,"image"=>$image,"price"=>$_POST["price"],"count"=>$_POST["count"],"created_at"=>date('Y-m-d H-i-s',time())]);
                     /*}
                  }
               }
            } */
            //if(isset($_POST["product_image_id"])){
               // Delete ảnh mô tả của sản phẩm
               /*$result = php_validate([
                  $_POST["status"]. 'a' => ["required"=>"Thao tác","equal"=>"xoa_anh_mo_ta"],
                  $_POST["id"].'b' => ["required"=>"Id"],
                  $_POST["product_image_id"]."c" => ["required" => "Id hình ảnh"]
               ]);*/
              /* if(!array_key_exists('error',$result)){
                  $img_id = fetch_row_1("select img_id from product_image where id = ?",[$_POST["product_image_id"]]);
                  if(count($img_id) == 1) {
                     $url_image = _DIR_["IMG"]["ADMINS"] . "product/" . $_POST["id"] . "/" . $img_id["img_id"];
                     $bool = file_exists($url_image) ? unlink($url_image) : false;
                     ($bool && db_delete_by_id("product_image",[$_POST["product_image_id"]])) ? echo_json(["msg" => "ok","success" => "Bạn đã Delete ảnh mô tả thành công"]) : echo_json(["msg" => "not_ok","error" => "Đã có lỗi xảy ra. Vui lòng reload lại trang."]);
                  }
               }
            }
         }
      } else {
         echo "<script type='text/javascript'>alert('Token expired !');location.href='admin_san_pham.php';</script>";
      }*/
    }
?>