<?php
   include_once("../lib/database.php");
   redirect_if_login_status_false();
   if(is_get_method()) {
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
      // code to be executed get method
      $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $where = "where 1=1 ";
      if($keyword || $keyword == 0 ) {
        if($search_option == "title") {
            $where .= "and lower(title) like lower('%$keyword%')";
        } else if($search_option == "content") {
            $where .= "and lower(content) like lower('%$keyword%')";
        } else if($search_option == "all") {
            $where .= "and lower(title) like lower('%$keyword%') ";
            $where .= "or lower(content) like lower('%$keyword%') ";
        } 
      }
      
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/summernote.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<style>
	.kh-file-lists {
		display:flex;
		flex-direction:column;
	}
	.kh-file-list {
		display:flex;
		flex-wrap:nowrap;
		flex-direction:row;
		position: relative;
		cursor: pointer;
		text-align: center;
		background-size: 100%;
		background-repeat: no-repeat;
	}
	.kh-custom-file {
		position:relative;
		border: 1px solid #ddd;
		border-radius:5px;
		width: 88px;
		height: 90px;
		margin-right:15px;
		margin-bottom:15px;
	}
	.kh-custom-file input[type='file'] {
		display: block;
		position: absolute !important;
		width: 90px;
		height: 100%;
		opacity: 0;
		cursor: pointer;
		left: 0;
	}
	.kh-custom-remove-img {
		position:relative;
	}
	.kh-custom-btn-remove {
		font-size:22px;
		position: absolute;
		right: -9px;
		top: -15px;
	}
	.kh-custom-btn-remove::before {
		content: "\2716";
		font-size: 20px;
		color: rebeccapurple
	}
	.kh-div-append-file{
		width:90px;
		height:90px;
		
	}
	.kh-div-append-file button.kh-btn-append-file {
		width:100%;
		height:100%;
		font-size:40px;
		background-color:#fff;
		border-radius:5px;
		border: 1px solid #ddd;
	}
	.kh-files {
		display:flex;
		justify-content:space-between;
	}
</style>
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
  /*#m-bang-tin_wrapper .buttons-html5 {
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
  <div class="container-fluid" style="padding:0px;">
    <section class="content">
        <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý bảng tin</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        <div class="input-group-append">
                           <button id="btn-them-bang-tin" class="btn btn-success">
                              Tạo bảng tin
                           </button>
                        </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <div class="col-12" style="padding-right:0px;padding-left:0px;">
                        <form style="margin-bottom: 17px;display:flex;" action="notification_manage.php" method="get">
                           <div class="" >
                              <select class="form-control" name="search_option">
                                <option value="">Cột tìm kiếm</option>
                                <option value="title" <?=$search_option == 'title' ? 'selected="selected"' : '' ?>>Tiêu đề</option>
                                <option value="content" <?=$search_option == 'content' ? 'selected="selected"' : '' ?>>Nội dung</option>
                                <option value="all" <?=$search_option == 'all' ? 'selected="selected"' : '' ?>>Tất cả</option>
                              </select>
                           </div>
                           <div class="ml-10" style="display:flex;">
                              <input type="text" name="keyword" placeholder="Nhập từ khoá..." class="form-control" value="<?=$keyword;?>">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                           </div>
                        </form>
                     </div>
                     <table id="m-bang-tin" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>Số thứ tự</th>
                              <th>Tiêu đề</th>
                              <th>Nội dung</th>
                              <th>Lượt xem</th>
                              <th>Ngày tạo</th>
                              <th>Thao tác</th>
                           </tr>
                        </thead>
                        <tbody id="list-bang-tin">
                        <?php
                        $get = $_GET;
                        unset($get['page']);
                        $str_get = http_build_query($get);
                        // query
                           $arr_paras = [];
                           $where .= " and is_delete = 0";
                           $keyword = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : null;
                           if($keyword) {
                              $where .= "";
                           }
                           $cnt = 0;
                           $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                           $limit = $_SESSION['paging'];
                           $start_page = $limit * ($page - 1);
                           $sql_get_total = "select count(*) as 'countt' from notification n $where";
                           $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                           array_push($arr_paras,$start_page);
                           array_push($arr_paras,$limit);
                           $sql_get_product = "select * from notification n $where order by n.id desc limit ?,?";
                           print_r($sql_get_product);
                           $rows = db_query($sql_get_product,$arr_paras);
                           foreach($rows as $row) {
                           ?>
                              <tr id="bang-tin<?=$row["id"];?>">
                                 <td><?=$total - ($start_page + $cnt);?></td>
                                 <td><?=$row['title']?></td>
                                 <td><?=$row['content']?></td>
                                 <td><?=$row['views']?></td>
                                 <td><?=$row['created_at'] ? Date("d-m-Y H:i:s",strtotime($row['created_at'])) : "";?></td>
                                 <td>
                                    <button class="btn-sua-bang-tin btn btn-primary" data-number="<?=$total - ($start_page + $cnt);?>"
                                    data-id="<?=$row["id"];?>" >
                                    Sửa
                                    </button>
                                    <button class="btn-xoa-bang-tin btn btn-danger" data-id="<?=$row["id"];?>">
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
                              <th>Tiêu đề</th>
                              <th>Nội dung</th>
                              <th>Lượt xem</th>
                              <th>Ngày tạo</th>
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
        <h4 id="msg-del" class="modal-title">Thông tin bảng tin</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-bang-tin" method="post" enctype='multipart/form-data'>
            
        </form>
      </div>
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
   var arr_list_file_del = [];
	var arr_input_file = new Map();
   function init_map_file(){
      if($('input[name="list_file_del"]').val() != "") {
         arr_list_file_del = $('input[name="list_file_del"]').val().split(",");
      }
	
      console.log(arr_list_file_del);
      if(arr_list_file_del != ['']) {
         arr_list_file_del.forEach((element) => {
            arr_input_file.set(element,element + "_has");
         });
      }
   }
	console.log(arr_input_file);
   //var arr_input_file = new Map();
	// update
	function readURLChange(input,key) {
		// key = "file_" + key;
		// 8_del, 8_upt
		 let target = event.currentTarget;
		 console.log(input.files);
		 if (input.files && input.files[0]) {
			var reader = new FileReader();
			if(arr_input_file.has(key)) {
				//arr_input_file.set(key,key + "_upt");
				if(arr_input_file.get(key).indexOf("_has") == -1) {
					if(arr_input_file.get(key).indexOf("_del") > 0) {
						//let file_img_del = $(input).closest('.kh-custom-file').attr('data-src');
						arr_input_file.set(key,key + "_upt");
					} else {
						console.log("aaaa");
					}
				} else {
					console.log("true_upt" + arr_input_file.get(key));
					//let file_img_del = $(input).closest('.kh-custom-file').attr('data-src');
					arr_input_file.set(key,key + "_upt");
				}
			} else {
				arr_input_file.set(key,key + "_ins");
				console.log(arr_input_file);
			}
			reader.onload = function (e) {
			   $(target).parent().css({
				'background-image' : 'url("' + e.target.result + '")',
				'background-size': 'cover',
				'background-position': '50%'
			   });
			  $(target).siblings('.kh-custom-remove-img').css({'display': 'block'});
			}
			reader.readAsDataURL(input.files[0]);
		 }
	}
	function removeImageChange(input,key){
		//key = "file_" + key;
		$(input).parent().css({'display':'none'});
		$(input).closest('.kh-custom-file').css({'background-image':'url()'});
		arr_input_file.set(key,key + "_upt");
	}
	function removeImageDel(input,key) {
		//key = "file_" + key;
		$(input).parent().css({'display':'none'});
		$(input).closest('.kh-custom-file').remove();
		//console.log(file_img_del);
		$(input).closest('.kh-custom-file').css({'background-image':'url()'});
		console.log(arr_input_file.get(key));
		if(arr_input_file.has(key)) {
			if(arr_input_file.get(key).indexOf("_has") == -1) {
				//console.log("false_has : " + arr_input_file[key]);
				if(arr_input_file.get(key).indexOf("_upt") > 0){
					arr_input_file.set(key,key + "_del");
				} else {
					arr_input_file.delete(key);
				}
			} else {
				//console.log("true_del" + arr_input_file[key]);
				arr_input_file.set(key,key + "_del");
			}
		}
		/*} else {
			console.log("del_key_ins_upt : " + arr_input_file.get(key));
			arr_input_file.delete(key);
		}*/
	}
	function gameChange(){
		$('input[name="list_file_del"]').val(Array.from(arr_input_file.values()).join(","));
		console.log(Array.from(arr_input_file.values()).join(","));
		//return true;
	}
	//

	function readURL(input,key) {
		// key = "file_" + key;
		// 8_del, 8_upt
      let target = event.currentTarget;
      console.log(input.files);
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         arr_input_file.set(key,key);
         console.log(arr_input_file);
         reader.onload = function (e) {
            $(target).parent().css({
            'background-image' : 'url("' + e.target.result + '")',
            'background-size': 'cover',
            'background-position': '50%'
            });
            //$(target).siblings('.kh-custom-remove-img').css({'display': 'block'});
         }
         reader.readAsDataURL(input.files[0]);
      }
	 };
   function removeImage(input,key){
      //key = "file_" + key;
      $(input).parent().css({'display':'none'});
      $(input).closest('.kh-custom-file').remove();
      arr_input_file.delete(key);
   }
   function game() {
      $('input[name="list_file_del"]').val(Array.from(arr_input_file.keys()).join(","));
      console.log(Array.from(arr_input_file.keys()).join(","));
      //return true;
   }
   function addFileInput(parent){
      let game_start = $(".kh-custom-file").last().attr('data-id');
      let count = $(".kh-file-list:last-child .kh-custom-file").length;
      game_start = parseInt(game_start) + 1;
      if(isNaN(game_start)) {
         game_start = 1;
      }
      let file_html = `
      <div data-id=${game_start} class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
         <input class="nl-form-control" name="img[]" type="file" onchange="readURL(this,'${game_start}')">
         <input type="hidden" name="image" value="">
         <div class="kh-custom-remove-img" style="display:block;">
            <span class="kh-custom-btn-remove" onclick="removeImage(this,'${game_start}')"></span>
         </div>
      </div>`;
      if(count % 6 == 0){
         file_html = `<div class="kh-file-list">${file_html}</div>`;
         $(file_html).appendTo('.kh-file-lists');
      } else {
         $(file_html).appendTo(parent);
      }
   }
   function addFileInputChange(parent){
      let game_start = $(".kh-custom-file").last().attr('data-id');
      let count = $(".kh-file-list:last-child > .kh-custom-file").length;
      console.log(count);
      game_start = parseInt(game_start) + 1;
      if(isNaN(game_start)) {
         game_start = 1;
      }
      
      let file_html = `
      <div data-id=${game_start} class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
         <input class="nl-form-control" name="img[]" type="file" onchange="readURLChange(this,'${game_start}')">
         <input type="hidden" name="image" value="">
         <div class="kh-custom-remove-img" style="display:block;">
            <span class="kh-custom-btn-remove" onclick="removeImageDel(this,'${game_start}')"></span>
         </div>
      </div>`;
      if(count % 6 == 0){
         file_html = `<div class="kh-file-list">${file_html}</div>`;
         $(file_html).appendTo('.kh-file-lists');
      } else {
         $(file_html).appendTo(parent);
      }
   }
</script>
<script>
   var dt_n;
   $(document).ready(function (e) {
      dt_n = $("#m-bang-tin").DataTable({
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
         "order": [[ 0, "desc" ]],
         "paging":false,
         "searchHighlight": true,
         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      })
      dt_n.buttons().container().appendTo('#m-bang-tin_wrapper .col-md-6:eq(0)');
   });
</script>
<script>
   $(document).ready(function(){
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
        let title = $('input[name=ten_san_pham]').val();
        let content = $('#summernote').summernote('code');
        /*if(title.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Tiêu đề không được để trống"
			});
            test = false;
        } else if(content.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Nội dung bảng tin không được để trống"
            });
            test = false;
        }*/
        test = true;
        return test;
      }
      // Insert san pham
      var click_number;
      $(document).on('click','#btn-them-bang-tin',function(event){
         let number = parseInt($('tbody tr').length) + 1;
         click_number = $(this).closest('tr');
         $('#form-bang-tin').load("ajax_notification.php?number=" + number,() => {
            $('#modal-xl').modal('show');
            $('#btn-luu-bang-tin').text("Insert");
            $(function(){
               setTimeout(() => {
                  $('#summernote').summernote({height: 120});
               },100);
               /*$(".parent[data-id]").click(function(e){
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
               })*/
               init_map_file();
            });
            $("#fileInput").on("change",function(){
               $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
               readURL(this); 
            });
         });
      });
      // Update sản phẩm
      $(document).on('click','.btn-sua-bang-tin',function(event){
         let id = $(event.currentTarget).attr('data-id');
         click_number = $(this).closest('tr');
         let number = $(event.currentTarget).attr('data-number');
         $('#form-bang-tin').load("ajax_notification.php?id=" + id + "&number=" + number,() => {
            $('#modal-xl').modal('show');
            $('#btn-luu-bang-tin').text("Update");
            $(function(){
               setTimeout(() => {
                  $('#summernote').summernote({height: 120});
               },100);
               /*$(".parent[data-id]").click(function(e){
                  let child = $(e.currentTarget).find('li').length;
                  if(!child){
                     let id = $(e.currentTarget).attr('data-id');
                     let name = $(e.currentTarget).text();
                     name = name.substr(0,name.length - 1);
                     console.log(name);
                     $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                        $("input[name='category_id']").val(id);
                        $("input[name='category_name']").val(name);
                        $("#breadcrumb-menu").empty();
                        $("#breadcrumb-menu").append(data);
                        $("#breadcrumb-menu").parent().css({"margin-top":"-25px"});
                     });
                  }
               })*/
               init_map_file();
            });
            $("#fileInput").on("change",function(){
               $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
               readURL(this); 
            });
         });
      });
      // Delete sản phẩm
      $(document).on('click','.btn-xoa-bang-tin',function(event){
         let id = $(event.currentTarget).attr('data-id');
         click_number = $(this).closest('tr');
         $.confirm({
            title: 'Thông báo',
            content: 'Bạn có chắc chắn muốn xoá bảng tin này ?',
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
                        console.log(res);
                        res_json = JSON.parse(res);
                        if(res_json.msg == "ok") {
                           $.alert({
                              title: "Thông báo",
                              content: res_json.success
                           });
                           dt_n.row(click_number).remove().draw();
                        } else {
                           $.alert({
                              title: "Thông báo",
                              content: res_json.error
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
      // xử lý thao tác Insert Update
      $(document).on('click','#btn-luu-bang-tin',function(event){
         event.preventDefault();
         let formData = new FormData($('#form-bang-tin')[0]);
         let number = 1;
         formData.append('token',"<?php echo_token(); ?>");
         formData.append('id',$('input[name=id]').val());
         formData.append('title',$('input[name=title]').val());
         formData.append('content',$('#summernote').summernote('code'));
         formData.append('status',$('#btn-luu-bang-tin').text().trim());
         if(status == "Insert"){
               game();
               number = parseInt($('tbody tr').length) + parseInt(number);
         } else {
               gameChange();
               number = $('tbody tr td:first-child').text();
         }
         formData.append('list_file_del',$('input[name="list_file_del"]').val());
         let img = document.getElementsByName('img[]');
         let file = $('input[name=img_bangtin_file]')[0].files;
         //console.log(file);
         if(file.length > 0) {
            formData.append('img_bangtin_file',file[0]); 
         }
         if(img.length > 0) {
            let len = img.length;
            for(let i = 0 ; i < len ;i++) {
               formData.append('img',$('input[name="img[]"]')[i].files);
            }
         }
         if(validate()) {
            $.ajax({
               url:window.location.href,
               type:"POST",
               cache:false,
               dataType:"json",
               contentType: false,
               processData: false,
               data:formData,
               success:function(res_json){
                  console.log(res_json);
                  if(res_json.msg == 'ok'){
                     let status = $('#btn-luu-bang-tin').text().trim();
                     let record = `
                     <tr id="bang-tin${res_json.id}">
                           <td>${res_json.number}</td>
                           <td>${res_json.title}</td>
                           <td>${res_json.content}</td>
                           <td>0</td>
                           <td>${res_json.created_at}</td>
                           <td>
                              <button data-id="${res_json.id}" data-name="${name}" data-count="${res_json.count}" data-price="${res_json.price}" data-name_type="${res_json.category_id}" data-image="${res_json.image}" class="btn-sua-bang-tin btn btn-primary">
                                 Sửa
                              </button>
                              <button data-id="${res_json.id}" style="margin-left:3px;" class="btn-xoa-bang-tin btn btn-danger">
                                 Xoá
                              </button>
                           </td>
                     </tr>`;
                     record = $(record);
                     let msg ="";
                     if(status == "Insert"){
                        $('#list-bang-tin').prepend(record);
                        setTimeout(() => {
                              $("#bang-tin" + res_json.id).css('background-color','#d4efecc2');
                        },1000);
                        msg = "Thêm dữ liệu thành công.";
                        $.alert({
                              title: "Thông báo",
                              content: msg
                        });
                        dt_n.row.add(record[0]).draw();
                        //alert(msg);
                        if($('#display-image').length){
                              $('#display-image').replaceWith('<div data-img="" class="img-fluid" id="where-replace">' + "<span></span>" + "</div>");
                        }
                     } else if(status == "Update") {
                        console.log(res_json);
                        msg = "Sửa dữ liệu thành công.";
                        $.alert({
                              title: "Thông báo",
                              content: msg
                        });
                        let one_row = dt_n.row(click_number).data();
                        one_row[0] = `${res_json.number}`;
                        one_row[1] = `${res_json.title}`;
                        one_row[2] = `${res_json.content}`;
                        dt_n.row(click_number).data(one_row).draw();
                     }
                     $('#form-bang-tin').trigger('reset');
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
      prevText: "<",
      nextText: ">",
		onPageClick: function(){

		},
      cssStyle: 'light-theme'
    });
  });
</script>905
<script>
   $(function(){
      $('.breadcrumb-item').click(function(){
         $('.kh-submenu').toggleClass('.kh-submenu-active');
      });
   });
</script>
<!--js section end-->
<?php
        include_once("include/footer.php");
?>
<?php
	function getFileUpload($i_order,$id){
		$sql = "select img_id from notification_image where product_img_id = '$id' and i_order = '$i_order' limit 1";
		$file_old_name = fetch_row($sql)['img_id'];
		return $file_old_name;
	}
?>
<?php
   } else if (is_post_method()) {
     // print_r($_FILES);
     // print_r($_POST["list_file_del"]);
      $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
      $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
      $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
      $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
      $title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : null;
      $content = isset($_REQUEST["content"]) ? $_REQUEST["content"] : null;
      //
      $list_file_del = isset($_REQUEST["list_file_del"]) ? $_REQUEST["list_file_del"] : null;
      if($list_file_del){
         $list_file_del = explode(",",$list_file_del);
      } else {
         $list_file_del = [];
      }
      //
      //$image_str = isset($_REQUEST["image_str"]) ? $_REQUEST["image_str"] : null;
      if($status == 'Delete') {
         $success = "Bạn đã Delete dữ liệu thành công";
         $error = "Network has problem. Please try again.";
         ajax_db_update_by_id('notification',['is_delete' => 1],[$id],["id" => $id,"success" => $success],['error' => $error]);
      } else if($status == "Insert") {
         $sql_check_exist = "select count(*) as 'countt' from notification where id = ?";
         $row = fetch_row($sql_check_exist,[$id]);
         if($row['countt'] > 0) {
            $error = "Tiêu đề bảng tin này đã tồn tại.";
            echo_json(['msg' => 'not_ok', 'error' => $error]);
         } else {
            $insert = db_insert_id('notification',['title'=>$title,'content'=>$content,"created_at"=>date('Y-m-d H-i-s',time()),'img_name'=>null]);
            if($insert > 0) {
               $image = null;
               //
               $dir = "upload/notify/";
               if(!file_exists($dir)) {
                  mkdir($dir, 0777); 
                  chmod($dir, 0777);
               }
               $dir = "upload/notify/" . $insert;
               if(!file_exists($dir)) {
                  mkdir($dir, 0777); 
                  chmod($dir, 0777);
               }
               //
               //file_upload(['file' => 'img_bangtin_file'],'notification','img_name',$dir,$insert,$image);
               if($_FILES['img_bangtin_file']['name'] != "") {
                  $ext = strtolower(pathinfo($_FILES['img_bangtin_file']['name'],PATHINFO_EXTENSION));
                  $file_name = md5(rand(1,999999999)). $id . "." . $ext;
                  $file_name = str_replace("_","",$file_name);
                  $path = $dir . "/" . $file_name ;
                  move_uploaded_file($_FILES['img_bangtin_file']['tmp_name'],$path);
                  $sql_update = "update notification set img_name='$path' where id = '$insert'";
                  db_query($sql_update);
               }
               $sql = "Insert into notification_image(notify_id,img_id,img_order) values";
               if(count($_FILES['img']['name']) > 0) {
                  $__arr = [];
                  $i = 0;
                  foreach($_FILES['img']['error'] as $key => $error) {
                     if($error == UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['img']['name'][$key],PATHINFO_EXTENSION));
                        $file_name = md5(rand(1,999999999)) . $insert . "." . $ext;
                        $file_name = str_replace("_","",$file_name);
                        $path = $dir . "/" . $file_name ;
                        move_uploaded_file($_FILES['img']['tmp_name'][$key],$path);
                        @chmod($dir, 0777);
                        $j = $list_file_del[$i];
                        array_push($__arr,"('$insert','$path',$j)");
                     }
                     $i++;
                  }
                  if(count($__arr) > 0) {
                     $sql .= implode(",",$__arr);
                     db_query($sql);
                  }
               }
               $success = "Insert dữ liệu thành công.";
               echo_json(["msg" => "ok","number"=>$number,"success" => $success,"id"=>$insert,"title"=>$title,"content"=>$content,"image"=>$image,"created_at"=>date('d-m-Y H-i-s',time())]);
            }
         }
      } else if($status == "Update") {
         $image = null;
         $dir = "upload/product/" . $id;
         if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
         }
         //file_upload(['file' => 'img_bangtin_file'],'notification','img_name',$dir,$id,$image);
         if($_FILES['img_bangtin_file']['name'] != "") {
            $sql_get_old_file = "select img_name from notification where id = '$id'";
            $old_file = fetch_row($sql_get_old_file)['img_name'];
            if(file_exists($old_file)){
               unlink($old_file);
            }
            $ext = strtolower(pathinfo($_FILES['img_bangtin_file']['name'],PATHINFO_EXTENSION));
            $file_name = md5(rand(1,999999999)). $id . "." . $ext;
            $file_name = str_replace("_","",$file_name);
            $path = $dir . "/" . $file_name ;
            move_uploaded_file($_FILES['img_bangtin_file']['tmp_name'],$path);
            $sql_update = "Update notification set img_name='$path' where id = '$id'";
            db_query($sql_update);
         }
         
         $list_file_del_length = count($list_file_del);
         for($i = 0 ; $i < count($list_file_del) ; $i++) {
            if(strpos($list_file_del[$i],"_del") !== false) {
               $i_order = explode("_",$list_file_del[$i])[0];
               $file_old_name = getFileUpload($i_order,$id);
               if(file_exists($file_old_name)) {
                  unlink($file_old_name);
                  chmod($dir, 0777);
               }
               $sql_delete_file = "Delete from notification_image where id = '$id' and img_order = $i_order";
               db_query($sql_delete_file);
               array_splice($list_file_del,$i, 1);
               $i--;
            }
         }
         //print_r($list_file_del);
         if(count($_FILES['img']['name']) > 0) {
            $file_old_name = "";
            $__arr = [];
            $i = 0;
            $sql = "Insert into notification_image(notify_id,img_id,img_order) values";
            foreach($_FILES['img']['error'] as $key => $error) {
               if($error == UPLOAD_ERR_OK) {
                  $ext = strtolower(pathinfo($_FILES['img']['name'][$key],PATHINFO_EXTENSION));
                  $file_name = md5(rand(1,999999999)). "." . $ext;
                  $file_name = str_replace("_","",$file_name);
                  $path = $dir . "/" . $file_name ;
                  if(strpos($list_file_del[$i],"_ins") !== false) {
                     move_uploaded_file($_FILES['img']['tmp_name'][$key],$path);
                     @chmod($dir, 0777);
                     $j = explode("_",$list_file_del[$i])[0];
                     //print_r($j)
                     array_push($__arr,"('$id','$path',$j)");
                     //print_r($__arr);
                  } else if(strpos($list_file_del[$i],"_upt") !== false) {
                     $i_order = explode("_",$list_file_del[$i])[0];
                     $file_old_name = getFileUpload($i_order,$id);
                     if(file_exists($file_old_name)) {
                        unlink($file_old_name);
                        chmod($dir, 0777);
                     }
                     move_uploaded_file($_FILES['img']['tmp_name'][$key],$path);
                     @chmod($dir, 0777);
                     $sql_update_file = "Update notification_image set img_id = '$path' where notification_id='$id' and i_order='$i_order'";
                     db_query($sql_update_file);
                  }
               } 
               $i++;
            }
            if(count($__arr) > 0) {
               $sql .= implode(",",$__arr);
               //print_r($sql);
               db_query($sql);
            }
         }
         if($image) {
            db_update_by_id('notification',['title'=>$title,'content'=>$content,'img_name'=>$image],[$id]);
         } else {
            db_update_by_id('notification',['title'=>$title,'content'=>$content],[$id]);
         }
         $success = "Update dữ liệu thành công.";
         $sql_get_file_name = "select img_name from notification where id = ?";
         $image = fetch_row($sql_get_file_name,[$id]);
         if($image) {
            $image = $image['img_name'];
         }
         echo_json(["msg" => "ok","number" => $number,'success' => $success,"id" => $id,"title"=>$title,"content"=>$content]);
      }
   }
?>