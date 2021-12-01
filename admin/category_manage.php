<?php
    include_once("../lib/database_v2.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        // permission crud for user
        $allow_read = $allow_update = $allow_delete = $allow_insert = false; 
        if(check_permission_crud("category_manage.php","read")) {
          $allow_read = true;
        }
        if(check_permission_crud("category_manage.php","update")) {
          $allow_update = true;
        }
        if(check_permission_crud("category_manage.php","delete")) {
          $allow_delete = true;
        }
        if(check_permission_crud("category_manage.php","insert")) {
          $allow_insert = true;
        }
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        // code to be executed get method
?>
<!--html & css section start-->
<style>
    .card-header::after{
      display:none;
    }
    tr:hover {
      border:1px solid red;
    }

</style>
<style>
  .breadcrumb-item+.breadcrumb-item::before {
    display: inline-block;
    padding-right: 0.7rem;
    color: #9c27b0;
    content: "\203A\203A";
    font-weight:bold;
   }
   .breadcrumb-item-aaa:last-child a{
    text-decoration: underline;
    color:#9c27b0 !important;
    border-radius:5px;
   }
</style>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="css/select.dataTables.min.css">
<link rel="stylesheet" href="css/colReorder.dataTables.min.css">

<!-- /.row -->
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid">
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header" style="display: flex;justify-content: space-between;">
              <h3 class="card-title">Quản lý danh mục</h3>
              <div class="card-tools">
                <div class="input-group">
                  <div class="input-group-append">
                    <?php
                      if($allow_insert) {
                    ?>
                    <button id="btn-them-loai-san-pham" class="dt-button button-blue">
                      Thêm danh mục
                    </button>
                    <?php
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                <ol style="background: #fff;border: 1px solid #9c27b0;" class="breadcrumb float-sm-left">
                  <li class="breadcrumb-item"><a style="cursor:pointer;color: #9c27b0;" href="category_manage.php">Quản lý menu</a></li>
                  <?php
                    $parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : null;
                  ?>
                  <?=generate_breadcrumb_menus_3($parent_id);?>
                  </ol>
                </div>
              </div>
			  <div id="btn-file" class="row">
				<div class="col-12">
					<div class="col-12" style="padding-right:0px;padding-left:0px;">
					</div>
          <div class="col-12" style="padding-right:0px;padding-left:0px;">
              <form id="form-main" style="margin-bottom: 17px;display:flex;" action="category_manage.php" method="get">
                  <div class="" >
                    <select onchange="searchSubmit()" id="select-type2" style="width:300px" class="form-control" name="parent_id">
                      <option value="">Chọn danh mục cần tìm</option>
                      <?php
                        $sql = "select * from product_type where is_delete = 0";
                        $rows2 = db_query($sql);
                        foreach($rows2 as $row2) {
                      ?>
                        <option value="<?=$row2['id']?>" <?=$parent_id == $row2['id'] ? "selected" : ""; ?>><?=$row2['name'];?></option>
                      <?php
                        }
                      ?>
                    </select>
                  </div>
              </form>
            </div>
          <div class="mb-3 col-12 d-flex j-between" style="padding-right:0px;padding-left:0px;">
            <div>
              <?php
                if($allow_delete) {
              ?>
              <button tabindex="-1" onclick="delMore()" id="btn-delete-fast" class="dt-button button-red">Xoá nhanh</button>
              <?php } ?>
              <?php
                if($allow_update) {
              ?>
              <button tabindex="-1" onclick="uptMore()" id="btn-upt-fast" class="dt-button button-green">Sửa nhanh</button>
              <?php }?>
              <?php
                if($allow_read) {
              ?>
              <button tabindex="1" onclick="readMore()" id="btn-read-fast" class="dt-button button-grey">Xem nhanh</button>
              <?php } ?>
              <?php
                if($allow_insert) {
              ?>
              <button tabindex="1" onclick="insMore()" id="btn-ins-fast" class="dt-button button-blue">Thêm nhanh</button>
              <?php } ?>
            </div>
            <div class="section-save">
                <?php
                  if($upt_more == 1 && $allow_update){
                ?>
              <button onclick="sendDataUptAll()" class="dt-button button-green">Lưu thay đổi ?</button>
              <?php } ?>
            </div>
          </div>
					 <table id="m-product-type" class="table table-bordered table-striped">
						<thead>
						  <tr>
                <th></th>
                <th>Số thứ tự</th>
                <th>Tên danh mục</th>
                <th>Ngày thêm</th>
                <th>Thao tác</th>
						  </tr>
						</thead>
						<?php
							$get = $_GET;
							unset($get['page']);
              //
              $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
              
              
              //
							$str_get = http_build_query($get);
							$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
							
							$arr_paras = [];
							$where = "where 1 = 1 and is_delete = 0";
							if($page){
							  $where = "where 1 = 1 and parent_id is null and is_delete = 0";
							} 
							if($parent_id) {
							  $where = "where 1 = 1 and parent_id = ? and is_delete = 0";
							  array_push($arr_paras,$_REQUEST['parent_id']);
							}
              if($str) {
                $where .= " and product_type.id in ($str)";
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
							//print_r($sql_get_product_type);
						  ?>
						<tbody id="list-loai-san-pham">
						<?php foreach($product_types as $product_type) {?>
						  <tr class="parent-type" style="cursor:pointer;" id="<?=$product_type["id"];?>">
                <td></td>
                <td onclick="location.href='category_manage.php?parent_id=<?php echo $product_type['id'];?>'"><?php echo $total - ($start_page + $cnt);?></td>
                <?php
                  if($upt_more != 1){
                ?>
                <td onclick="location.href='category_manage.php?parent_id=<?php echo $product_type['id'];?>'"><?php echo $product_type["name"];?></td>
                <?php
                  } else {
                ?>
                <td><input tabindex="<?=$cnt+1;?>" class='kh-inp-ctrl' type="text" name="pt_name" value="<?=$product_type['name'];?>"></td>
                <?php
                  }
                ?>
                <td onclick="location.href='category_manage.php?parent_id=<?php echo $product_type['id'];?>'"><?=Date("d-m-Y H:i:s",strtotime($product_type["created_at"]));?></td>
                <td>
                  <?php
                    if($upt_more != 1) {
                  ?>
                  <?php
                    if($allow_read) {
                  ?>
                  <button class="btn-xem-loai-san-pham dt-button button-grey"
                  data-id="<?php echo $product_type["id"];?>">
                  Xem
                  </button>
                  <?php } ?>
                  <?php if($allow_update) {?>
                  <button class="btn-sua-loai-san-pham dt-button button-green"
                  data-id="<?php echo $product_type["id"];?>" data-number="<?php echo $total - ($start_page + $cnt);?>">
                  Sửa
                  </button>
                  <?php 
                    } 
                    if($allow_delete) {
                  ?>
                  <button class="btn-xoa-loai-san-pham dt-button button-red" data-id="<?php echo $product_type["id"];?>">
                  Xoá
                  </button>
                  <?php } ?>
                  <?php
                    } else {
                  ?>
                  <button tabindex="-1" dt-count="0" data-id="<?=$product_type["id"];?>" onclick="uptThisRow()" class="dt-button button-green">Sửa</button>
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
                <th>Tên danh mục</th>
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
        <h4 class="modal-title">Thông tin danh mục</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="form-product-type" class="modal-body">
        <form id="form-loai-san-pham" action="" method="post">

        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content" style="height:auto;min-height:600px;">
      <div class="modal-header">
        <h4 class="modal-title">Thêm dữ liệu nhanh</h4>
        <button onclick="insAll()" class="dt-button button-blue">Lưu dữ liệu</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="form-product-type2" class="modal-body">
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
                  <button onclick="delRow()" style="font-size:15px;" class="dt-button button-blue k-btn-minus">-</button>
                </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl3">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Thông tin danh mục</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="form-product-type3" class="modal-body">
          
      </div>
    </div>
  </div>
</div>
<?php
  include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/dataTables.select.min.js"></script>
<script src="js/colOrderWithResize.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>
<script src="js/buttons.colVis.min.js"></script>
<script src="js/dataTables.searchHighlight.min.js"></script> 
<script src="js/jquery.highlight.js"></script>
<script src="js/select2.min.js"></script>
<script>
    var dt_pt;
    $(document).ready(function (e) {
      $('#first_tab').on('focus', function() {
        $('input[tabindex="1"]').focus();
      });
      $('#btn-ins-fast').on('focus',function(){
        $('input[tabindex="<?=$cnt;?>"]').focus();
      });
      dt_pt = $("#m-product-type").DataTable({
        "sDom": 'RBlfrtip',
        columnDefs: [
          { 
              "name":"pi-checkbox",
              "orderable": false,
              "className": 'select-checkbox',
              "targets": 0
          },{ 
              "name":"manipulate",
              "orderable": false,
              "className": 'manipulate',
              "targets": 4,
              'searchable': false,
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
            },
            "buttons": {
              "copy": 'Copy',
              "copySuccess": {
                  1: "Bạn đã sao chép một dòng thành công",
                  _: "Bạn đã sao chép %d dòng thành công"
              },
              "copyTitle": 'Thông báo',
            }
         },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "searchHighlight": true,
        "paging":false,
        "oColReorder": {
            "bAddFixed":false
        },
        "buttons": [
          {
            "extend": "copy",
            "text": "Sao chép bảng (1)",
            "key": {
               "key": '1',
            },
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "excel",
            "text": "Excel (2)",
            "key": {
              "key": '2',
            },
            "autoFilter": true,
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu danh mục sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "pdf",
            "text": "PDF (3)",
            "key": {
              "key": '3',
            },
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu danh mục sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "csv",
            "text": "CSV (4)",
            "charset": 'UTF-8',
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "bom": true,
            "key": {
              "key": '4',
            },
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "print",
            "text": "In bảng (5)",
            "key": {
              "key": '5',
            },
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu danh mục sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "colvis",
            "text": "Ẩn / Hiện cột (7)",
            "columns": ':not(.select-checkbox)',
            "key": {
              "key": '7',
            },
          }
        ]
      });
      dt_pt.buttons().container().appendTo('#m-product-type_wrapper .col-md-6:eq(0)');
      //
      dt_pt.on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
          dt_pt.rows().deselect();
          $("th.select-checkbox").removeClass("selected");
        } else {
          dt_pt.rows().select();
          $("th.select-checkbox").addClass("selected");
        }
      }).on("select deselect", function() {
        if (dt_pt.rows({
                selected: true
          }).count() !== dt_pt.rows().count()) {
          $("th.select-checkbox").removeClass("selected");
        } else {
          $("th.select-checkbox").addClass("selected");
        }
      });
    
    });
    function createDataUptAll(){
      let arr_id = [];
      let arr_pt_name = [];
      let _data = dt_pt.rows(".selected").select().data();
      let count = 0;
      console.log(_data);
      for(i = 0 ; i < _data.length ; i++) {
        arr_id.push(_data[i].DT_RowId);
        count++;
      };
      if(count == 0) {
        $.alert({
          title: "Thông báo",
          content: "Bạn cần chọn dòng để sửa",
        })
        return false;
      }
      $("tr.selected input[name='pt_name']").each(function(){
        arr_pt_name.push($(this).val());
      });
      let result = {};
      arr_id.forEach((id,i) => result[id] = arr_pt_name[i]);
      console.log(JSON.stringify(result));
      return JSON.stringify(result);
    }
    function sendDataUptAll(){
      let json = createDataUptAll();
      if(json == false) {
        return;
      }
      $.ajax({
        url: window.location.href,
        type: "POST",
        data: {
          "status": "upt_all",
          "json": json,
          "parent_id": "<?=$parent_id;?>",
          "token": "<?php echo_token();?>"
        },
        success: function(data){
          console.log(data);
          data = JSON.parse(data);
          if(data.msg == "ok") {
            $.alert({
              title: "Thông báo",
              content: "Bạn đã sửa thành công",
              buttons: {
                "Ok": function(){
                  location.reload();
                }
              }
            })
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
        $('#form-product-type2 table').remove();
        $('#form-product-type2 #paging').remove();
        let html = `
        <table class='table table-bordered' style="min-height:100px;height:auto;">
          <thead>
            <tr>
              <th>Số thứ tự</th>
              <th>Tên danh mục</th>
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
                <td><input class='kh-inp-ctrl' name='name2' type='text' value=''></td>
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
                <td><input class='kh-inp-ctrl' name='name2' type='text' value=''></td>
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
        $(html).appendTo('#form-product-type2');
        apply_dom = false;
        $('.t-bd-1').css({"display":"contents"});
        console.log(html);
      } else {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
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
        $('#form-product-type2 table').remove();
        $('#form-product-type2 #paging').remove();
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
          <td><input class='kh-inp-ctrl' name='name2' type='text' value=''></td>
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
        $(html).appendTo('#form-product-type2 table');
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
    function insMore2(){
      let name2 = $(event.currentTarget).closest('tr').find('td input[name="name2"]').val();
      if(name2 == "") {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng không để trống tên danh mục",
        })
        return;
      }
      let this2 = $(event.currentTarget);
      $.ajax({
        url: window.location.href,
        type: "POST",
        data: {
          status: "ins_more",
          name2: name2,
          parent_id: '<?=$parent_id?>',
          token: '<?php echo_token();?>',
        },
        success: function(data){
          console.log(this2);
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
            })
          }
        },error: function(data){
          console.log("Error: " + data);
        }
      })
    }
    function searchSubmit(){
      $("#form-main").submit();
    }
    function delMore(){
        let arr_del = [];
        let _data = dt_pt.rows(".selected").select().data();
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
                                    location.reload();
                                  }
                                }
                            });
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
        let _data = dt_pt.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
          arr_del.push(_data[i].DT_RowId);
        }
        let str_arr_upt = arr_del.join(",");
        location.href="category_manage.php?upt_more=1&parent_id=<?=$parent_id;?>&str=" + str_arr_upt;
    }
    function uptThisRow(){
      let name = $(event.currentTarget).closest("tr").find("td input[name='pt_name']").val();
      let id = $(event.currentTarget).attr('data-id');
      let this2 = $(event.currentTarget);
      $.ajax({
        url: window.location.href,
        type: "POST",
        data: {
          status: "upt_more",
          pt_id: id,
          pt_name: name,
          token: '<?php echo_token();?>',
        },success: function(data){
          data = JSON.parse(data);
          if(data.msg == "ok"){
            $.alert({
              title: "Thông báo",
              content: "Bạn đã sửa dữ liệu thành công",
              buttons: {
                "Ok": function(){
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
    function readMore(){
      let arr_del = [];
      let _data = dt_pt.rows(".selected").select().data();
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
      $('#form-product-type3').load(`ajax_category_manage.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
        let html2 = `
          <div id="paging" style="justify-content:center;" class="row">
            <nav id="pagination3">
            </nav>
          </div>
        `;
        $(html2).appendTo('#form-product-type3');
        $('#modal-xl3').modal({backdrop: 'static', keyboard: false});
        $('.t-bd-read').css({
          "display":"none",
        });
        $('.tb-bd-read-1').css({
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
            $(`.tb-bd-read-${pageNumber}`).css({"display":"contents"});

          },
          cssStyle: 'light-theme',
        });
      });
    }
    function insAll(){
      let arr_ins_all = [];
      let count = 0;
      $("td input[name='name2']").each(function(){
        let temp = $(this).val();
        if(temp != "" && temp != null) {
          arr_ins_all.push(temp);
          count++;
        }
      });
      if(count == 0){
        $.alert({
          title: "Thông báo",
          content: "Vui lòng không để trống tên danh mục",
        });
        return;
      }
      $.confirm({
        title: "Thông báo",
        content: `Bạn có chắc chắn muốn sửa ${count} dòng này ?`,
        buttons: {
          "Có": function(){
            $.ajax({
              url: window.location.href,
              type: "POST",
              data: {
                status: "upt_all",
                rows: arr_ins_all.join(","),
                token: "<?php echo_token();?>"
              },
              success: function(data) {
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
            });
          },"Không": function(){

          }
        },
      })
      console.log(arr_ins_all);
    }
    function uptAll(){
      let arr_upt_all = [];
      let count = 0;
      $("td input[name='name2']").each(function(){
        let temp = $(this).val();
        if(temp != "" && temp != null) {
          arr_upt_all.push(temp);
          count++;
        }
      });
    }
</script>
<script>
   $(document).ready(function(){
    $('#select-type2').select2();
      // thêm danh mục
      $(document).on('click','#btn-them-loai-san-pham',function(event){
        $('#form-loai-san-pham').load("ajax_category_manage.php?parent_id=<?=$parent_id;?>" + "&status=Insert",() => {
          $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        });
      });
      // sửa danh mục
      $(document).on('click','.btn-sua-loai-san-pham',function(event){
        let id = $(event.currentTarget).attr('data-id');
        $('#form-loai-san-pham').load("ajax_category_manage.php?id=" + id + "&status=Update",() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        });
      });
      // xoá danh mục
      $(document).on('click','.btn-xoa-loai-san-pham',function(event){
		    let id = $(event.currentTarget).attr('data-id');
        $.confirm({
          title: 'Thông báo',
          content: 'Bạn có chắc chắn muốn xoá danh mục này ?',
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
                    content: res_json.success,
                    buttons: {
                      "Ok": function(){
                        location.reload();
                      }
                    }
                  });
                  //$('#loai-san-pham' + res_json.id).remove();
                  //dt_pt.row(click_number).remove().draw();
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
      // xem danh muc
      $(document).on('click','.btn-xem-loai-san-pham',function(event){
        let id = $(event.currentTarget).attr('data-id');
        $('#form-loai-san-pham').load("ajax_category_manage.php?id=" + id + "&status=Read",() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        });
      });
      // xử lý thao tác thêm sửa
      $(document).on('click','#btn-luu-loai-san-pham',function(event){
         event.preventDefault();
         if(!$('input[name=ten_loai_san_pham]').val()) {
            $.alert({
              title: "Thông báo",
              content: "Vui lòng không để trống tên danh mục"
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
              status: $('#btn-luu-loai-san-pham').attr("data-status"),
              number: $('input[name=number]').val(),
            },
            success:function(res){
              let res_json = JSON.parse(res);
              console.log(res_json);
              $('#form-loai-san-pham').trigger('reset');
              $('#modal-xl').modal('hide');
              if(res_json.msg == "ok"){
                let status = $('#btn-luu-loai-san-pham').attr("data-status");
                if(status == "Insert"){
                  $.alert({
                    title: "Thông báo",
                    content: "Thêm danh mục thành công",
                    buttons: {
                      "Ok": function(){
                        location.href="category_manage.php";
                      }
                    }
                  });
                  dt_pt.row.add(record[0]).draw();
                } else if(status == "Update") {
                  $.alert({
                    title: "Thông báo",
                    content: "Sửa danh mục thành công",
                    buttons: {
                      "Ok": function(){
                        location.reload();
                      }
                    }
                  });
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
                $error = "Tên danh mục này đã tồn tại.";
                echo_json(['msg' => 'not_ok','error' => $error]);
            } else if(1 == 1) { //$row['countt'] == 0
                ajax_db_update_by_id('product_type', ['name' => $name],[$id],["id" => $id,"name"=>$name,"number"=>$number]);
            }
        } else if($status == "Insert") {
            $sql_check_exist = "Select count(*) as 'countt' from product_type where name = ?";
            $row = fetch_row($sql_check_exist,[$name]);
            if(1 == 2) {
                $error = "Tên danh mục này đã tồn tại.";
                echo_json(['msg' => 'not_ok','error' => $error]);
            } else if(1 == 1){
                if($parent_id) {
                    ajax_db_insert_id('product_type',['name'=>$name,'parent_id' => $parent_id],['id' => $id,"number"=>$number,'name'=>$name,'created_at'=>date('d-m-Y H:i:s',time())]);
                } else {
                    ajax_db_insert_id('product_type',['name'=>$name],['id' => $id,"number"=>$number,'name'=>$name,'created_at'=>date('d-m-Y H:i:s',time())]);
                }
            }
        } else if($status == "upt_more") {
          $pt_id = isset($_REQUEST["pt_id"]) ? $_REQUEST["pt_id"] : null;
          $pt_name = isset($_REQUEST["pt_name"]) ? $_REQUEST["pt_name"] : null;
          $sql = "Update product_type set name='$pt_name' where id = '$pt_id'";
          sql_query($sql);
          echo_json(["msg" => "ok"]);
        } else if($status == "del_more") {
          $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
          $rows = explode(",",$rows);
          foreach($rows as $row) {
            $sql = "Update product_type set is_delete = 1 where id = '$row'";
            sql_query($sql);
          }
          echo_json(["msg" => "ok"]);
        } else if($status == "ins_more") {
          $name2 = isset($_REQUEST["name2"]) ? $_REQUEST["name2"] : null;
          $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
          if(!$parent_id || $parent_id == ""){
            $parent_id = null;
          }
          db_insert_id('product_type',['name'=>$name2,'parent_id' => $parent_id]);
          //sql_query($sql);
          echo_json(["msg" => "ok"]);
        } else if($status == "ins_all") {
          $rows = isset($_REQUEST["rows"]) ? $_REQUEST["rows"] : null;
          $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
          if($rows) {
            if(!$parent_id || $parent_id == ""){
              $parent_id = null;
            }
            foreach(explode(",",$rows) as $row) {
              db_insert_id('product_type',['name'=>$row,'parent_id' => $parent_id]);
            }
          }
          echo_json(["msg" => "ok"]);
        } else if($status == "upt_all") {
          $json = isset($_REQUEST["json"]) ? $_REQUEST["json"] : null;
          $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
          if($json) {
            //print_r($json);
            $rows = (array)json_decode($json);
            if(!$parent_id || $parent_id == ""){
              $parent_id = null;
            }
            foreach($rows as $key => $value) {
              db_update_by_id('product_type',['name'=>$value,'parent_id' => $parent_id],[$key]);
            }
            echo_json(["msg" => "ok"]);
          }
        }
    }
    
?>