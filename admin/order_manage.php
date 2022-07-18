<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        $allow_read = false;
        if(check_permission_crud("order_manage.php","read")) {
          $allow_read = true;
        }
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
        $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
        $total_min = isset($_REQUEST['total_min']) ? $_REQUEST['total_min'] : null;
        $total_max = isset($_REQUEST['total_max']) ? $_REQUEST['total_max'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
        $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
        $select_payment_status_id = isset($_REQUEST['select_payment_status_id']) ? $_REQUEST['select_payment_status_id'] : null;
        $select_payment_method = isset($_REQUEST['select_payment_method']) ? $_REQUEST['select_payment_method'] : null;
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $where = "where 1=1 and u.type='customer' ";
        $order_by = "Order by o.id desc";
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
        if($total_min) {
          $total_min = str_replace(".","",$total_min);
          $where .= " and (total >= '$total_min')";
        }
        if($total_max) {
          $total_max = str_replace(".","",$total_max);
          $where .= " and (total <= '$total_max')";
        }

        if($date_min) {
          $date_min = Date("Y-m-d",strtotime($date_min));
          $where .= " and (o.created_at >= '$date_min 00:00:00')";
        }
        if($date_max) {
          $date_max = Date("Y-m-d",strtotime($date_max));
          $where .= " and (o.created_at <= '$date_max 23:59:59')";
        }
        if($select_payment_status_id) {
          if($select_payment_status_id == "payment_completed"){
            $where .= " and o.payment_status_id='1'";
          } else if($select_payment_status_id == "payment_not_completed"){
            $where .= " and o.payment_status_id='0'";
          }
        }
        if($select_payment_method) {
          $where .= " and o.payment_method_id='$select_payment_method'";
        }
        if($orderByColumn && $orderStatus) {
          $order_by = "ORDER BY $orderByColumn $orderStatus";
        }
        $where .= " $order_by";
        //log_v($where);
?>
<!--html & css section start-->
<style>
  .sort-asc,.sort-desc {
    display: none;
  }
</style>
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
              <div class="card-body ok-game-start">
                  <div id="load-all">
                    <link rel="stylesheet" href="css/tab.css">             
                    <div style="padding-right:0px;padding-left:0px;" class="col-12 mb-20 d-flex a-center j-between">
                      <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                      
                          <?php
                            $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                            $_SESSION['order_manage_tab'] = isset($_SESSION['order_manage_tab']) ? $_SESSION['order_manage_tab'] : [];
                            $_SESSION['order_tab_id'] = isset($_SESSION['order_tab_id']) ? $_SESSION['order_tab_id'] : 0;
                          ?>
                          <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('order_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>
                          <?php
                            $ik = 0;
                            $is_active = false;
                            if(count($_SESSION['order_manage_tab']) > 0) {
                                foreach($_SESSION['order_manage_tab'] as $tab) {
                                  if($tab['tab_unique'] == $tab_unique) {
                                      $_SESSION['order_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                  }
                          ?>
                            <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                <button onclick="loadDataInTab('<?=$_SESSION['order_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
                                
                                <span onclick="delTabFilter('<?=($tab['tab_unique'] == $tab_unique);?>')" class="k-tab-delete"></span>
                                
                            </li>
                          <?php
                                $ik++;
                            }
                          }
                          ?>                      
                      </ul>
                      <div class="d-flex j-center a-center" style="position:relative;">
                        <div onclick="saveTabFilter()" style="" class="add-tab">
                            <button class="btn-add-tab"><span class="add-tab-plus">+</span></button>
                        </div>
                      </div>
                    </div>
                    <div class="img-load" style="display:none;text-align:center;" class="d-flex" style="width:100%;">
                      <img src="img/load.gif" alt="">
                    </div>
                    <div id="is-load">
                      <div style="padding-left:0;padding-right:0;" class="col-12" >
                        <form id="form-filter" autocomplete="off" action="order_manage.php" method="get" onsubmit="searchTabLoad('#form-filter')">
                            <div class="d-flex a-start">
                              <div class="" style="margin-top:5px;">
                                <select onchange="choose_type_search()" class="form-control" name="search_option2">
                                    <option value="">Bộ lọc tìm kiếm</option>
                                    <option value="keyword">Từ khoá</option>
                                    <option value="total2">Khoảng tổng tiền</option>
                                    <option value="date2">Ngày tạo đơn hàng</option>
                                    <option value="all2">Tất cả</option>
                                </select>
                              </div>
                              <div id="s-cols" class="k-select-opt ml-10 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                <span onclick="selectOptionInsert()" class="k-select-opt-ins"></span>
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
                              <div id="s-total2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($total_min || $total_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                <div class="ele-price2">
                                    <div class="" style="display:flex;">
                                      <input type="text" name="total_min" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Tổng tiền 1" class="form-control" value="<?=$total_min ? number_format($total_min,0,".",".") : null;?>">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                      <input type="text" name="total_max" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Tổng tiền 2" class="form-control" value="<?=$total_max ? number_format($total_max,0,".",".") : null;?>">
                                    </div>
                                </div>
                              </div>
                              <div id="s-date2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($date_min || $date_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                <div class="ele-date2">
                                    <div class="" style="display:flex;">
                                      <input type="text" name="date_min" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$date_min ? Date("d-m-Y",strtotime($date_min)) : null;?>">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                      <input type="text" name="date_max" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$date_max ? Date("d-m-Y",strtotime($date_max)) : null;?>">
                                    </div>
                                </div>
                              </div>
                              <input type="hidden" name="is_search" value="true">
                              <div id="s-payment_method2" class="k-select-opt ml-10 col-2 s-all2" style="border:1px dashed blue !important;<?=$select_payment_status_id ? "display:block;" : "display:none;";?>">
                                <select name="select_payment_method" class="form-control">
                                  <option value="">Phương thức thanh toán</option>
                                  <?php
                                    $sql = "select * from payment_method";
                                    $payment = fetch_all(sql_query($sql));
                                    foreach($payment as $pay) {
                                  ?>
                                      <option value="<?=$pay['id']?>" <?=$select_payment_method == $pay['id'] ? 'selected="selected"' : '' ?>><?=$pay['payment_name']?></option>
                                  <?php } ?>
                                  <option value="all" <?=$search_option == 'all' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                </select>
                              </div>
                              <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                              <button type="submit" class="btn btn-default ml-10" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                      </div>
                      <div class="mb-3 col-12 d-flex j-between" style="padding-left:0;padding-right:0;">
                        <div class="mt-15">
                          <!-- <button tabindex="-1" onclick="showListPayment()" class="dt-button button-red">Thanh toán online</button> -->
                          <?php
                            if($allow_read) {
                          ?>
                          <button onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                          <?php } ?>         
                        </div>
                        
                      </div>
                      <div class="table-game-start">
                      <table id="table-order_manage" class="table table-bordered table-striped">
                        <thead>
                          <tr style="cursor:pointer;">
                            <th style="width:20px !important;">
                                <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                            </th>
                            <th class="th-so-thu-tu" style='width:100px'>Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="th-ma-hoa-don w-120">Mã hoá đơn <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="th-ten-khach-hang w-150">Tên khách hàng <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="th-dia-chi-nhan-hang w-300">Địa chỉ nhận hàng <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="th-tong-tien w-100">Tổng tiền <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="th-tinh-trang-thanh-toan" style="width:250px;">Trạng thái <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="th-ngay-tao w-100">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                            <th class="w-200">Thao tác</th>
                          </tr>
                        </thead>
                        <?php
                          // query
                          $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;  
                          $limit = $_SESSION['paging'];
                          $start_page = $limit * ($page - 1);
                          $sql_get_total = "select count(*) as 'countt' from orders o inner join user u on o.customer_id = u.id $where";
                          $total = fetch(sql_query($sql_get_total))['countt'];
                        ?>
                        <tbody dt-parent-id dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-order">
                        <?php
                          
                          $sql_get_order = "select o.id as 'o_id',o.is_cancel as 'o_is_cancel',o.delivery_status_id as 'o_delivery_status_id',o.address as 'o_address', o.orders_code,o.total,o.payment_status_id,
                          o.created_at as 'o_created_at',o.customer_id as 'o_customer_id',u.full_name,u.phone from orders o inner join user u on o.customer_id = u.id and u.type='customer' $where limit $start_page,$limit";
                          $rows = fetch_all(sql_query($sql_get_order));
                          $i = 0;
                          $cnt = 0;
                          foreach($rows as $row) {
                        ?>
                          <tr id="<?=$row['o_id']?>">
                              <td>
                                <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["o_id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange()" type="checkbox" name="check_id<?=$row["o_id"];?>">
                              </td>
                              <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                              <td class="ma-hoa-don"><?=$row['orders_code']?></td>
                              <td class="ten-khach-hang"><?=$row['full_name']?></td>
                              <td class="dia-chi-nhan-hang"><?=$row['o_address']?></td>
                              <td class="tong-tien"><?=number_format($row['total'],0,"",".")."đ";?></td>
                              <td class="tinh-trang-thanh-toan">
                                <?php
                                  $sql_get_payment_status = "select * from payment_status where id = " . $row['payment_status_id'];
                                  $res = fetch(sql_query($sql_get_payment_status));
                                  //
                                  $sql_get_payment_status = "select * from delivery_status where id = " . $row['o_delivery_status_id'];
                                  $res2 = fetch(sql_query($sql_get_payment_status));
                                  echo $res['payment_status_name'] . " - " . $res2['delivery_status_name'];
                                  
                                ?>
                              </td>
                              <td class="ngay-tao"><?=Date("d-m-Y",strtotime($row['o_created_at']));?></td>
                              <td>
                                <?php
                                    if($allow_read){
                                ?>
                                <button class="btn-xem-hoa-don dt-button button-grey"
                                data-id="<?=$row["o_id"];?>" >
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                </button>
                                <?php } ?>
                                <?php
                                  if($row['o_delivery_status_id'] == 1) {
                                ?>
                                  <button onclick="showModalShipOrder('<?=$row['o_id'];?>')" class="dt-button button-grey"
                                  data-id="<?=$row["o_id"];?>" >
                                  <i class="fas fa-shipping-fast"></i>
                                  </button>
                                <?php } ?>
                                  
                                <?php if($row['o_delivery_status_id'] > 1) {?>

                                <?php } else if($row['o_is_cancel'] == 0) {?>
                                  <button onclick="cancelOrder('<?=$row['o_id'];?>')" class="dt-button button-red"
                                  data-id="<?=$row["o_id"];?>" >
                                  Huỷ đơn hàng
                                  </button>
                                <?php
                                  } else if($row['o_is_cancel'] != 0){
                                ?>
                                  <button class="dt-button button-red">
                                  Đơn hàng đã huý
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
                        <?php
                            $count_row_table = count($rows);
                            if($count_row_table == 0) {
                        ?>
                        <tr>
                            <td style="text-align:center;font-size:17px;" colspan="20">Không có dữ liệu</td>
                        </tr>
                        <?php } ?>
                        <tfoot>
                          <tr>
                            <th style="width:20px !important;">
                              <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                            </th>
                            <th>Số thứ tự</th>
                            <th>Mã hoá đơn</th>
                            <th>Tên người dùng</th>
                            <th>Địa chỉ nhận hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                          </tr>
                        </tfoot>
                      </table>
                      </div>
                      <ul id="pagination" style="justify-content:center;display:flex;" class="pagination"></ul>
                    </div>
                  </div>
              </div>
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
<!-- /.Load danh sách nhân viên giao hàng -->
<div class="modal fade" id="modal-xl3">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Giao đơn hàng cho shipper vận chuyển</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="form-order-shipper">

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
<?php
    include_once("include/dt_script.php");
?>
<!--searching filter-->
<script src="js/toastr.min.js"></script>
<script src="js/khoi_all.js"></script>
<script>
  setSortTable();
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
  function selectOptionRemove(){
    $(event.currentTarget).siblings('.ele-select').remove()
    $(event.currentTarget).siblings("div").find("input").val("");
    $(event.currentTarget).closest('div').css({"display":"none"});
  }
  function selectOptionInsert(){
    let file_html = "";
    if($(event.currentTarget).closest('#s-cols').length) {
        file_html = `
        <div class="ele-select ele-cols mt-10">
          <input type="text" name="keyword[]" placeholder="Nhập từ khoá..." class="form-control" value="">
          <span onclick="select_remove_child('.ele-cols')" class="kh-select-child-remove"></span>
        </div>
        `;
    }
    $(file_html).appendTo($(event.currentTarget).parent());
    $(event.currentTarget).parent().css({
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
  }
  function select_remove_child(_class){
    $(event.currentTarget).closest(_class).remove();
  }
  function updatePaymentStatus(order_id){
    let payment_status_id = $('select[name="update-payment-status"] > option:selected').val();
    if(payment_status_id != "") {
      $.ajax({
        url:window.location.href,
        type:"POST",
        data: {
          "order_id": order_id,
          "status": "update_payment_status",
          "payment_status_id": payment_status_id,
        },success:function(data){
          console.log(data);
          data = JSON.parse(data);
          if(data.msg == "ok") {
            $.alert({
              title: "Thông báo",
              content: "Bạn đã thay đổi trạng thái thanh toán đơn hàng thành công",
            })
          }
        }
      })
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
    $(document).on('click','.btn-xem-hoa-don',function(event){
        let id = $(event.currentTarget).attr('data-id');
        $('#form-order').load("ajax_order_manage.php?order_id=" + id + "&status=show_order_detail",() => {
          $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        });
    });
    // mở modal shipping
    function showModalShipOrder(order_id){
      $('#form-order-shipper').load("ajax_order_manage.php?status=load_shipper&order_id="+order_id,() => {
        $('#modal-xl3').modal({backdrop: 'static', keyboard: false});
        $(".kh-datepicker").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd-mm-yy',
        });
      });
    }
    // giao đơn cho shipper vận chuyển
    function giveOrderToShipper(order_id){
      let shipper_id = $("select[name='choose_shipper'] > option:selected").val();
      let delivery_date = $("input[name='delivery_date']").val();
      console.log(delivery_date);
      if(shipper_id == "") {
        $.alert({
          title: "Thông báo",
          content: "Bạn vui lòng chọn shipper giao hàng",
        });
        return;
      } else if(delivery_date == "") {
        $.alert({
          title: "Thông báo",
          content: "Bạn vui lòng chọn ngày giao hàng",
        });
        return;
      }
      delivery_date = delivery_date.split("-");
      delivery_date = delivery_date[2] + "-" + delivery_date[1] + "-" + delivery_date[0];
      $.confirm({
        content: 'Bạn có chắc chắn muốn giao đơn hàng cho shipper này ?',
        buttons: {
          "Có":function(){
            $.ajax({
                url:window.location.href,
                type:"POST",
                data: {
                  status:"give_order_to_shipper",
                  id : order_id,
                  shipper_id : shipper_id,
                  delivery_date : delivery_date,
                },success:function(data) {
                  data = JSON.parse(data);
                  if(data.msg == "ok") {
                    $.alert({
                      title: "Thông báo",
                      content: data.success,
                      buttons: {
                        "Ok":function(){
                          location.reload();
                        }
                      }
                    });
                  }
                },error:function(data) {

                }
              })
          },
          "Không":function(){

          }
        }
      })
    }
    // huỷ đơn hàng
    function cancelOrder(order_id){
      $.confirm({
        content: 'Bạn có chắc chắn muốn huỷ nhiều đơn hàng cùng lúc ?',
        buttons: {
          "Có": function(){
            $.ajax({
              url:window.location.href,
              type:"POST",
              data: {
                "status":"cancel_order",
                "id" : order_id,
              },success:function(data) {
                data = JSON.parse(data);
                if(data.msg == "ok") {
                  $.alert({
                    title: "Thông báo",
                    content: data.success,
                    buttons: {
                      "Ok":function(){
                        loadDataComplete();
                      }
                    }
                  });
                }
              },error:function(data) {

              }
            })
          },"Không":function(){

          }
        }
      })
      
    }
    // huỷ nhiều đơn hàng
    function cancelMoreOrder(){
      $.confirm({
        content: 'Bạn có chắc chắn muốn huỷ nhiều đơn hàng cùng lúc ?',
        buttons: {
          "Có":function(){
              let arr_del = [];
              let _data = dt_order.rows(".selected").select().data();
              let count4 = _data.length;
              for(i = 0 ; i < count4 ; i++) {
                arr_del.push(_data[i].DT_RowId);
              }
              let str_arr_upt = arr_del.join(",");
              $.ajax({
                url:window.location.href,
                type:"POST",
                data: {
                  "status":"cancel_more_order",
                  "list_id" : str_arr_upt,
                },success:function(data) {
                  data = JSON.parse(data);
                  if(data.msg == "ok") {
                    $.alert({
                      title: "Thông báo",
                      content: data.success,
                      buttons: {
                        "Ok":function(){
                          loadDataComplete();
                        }
                      }
                    });
                  }
                },error:function(data) {

                }
              })
          },
          "Không":function(){

          }
        }
        
      })
      
    }
   
</script>
<!--js section end-->
<?php
    include_once("include/pagination.php");
    include_once("include/footer.php");
?>
<?php
    } else if (is_post_method()) {
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
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
        } else if($status == "cancel_order") {
          $sql_cancel_order = "Update orders set is_cancel = '1' where id = '$id'";
          sql_query($sql_cancel_order);
          echo_json(["msg" => "ok","success" => "Bạn đã huỷ đơn hàng thành công"]);
        } else if($status == "cancel_more_order") {
          $list_id = isset($_REQUEST["list_id"]) ? $_REQUEST["list_id"] : null;
          $sql_cancel_more_order = "Update orders set is_cancel = '1' where id in ($list_id)";
          sql_query($sql_cancel_more_order);
          echo_json(["msg" => "ok","success" => "Bạn đã huỷ nhiều đơn hàng thành công"]);
        } else if($status == "give_order_to_shipper") {
          $shipper_id = isset($_REQUEST["shipper_id"]) ? $_REQUEST["shipper_id"] : null;
          $delivery_date = isset($_REQUEST["delivery_date"]) ? Date("Y-m-d",strtotime($_REQUEST["delivery_date"])) : null;
          $sql_give_order_to_shipper = "Update orders set shipper_id = '$shipper_id',delivery_date = '$delivery_date',delivery_status_id = '2' where id = '$id'";
          sql_query($sql_give_order_to_shipper);
          $sql_ins_history = "Insert into orders_delivery_status(order_id,delivery_status_id,reason) values(?,?,?)";
          sql_query($sql_ins_history,[$id,2,"Đơn hàng đã được chuyển cho shipper giao hàng ở trạng thái đã xác nhận"]);
          echo_json(["msg" => "ok","success" => "Đơn hàng đã được chuyển cho shipper xử lý thành công ở trạng thái đã xác nhận"]);
        } else if($status == "saveTabFilter") {
          $_SESSION['order_tab_id'] = isset($_SESSION['order_tab_id']) ? $_SESSION['order_tab_id'] + 1 : 1;
          $tab_name = isset($_SESSION['order_tab_id']) ? "tab_" . $_SESSION['order_tab_id'] : null;
          $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
          $tab_unique = uniqid("tab_");
          $_SESSION['order_manage_tab'] = isset($_SESSION['order_manage_tab']) ? $_SESSION['order_manage_tab'] : [];
          array_push($_SESSION['order_manage_tab'],[
             "tab_unique" => $tab_unique,
             "tab_name" => $tab_name,
             "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
          ]);
          echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['order_manage_tab']) - 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
       } else if($status == "deleteTabFilter") {
          $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
          $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
          array_splice($_SESSION['order_manage_tab'],$index,1);
          if(trim($is_active_2) == "") {
             echo_json(["msg" => "ok"]);
          }  else if($is_active_2 == 1) {
             if(array_key_exists($index,$_SESSION['order_manage_tab'])) {
                echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['order_manage_tab'][$index]['tab_urlencode']]);
             } else if(array_key_exists($index - 1,$_SESSION['order_manage_tab'])){
                echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['order_manage_tab'][$index - 1]['tab_urlencode']]);
             } else {
                echo_json(["msg" => "ok","tab_urlencode" => "order_manage.php?tab_unique=all"]);
             }
          }
       } else if($status == "changeTabNameFilter") {
          $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
          $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
          $_SESSION['order_manage_tab'][$index]['tab_name'] = $new_tab_name;
          echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['order_manage_tab'][$index]['tab_urlencode']]);
       } else if($status == "update_payment_status") {
        $payment_status_id = isset($_REQUEST["payment_status_id"]) ? $_REQUEST["payment_status_id"] : null;
        $order_id = isset($_REQUEST["order_id"]) ? $_REQUEST["order_id"] : null;
        $sql_update_payment_status = "Update orders set payment_status_id = $payment_status_id where id = $order_id";
        sql_query($sql_update_payment_status);
        echo_json(["msg" => "ok"]);
       }
    }
?>