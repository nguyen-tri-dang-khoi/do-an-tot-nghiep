<?php
    include_once("../lib/database_v2.php");
    logout_session_timeout();
    check_access_token();
    redirect_if_login_status_false();
    if(is_get_method()) {
        // permission crud for customer
        $allow_read = $allow_lock = $allow_unlock = false; 
        if(check_permission_crud("customer_manage.php","read")) {
          $allow_read = true;
        }
        if(check_permission_crud("customer_manage.php","lock")) {
            $allow_lock = true;
        }
        if(check_permission_crud("customer_manage.php","unlock")) {
            $allow_unlock = true;
        }
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $birthday_min = isset($_REQUEST['birthday_min']) ? $_REQUEST['birthday_min'] : null;
        $birthday_max = isset($_REQUEST['birthday_max']) ? $_REQUEST['birthday_max'] : null;
        $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
        $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $where = "where 1=1 ";
        $wh_child = [];
        $arr_search = [];
        if($keyword && is_array($keyword)) {
            $wh_child = [];
            if($search_option) {
                if($search_option == "all") {
                    foreach($keyword as $key) {
                    if($key != "") {
                        array_push($wh_child,"(lower(phone) like lower('%$key%') or lower(address) like lower('%$key%') or lower(email) like lower('%$key%') or lower(full_name) like lower('%$key%'))");
                    }
                    }
                } else if($search_option == "phone") {
                    foreach($keyword as $key) {
                    if($key != "") {
                        array_push($wh_child,"(lower(phone) like lower('%$key%'))");
                    }
                    }
                } else if($search_option == "address") {
                    foreach($keyword as $key) {
                    if($key != "") {
                        array_push($wh_child,"(lower(address) like lower('%$key%'))");
                    }
                    }
                } else if($search_option == "email") {
                    foreach($keyword as $key) {
                    if($key != "") {
                        array_push($wh_child,"(lower(email) like lower('%$key%'))");
                    }
                    }
                } else if($search_option == "full_name") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(full_name) like lower('%$key%'))");
                        }
                    }
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        if($birthday_min && is_array($birthday_min) && $birthday_max && is_array($birthday_max)) {
            $wh_child = [];
            foreach(array_combine($birthday_min,$birthday_max) as $b_min => $b_max) {
                if($b_min != "" && $b_max != "") {
                    $b_min = Date("Y-m-d",strtotime($b_min));
                    $b_max = Date("Y-m-d",strtotime($b_max));
                    array_push($wh_child,"(birthday >= '$b_min 00:00:00' and birthday <= '$b_max 23:59:59')");
                } else if($b_min == "" && $b_max != ""){
                    $b_min = Date("Y-m-d",strtotime($b_min));
                    array_push($wh_child,"(birthday >= '$b_min 00:00:00')");
                } else if($b_min != "" && $b_max == ""){
                    $b_max = Date("Y-m-d",strtotime($b_max));
                    array_push($wh_child,"(birthday <= '$b_max 23:59:59')");
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
                    array_push($wh_child,"(created_at >= '$d_min 00:00:00' and created_at <= '$d_max 23:59:59')");
                } else if($d_min != "" && $d_max == "") {
                    $d_min = Date("Y-m-d",strtotime($d_min));
                    array_push($wh_child,"(created_at >= '$d_min 00:00:00')");
                } else if($d_min == "" && $d_max != "") {
                    $d_max = Date("Y-m-d",strtotime($d_max));
                    array_push($wh_child,"(created_at <= '$d_max 23:59:59')");
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        log_v($where);
?>
<!--html & css section start-->

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="css/select.dataTables.min.css">
<link rel="stylesheet" href="css/colReorder.dataTables.min.css">
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
                        <form style="margin-bottom: 17px;display:flex;align-items:flex-start;" action="customer_manage.php" method="get">
                            <div class="" style="margin-top:5px;">
                              <select onchange="choose_type_search()" class="form-control" name="search_option">
                                 <option value="">Bộ lọc tìm kiếm</option>
                                 <option value="keyword" <?=$search_option == 'type' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                                 <option value="date2" <?=$search_option == 'date2' ? 'selected="selected"' : '' ?>>Phạm vi ngày</option>
                                 <option value="birthday2" <?=$search_option == 'birthday2' ? 'selected="selected"' : '' ?>>Ngày sinh</option>
                                 <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                              </select>
                           </div>
                           <div id="s-cols" class="k-select-opt ml-15 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column": "display:none;";?>">
                              <span class="k-select-opt-remove"></span>
                              <span class="k-select-opt-ins"></span>
                              <div class="ele-cols d-flex f-column">
                                 <select name="search_option" class="form-control mb-10">
                                    <option value="">Chọn cột tìm kiếm</option>
                                    <option value="phone" <?=$search_option == 'phone' ? 'selected="selected"' : '' ?>>Số điện thoại</option>
                                    <option value="email" <?=$search_option == 'email' ? 'selected="selected"' : '' ?>>Email</option>
                                    <option value="full_name" <?=$search_option == 'full_name' ? 'selected="selected"' : '' ?>>Tên đầy đủ</option>
                                    <option value="all" <?=$search_option == 'all' ? 'selected="selected"' : '' ?>>Tất cả</option>
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
                           <div id="s-birthday2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($birthday_min && $birthday_min != [""] || $birthday_max && $birthday_max != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                              <span class="k-select-opt-remove"></span>
                              <span class="k-select-opt-ins"></span>
                              <div class="ele-date2">
                                 <div class="" style="display:flex;">
                                    <input type="text" name="birthday_min[]" placeholder="Ngày sinh 1" class="kh-datepicker2 form-control" value="">
                                 </div>
                                 <div class="ml-10" style="display:flex;">
                                    <input type="text" name="birthday_max[]" placeholder="Ngày sinh 2" class="kh-datepicker2 form-control" value="">
                                 </div>
                              </div>
                              <?php
                                 if(is_array($birthday_min) && is_array($birthday_max)) {
                                    foreach(array_combine($birthday_min,$birthday_max) as $b_min => $b_max){
                              ?>
                              <?php
                                 if($b_min != "" || $b_max != "") {
                              ?>
                              <div class="ele-select ele-date2 mt-10">
                                 <div class="" style="display:flex;">
                                    <input type="text" name="birthday_min[]" placeholder="Ngày sinh 1" class="kh-datepicker-ym form-control" value="<?=$b_min ? Date("d-m-Y",strtotime($b_min)) : "";?>">
                                 </div>
                                 <div class="ml-10" style="display:flex;">
                                    <input type="text" name="birthday_max[]" placeholder="Ngày sinh 2" class="kh-datepicker-ym form-control" value="<?=$b_max ? Date("d-m-Y",strtotime($b_max)) : "";?>">
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
                           <div id="s-date2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_min && $date_min != [""] || $date_max && $date_max != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                              <span class="k-select-opt-remove"></span>
                              <span class="k-select-opt-ins"></span>
                              <div class="ele-date2">
                                 <div class="" style="display:flex;">
                                    <input type="text" name="date_min[]" placeholder="Ngày đăng ký 1" class="kh-datepicker2 form-control" value="">
                                 </div>
                                 <div class="ml-10" style="display:flex;">
                                    <input type="text" name="date_max[]" placeholder="Ngày đăng ký 2" class="kh-datepicker2 form-control" value="">
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
                                    <input type="text" name="date_min[]" placeholder="Ngày đăng ký 1" class="kh-datepicker2 form-control" value="<?=$d_min ? Date("d-m-Y",strtotime($d_min)) : "";?>">
                                 </div>
                                 <div class="ml-10" style="display:flex;">
                                    <input type="text" name="date_max[]" placeholder="Ngày đăng ký 2" class="kh-datepicker2 form-control" value="<?=$d_max ? Date("d-m-Y",strtotime($d_max)) : "";?>">
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
                        <div class="col-12 mb-3 d-flex j-between" style="padding-right:0px;padding-left:0px;">
                            <div>
                                <?php
                                    if($allow_read) {
                                ?>
                                <button tabindex="-1" onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                                <?php } ?>
                                <?php
                                    if($allow_lock){
                                ?>
                                <button tabindex="1" onclick="lockMore()" class="dt-button button-brown">Khoá nhanh</button>
                                <?php } ?>
                                <?php
                                    if($allow_unlock){
                                ?>
                                <button tabindex="1" onclick="unlockMore()" class="dt-button button-brown">Mở khoá nhanh</button>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
							// set get
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
                                    <th></th>
                                    <th>Số thứ tự</th>
                                    <th>Tên đầy đủ</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày sinh</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($rows as $row) { ?>
                                <tr id="<?=$row["id"]?>">
                                    <td></td>
                                    <td><?=$total - ($start_page + $cnt);?></td>
                                    <td>
                                        <?php 
                                            if($row['is_lock'] == 1){
                                                echo '<i class="fas fa-lock mr-1"></i>';
                                            }
                                            echo $row["full_name"];
                                        ?>
                                    </td>
                                    <td><?=$row["email"]?></td>
                                    <td><?=$row["phone"]?></td>
                                    <td><?=$row["address"]?></td>
                                    <td><?=Date("d-m-Y",strtotime($row["birthday"]));?></td>
                                    <td><?=Date("d-m-Y",strtotime($row["created_at"]));?></td>
                                    <td>
                                        <button class="btn-read-customer dt-button button-grey"data-id="<?=$row["id"];?>">Xem</button>
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
                                    <th>Tên đầy đủ</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày sinh</th>
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
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin khách hàng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage_customer" method="post">
                    
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
<?php
    include_once("include/dt_script.php");
?>
<!--searching filter-->
<script>
    $(".kh-datepicker2").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText, inst) {
            dateText = dateText.split("-");
            $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
        }
    })
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
                <input type="text" name="date_min[]" placeholder="Ngày đăng ký 1" class="kh-datepicker2 form-control" value="">
                </div>
                <div class="ml-10" style="display:flex;">
                <input type="text" name="date_max[]" placeholder="Ngày đăng ký 2" class="kh-datepicker2 form-control" value="">
                </div>
                <span onclick="select_remove_child('.ele-date2')" class="kh-select-child-remove"></span>
            </div>
            `;
        } else if($(event.currentTarget).closest('#s-birthday2').length) {
            file_html = `
            <div class="ele-select ele-date2 mt-10">
                <div class="" style="display:flex;">
                <input type="text" name="birthday_min[]" placeholder="Ngày sinh 1" class="kh-datepicker2 form-control" value="">
                </div>
                <div class="ml-10" style="display:flex;">
                <input type="text" name="birthday_max[]" placeholder="Ngày sinh 2" class="kh-datepicker2 form-control" value="">
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
        $(".kh-datepicker2").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
            onSelect: function(dateText, inst) {
                dateText = dateText.split("-");
                $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
            }
        })
        $("input[type='number']").bind('paste',function(e){
            var pastedData = e.originalEvent.clipboardData.getData('text');
            if (!pastedData.match(/^[0-9]+$/)){
                e.preventDefault();
            }
        })
   });
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
</script>
<script>
    var dt_customer;
    $(document).ready(function (e) {
        $.fn.dataTable.moment('DD-MM-YYYY');
        dt_customer = $("#m-customer-table").DataTable({
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
                    "targets": 8
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
            "paging":false,
            "searchHighlight": true,
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
                    "filename": "danh_sach_khach_hang_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu khách hàng trích xuất ngày <?=Date("d-m-Y",time());?>",
                    "exportOptions":{
                        columns: ':visible:not(.select-checkbox):not(.manipulate)'
                    },
                },{
                    "extend": "pdf",
                    "text": "PDF (3)",
                    "key": {
                        "key": '3',
                    },
                    "filename": "danh_sach_khach_hang_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu khách hàng trích xuất ngày <?=Date("d-m-Y",time());?>",
                    "exportOptions":{
                        columns: ':visible:not(.select-checkbox):not(.manipulate)'
                    },
                },{
                    "extend": "csv",
                    "text": "CSV (4)",
                    "charset":"UTF-8",
                    "filename": "danh_sach_khach_hang_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
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
                    "filename": "danh_sach_khach_hang_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu khách hàng trích xuất ngày <?=Date("d-m-Y",time());?>",
                    "key": {
                        "key": '5',
                    },
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
        dt_customer.buttons().container().appendTo('#m-customer-table_wrapper .col-md-6:eq(0)');
        //
        dt_customer.buttons.exportData( {
            columns: ':visible'
        });
        dt_customer.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                dt_customer.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                dt_customer.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function() {
            if (dt_customer.rows({
                    selected: true
                }).count() !== dt_customer.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });
        //
        
    });
    // xem
    $(document).on('click','.btn-read-customer',function(e){
        let id = $(e.currentTarget).attr('data-id');
        console.log(id);
        let target = $(e.currentTarget);
        target.closest("tr").addClass("bg-color-selected");
        $('#manage_customer').load("ajax_customer.php?status=Read&id=" + id,() => {
            console.log("ajax_customer.php?status=Read&id=" + id);
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    });
    // remove mau hong
    $("#modal-xl").on("hidden.bs.modal",function(){
      $("tr").removeClass('bg-color-selected');
    })
    function readMore(){
        let arr_del = [];
        let _data = dt_customer.rows(".selected").select().data();
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
        $('#manage_customer').load(`ajax_customer.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
            let html2 = `
            <div id="paging" style="justify-content:center;" class="row">
                <nav id="pagination3">
                </nav>
            </div>
            `;
            $(html2).appendTo('#manage_customer');
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
    function unlockMore(){
        let arr_del = [];
        let _data = dt_customer.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn mở khoá " + _data.length + " tài khoản khách hàng này",
                buttons: {
                    "Có": function(){
                        $.ajax({
                            url: window.location.href,
                            type: "POST",
                            data: {
                                status: "unlock_more",
                                token: "<?php echo_token(); ?>",
                                rows: arr_del.join(","),
                            },
                            success: function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok"){
                                    $.alert({
                                        title: "Thông báo",
                                        content: "Bạn đã mở khoá tài khoản khách hàng thành công",
                                        buttons: {
                                            "Ok": function(){
                                                location.href="customer_manage.php";
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
                content: "Bạn chưa chọn tài khoản để mở khoá",
            });
        }
    }
    function lockMore(){
        let arr_del = [];
        let _data = dt_customer.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn khoá " + _data.length + " tài khoản khách hàng này",
                buttons: {
                    "Có": function(){
                        $.ajax({
                            url: window.location.href,
                            type: "POST",
                            data: {
                                status: "lock_more",
                                token: "<?php echo_token(); ?>",
                                rows: arr_del.join(","),
                            },
                            success: function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok"){
                                    $.alert({
                                        title: "Thông báo",
                                        content: "Bạn đã khoá tài khoản khách hàng thành công",
                                        buttons: {
                                            "Ok": function(){
                                                location.href="customer_manage.php";
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
                content: "Bạn chưa chọn tài khoản để khoá",
            });
        }
    }
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
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        if($status == "lock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update customer set is_lock = 1 where id = '$row'";
                sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "unlock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update customer set is_lock = 0 where id = '$row'";
                sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
        }
    }
?>