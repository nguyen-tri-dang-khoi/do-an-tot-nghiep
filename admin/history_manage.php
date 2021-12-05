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
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
      $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
      $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
      $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
      $where = "where 1=1 ";
      $wh_child = [];
      $arr_search = [];
      if($keyword && is_array($keyword)) {
         $wh_child = [];
         if($search_option == "all") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(n.title) like lower('%$key%') or lower(n.content) like lower('%$key%'))");
               }
            }
         } else if($search_option == "title") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(n.title) like lower('%$key%'))");
               }
            }
         }
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }
      if($date_min && is_array($date_min) && $date_max && is_array($date_max)) {
        $wh_child = [];
        foreach(array_combine($date_min,$date_max) as $d_min => $d_max) {
            if($d_min != "" && $d_max != "") {
              $d_min = Date("Y-m-d",strtotime($d_min));
              $d_max = Date("Y-m-d",strtotime($d_max));
              array_push($wh_child,"(n.created_at >= '$d_min 00:00:00' and n.created_at <= '$d_max 23:59:59')");
            } else if($d_min != "" && $d_max == "") {
              $d_min = Date("Y-m-d",strtotime($d_min));
              array_push($wh_child,"(n.created_at >= '$d_min 00:00:00')");
            } else if($d_min == "" && $d_max != "") {
              $d_max = Date("Y-m-d",strtotime($d_max));
              array_push($wh_child,"(n.created_at <= '$d_max 23:59:59')");
            }
        }
        $wh_child = implode(" or ",$wh_child);
        if($wh_child != "") {
            $where .= " and ($wh_child)";
        }
      }
      log_v($where);
?>
<?php
		
?>
<!--html & css section start-->
<style>
    .card-header::after{
      display:none;
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
              <h3 class="card-title">Quản lý lịch sử tìm kiếm</h3>
            </div>
            <div class="card-body">
              <div id="btn-file" class="row">
                <div class="col-12">
                  <div class="col-12" style="padding-right:0px;padding-left:0px;">
                    <form style="margin-bottom: 17px;display:flex;align-items:flex-start;" action="history_manage.php" method="get">
                      <div class="" style="margin-top:5px;">
                          <select onchange="choose_type_search()" class="form-control" name="search_option">
                              <option value="">Bộ lọc tìm kiếm</option>
                              <option value="keyword" <?=$search_option == 'keyword' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                              <option value="date2" <?=$search_option == 'date2' ? 'selected="selected"' : '' ?>>Phạm vi ngày</option>
                              <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                          </select>
                        </div>
                        <div id="s-cols" class="k-select-opt ml-15 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column": "display:none;";?>">
                          <span class="k-select-opt-remove"></span>
                          <span class="k-select-opt-ins"></span>
                          <div class="ele-cols d-flex f-column">
                              <select name="search_option" class="form-control mb-10">
                                <option value="">Chọn cột tìm kiếm</option>
                                <option value="title" <?=$search_option == 'title' ? 'selected="selected"' : '' ?>>Tiêu đề bài viết</option>
                              </select>
                              <input type="text" name="keyword[]" placeholder="Nhập từ khoá..." class="form-control" value="">
                          </div>
                          <?php
                          if(is_array($keyword)) {
                              foreach($keyword as $key) {
                          ?>
                              <?php
                              if($key != "") {
                              ?>
                              <div class="ele-select ele-cols mt-10">
                                <input type="text" name="keyword[]" placeholder="Nhập từ khoá..." class="form-control" value="<?=$key;?>">
                                <span onclick="select_remove_child('.ele-cols')" class="kh-select-child-remove"></span>
                              </div>
                              <?php
                              }
                              ?>
                          <?php   
                              }
                          }
                          ?>
                        </div>
                        <div id="s-date2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_min && $date_min != [""] || $date_max && $date_max != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                          <span class="k-select-opt-remove"></span>
                          <span class="k-select-opt-ins"></span>
                          <div class="ele-date2">
                              <div class="" style="display:flex;">
                                <input type="text" name="date_min[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
                              </div>
                              <div class="ml-10" style="display:flex;">
                                <input type="text" name="date_max[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
                              </div>
                          </div>
                          <?php
                              if(is_array($date_min) && is_array($date_max)) {
                                foreach(array_combine($date_min,$date_max) as $d_min => $d_max){
                          ?>
                          <?php
                              if($d_min != "" || $d_max != "") {
                          ?>
                          <div class="ele-select ele-date2 mt-10">
                              <div class="" style="display:flex;">
                                <input type="text" name="date_min[]" placeholder="Ngày 1" class="kh-datencker2 form-control" value="<?=$d_min ? Date("d-m-Y",strtotime($d_min)) : "";?>">
                              </div>
                              <div class="ml-10" style="display:flex;">
                                <input type="text" name="date_max[]" placeholder="Ngày 2" class="kh-datencker2 form-control" value="<?=$d_max ? Date("d-m-Y",strtotime($d_max)) : "";?>">
                              </div>
                              <span onclick="select_remove_child('.ele-date2')" class="kh-select-child-remove"></span>
                          </div>
                          <?php
                          }
                          ?>
                          <?php 
                                }
                              }
                          ?>
                        </div>
                        <button type="submit" class="btn btn-default ml-15" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                    </form>
                  </div>
                  <table id="m-product-type" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      <th></th>
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
                    <tbody>
                    <?php foreach($keyword_historys as $keyword_history) {?>
                      <tr id="<?=$keyword_history["id"];?>">
                          <td></td>
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
                        <th></th>
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
<!--searching filter-->
<script>
  $(".kh-datepicker2").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    onSelect: function(dateText, inst) {
      console.log(dateText.split("-"));
      dateText = dateText.split("-");
      $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
    }
  });
   function choose_type_search(){
      let _option = $("select[name='search_option'] > option:selected").val();
      if(_option.indexOf("2") > -1) {
         if(_option.indexOf("all") > -1) {
            $(".s-all2").css({"display": "flex"});
         } else {
            $(`#s-${_option}`).css({"display": "flex"});
         }
      } else {
         $('#s-cols').css({"display": "flex"});
      }
      $("select[name='search_option'] > option[value='']").prop('selected',true);
   }
   $('.k-select-opt-remove').click(function(){
      $(event.currentTarget).siblings('.ele-select').remove()
      $(event.currentTarget).siblings("div").find("input").val("");
      $(event.currentTarget).closest('div').css({"display":"none"});
   });
   $('.k-select-opt-ins').click(function(){
      let file_html = "";
      if($(event.currentTarget).closest('#s-date2').length) {
         file_html = `
         <div class="ele-select ele-date2 mt-10">
            <div class="" style="display:flex;">
               <input type="text" name="date_min[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
            </div>
            <div class="ml-10" style="display:flex;">
               <input type="text" name="date_max[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
            </div>
            <span onclick="select_remove_child('.ele-date2')" class="kh-select-child-remove"></span>
         </div>
         `;
      } else if($(event.currentTarget).closest('#s-cols').length) {
         file_html = `
         <div class="ele-select ele-cols mt-10">
            <input type="text" name="keyword[]" placeholder="Nhập từ khoá..." class="form-control" value="">
            <span onclick="select_remove_child('.ele-cols')" class="kh-select-child-remove"></span>
         </div>
         `;
      }
      $(file_html).appendTo($(this).parent());
      $(this).parent().css({
         "flex-direction": "column",
         "justify-content": "space-between",
      });
      if($(event.currentTarget).closest('#s-date2').length) {
         $(".kh-datepicker2").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
            onSelect: function(dateText, inst) {
              console.log(dateText.split("-"));
              dateText = dateText.split("-");
              $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
            }
         });
      } 
   });
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
</script>
<script>
    var dt_keyword;
    $(document).ready(function (e) {
      dt_keyword = $("#m-product-type").DataTable({
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
              "targets": 3
          }, 
        ],
         select: {
            style: 'multi+shift',
            selector: 'td:first-child'
         },
         order: [
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
              "filename": "danh_sach_tu_khoa_tim_kiem_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
              "title": "Dữ liệu từ khoá tìm kiếm trích xuất ngày <?=Date("d-m-Y",time());?>",
              "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
              "extend": "pdf",
              "text": "PDF (3)",
              "key": {
                  "key": '3',
              },
              "filename": "danh_sach_tu_khoa_tim_kiem_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
              "title": "Dữ liệu từ khoá tìm kiếm trích xuất ngày <?=Date("d-m-Y",time());?>",
              "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
              "extend": "csv",
              "text": "CSV (4)",
              "key": {
                  "key": '4',
              },
              "charset": 'UTF-8',
              "bom":true,
              "filename": "danh_sach_tu_khoa_tim_kiem_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
              "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
              "extend": "print",
              "text": "In bảng (5)",
              "key": {
                  "key": '5',
              },
              "filename": "danh_sach_tu_khoa_tim_kiem_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
              "title": "Dữ liệu từ khoá tìm kiếm trích xuất ngày <?=Date("d-m-Y",time());?>",
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
      //
      dt_keyword.buttons.exportData( {
         columns: ':visible'
      });
      dt_keyword.on("click", "th.select-checkbox", function() {
         if ($("th.select-checkbox").hasClass("selected")) {
            dt_keyword.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
         } else {
            dt_keyword.rows().select();
            $("th.select-checkbox").addClass("selected");
         }
      }).on("select deselect", function() {
         if (dt_keyword.rows({
                  selected: true
            }).count() !== dt_keyword.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
         } else {
            $("th.select-checkbox").addClass("selected");
         }
      });
      //
      dt_keyword.buttons().container().appendTo('#m-product-type_wrapper .col-md-6:eq(0)');
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