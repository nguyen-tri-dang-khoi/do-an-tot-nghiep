<?php
    include_once("../lib/database.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        $allow_read = false;
        if(check_permission_crud("order_manage.php","read")) {
          $allow_read = true;
        }
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $total_min = isset($_REQUEST['total_min']) ? $_REQUEST['total_min'] : null;
        $total_max = isset($_REQUEST['total_max']) ? $_REQUEST['total_max'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
        $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
        $select_payment_status = isset($_REQUEST['select_payment_status']) ? $_REQUEST['select_payment_status'] : null;
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $is_search = isset($_REQUEST['is_search']) ? true : false;
        $where = "where 1=1 ";
        $wh_child = [];
        $arr_search = [];
        if($keyword && is_array($keyword)) {
          $wh_child = [];
          if($search_option == "all") {
              foreach($keyword as $key) {
                if($key != "") {
                    array_push($wh_child,"(lower(o.orders_code) like lower('%$key%') or lower(c.full_name) like lower('%$key%') or lower(o.address) like lower('%$key%') or lower(total) like lower('%$key%'))");
                }
              }
          } else if($search_option == "orders_code") {
              foreach($keyword as $key) {
                if($key != "") {
                    array_push($wh_child,"(lower(o.orders_code) like lower('%$key%'))");
                }
              }
          } else if($search_option == "full_name") {
              foreach($keyword as $key) {
                if($key != "") {
                    array_push($wh_child,"(lower(c.full_name) like lower('%$key%'))");
                }
              }
          } else if($search_option == "o_address") {
              foreach($keyword as $key) {
                if($key != "") {
                    array_push($wh_child,"(lower(o.address) like lower('%$key%'))");
                }
              }
          } else if($search_option == "total") {
              foreach($keyword as $key) {
                if($key != "") {
                    array_push($wh_child,"(lower(total) like lower('%$key%'))");
                }
              }
          }
          $wh_child = implode(" or ",$wh_child);
          if($wh_child != "") {
              $where .= " and ($wh_child)";
          }
        }
        if($total_min && is_array($total_min) && $total_max && is_array($total_max)) {
          $wh_child = [];
          foreach(array_combine($total_min,$total_max) as $t_min => $t_max) {
              if($t_min != "" && $t_max != "") {
                $t_min = str_replace(",","",$t_min);
                $t_max = str_replace(",","",$t_max);
                array_push($wh_child,"(total >= '$t_min' and total <= '$t_max')");
              } else if($t_min == "" && $t_max != ""){
                $t_max = str_replace(",","",$t_max);
                array_push($wh_child,"(total <= '$t_max')");
              } else if($t_min != "" && $t_max == ""){
                $t_min = str_replace(",","",$t_min);
                array_push($wh_child,"(total >= '$t_min')");
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
                array_push($wh_child,"(o.created_at >= '$d_min 00:00:00' and o.created_at <= '$d_max 23:59:59')");
              } else if($d_min != "" && $d_max == "") {
                $d_min = Date("Y-m-d",strtotime($d_min));
                array_push($wh_child,"(o.created_at >= '$d_min 00:00:00')");
              } else if($d_min == "" && $d_max != "") {
                $d_max = Date("Y-m-d",strtotime($d_max));
                array_push($wh_child,"(o.created_at <= '$d_max 23:59:59')");
              }
          }
          $wh_child = implode(" or ",$wh_child);
          if($wh_child != "") {
              $where .= " and ($wh_child)";
          }
        }
        if($select_payment_status) {
          if($select_payment_status == "payment_completed"){
            $where .= " and o.payment_status='1'";
          } else if($select_payment_status == "payment_not_completed"){
            $where .= " and o.payment_status='0'";
          }
        }
        log_v($where);
?>
<!--html & css section start-->
<style>
  table.dataTable tr th.select-checkbox.selected::after {
    content: "\2713";
    margin-top: -11px;
    margin-left: -4px;
    text-align: center;
    color: #9900ff;
  }
  .dt-buttons {
    float:left;
  }
</style>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="css/select.dataTables.min.css">
<link rel="stylesheet" href="css/colReorder.dataTables.min.css">
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
                  <form style="margin-bottom: 17px;display:flex;align-items:flex-start;" autocomplete="off" action="order_manage.php" method="get" onsubmit="customInpSend()">
                      <div class="" style="margin-top:5px;">
                        <select onchange="choose_type_search()" class="form-control" name="search_option2">
                            <option value="">Bộ lọc tìm kiếm</option>
                            <option value="keyword">Từ khoá</option>
                            <option value="payment_status2">Tình trạng thanh toán</option>
                            <option value="payment_method2">Phương thức thanh toán</option>
                            <option value="total2">Khoảng tổng tiền</option>
                            <option value="date2">Ngày tạo đơn hàng</option>
                            <option value="all2">Tất cả</option>
                        </select>
                      </div>
                      <div id="s-cols" class="k-select-opt ml-15 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column": "display:none;";?>">
                        <span class="k-select-opt-remove"></span>
                        <span class="k-select-opt-ins"></span>
                        <div class="ele-cols d-flex f-column">
                            <select name="search_option" class="form-control mb-10">
                              <option value="">Chọn cột tìm kiếm</option>
                              <option value="orders_code" <?=$search_option == 'orders_code' ? 'selected="selected"' : '' ?>>Mã hoá đơn</option>
                              <option value="full_name" <?=$search_option == 'full_name' ? 'selected="selected"' : '' ?>>Tên người dùng</option>
                              <option value="o_address" <?=$search_option == 'o_address' ? 'selected="selected"' : '' ?>>Địa chỉ</option>
                              <option value="total" <?=$search_option == 'total' ? 'selected="selected"' : '' ?>>Tổng tiền</option>
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
                      <div id="s-total2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($total_min && $total_min != [""] || $total_max && $total_max != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                        <span class="k-select-opt-remove"></span>
                        <span class="k-select-opt-ins"></span>
                        <div class="ele-price2">
                            <div class="" style="display:flex;">
                              <input type="text" name="total_min[]" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Tổng tiền 1" class="form-control" value=""  >
                            </div>
                            <div class="ml-10" style="display:flex;">
                              <input type="text" name="total_max[]" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Tổng tiền 2" class="form-control" value="" >
                            </div>
                        </div>
                        <?php
                            if(is_array($total_min) && is_array($total_max)) {
                              foreach(array_combine($total_min,$total_max) as $t_min => $t_max){
                        ?>
                            <?php
                            if($t_min != "" || $t_max != "") {
                            ?>
                            <div class="ele-select ele-price2 mt-10">
                              <div class="" style="display:flex;">
                                  <input type="text" min="0" name="total_min[]" placeholder="Tổng tiền 1" class="form-control" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$t_min;?>"  >
                              </div>
                              <div class="ml-10" style="display:flex;">
                                  <input type="text" min="0" name="total_max[]" placeholder="Tổng tiền 2" class="form-control" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$t_max;?>"  >
                              </div>
                              <span onclick="select_remove_child('.ele-price2')" class="kh-select-child-remove"></span>
                            </div>
                            <?php
                            }?>
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
                              <input type="text" name="date_min[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$d_min ? Date("d-m-Y",strtotime($d_min)) : "";?>">
                            </div>
                            <div class="ml-10" style="display:flex;">
                              <input type="text" name="date_max[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$d_max ? Date("d-m-Y",strtotime($d_max)) : "";?>">
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
                      <input type="hidden" name="is_search" value="true">
                      <div id="s-payment_status2" class="k-select-opt ml-15 col-2 s-all2" style="border:1px dashed blue !important;<?=$select_payment_status ? "display:block;" : "display:none;";?>">
                        <select onchange="activePayment()" name="select_payment_status" class="form-control">
                          <option value="">Tình trạng thanh toán</option>
                          <option value="payment_completed" <?=$select_payment_status == 'payment_completed' ? 'selected="selected"' : '' ?>>Đã thanh toán</option>
                          <option value="payment_not_completed" <?=$select_payment_status == 'payment_not_completed' ? 'selected="selected"' : '' ?>>Chưa thanh toán</option>
                        </select>
                      </div>
                      <div id="s-payment_method2" class="k-select-opt ml-15 col-2 s-all2" style="border:1px dashed blue !important;<?=$select_payment_status ? "display:block;" : "display:none;";?>">
                        <select style="<?=$select_payment_status=='payment_completed' ? "cursor:pointer;" : "cursor:not-allowed;";?>" name="select_payment_method" class="form-control" <?=$select_payment_status=='payment_completed' ? "" : "disabled"?>>
                          <option value="">Phương thức thanh toán</option>
                          <?php
                            $sql = "select * from payment_method";
                            $payment = fetch_all(sql_query($sql));
                            foreach($payment as $pay) {
                          ?>
                              <option value="<?=$pay['id']?>" <?=$search_option == $pay['id'] ? 'selected="selected"' : '' ?>><?=$pay['payment_name']?></option>
                          <?php } ?>
                          <option value="all" <?=$search_option == 'all' ? 'selected="selected"' : '' ?>>Tất cả</option>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-default ml-15" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                  </form>
                </div>
                <div class="mb-3 col-12 d-flex j-between" style="padding-right:0px;padding-left:0px;">
                  <div>
                    <button tabindex="-1" onclick="showListPayment()" class="dt-button button-red">Thanh toán online</button>
                    <?php
                      if($allow_read) {
                    ?>
                    <button onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                    <?php } ?>         
                  </div>
                </div>
                <table id="m-order" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th></th>
					            <th>Số thứ tự</th>
                      <th>Mã hoá đơn</th>
                      <th>Tên khách hàng</th>
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
                    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                    $limit = $_SESSION['paging'];
                    $start_page = $limit * ($page - 1);
                    $sql_get_total = "select count(*) as 'countt' from orders o inner join customer c on o.customer_id = c.id $where";
                    $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                    array_push($arr_paras,$start_page);
                    array_push($arr_paras,$limit);
                    $sql_get_order = "select o.id as 'o_id',o.address as 'o_address', o.orders_code,o.total,o.payment_status,
                    o.created_at as 'o_created_at',o.customer_id as 'o_customer_id',c.full_name,c.phone from orders o inner join customer c on o.customer_id = c.id $where limit ?,?";
                    $rows = db_query($sql_get_order,$arr_paras);
                    $i = 0;
				          	$cnt = 0;
                    foreach($rows as $row) {
                  ?>
                    <tr id="<?=$row['o_id']?>">
                        <td></td>
						            <td><?=$total - ($start_page + $cnt);?></td>
                        <td><?=$row['orders_code']?></td>
                        <td><?=$row['full_name']?></td>
                        <td><?=$row['o_address']?></td>
                        <td><?=number_format($row['total'],0,"",".")."đ";?></td>
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
                        <td><?=Date("d-m-Y H:i:s",strtotime($row['o_created_at']));?></td>
                        <td>
                          <?php
                              if($allow_read){
                          ?>
                          <button class="btn-xem-hoa-don dt-button button-grey"
                          data-id="<?=$row["o_id"];?>" >
                          Xem
                          </button>
                          <?php } ?>
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
                      <th></th>
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

<!-- /.Xem chi tiết đơn hàng -->
<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Thông tin hoá đơn</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="form-order">

        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.Quản lý phương thức thanh toán -->
<div class="modal fade" id="modal-xl2">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Phương thức thanh toán</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="form-payment">

         </div>
      </div>
      <div class="modal-footer justify-content-between">
        
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
<script src="js/dataTables.bootstrap4.min.js"></script>
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
    let _option = $("select[name='search_option2'] > option:selected").val();
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
    } else if($(event.currentTarget).closest('#s-total2').length) {
        file_html = `
        <div class="ele-select ele-price2 mt-10">
          <div class="" style="display:flex;">
              <input type="text" name="total_min[]" placeholder="Tổng tiền 1" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
          </div>
          <div class="ml-10" style="display:flex;">
              <input type="text" name="total_max[]" placeholder="Tổng tiền 2" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
          </div>
          <span onclick="select_remove_child('.ele-price2')" class="kh-select-child-remove"></span>
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
  function activePayment(){
    let status = $("select[name='select_payment_status'] > option:selected").val();
    if(status == "payment_completed") {
      $("select[name='select_payment_method']").prop("disabled",false);
      $("select[name='select_payment_method']").css({"cursor":"pointer"});
    } else {
      $("select[name='select_payment_method']").prop("disabled",true);
      $("select[name='select_payment_method']").css({"cursor":"not-allowed"});
      $("select[name='select_payment_method'] > option[value='']").prop('selected',true);
    }
  }
  function showListPayment(){
    $('#form-payment').load(`ajax_order_manage.php?status=show_list_payment`,() => {
      $('#modal-xl2').modal({backdrop: 'static', keyboard: false});
    });
  }
  function showOrderDetail(){
    let order_id = $(event.currentTarget).attr('data-order-id');
    $('#form-order-detail').load(`ajax_order_manage.php?status=show_order_detail&order_id=${order_id}`,() => {
      $('#modal-xl').modal({backdrop: 'static', keyboard: false});
    });
  }
  function changeActivePayment(){
    let id = $(event.currentTarget).attr('data-id');
    let yn = $(event.currentTarget).attr('data-active');
    let this2 = $(event.currentTarget);
    $.ajax({
      url: window.location.href,
      type: "POST",
      data: {
        token: "<?php echo_token();?>",
        status: "active_payment",
        yn: yn,
        payment_id: id,
      },
      success: function(data) {
        console.log(data);
        data = JSON.parse(data);
        if(data.msg == "ok") {
          if(data.yn == "y") {
            this2.removeClass("button-red");
            this2.addClass("button-green");
            this2.attr("data-active",'n');
            this2.text("Active");
            this2.closest("tr").find("td").eq(2).text("Ngưng Hoạt động");
          } else if(data.yn == "n") {
            this2.removeClass("button-green");
            this2.addClass("button-red");
            this2.attr("data-active",'y');
            this2.text("Inactive");
            this2.closest("tr").find("td").eq(2).text("Hoạt động");
          }
        }
      },error:function(data) {
        console.log("Error: " + data);
      }
    });
  }
   
</script>
<script>
    var dt_order;
    $(document).ready(function (e) {
      dt_order = $("#m-order").DataTable({
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
               "targets": 7
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
                "autoFilter": true,
                "filename": "danh_sach_hoa_don_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                "title": "Dữ liệu hoá đơn trích xuất ngày <?=Date("d-m-Y",time());?>",
                "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
                "extend": "pdf",
                "text": "PDF (3)",
                "key": {
                    "key": '3',
                },
                "filename": "danh_sach_hoa_don_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                "title": "Dữ liệu hoá đơn trích xuất ngày <?=Date("d-m-Y",time());?>",
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
                "filename": "danh_sach_hoa_don_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
                "extend": "print",
                "text": "In bảng (5)",
                "key": {
                    "key": '5',
                },
                "filename": "danh_sach_hoa_don_trich_xuat_ngay_<?=Date("d-m-Y",time());?>",
                "title": "Dữ liệu hoá đơn trích xuất ngày <?=Date("d-m-Y",time());?>",
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
      dt_order.buttons.exportData( {
         columns: ':visible'
      });
      dt_order.on("click", "th.select-checkbox", function() {
         if ($("th.select-checkbox").hasClass("selected")) {
            dt_order.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
         } else {
            dt_order.rows().select();
            $("th.select-checkbox").addClass("selected");
         }
      }).on("select deselect", function() {
         if (dt_order.rows({
                  selected: true
            }).count() !== dt_order.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
         } else {
            $("th.select-checkbox").addClass("selected");
         }
      });
      //
      dt_order.buttons().container().appendTo('#m-order_wrapper .col-md-6:eq(0)');
    });
    // Xem san pham
    $(document).on('click','.btn-xem-hoa-don',function(event){
        let id = $(event.currentTarget).attr('data-id');
        $('#form-order').load("ajax_order_manage.php?order_id=" + id + "&status=show_order_detail",() => {
          $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        });
    });
    function readMore(){
      let arr_del = [];
      let _data = dt_order.rows(".selected").select().data();
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
      $('#form-order').load(`ajax_order_manage.php?status=show_order_detail_fast&str_arr_upt=${str_arr_upt}`,() => {
        let html2 = `
          <div id="paging" style="justify-content:center;" class="row">
            <nav id="pagination3">
            </nav>
          </div>
        `;
        $(html2).appendTo('#form-order');
        $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        $('.t-row').css({
          "display":"none",
        });
        $('.t-row-1').css({
          "display":"contents",
        });
        $('#pagination3').pagination({
         items: count4,
         itemsOnPage: 1,
         currentPage: 1,
         prevText: "<",
         nextText: ">",
         onPageClick: function(pageNumber,event){
            $(`.t-row`).css({"display":"none"});
            $(`.t-row-${pageNumber}`).css({"display":"contents"});
         },
         cssStyle: 'light-theme',
        });
      });
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
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        /*if($status == 1) {
           ajax_db_update_by_id('orders',['payment_status'=>1],[$id]);
        } else if($status == -1) {
            $sql_load_order_detail = "select pi.name,pi.img_name as 'image',od.count,od.price from product_info pi inner join order_detail od on pi.id = od.product_info_id where od.order_id = ?";
            ajax_db_query($sql_load_order_detail,[$id]);
        } else if($status == 0) {
          $sql_get_client_info = "select username,email,birthday,phone,address,img_name from customer where id = ? limit 1";
          ajax_fetch_row($sql_get_client_info,[$id]);
        } else*/
        if($status == "active_payment") {
          $payment_id = isset($_REQUEST["payment_id"]) ? $_REQUEST["payment_id"] : null;
          $yn = isset($_REQUEST["yn"]) ? $_REQUEST["yn"] : null;
          if($yn == "n") {
            $sql_update = "update payment_method set is_active='1' where id = '$payment_id'";
          } else if($yn == "y") {
            $sql_update = "update payment_method set is_active='0' where id = '$payment_id'";
          }
          sql_query($sql_update);
          echo_json(["msg" => "ok","yn" => $yn]);
        }
    }
?>