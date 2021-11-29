<?php
   include_once("../lib/database.php");
   redirect_if_login_status_false();
   if(is_get_method()) {
      // permission crud for user
      $allow_read = $allow_update = $allow_delete = $allow_insert = false; 
      if(check_permission_crud("notification_manage.php","read")) {
        $allow_read = true;
      }
      if(check_permission_crud("notification_manage.php","update")) {
        $allow_update = true;
      }
      if(check_permission_crud("notification_manage.php","delete")) {
        $allow_delete = true;
      }
      if(check_permission_crud("notification_manage.php","insert")) {
        $allow_insert = true;
      }
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
      // code to be executed get method
      $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
      $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
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
   .dt-buttons {
      float:left;
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
<link rel="stylesheet" href="css/select.dataTables.min.css">
<link rel="stylesheet" href="css/colReorder.dataTables.min.css">
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
                           <?php
                              if($allow_insert) {
                           ?>
                           <button id="btn-them-bang-tin" class="dt-button button-blue">
                              Tạo bảng tin
                           </button>
                           <?php } ?>
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
                     <div class="col-12 mb-3 d-flex j-between" style="padding-right:0px;padding-left:0px;">
                        <div>
                           <?php
                              if($allow_delete) {
                           ?>
                           <button onclick="delMore()" id="btn-delete-fast" class="dt-button button-red">Xoá nhanh</button>
                           <?php } ?>
                           <?php
                              if($allow_update) {
                           ?>
                           <button onclick="uptMore()" id="btn-upt-fast" class="dt-button button-green">Sửa nhanh</button>
                           <?php } ?>
                           <?php
                              if($allow_read) {
                           ?>
                           <button onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                           <?php } ?>
                           <?php
                              if($allow_insert) {
                           ?>
                           <button onclick="insMore()" id="btn-ins-fast" class="dt-button button-blue">Thêm nhanh</button>
                           <?php } ?>
                        </div>
                        <div class="section-save">
                           <?php
                              if($upt_more == 1 && $allow_update){
                           ?>
                           <button onclick="uptAll()" class="dt-button button-green">Lưu thay đổi ?</button>
                           <?php } ?>
                        </div>
                     </div>
                     <table id="m-bang-tin" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th></th>
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
                           if($str) {
                              $where .= " and n.id in ($str)";
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
                           //print_r($sql_get_product);
                           $rows = db_query($sql_get_product,$arr_paras);
                           foreach($rows as $row) {
                           ?>
                              <tr id="<?=$row["id"];?>">
                                 <td></td>
                                 <td><?=$total - ($start_page + $cnt);?></td>
                                 <td><?=$upt_more == 1 ? "<input class='kh-inp-ctrl' type='text' name='n_title' value='$row[title]'" : $row['title'];?></td>
                                 <td><?=$upt_more == 1 ? "<textarea class='t-summernote' name='n_content'>" . $row['content'] . "</textarea>" : $row['content']?></td>
                                 <td><?=$row['views']?></td>
                                 <td><?=$row['created_at'] ? Date("d-m-Y H:i:s",strtotime($row['created_at'])) : "";?></td>
                                 <td>
                                    <?php
                                       if($upt_more != 1) {
                                    ?>
                                    <?php
                                       if($allow_read) {
                                    ?>
                                       <button class="btn-xem-bang-tin dt-button button-grey"
                                       data-id="<?=$row["id"];?>">
                                       Xem
                                       </button>
                                    <?php } ?>
                                    <?php 
                                       if($allow_update) {
                                    ?>
                                       <button class="btn-sua-bang-tin dt-button button-green" data-number="<?=$total - ($start_page + $cnt);?>"
                                       data-id="<?=$row["id"];?>" >
                                       Sửa
                                       </button>
                                    <?php } ?>
                                    <?php
                                       if($allow_delete) {
                                    ?>
                                       <button class="btn-xoa-bang-tin dt-button button-red" data-id="<?=$row["id"];?>">
                                       Xoá
                                       </button>
                                    <?php } ?>
                                    <?php
                                       } else {
                                    ?>
                                    <?php
                                       if($allow_update) {
                                    ?>
                                       <button dt-count="0" data-id="<?=$row["id"];?>" onclick="uptThisRow()" class="dt-button button-green">Sửa</button>
                                    <?php } ?>
                                    <?php
                                       }
                                    ?>
                                 </td>
                              </tr>
                           <?php
                              $cnt++;
                           }
                           ?>
                        </tbody>
                        <tfoot>
                            <tr>
                              <th></th>
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
<div class="modal fade" id="modal-xl" >
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
<div class="modal fade" id="modal-xl2">
  <div class="modal-dialog modal-xl" style="min-width:1650px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thêm dữ liệu bảng tin nhanh</h4>
        <button onclick="insAll()" class="dt-button button-blue">Lưu dữ liệu</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="form-notify2" class="modal-body">
            <div class="row j-between">
               <div style="margin-left: 7px;" class="form-group">
                  <label for="">Nhập số dòng cần thêm: </label>
                  <input style="margin-left:5px;width: auto;" class="kh-inp-ctrl" type="number" name='count2'>
                  <button onclick="showRow(1)" class="dt-button button-blue">Ok</button>
               </div>
               <div class="d-flex j-between">
                  <div class="k-plus">
                     <button data-plus="1" onclick="insRow()" style="font-size:15px;" class="dt-button button-blue k-btn-plus">+</button>
                  </div>
                  <div class="k-minus">
                     <button data-minus="1" onclick="delRow()" style="font-size:15px;" class="dt-button button-blue k-btn-minus">-</button>
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
<script src="js/summernote.min.js"></script>
<script src="js/summernote-vi-VN.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.select.min.js"></script>
<script src="js/colOrderWithResize.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>
<script src="js/buttons.colVis.min.js"></script>
<script src="js/dataTables.searchHighlight.min.js"></script> 
<script src="js/jquery.highlight.js"></script>
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
   $('.t-summernote').summernote({
      height: 1,
      width: 400,
      lang: 'vi-VN'
   });
   $(document).ready(function (e) {
      dt_n = $("#m-bang-tin").DataTable({
         "sDom": 'RBlfrtip',
         "columnDefs": [
            { 
               "name":"pi-checkbox",
               "orderable": false,
               "className": 'select-checkbox',
               "targets": 0
            },{ 
               "name":"manipulate",
               "orderable": false,
               "className": 'manipulate',
               "targets": 6
            },  
         ],
         "select": {
            style: 'os',
            selector: 'td:first-child'
         },
         "order": [
            [1, 'desc']
         ],
         "language": {
            "emptyTable": "Không có dữ liệu",
            "sZeroRecords": 'Không tìm thấy kết quả',
            "infoEmpty": "",
            "infoFiltered":"Lọc dữ liệu từ _MAX_ dòng",
            "search":"Tìm kiếm trong bảng này:",   
            "info":"Hiển thị từ dòng _START_ đến dòng _END_ trên tổng số _TOTAL_ dòng",
            "select": {
               "rows": "Đã chọn %d dòng",
            }
         },
         "responsive": true, 
         "lengthChange": false, 
         "autoWidth": false,
         "paging":false,
         "searchHighlight": true,
         "oColReorder": {
            "bAddFixed":false
         },
         "buttons": [
          {
            "extend": "copy",
            "text": "Sao chép bảng (1)",
            "key" : {
               "key" : "1",
            },
            "exportOptions":{
               columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "excel",
            "text": "Excel (2)",
            "key" : {
               "key" : "2",
            },
            "autoFilter": true,
            "filename": "danh_sach_bang_tin_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu bảng tin trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
               columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "pdf",
            "text": "PDF (3)",
            "key" : {
               "key" : "3",
            },
            "filename": "danh_sach_bang_tin_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu bảng tin trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
               columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "csv",
            "text": "CSV (4)",
            "filename": "danh_sach_bang_tin_ngay_<?=Date("d-m-Y",time());?>",
            "charset": 'UTF-8',
            "bom":true,
            "key" : {
               "key" : "4",
            },
            "exportOptions":{
               columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "print",
            "text": "In bảng (5)",
            "key" : {
               "key" : "5",
            },
            "filename": "danh_sach_bang_tin_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu bảng tin trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
               columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "colvis",
            "text": "Ẩn / Hiện cột (7)",
            "columns": ':not(.select-checkbox)',
            "key" : {
               "key" : "7",
            }
          }
        ]
      })
      dt_n.buttons().container().appendTo('#m-bang-tin_wrapper .col-md-6:eq(0)');
      //
      dt_n.buttons.exportData( {
         columns: ':visible'
      });
      dt_n.on("click", "th.select-checkbox", function() {
         if ($("th.select-checkbox").hasClass("selected")) {
               dt_n.rows().deselect();
               $("th.select-checkbox").removeClass("selected");
         } else {
               dt_n.rows().select();
               $("th.select-checkbox").addClass("selected");
         }
      }).on("select deselect", function() {
         if (dt_n.rows({
                  selected: true
               }).count() !== dt_n.rows().count()) {
               $("th.select-checkbox").removeClass("selected");
         } else {
               $("th.select-checkbox").addClass("selected");
         }
      });
      //
   });
   function insMore(){
      //$('#modal-xl2').modal('show');
      $('#modal-xl3').modal({backdrop: 'static', keyboard: false});
   }
   function insAll(){
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      $('td input[name="n_title2"]').each(function(){
         formData.append("n_title2[]",$(this).val());
      });
      $('td input[name="n_content2"]').each(function(){
         formData.append("n_content2[]",$(this).val());
      });
      $('td input[name="img3[]"]').each(function(){
         formData.append("img3[]",$(this)[0].files[0]);
      });
      formData.append("token","<?php echo_token(); ?>");
      formData.append("status","ins_all");
      formData.append("len",len);
      $.ajax({
         url: window.location.href,
         type: "POST",
         data: formData,
         cache: false,
         contentType: false,
         processData: false,
         success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
               $.alert({
                  title: "Thông báo",
                  content: "Bạn đã thêm dữ liệu thành công",
                  buttons: {
                     "Ok": function(){
                        location.reload();
                     }
                  }
               });
            }
         },
         error: function(data){
            console.log("Error: " + data);
         }
      })
   }
   function insMore(){
      //$('#modal-xl2').modal('show');
      $('#modal-xl2').modal({backdrop: 'static', keyboard: false});
   }
   function showRow(page,apply_dom = true){
      let count = $('input[name="count2"]').val();
      if(count == "") {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng không để trống số dòng thêm",
        })
        return;
      }
      if(count < 1) {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng nhập số dòng lớn hơn 0",
        })
        return;
      }
      limit = 7;
      if(apply_dom) {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
        $('#form-notify2 table').remove();
        $('#form-notify2 #paging').remove();
        let html = `
        <table class='table table-bordered' style="min-height:100px;height:auto;">
          <thead>
            <tr>
              <th>Số thứ tự</th>
              <th>Tiêu đề</th>
              <th>Nội dung</th>
              <th>Ảnh đại diện</th>
              <th>Thao tác</th>
            </tr>
          </thead>
        `;
        count2 = parseInt(count / 7);
        g = 1;
        for(i = 0 ; i < count2 ; i++) {
          html += `<tbody style='display:none;' class='t-bd t-bd-${parseInt(i) + 1}'>`;
          for(j = 0 ; j < 7 ; j++) {
            html += `
              <tr data-row-id="${parseInt(g)}">
               <td>${parseInt(g)}</td>
               <td><input class='kh-inp-ctrl' name='n_title2' type='text' value=''></td>
               <td><textarea class='kh-inp-ctrl' name='n_content2' value=''></textarea></td>
               <td>
                  <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                     <input class="nl-form-control" name="img3[]" type="file" onchange="readURL(this,'1')">
                  </div>
               </td>
               <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
              </tr>
            `;
            g++;
          }
          html += "</tbody>";
        }
        if(count % 7 != 0) {
          count3 = count % 7;
          html += `<tbody style='display:none;' class='t-bd t-bd-${parseInt(i) + 1}'>`;
          for(k = i ; k < parseInt(count3) + parseInt(i) ; k++) {
            html += `
              <tr data-row-id="${parseInt(g)}">
               <td>${parseInt(g)}</td>
               <td><input class='kh-inp-ctrl' name='n_title2' type='text' value=''></td>
               <td><textarea class='kh-inp-ctrl' name='n_content2' value=''></textarea></td>
               <td>
                  <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                     <input class="nl-form-control" name="img3[]" type="file" onchange="readURL(this,'1')">
                  </div>
               </td>
               <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
              </tr>
            `;
            g++;
          }
          html += "</tbody>";
        }
        html += `
          </table>
        `;
        html += `
          <div id="paging" style="justify-content:center;" class="row">
            <nav id="pagination2">
            </nav>
          </div>
        `;
        $(html).appendTo('#form-notify2');
        apply_dom = false;
        $('.t-bd-1').css({"display":"contents"});
        console.log(html);
      } else {
        $('.t-bd').css({"display":"none"});
        $('.t-bd-' + page).css({"display":"contents"});
      }
      $('#pagination2').pagination({
        items: count,
        itemsOnPage: limit,
        currentPage: page,
        prevText: "<",
        nextText: ">",
        onPageClick: function(pageNumber,event){
          showRow(pageNumber,false);
        },
        cssStyle: 'light-theme',
      });
      $('#modal-xl2').on('hidden.bs.modal', function (e) {
        $('#form-notify2 table').remove();
        $('#form-notify2 #paging').remove();
        $('input[name="count2"]').val("");
      })
   } 
   function insRow(){ 
      let page = $('[data-plus]').attr('data-plus');
      let html = "";
      let count2 = parseInt(page / 7) + 1;
      html = `
         <tr data-row-id='${parseInt(page) + 1}'>
            <td>${parseInt(page) + 1}</td>
            <td><input class='kh-inp-ctrl' name='n_title2' type='text' value=''></td>
            <td><textarea class='kh-inp-ctrl' name='n_content2' value=''></textarea></td>
            <td>
               <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                  <input class="nl-form-control" name="img3[]" type="file" onchange="readURL(this,'1')">
               </div>
            </td>
            <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
         </tr>
      `;
      if(page % 7 != 0) {
         $('.t-bd').css({"display":"none"});
         $(`.t-bd-${parseInt(count2)}`).css({"display":"contents"});
         $(html).appendTo(`.t-bd-${count2}`);
      } else {
         $('.t-bd').css({"display":"none"});
         html = `<tbody style='display:contents;' class='t-bd t-bd-${parseInt(count2)}'>${html}</tbody>`;
         $(html).appendTo('#form-notify2 table');
      }
      $('[data-plus]').attr('data-plus',parseInt(page) + 1);
      $('input[name="count2"]').val(parseInt(page) + 1);
      $('#pagination2').pagination({
         items: parseInt(page) + 1,
         itemsOnPage: 7,
         currentPage: count2,
         prevText: "<",
         nextText: ">",
         onPageClick: function(pageNumber,event){
            showRow(pageNumber,false);
         },
         cssStyle: 'light-theme',
      });
   }
   function insMore2(){
      let name_p2 = $(event.currentTarget).closest('tr').find('td input[name="name_p2"]').val();
      let price_p2 = $(event.currentTarget).closest('tr').find('td input[name="price_p2"]').val();
      let count_p2 = $(event.currentTarget).closest('tr').find('td input[name="count_p2"]').val();
      let desc_p2 = $(event.currentTarget).closest('tr').find('td textarea[name="desc_p2"]').val();
      let type_p2 = $(event.currentTarget).closest('tr').find('td input[name="category_id"]').val();
      let file = $(event.currentTarget).closest('tr').find('input[name="img2[]"]')[0].files;
      console.log(name_p2);
      console.log(price_p2);
      console.log(count_p2);
      console.log(type_p2);
      let formData = new FormData();
      formData.append("name_p2",name_p2);
      formData.append("price_p2",price_p2);
      formData.append("count_p2",count_p2);
      formData.append("type_p2",type_p2);
      formData.append("desc_p2",desc_p2);
      formData.append("status","ins_more");
      formData.append("token","<?php echo_token();?>");
      if(file.length > 0) {
         formData.append('file_p2',file[0]); 
      }
      let this2 = $(event.currentTarget);
      $.ajax({
         url: window.location.href,
         type: "POST",
         cache: false,
         contentType: false,
         processData: false,
         data:formData,
         success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
               $.alert({
                  title: "Thông báo",
                  content: "Bạn đã thêm dữ liệu thành công",
                  buttons: {
                     "Ok": function(){
                        this2.text("Đã thêm");
                        this2.prop("disabled",true);
                        this2.css({
                           "border": "1px solid #cac0c0",
                           "color": "#cac0c0",
                           "pointer-events": "none",
                        });
                     }
                  }
               });
            }
         },error: function(data){
            console.log("Error: " + data);
         }
      })
   }
   function uptAll(){
      let formData = new FormData();
      let _data = dt_pi.rows(".selected").select().data();
      if(_data.length == 0) {
         $.alert({
            title:"Thông báo",
            content:"Vui lòng chọn dòng cần lưu",
         });
         return;
      }
      for(i = 0 ; i < _data.length ; i++) {
         formData.append("n_id2[]",_data[i].DT_RowId);
      }
      $('tr.selected input[name="n_title2"]').each(function(){
         formData.append("n_title2[]",$(this).val());
      });
      $('tr.selected input[name="n_content2"]').each(function(){
         formData.append("n_content2[]",$(this).val());
      });
      $('tr.selected input[name="img3[]"]').each(function(){
         formData.append("img3[]",$(this)[0].files[0]);
      });
      formData.append("token","<?php echo_token(); ?>");
      formData.append("status","upt_all");
      formData.append("len",_data.length);
      $.ajax({
         url: window.location.href,
         type: "POST",
         data: formData,
         cache: false,
         contentType: false,
         processData: false,
         success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
               $.alert({
                  title: "Thông báo",
                  content: "Bạn đã sửa dữ liệu thành công",
                  buttons: {
                     "Ok": function(){
                        location.reload();
                     }
                  }
               });
            }
         },
         error: function(data){
            console.log("Error: " + data);
         }
      })
   }
   function delRow(){
      let page = $('[data-plus]').attr('data-plus');
      let currentPage1 = page / 7;
      if(page % 7 != 0) currentPage1 = parseInt(currentPage1) + 1;
      $(`[data-row-id="${page}"]`).remove();
      page--;
      $('[data-plus]').attr('data-plus',page);
      $('input[name="count2"]').val(page);
      currentPage1 = page / 7;
      if(page % 7 != 0) currentPage1 = parseInt(currentPage1) + 1;
      else $(`.t-bd-${parseInt(currentPage1) + 1}`).remove();
      $('.t-bd').css({"display":"none"});
      $(`.t-bd-${parseInt(currentPage1)}`).css({"display":"contents"});
      $('#pagination2').pagination({
        items: parseInt(page),
        itemsOnPage: 7,
        currentPage: currentPage1,
        prevText: "<",
        nextText: ">",
        onPageClick: function(pageNumber,event){
          showRow(pageNumber,false);
        },
        cssStyle: 'light-theme',
      });
   }
   function readMore(){
      let arr_del = [];
      let _data = dt_n.rows(".selected").select().data();
      let count4 = _data.length;
      for(i = 0 ; i < count4 ; i++) {
         arr_del.push(_data[i].DT_RowId);
      }
      let str_arr_upt = arr_del.join(",");
      if(arr_del.length == 0) {
         $.alert({
            title: "Thông báo",
            content: "Bạn vui lòng chọn dòng cần xem",
         });
         return;
      }
      $('#form-bang-tin').load(`ajax_notification.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
         let html2 = `
            <div id="paging" style="justify-content:center;" class="row">
               <nav id="pagination3">
               </nav>
            </div>
         `;
         $(html2).appendTo('#form-bang-tin');
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
         $('.t-bd-read').css({
            "display":"none",
         });
         $('.t-bd-read-1').css({
            "display":"contents",
         });
         $('#pagination3').pagination({
            items: count4,
            itemsOnPage: 1,
            currentPage: 1,
            prevText: "<",
            nextText: ">",
            onPageClick: function(pageNumber,event){
               $(`.t-bd-read`).css({"display":"none"});
               $(`.t-bd-read-${pageNumber}`).css({"display":"contents"});
            },
            cssStyle: 'light-theme',
         });
      });
   }
   function delMore(){
        let arr_del = [];
        let _data = dt_n.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
               title: "Thông báo",
               content: "Bạn có chắc chắn muốn xoá " + _data.length + " dòng này",
               buttons: {
                  "Có": function(){
                     $.ajax({
                           url: window.location.href,
                           type: "POST",
                           data: {
                              status: "del_more",
                              token: "<?php echo_token(); ?>",
                              rows: arr_del.join(","),
                           },
                           success: function(data){
                              data = JSON.parse(data);
                              if(data.msg == "ok"){
                              $.alert({
                                 title: "Thông báo",
                                 content: "Bạn đã xoá dữ liệu thành công",
                                 buttons: {
                                    "Ok": function(){
                                       location.href="notification_manage.php";
                                    }
                                 }
                              })
                              }
                           },error: function(data){
                              console.log("Error:" + data);
                           }
                     });
                  },"Không": function(){

                  }
               }
            });
        } else {
            $.alert({
                title: "Thông báo",
                content: "Bạn chưa chọn dòng cần xoá",
            });
        }
    }
    function uptMore(){
      let arr_del = [];
      let _data = dt_n.rows(".selected").select().data();
      for(i = 0 ; i < _data.length ; i++) {
         arr_del.push(_data[i].DT_RowId);
      }
      let str_arr_upt = arr_del.join(",");
      location.href="notification_manage.php?upt_more=1&str=" + str_arr_upt;
    }
    function uptThisRow(){
        let title = $(event.currentTarget).closest("tr").find("td input[name='n_title']").val();
        let content = $(event.currentTarget).closest("tr").find("td .t-summernote").summernote('code');
        let id = $(event.currentTarget).attr('data-id');
        let this2 = $(event.currentTarget);
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
               status: "upt_more",
               n_title: title,
               n_content: content,
               n_id: id,
               token: '<?php echo_token();?>'
            },success: function(data){
                data = JSON.parse(data);
                if(data.msg == "ok"){
                $.alert({
                    title: "Thông báo",
                    content: "Bạn đã sửa dữ liệu thành công",
                    buttons: {
                       "Ok" : function(){
                           let num_of_upt = this2.attr('dt-count');
                           num_of_upt++;
                           this2.attr('dt-count',num_of_upt);
                           this2.text(`Sửa (${num_of_upt})`);
                        }
                    }
                });
                }
            },error:function(data){
                console.log("Error: " + data);
            }
        });
    }
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
        if(title.trim() == "") {
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
        }
        return test;
      }
      // Insert san pham
      var click_number;
      $(document).on('click','#btn-them-bang-tin',function(event){
         click_number = $(this).closest('tr');
         $('#form-bang-tin').load("ajax_notification.php?status=Insert",() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            $('#btn-luu-bang-tin').text("Thêm");
            $(function(){
               setTimeout(() => {
                  $('#summernote').summernote({height: 120,lang: 'vi-VN'});
               },100);
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
         $('#form-bang-tin').load("ajax_notification.php?status=Update&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            $('#btn-luu-bang-tin').text("Sửa");
            $(function(){
               setTimeout(() => {
                  $('#summernote').summernote({height: 120,lang: 'vi-VN'});
               },100);
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
                        console.log(id);
                        res_json = JSON.parse(res);
                        if(res_json.msg == "ok") {
                           arr_input_file = new Map();
                           arr_list_file_del = [];
                           $.alert({
                              title: "Thông báo",
                              content: res_json.success
                           });
                           dt_n.row(click_number).remove().draw();
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
      // xem bang tin
      $(document).on('click','.btn-xem-bang-tin',function(event){
         let id = $(event.currentTarget).attr('data-id');
         click_number = $(this).closest('tr');
         $('#form-bang-tin').load("ajax_notification.php?status=Read&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
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
         formData.append('number',$('input[name=number]').val());
         formData.append('content',$('#summernote').summernote('code'));
         formData.append('status',$('#btn-luu-bang-tin').attr('data-status').trim());
         if(status == "Insert"){
            game();
         } else {
            gameChange();
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
                     arr_input_file = new Map();
                     arr_list_file_del = [];
                     $("input[name='list_file_del']").val("");
                     let status = $('#btn-luu-bang-tin').attr('data-status').trim();
                     let msg ="";
                     if(status == "Insert"){
                        msg = "Thêm dữ liệu thành công.";
                        $.alert({
                           title: "Thông báo",
                           content: msg,
                           buttons: {
                              Ok : function(){
                                 location.href="notification_manage.php";
                              }
                           }
                        });
                     } else if(status == "Update") {
                        console.log(res_json);
                        msg = "Sửa dữ liệu thành công.";
                        $.alert({
                           title: "Thông báo",
                           content: msg,
                           buttons: {
                              Ok : function(){
                                 location.reload();
                              }
                           }
                        });
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
</script>
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
	
?>
<?php
   } else if (is_post_method()) {
      function getFileUpload($img_order,$id){
         $sql = "select img_id from notification_image where notify_id = '$id' and img_order = '$img_order' limit 1";
         $file_old_name = fetch_row($sql)['img_id'];
         return $file_old_name;
      }
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
         $success = "Bạn đã xoá dữ liệu thành công";
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
               $img_order = explode("_",$list_file_del[$i])[0];
               $file_old_name = getFileUpload($img_order,$id);
               if(file_exists($file_old_name)) {
                  unlink($file_old_name);
                  chmod($dir, 0777);
               }
               $sql_delete_file = "Delete from notification_image where notify_id = '$id' and img_order = $img_order";
               db_query($sql_delete_file);
               array_splice($list_file_del,$i, 1);
               $i--;
            }
         }
         if(isset($_FILES['img'])) {
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
                        $img_order = explode("_",$list_file_del[$i])[0];
                        $file_old_name = getFileUpload($img_order,$id);
                        if(file_exists($file_old_name)) {
                           unlink($file_old_name);
                           chmod($dir, 0777);
                        }
                        move_uploaded_file($_FILES['img']['tmp_name'][$key],$path);
                        @chmod($dir, 0777);
                        $sql_update_file = "Update notification_image set img_id = '$path' where notify_id='$id' and img_order='$img_order'";
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
      } else if($status == "upt_more") {
         $n_id = isset($_REQUEST["n_id"]) ? $_REQUEST["n_id"] : null;
         $n_title = isset($_REQUEST["n_title"]) ? $_REQUEST["n_title"] : null;
         $n_content = isset($_REQUEST["n_content"]) ? $_REQUEST["n_content"] : null;
         $sql = "Update notification set title='$n_title',content='$n_content' where id='$n_id'";
         sql_query($sql);
         echo_json(["msg" => "ok"]);
      } else if($status == "del_more") {
         $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
         $rows = explode(",",$rows);
         foreach($rows as $row) {
            $sql = "Update notification set is_delete = 1 where id = '$row'";
            sql_query($sql);
         }
         echo_json(["msg" => "ok"]);
      } else if($status == "ins_more") {
         $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
         if($user_id) {
            $name_p2 = isset($_REQUEST["name_p2"]) ? $_REQUEST["name_p2"] : null;
            $count_p2 = isset($_REQUEST["count_p2"]) ? $_REQUEST["count_p2"] : null;
            $price_p2 = isset($_REQUEST["price_p2"]) ? $_REQUEST["price_p2"] : null;
            $desc_p2 = isset($_REQUEST["desc_p2"]) ? $_REQUEST["desc_p2"] : null;
            $type_p2 = isset($_REQUEST["type_p2"]) ? $_REQUEST["type_p2"] : null;
            $dir = "upload/product/";
            $sql = "Insert into product_info(product_type_id,user_id,name,img_name,description,count,price) values('$type_p2','$user_id','$name_p2','1','$desc_p2','$count_p2','$price_p2')";
            sql_query($sql);
            $insert = ins_id();
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            $dir = "upload/product/" . $insert;
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            if($_FILES['file_p2']['name'] != "") {
               $ext = strtolower(pathinfo($_FILES['file_p2']['name'],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $id . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['file_p2']['tmp_name'],$path);
               $sql_update = "update product_info set img_name='$path' where id = '$insert'";
               db_query($sql_update);
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "ins_all") {
         $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
         $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
         if($user_id) {
            $name_p2 = isset($_REQUEST["name_p2"]) ? $_REQUEST["name_p2"] : null;
            $count_p2 = isset($_REQUEST["count_p2"]) ? $_REQUEST["count_p2"] : null;
            $price_p2 = isset($_REQUEST["price_p2"]) ? $_REQUEST["price_p2"] : null;
            $desc_p2 = isset($_REQUEST["desc_p2"]) ? $_REQUEST["desc_p2"] : null;
            $type_p2 = isset($_REQUEST["type_p2"]) ? $_REQUEST["type_p2"] : null;
            $file_p2 = isset($_FILES["file_p2"]) ? $_FILES["file_p2"] : null;
            for($i = 0 ; $i < $len ; $i++) {
               $dir = "upload/product/";
               $sql = "Insert into product_info(product_type_id,user_id,name,img_name,description,count,price) values('$type_p2[$i]','$user_id','$name_p2[$i]','1','$desc_p2[$i]','$count_p2[$i]','$price_p2[$i]')";
               //print_r($sql);
               sql_query($sql);
               $insert = ins_id();
               if(!file_exists($dir)) {
                  mkdir($dir, 0777); 
                  chmod($dir, 0777);
               }
               $dir = "upload/product/" . $insert;
               if(!file_exists($dir)) {
                  mkdir($dir, 0777); 
                  chmod($dir, 0777);
               }
               if($_FILES['img2']['name'][$i] != "") {
                  $ext = strtolower(pathinfo($_FILES['img2']['name'][$i],PATHINFO_EXTENSION));
                  $file_name = md5(rand(1,999999999)). $insert . "." . $ext;
                  $file_name = str_replace("_","",$file_name);
                  $path = $dir . "/" . $file_name ;
                  move_uploaded_file($_FILES['img2']['tmp_name'][$i],$path);
                  $sql_update = "update product_info set img_name='$path' where id = '$insert'";
                  sql_query($sql_update);
               }
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "upt_all") {
         $pi_id = isset($_REQUEST["pi_id"]) ? $_REQUEST["pi_id"] : null;
         $pi_name = isset($_REQUEST["pi_name"]) ? $_REQUEST["pi_name"] : null;
         $pi_count = isset($_REQUEST["pi_count"]) ? $_REQUEST["pi_count"] : null;
         $pi_price = isset($_REQUEST["pi_price"]) ? $_REQUEST["pi_price"] : null;
         $pi_desc = isset($_REQUEST["pi_desc"]) ? $_REQUEST["pi_desc"] : null;
         $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
         if($len && is_numeric($len)) {
            for($i = 0 ; $i < $len ; $i++){
               $sql = "Update product_info set name='$pi_name[$i]',count='$pi_count[$i]',price='$pi_price[$i]',description='$pi_desc[$i]' where id='$pi_id[$i]'";
               sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
         }
      }
   }
?>