<?php
   include_once("../lib/database.php");
   if(is_get_method()) {
      include_once("include/head.meta.php");
      include_once("include/left_menu.php"); 
      $allow_read = $allow_update = $allow_delete = $allow_insert = false;
      if(check_permission_crud("product_manage.php","read")) {
        $allow_read = true;
      }
      if(check_permission_crud("product_manage.php","update")) {
        $allow_update = true;
      }
      if(check_permission_crud("product_manage.php","delete")) {
        $allow_delete = true;
      }
      if(check_permission_crud("product_manage.php","insert")) {
        $allow_insert = true;
      }
      // if(check_permission_crud("product_manage.php","check_product")) {
      //    $allow_check_product = true;
      // }
      $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
      $price_min = isset($_REQUEST['price_min']) ? $_REQUEST['price_min'] : null;
      $price_max = isset($_REQUEST['price_max']) ? $_REQUEST['price_max'] : null;
      $count_min = isset($_REQUEST['count_min']) ? $_REQUEST['count_min'] : null;
      $count_max = isset($_REQUEST['count_max']) ? $_REQUEST['count_max'] : null;
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $pt_type = isset($_REQUEST['pt_type']) ? $_REQUEST['pt_type'] : null;
      $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
      $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
      $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
      $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
      $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
      $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
      $order_by = "Order by pi.id desc";
      $where = "where 1=1 and pi.is_delete = 0 ";
      $wh_child = [];
      $arr_search = [];
      if($keyword && is_array($keyword)) {
         $wh_child = [];
         if($search_option == "all") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.name) like lower('%$key%') or lower(pi.count) like lower('%$key%') or lower(pi.price) like lower('%$key%') or lower(pt.name) like lower('%$key%'))");
               }
            }
         } else if($search_option == "name") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.name) like lower('%$key%'))");
               }
            }
         } else if($search_option == "price") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.price) like lower('%$key%'))");
               }
            }
         } else if($search_option == "count") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.count) like lower('%$key%'))");
               }
            }
         } else if($search_option == "type") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pt.name) like lower('%$key%'))");
               }
            }
         }
         
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }
      if($pt_type && is_array($pt_type)) {
         $wh_child = [];
         foreach($pt_type as $pt) {
            if($pt != "") {
               array_push($wh_child,"pi.product_type_id = '$pt'");
            }
         }
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }

      if($price_min) {
         $price_min = str_replace(".","",$price_min);
         $where .= " and (pi.price >= '$price_min')";
      }
      if($price_max) {
         $price_max = str_replace(".","",$price_max);
         $where .= " and (pi.price <= '$price_max')";
      }

      if($count_min) {
         $count_min = str_replace(".","",$count_min);
         $where .= " and (pi.count >= '$count_min')";
      }
      if($count_max) {
         $count_max = str_replace(".","",$count_max);
         $where .= " and (pi.count <= '$count_max')";
      }

      if($date_min) {
         $date_min = Date("Y-m-d",strtotime($date_min));
         $where .= " and (pi.created_at >= '$date_min 00:00:00')";
      }
      if($date_max) {
         $date_max = Date("Y-m-d",strtotime($date_max));
         $where .= " and (pi.created_at <= '$date_max 00:00:00')";
      }
      if($str) {
         $where .= " and pi.id in ($str)";
      }
      if($orderStatus && $orderByColumn) {
         $order_by = "ORDER BY $orderByColumn $orderStatus";
      }
      $where .= " $order_by";
      //log_v($where);
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/summernote.min.css">
<link rel="stylesheet" href="css/toastr.min.css">
<style>
   .dt-buttons {
       float:left;
   }
	ul.col-md-6 > li.parent:first-child {
      border: 1px solid #dce1e5;
      position: relative;
      height: 39px;
      margin: auto;
      padding-top: 3px;
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
    color: red;
    font-weight:600;
    border:none;
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
      width:250px;
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
      background-color: #F0F0F0 !important;
   }
   .expand{
      font-size:12px;
      float:right;
      margin-right:5px;
   }
</style>
<style>
   .sort-asc,.sort-desc {
    display: none;
  }
</style>
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid" style="padding:0px;">
    <section class="content">
        <div class="row" style="">
            <div class="col-12">
               <div class="card" style="max-width:100%;">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý sản phẩm</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        <div class="input-group-append">
                           <?php
                              if($allow_insert) {
                           ?>
                           <button onclick="openModalInsert()" class="dt-button button-blue">
                              Thêm sản phẩm
                           </button>
                           <?php } ?>
                        </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-body ok-game-start">
                     <div id="load-all">
                        <link rel="stylesheet" href="css/tab.css">             
                        <div style="padding-right:0px;padding-left:0px; flex: 1;display: flex;overflow: auto;overflow-y:hidden;" class="col-12 mb-20 j-between d-flex a-center">
                           <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;" class="ul-tab" id="ul-tab-id">
                              <?php
                                 $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                 
                                 $_SESSION['product_manage_tab'] = isset($_SESSION['product_manage_tab']) ? $_SESSION['product_manage_tab'] : [];
                                 $_SESSION['product_tab_id'] = isset($_SESSION['product_tab_id']) ? $_SESSION['product_tab_id'] : 0;
                              ?>
                              <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('product_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>

                              <?php
                                 $ik = 0;
                                 $is_active = false;
                                 if(count($_SESSION['product_manage_tab']) > 0) {
                                    foreach($_SESSION['product_manage_tab'] as $tab) {
                                       if($tab['tab_unique'] == $tab_unique) {
                                          $_SESSION['product_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                       }
                              ?>
                                 <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                    <button onclick="loadDataInTab('<?=$_SESSION['product_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
                                    <span onclick="delTabFilter('<?=($tab['tab_unique'] == $tab_unique);?>')" class="k-tab-delete"></span>
                                 </li>
                              <?php
                                    $ik++;
                                 }
                              }
                              ?>
                              
                           </ul>
                           <div class="ml-10 d-flex j-center a-center" style="position:relative;">
                              <div onclick="saveTabFilter()" style="" class="add-tab">
                                 <button class="btn-add-tab"><span class="add-tab-plus">+</span></button>
                              </div>
                           </div>
                        </div>
                        <div class="img-load" style="display:none;text-align:center;" class="d-flex" style="width:100%;">
                           <img src="img/load.gif" alt="">
                        </div>
                        <div id="is-load">
                           <div class="col-12" style="padding-right:0px;padding-left:0px;">
                              <form id="form-filter" style="" autocomplete="off" action="product_manage.php" method="get" onsubmit="searchTabLoad('#form-filter')">
                                    <div class="d-flex a-start">
                                       <div class="" style="margin-top:5px;">
                                          <select onchange="choose_type_search()" class="form-control" name="search_option">
                                             <option value="">Bộ lọc tìm kiếm</option>
                                             <option value="keyword" <?=$search_option == 'type' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                                             <option value="price2" <?=$search_option == 'price2' ? 'selected="selected"' : '' ?>>Khoảng giá</option>
                                             <option value="count2" <?=$search_option == 'count2' ? 'selected="selected"' : '' ?>>Khoảng số lượng</option>
                                             <option value="date2" <?=$search_option == 'date2' ? 'selected="selected"' : '' ?>>Phạm vi ngày</option>
                                             <option value="type2" <?=$search_option == 'type2' ? 'selected="selected"' : '' ?>>Danh mục</option>
                                             <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                          </select>
                                       </div>
                                       <div id="s-cols" class="k-select-opt ml-10 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column": "display:none;";?>">
                                          <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                          <span onclick="selectOptionInsert()" class="k-select-opt-ins"></span>
                                          <div class="ele-cols d-flex f-column">
                                             <select name="search_option" class="form-control mb-10">
                                                <option value="">Chọn cột tìm kiếm</option>
                                                <option value="name" <?=$search_option == 'name' ? 'selected="selected"' : '' ?>>Tên sản phẩm</option>
                                                <option value="count" <?=$search_option == 'count' ? 'selected="selected"' : '' ?>>Số lượng</option>
                                                <option value="price" <?=$search_option == 'price' ? 'selected="selected"' : '' ?>>Đơn giá</option>
                                                <option value="type" <?=$search_option == 'type' ? 'selected="selected"' : '' ?>>Danh mục sản phẩm</option>
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
                                       <div id="s-price2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($price_min || $price_max ) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                          <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                          <div class="ele-price2">
                                             <div class="" style="display:flex;">
                                                <input type="text" name="price_min" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Giá 1" class="form-control" value="<?=$price_min ? number_format($price_min,0,".",".") : '';?>">
                                             </div>
                                             <div class="ml-10" style="display:flex;">
                                                <input type="text" name="price_max" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Giá 2" class="form-control" value="<?=$price_max ? number_format($price_max,0,".",".") : '';?>" >
                                             </div>
                                          </div>
                                       </div>
                                       <div id="s-count2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($count_min || $count_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                          <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                          <div class="ele-count2">
                                             <div class="" style="display:flex;">
                                                <input type="text" name="count_min" placeholder="Sl 1" class="form-control" value="<?=$count_min ? number_format($count_min,0,".",".") : null;?>" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                             </div>
                                             <div class="ml-10" style="display:flex;">
                                                <input type="text" name="count_max" placeholder="Sl 2" class="form-control" value="<?=$count_max ? number_format($count_max,0,".",".") : null;?>" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                             </div>
                                          </div>
                                       </div>
                                       <div id="s-date2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_min || $date_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
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
                                       <div id="s-type2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($pt_type && $pt_type != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                          <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                          <span onclick="selectOptionInsert()" class="k-select-opt-ins"></span>
                                          <div class="ele-type2">
                                             <select class="select-type2" style="width:100%;" class="form-control" name="pt_type[]">
                                                <option value="">Chọn danh mục cần tìm</option>
                                                <?php
                                                   $sql = "select * from product_type where is_delete = 0 and id in (select distinct product_type_id from product_info where is_delete = 0)";
                                                   $rows2 = fetch_all(sql_query($sql));
                                                   foreach($rows2 as $row2) {
                                                ?>
                                                   <option value="<?=$row2['id']?>"><?=$row2['name'];?></option>
                                                <?php
                                                   }
                                                ?>
                                             </select>
                                          </div>
                                          <?php
                                          if(is_array($pt_type)) {
                                             foreach($pt_type as $pt){
                                          ?>
                                          <?php
                                             if($pt != "") {
                                             ?>
                                          <div class="ele-select ele-type2 mt-10">
                                             <select class="select-type2" style="width:100%" class="form-control" name="pt_type[]">
                                                <option value="">Chọn danh mục cần tìm</option>
                                                <?php
                                                   $sql = "select * from product_type where is_delete = 0 and id in (select distinct product_type_id from product_info where is_delete = 0)";
                                                   $rows2 = fetch_all(sql_query($sql));
                                                   foreach($rows2 as $row2) {
                                                ?>
                                                   <option value="<?=$row2['id']?>" <?=$pt == $row2['id'] ? "selected" : ""; ?>><?=$row2['name'];?></option>
                                                <?php
                                                   }
                                                ?>
                                             </select>
                                             <span onclick="select_remove_child('.ele-type2')" class="kh-select-child-remove"></span>
                                          </div>
                                          <?php
                                          }?>
                                          <?php
                                             }
                                          }
                                          ?>
                                       </div>
                                       <button type="submit" class="btn btn-default ml-10" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                                    </div>
                                    <div class="d-flex a-start" style="padding-left:0;padding-right:0;display:flex;margin-top:15px;">
                                       <div style="" class="form-group row" style="flex-direction:row;align-items:center;">
                                          <!--<label for="">Sắp xếp:</label>-->
                                          <select name="orderByColumn" class="ml-10 form-control col-5">
                                             <option value="">Sắp xếp theo cột</option>
                                             <option value="pi.name" <?=$orderByColumn == "pi.name" ? "selected" : "";?>>Tên sản phẩm</option>
                                             <option value="pi.count" <?=$orderByColumn == "pi.count" ? "selected" : "";?>>Số lượng</option>
                                             <option value="pi.price" <?=$orderByColumn == "pi.price" ? "selected" : "";?>>Đơn giá</option>
                                             <option value="pt.name" <?=$orderByColumn == "pt.name" ? "selected" : "";?>>Danh mục</option>
                                             <option value="pi.created_at" <?=$orderByColumn == "pi.created_at" ? "selected" : "";?>>Ngày đăng</option>
                                          </select>
                                          <select name="orderStatus" class="ml-10 form-control col-5">
                                             <option value="">Thao tác sắp xếp</option>
                                             <option value="asc" <?=$orderStatus == "asc" ? "selected" : "";?>>Tăng dần (a - z) (1 - 9)</option>
                                             <option value="desc" <?=$orderStatus == "desc" ? "selected" : "";?>>Giảm dần (z - a) (9 - 1)</option>
                                          </select>
                                          <button type="submit" class="btn btn-default ml-10"><i class="fas fa-sort"></i></button>
                                       </div>     
                                    </div>
                                    <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
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
                                 <button onclick="uptMore('','<?=$tab_unique;?>')" id="btn-upt-fast" class="dt-button button-green">Sửa nhanh</button>
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
                           <div class="table-game-start">
                              <table id="table-product_manage" class="table table-bordered table-striped">
                                 <thead>
                                    <tr style="cursor:pointer;">
                                       <th style="width:20px !important;">
                                          <input <?=$upt_more == 1 ? "checked" : "";?> style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                       </th>
                                       <th class="w-100 th-so-thu-tu">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="th-ten-san-pham">Tên sản phẩm <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="w-120 th-so-luong">Số lượng <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="w-150 th-gia-goc">Giá gốc <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="w-150 th-don-gia">Đơn giá <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="w-200 th-danh-muc">Danh mục <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="w-100">Tình trạng</th>
                                       <th class="w-150 th-ngay-dang">Ngày đăng <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                       <th class="w-200">Thao tác</th>
                                    </tr>
                                 </thead>
                                 <?php
                                    $cnt = 0;
                                    $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1; 
                                    $limit = $_SESSION['paging'];
                                    $start_page = $limit * ($page - 1);
                                    $sql_get_total = "select count(*) as 'countt' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where";
                                    $total = fetch(sql_query($sql_get_total))['countt'];
                                    $sql_get_product = "select pi.id as 'pi_id',pi.is_active as 'pi_is_active', pi.name as 'pi_name',pi.product_type_id as 'pi_product_type_id',pi.price,pi.cost,pi.count,pi.img_name as 'pi_img_name',pi.created_at,pt.name as 'pt_name',pi.product_type_id as 'pt_id' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where limit $start_page,$limit";
                                    $rows = fetch_all(sql_query(($sql_get_product)));
                                 ?>
                                 <tbody dt-parent-id dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" id="list-san-pham" class="list-product">
                                 <?php
                                    foreach($rows as $row) {
                                 ?>
                                       <tr class="<?=$upt_more == 1 ? "selected" : "";?>" id="<?=$row["pi_id"];?>">
                                          <td>
                                             <input <?=$upt_more == 1 ? "checked" : "";?> style="width:16px;height:16px;cursor:pointer" value="<?=$row["pi_id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange()" type="checkbox" name="check_id<?=$row["pi_id"];?>">
                                          </td>
                                          <td class="so-thu-tu w-150"><?=$total - ($start_page + $cnt);?></td>
                                          <td draggable="true" class="ten-san-pham">
                                             <?= ($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' name='upt_name' value='" . $row['pi_name'] . "'><span class='text-danger'></span>" : $row['pi_name'];?>
                                          </td>
                                          <td class="so-luong">
                                             <?=($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)' name='upt_count' style='' value='" . number_format($row['count'],0,'','.') . "'><span class='text-danger'></span>" : number_format($row['count'],0,'','.');?>
                                          </td>
                                          <td class="gia-goc">
                                             <?=($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)' name='upt_cost' style='' value='" . number_format($row['cost'],0,'','.') . "'><span class='text-danger'></span>" : number_format($row['cost'],0,'','.') . "đ";?>
                                          </td>
                                          <td class="don-gia">
                                             <?=($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)' name='upt_price' style='' value='" . number_format($row['price'],0,'','.') . "'><span class='text-danger'></span>" : number_format($row['price'],0,'','.') . "đ";?>
                                          </td>
                                          <td class="danh-muc"><?=$row['pt_name']?></td>
                                          <td>
                                             <div class="custom-control custom-switch">
                                                <input type="checkbox" onchange="toggleStatus('<?=$row['pi_id']?>','<?= $row['pi_is_active'] == 1 ? 'Deactive' : 'Active';?>','<?=$row['pi_product_type_id']?>')" class="custom-control-input" id="customSwitches<?=$row['pi_id'];?>" <?= $row['pi_is_active'] == 1 ? "checked" : "";?>>
                                                <label class="custom-control-label" for="customSwitches<?=$row['pi_id'];?>"></label>
                                             </div>  
                                          </td>
                                          <td class="ngay-dang"><?=$row['created_at'] ? Date("d-m-Y",strtotime($row['created_at'])) : "";?></td>
                                          <td>
                                             <?php
                                                if($upt_more != 1) {
                                             ?>
                                             <?php
                                                if($allow_read){
                                             ?>
                                             <button onclick="readModal()" class="btn-xem-san-pham dt-button button-grey"
                                             data-id="<?=$row["pi_id"];?>" >
                                             Xem
                                             </button>
                                             <?php } ?>
                                             <?php
                                                if($allow_update) {
                                             ?>
                                             <button onclick="openModalUpdate()" class="btn-sua-san-pham dt-button button-green"
                                             data-id="<?=$row["pi_id"];?>" >
                                             Sửa
                                             </button>
                                             <?php } ?>
                                             <?php
                                                if($allow_delete) {
                                             ?>
                                             <button onclick="processDelete()" class="btn-xoa-san-pham dt-button button-red" data-id="<?=$row["pi_id"];?>">
                                             Xoá
                                             </button>
                                             <?php } ?>
                                             <?php
                                                } else {
                                             ?>
                                                <button dt-count="0" onclick="uptMore2()" class="btn-upt-more-1 dt-button button-green" data-id="<?=$row["pi_id"];?>">
                                             Sửa
                                             </button>
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
                                 <?php
                                    $count_row_table = count($rows);
                                    if($count_row_table == 0) {
                                 ?>
                                 <tr>
                                    <td style="text-align:center;font-size:17px;" colspan="10">Không có dữ liệu</td>
                                 </tr>
                                 <?php } ?>
                                 <tfoot>
                                    <tr>
                                       <th style="width:20px !important;">
                                       <input <?=$upt_more == 1 ? "checked" : "";?> style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                       </th>
                                       <th>Số thứ tự</th>
                                       <th>Tên sản phẩm</th>
                                       <th>Số lượng</th>
                                       <th>Giá gốc</th>
                                       <th>Đơn giá</th>
                                       <th>Danh mục</th>
                                       <th>Tình trạng</th>
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
        <h4>Thông tin sản phẩm</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id='form-product'>
         </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2">
  <div class="modal-dialog modal-xl" style="min-width:1650px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thêm dữ liệu sản phẩm nhanh</h4>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="form-insert" class="modal-body">
            <div class="row j-between a-center">
               <div style="margin-left: 7px;" class="form-group">
                     <label for="">Nhập số dòng: </label>
                     <div class="" style="justify-content:flex-end;display:inline-flex">
                     <div class="k-number-row">
                        <input type="number" style="width:100px" name="count3" class="kh-inp-ctrl">
                     </div>
                     <div class="k-plus">
                        <button data-plus="0" onclick="insRow()" style="font-size:15px;" class="dt-button button-blue k-btn-plus">+</button>
                     </div>
                     <div class="k-minus">
                        <button onclick="delRow()" style="font-size:15px;" class="dt-button button-blue k-btn-minus">-</button>
                     </div>
                  </div>  
               </div>
               <div class="form-group">
                  <button onclick="insAll()" class="dt-button button-blue">Lưu dữ liệu</button> 
               </div>
               <!-- <div class="d-flex f-column form-group">
                     <div style="cursor:pointer;" class="d-flex list-file-read mt-10 mb-10">
                     <div class="file file-csv mr-10">
                        <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Tên sp','Số lượng','Đơn giá','Mô tả sp'],['name_p2','count_p2','price_p2','desc_p2'])">
                     </div>
                     <div class="file file-excel mr-10">
                        <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Tên sp','Số lượng','Đơn giá','Mô tả sp'],['name_p2','count_p2','price_p2','desc_p2'])">
                     </div>
                     <div class="d-empty">
                        <button onclick="delEmpty()" style="font-size:30px;font-weight:bold;width:64px;height:64px;" class="dt-button button-red k-btn-plus">x</button>
                     </div>
                     </div>
               </div> -->
            </div>
            <!--table-->
            <table class='table table-bordered' style="height:auto;">
               <thead>
               <tr>
                  <th class="w-100">Số thứ tự</th>
                  <th class="w-300">Tên sản phẩm</th>
                  <th class="w-300">Danh mục</th>
                  <th>Số lượng</th>
                  <th>Giá gốc</th>
                  <th>Đơn giá</th>
                  <th class="w-300">Mô tả sản phẩm</th>
                  <th>Ảnh đại diện</th>
                  <th>Thao tác</th>
               </tr>
               </thead>
            </table>
         </div>
      </div>
    </div>
  </div>
</div>
<!--html & css section end-->
<?php
   include_once("include/bottom.meta.php");
?>

<script src="js/summernote.min.js"></script>
<script src="js/summernote-vi-VN.js"></script>
<?php
    include_once("include/dt_script.php");
?>
<script src="js/khoi_all.js"></script>
<script>
   $('.select-type2').select2();
</script>
<script>
    
</script>
<!--searching filter-->
<script>
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
   function selectOptionRemove(){
      $(event.currentTarget).siblings('select').find('option').prop("selected",false);
      $(event.currentTarget).siblings('select').find("option[value='']").prop("selected",true);
      $(event.currentTarget).siblings('.ele-select').remove()
      $(event.currentTarget).siblings("div").find("input").val("");
      $(event.currentTarget).closest('div').css({"display":"none"});
   }
   function selectOptionInsert(){
      let file_html = "";
      if($(event.currentTarget).closest('#s-type2').length) {
         file_html = `
         <div class="ele-select ele-type2 mt-10">
            <select class="select-type2" style="width:100%" class="form-control" name="pt_type[]">
               <option value="">Chọn danh mục cần tìm</option>
               <?php
                  $sql = "select * from product_type where is_delete = 0 and id in (select distinct product_type_id from product_info where is_delete = 0)";
                  $rows2 = fetch_all(sql_query($sql));
                  foreach($rows2 as $row2) {
               ?>
                  <option value="<?=$row2['id']?>" <?=$pt_type == $row2['id'] ? "selected" : ""; ?>><?=$row2['name'];?></option>
               <?php
                  }
               ?>
            </select>
            <span onclick="select_remove_child('.ele-type2')" class="kh-select-child-remove"></span>
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
      $('.select-type2').select2();
   }
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
</script>
<!-- multi file upload-->
<script>
   var arr_list_file_del = [];
	var arr_input_file = new Map();
   let arr_file = [];
   let obj_arr_file = {};
   function init_map_file(){
      arr_list_file_del = [];
      arr_input_file = new Map();
      arr_file = [];
      obj_arr_file = {};
      if($('input[name="list_file_del"]').val() != "") {
         arr_list_file_del = $('input[name="list_file_del"]').val().split(",");
      }
      console.log(arr_list_file_del);
      if(arr_list_file_del != ['']) {
         arr_list_file_del.forEach((element) => {
            arr_input_file.set(parseInt(element),element + "_has");
         });
      }
   }
	function readURLChange(input,key) {
      key = parseInt(key);
      let target = event.currentTarget;
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         if(arr_input_file.has(key)) {
            if(arr_input_file.get(key).indexOf("_has") == -1) {
               if(arr_input_file.get(key).indexOf("_del") > 0) {
                  arr_input_file.set(key,key + "_upt");
               }
            } else {
               arr_input_file.set(key,key + "_upt");
            }
         } else {
            arr_input_file.set(key,key + "_ins");
         }
         obj_arr_file[key] = input.files[0];
         console.log(arr_input_file);
         reader.onload = function (e) {
            $(target).parent().css({
               'background-image' : 'url("' + e.target.result + '")',
               'background-size': 'cover',
               'background-position': '50%'
            });
         }
         reader.readAsDataURL(input.files[0]);
      }
	}
	// function removeImageChange(input,key){
	// 	$(input).parent().css({'display':'none'});
	// 	$(input).closest('.kh-custom-file').css({'background-image':'url()'});
	// 	arr_input_file.set(key,key + "_upt");
	// }
	function removeImageDel(input,key) {
		$(input).parent().css({'display':'none'});
		$(input).closest('.kh-custom-file').remove();
		$(input).closest('.kh-custom-file').css({'background-image':'url()'});
      key = parseInt(key);
		if(arr_input_file.has(key)) {
			if(arr_input_file.get(key).indexOf("_has") == -1) {
				if(arr_input_file.get(key).indexOf("_upt") > 0){
					arr_input_file.set(key,key + "_del");
				} else {
					arr_input_file.delete(key);
				}
			} else {
				arr_input_file.set(key,key + "_del");
			}
         delete obj_arr_file[key];

         console.log(arr_input_file);
		}
	}
	function gameChange(){
		$('input[name="list_file_del"]').val(Array.from(arr_input_file.values()).sort(
         (a,b) => {
            return parseInt(a.split("_")[0]) - parseInt(b.split("_")[0]);
         }
      ).join(","));
      //console.log($('input[name="list_file_del"]').val());
	}
   function showDragText(){
      // alert("Con me no");
      // event.preventDefault();
      $('.k-border').show();
   }
   function hideDragText(){
      $('.k-border').hide();
   }
	// function readURL(input,key) {
   //    let target = event.currentTarget;
   //    if (input.files && input.files[0]) {
   //       var reader = new FileReader();
   //       arr_input_file.set(key,key);
   //       console.log(arr_input_file);
   //       reader.onload = function (e) {
   //          $(target).parent().css({
   //             'background-image' : 'url("' + e.target.result + '")',
   //             'background-size': 'cover',
   //             'background-position': '50%'
   //          });
   //       }
   //       console.log(input.files[0]);
   //       reader.readAsDataURL(input.files[0]);
   //    }
	// }
   // function removeImage(input,key){
   //    $(input).parent().css({'display':'none'});
   //    $(input).closest('.kh-custom-file').remove();
   //    arr_input_file.delete(key);
   // }
   // function game() {
   //    $('input[name="list_file_del"]').val(Array.from(arr_input_file.keys()).join(","));
   // }
   // function addFileInput(){
   //    let game_start = $(".kh-custom-file").last().attr('data-id');
   //    let count = $(".kh-file-list:last-child .kh-custom-file").length;
   //    let count2 = $(".kh-custom-file").length;
   //    console.log(count2);
   //    // khi chua them hinh tao se dem so luong hinh truoc do neu 11 hinh ma chay vao day bao loi lien
   //    if(count2 > 11) {
   //       $.alert({
   //          'title':'Thông báo',
   //          'content':'Bạn chỉ được phép thêm tối đa 12 hình',
   //       });
   //       return;
   //    }
   //    game_start = parseInt(game_start) + 1;
   //    if(isNaN(game_start)) {
   //       game_start = 1;
   //    }
   //    let file_html = `
   //    <div data-id=${game_start} class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
   //       <input class="nl-form-control" name="img[]" type="file" onchange="readURL(this,'${game_start}')">
   //       <div class="kh-custom-remove-img" style="display:block;">
   //          <span class="kh-custom-btn-remove" onclick="removeImage(this,'${game_start}')"></span>
   //       </div>
   //    </div>`;
   //    if(count % 6 == 0){
   //       file_html = `<div class="kh-file-list">${file_html}</div>`;
   //       $(file_html).appendTo('.kh-file-lists');
   //    } else {
   //       $(file_html).appendTo('.kh-file-list:last-child');
   //    }
      
   // }
   function addFileInputChange(){
      let game_start = $(".kh-custom-file").last().attr('data-id');
      let count = $(".kh-file-list:last-child > .kh-custom-file").length;
      console.log(count);
      game_start = parseInt(game_start) + 1;
      if(isNaN(game_start)) {
         game_start = 1;
      }
      let count2 = $(".kh-custom-file").length;
      // khi chua them hinh tao se dem so luong hinh truoc do neu 11 hinh ma chay vao day bao loi lien
      if(count2 > 11) {
         $.alert({
            'title':'Thông báo',
            'content':'Bạn chỉ được phép thêm tối đa 12 hình',
         });
         return;
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
         $(file_html).appendTo('.kh-file-list:last-child');
      }
   }
   function allowDrop(){
      event.preventDefault();
      $('.k-border').show();
   }
   function drop(){
      let count = $(".kh-custom-file").last().attr('data-id');
      event.preventDefault();
      if (event.dataTransfer.items) {
         for (let i = 0; i < event.dataTransfer.items.length; i++) {
            if (event.dataTransfer.items[i].kind === 'file') {
               let file = event.dataTransfer.items[i].getAsFile();
               //console.log(file);
               addFileInputChange();
               var reader = new FileReader();
               let key = parseInt(i) + parseInt(count) + 1;
               //console.log(key);
               if(arr_input_file.has(key)) {
                  if(arr_input_file.get(key).indexOf("_has") == -1) {
                     if(arr_input_file.get(key).indexOf("_del") > 0) {
                        arr_input_file.set(key,key + "_upt");
                     }
                  } else {
                     arr_input_file.set(key,key + "_upt");
                  }
               } else {
                  arr_input_file.set(key,key + "_ins");
               }
               
               reader.onload = function (e) {
                  $(`.kh-custom-file[data-id=${key}]`).css({
                     'background-image' : 'url("' + e.target.result + '")',
                     'background-size': 'cover',
                     'background-position': '50%'
                  });
               }
               obj_arr_file[key] = file;
               reader.readAsDataURL(file);
            }
         }
         console.log(arr_input_file);
      }
      $('.k-border').hide();
   }
</script>
<script>
   function readURLok(input){
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
            $('#display-image').attr('src', e.target.result);
         }
         reader.readAsDataURL(input.files[0]);
      }
   }
   function validate(){
      let test = true;
      $('p.text-danger').text('');
      let name = $('input[name=ten_san_pham]').val();
      let product_type_id = $('input[name="product_type_id"]').val();
      let count = $('input[name=so_luong]').val();
      let price = $('input[name=don_gia]').val();
      let cost = $('input[name=gia_goc]').val();
      let img = $('input[name="img_sanpham_file"]')[0].files;
      let description = $('#summernote').summernote('code');
      if(name == "") {
         $('#name_err').text('Tên sản phẩm không được để trống');
         test = false;
      } else if(name.length > 200) {
         $('#name_err').text('Tên sản phẩm không được dài quá 200 ký tự');
         test = false;
      }
      count = count.replace(/\./g,'');
      if(count == "") {
         $('#count_err').text("Số lượng không được để trống");
         test = false;
      } else if(count >= 1000000000) {
         $('#count_err').text("Số lượng phải nhỏ hơn 1.000.000.000đ");
         test = false;
      }

      cost = cost.replace(/\./g,'');
      if(cost == "") {
         $('#cost_err').text("Giá gốc không được để trống");
         test = false;
      } else if(cost <= 10000) {
         $('#cost_err').text("Giá gốc phải lớn hơn 10.000đ");
         test = false;
      } else if(cost >= 1000000000){
         $('#cost_err').text("Giá gốc phải nhỏ hơn 1.000.000.000đ");
         test = false;
      } else if(cost % 1000 != 0 && cost % 1000 != 500) {
         $('#cost_err').text("Giá gốc không hợp lệ");
         test = false;
      } 

      price = price.replace(/\./g,'');
      if(price == "") {
         $('#price_err').text('Đơn giá không được để trống');
         test = false;
      } else if(price <= 100000){
         $('#price_err').text("Đơn giá phải lớn hơn 100.000đ");
         test = false;
      } else if(price >= 1000000000){
         $('#price_err').text("Giá bán phải nhỏ hơn 1.000.000.000đ");
         test = false;
      } else if(price % 1000 != 0 && price % 1000 != 500){
         $('#price_err').text("Đơn giá không hợp lệ");
         test = false;
      } else if(price - cost <= 50000) {
         $('#price_err').text('Giá bán phải lớn hơn giá gốc 50.000đ trở lên');
      }

      if(product_type_id == "") {
         $('#product_type_id_err').text('Danh mục sản phẩm không được để trống');
         test = false;
      } 
       
      if(img.length == 0) {
         if($('#where-replace > img').length == 0) {
            $('#image_err').text('Ảnh đại diện không được để trống');
            test = false;
         }
      }
      if(description == "<p><br></p>") {
         $('#desc_err').text("Mô tả sản phẩm không được để trống");
         test = false;
      } else if(description.length > 30011) {
         $('#desc_err').text("Mô tả sản phẩm không được dài quá 30000 ký tự");
         test = false;
      }
      return test;
   }
   function openModalInsert(){
      $('#form-product').load("ajax_product_info.php?status=Insert",() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
         $('#btn-luu-san-pham').text("Thêm");
         $(function(){
            setTimeout(() => {
               $('#summernote').summernote({height: 120,lang: 'vi-VN',disableDragAndDrop:true});
            },100);
            $(".parent[data-id]").click(function(e){
               let child = $(e.currentTarget).find('li').length;
               if(!child){
                  let id = $(e.currentTarget).attr('data-id');
                  let name = $(e.currentTarget).text();
                  name = name.substr(0,name.length - 1);
                  console.log(name);
                  $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                     $("input[name='product_type_id']").val(id);
                     $("input[name='category_name']").val(name);
                     $("#breadcrumb-menu").empty();
                     $("#breadcrumb-menu").append(data);
                  });
               }
            })
            init_map_file();
         });
         $("#fileInput").on("change",function(){
            $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
            readURLok(this); 
         });
         $('#file_input_anh_mo_ta').on('change', function() {
            readURLok(this,'#image_preview');
         });
      });
   }  
   function openModalUpdate(){
      let id = $(event.currentTarget).attr('data-id');
      $(event.currentTarget).closest("tr").addClass("bg-color-selected");
      $('#form-product').load("ajax_product_info.php?status=Update&id=" + id,() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
         $('#btn-luu-san-pham').text("Sửa");
         $(function(){
            setTimeout(() => {
               $('#summernote').summernote({height: 120,lang: 'vi-VN',disableDragAndDrop:true,});
            },100);
            $(".parent[data-id]").click(function(e){
               let child = $(e.currentTarget).find('li').length;
               if(!child){
                  let id = $(e.currentTarget).attr('data-id');
                  let name = $(e.currentTarget).text();
                  name = name.substr(0,name.length - 1);
                  console.log(name);
                  $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                     $("input[name='product_type_id']").val(id);
                     $("input[name='category_name']").val(name);
                     $("#breadcrumb-menu").empty();
                     $("#breadcrumb-menu").append(data);
                  });
               }
            })
            init_map_file();
         });
         $("#fileInput").on("change",function(){
            $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
            readURLok(this); 
         });
         $('#file_input_anh_mo_ta').on('change', function() {
            readURLok(this,'#image_preview');
         });
      });
   }
   function readModal(){
      let id = $(event.currentTarget).attr('data-id');
      $(event.currentTarget).closest("tr").addClass("bg-color-selected");
      $('#form-product').load("ajax_product_info.php?id=" + id + "&status=Read",() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
      });
   }
   function processModalInsertUpdate(){
      event.preventDefault();
      let frmSanPham = new FormData();
      frmSanPham.append('id',$('input[name=id]').val());
      frmSanPham.append('name',$('input[name=ten_san_pham]').val());
      frmSanPham.append('description',$('#summernote').summernote('code'));
      frmSanPham.append('count',$('input[name=so_luong]').val());
      frmSanPham.append('price',$('input[name=don_gia]').val());
      frmSanPham.append('cost',$('input[name=gia_goc]').val());
      frmSanPham.append('product_type_id',$("input[name='product_type_id']").val());
      frmSanPham.append('category_name',$("input[name='category_name']").val());
      frmSanPham.append('status',$('#btn-luu-san-pham').attr('data-status').trim());
      if($('input[name="img_sanpham_file"]')[0].files.length > 0) {
         if($('input[name="img_sanpham_file"]')[0].files[0].size > 0) {
            frmSanPham.append('img_sanpham_file',$('input[name="img_sanpham_file"]')[0].files[0]);
         }
      }
      for(const [key,value] of Object.entries(obj_arr_file)) {
         frmSanPham.append('img[]',value);
      }
      gameChange();
      //return;
      frmSanPham.append('list_file_del',$('input[name="list_file_del"]').val());
      if(validate()) {
         $.ajax({
            url:window.location.href,
            type:"POST",
            cache:false,
            dataType:"json",
            contentType: false,
            processData: false,
            data:frmSanPham,
            success:function(res_json){
               frmSanPham = new FormData();
               if(res_json.msg == 'ok'){
                  let status = $('#btn-luu-san-pham').attr('data-status').trim();
                  if(status == "Insert"){
                     setTimeout(() => {
                        $("#san-pham" + res_json.id).css('background-color','#d4efecc2');
                     },1000);
                     msg = "Thêm dữ liệu thành công.";
                     $.alert({
                        title: "Thông báo",
                        content: msg,
                        buttons:{
                           "Ok":function(){
                              loadDataComplete();
                           }
                        }
                     });
                  } else if(status == "Update") {
                     msg = "Sửa dữ liệu thành công.";
                     $.alert({
                        title: "Thông báo",
                        content: msg,
                        buttons: {
                           "Ok":function(){
                              loadDataComplete();
                           }
                        }
                     });
                     
                  }
                  $('#modal-xl').modal('hide');
               } else if(res_json.msg == 'not_ok') {
                  $.alert({
                     title: "Thông báo",
                     content: res_json.error
                  });
               }
            },
            error: function (data) {
               console.log('Error:'+ data);
            }
         });
      }
   }
   function processDelete(){
      let id = $(event.currentTarget).attr('data-id');
      let target = $(event.currentTarget);
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
                           content: res_json.success,
                           buttons:{
                              "Ok":function(){
                                 loadDataComplete();
                              }
                           }
                        });
                        
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
               target.closest("tr").removeClass("bg-color-selected");
            },
         }
      });
   }
</script>
<script>
   setSortTable();
   function insMore2(){
      let target2 = $(event.currentTarget).closest('tr');
      target2.find('p.text-danger').text("");
      let product_type_id = target2.find('td input[name="product_type_id"]').val();
      let ins_name = target2.find('td input[name="ins_name"]').val();
      let ins_count = target2.find('td input[name="ins_count"]').val();
      let ins_cost = target2.find('td input[name="ins_cost"]').val();
      let ins_price = target2.find('td input[name="ins_price"]').val();
      let ins_desc = target2.find('td textarea[name="ins_desc"]').val();
      console.log(ins_desc);
      let ins_img = target2.find('td input[name="ins_img"]')[0].files;
      let test = true;
      if(ins_name == ""){
         target2.find('td input[name="ins_name"] ~ p.text-danger').text("Không được để trống");
         test = false;
      } else if(ins_name.length > 200) {
         target2.find('td input[name="ins_name"] ~ p.text-danger').text("Không được quá 200 ký tự");
         test = false;
      }
      //
      if(product_type_id == ""){
         target2.find('td input[name="product_type_id"]').closest('td').find('p.text-danger').text("Không được để trống");
         test = false;
      }
      //
      if(ins_count == ""){
         target2.find('td input[name="ins_count"] ~ p.text-danger').text("Không được để trống");
         test = false;
      } 
      //
      ins_cost = ins_cost.replace(/\./g,"");
      if(ins_cost == "") {
         target2.find('td input[name="ins_cost"] ~ p.text-danger').text("Không được để trống");
         test = false;
      } else if(ins_cost < 10000){
         target2.find('td input[name="ins_cost"] ~ p.text-danger').text("Không được nhỏ hơn 10.000đ");
         test = false;
      } else if(ins_cost % 1000 != 0 && ins_cost % 1000 != 500){
         target2.find('td input[name="ins_cost"] ~ p.text-danger').text("Định dạng giá gốc không hợp lệ");
         test = false;
      }
      ins_price = ins_price.replace(/\./g,"");
      if(ins_price == "") {
         target2.find('td input[name="ins_price"] ~ p.text-danger').text("Không được để trống");
         test = false;
      } else if(ins_price < 100000){
         target2.find('td input[name="ins_price"] ~ p.text-danger').text("Không được nhỏ hơn 100.000đ");
         test = false;
      } else if(ins_price % 1000 != 0 && ins_price % 1000 != 500){
         target2.find('td input[name="ins_price"] ~ p.text-danger').text("Định dạng giá bán không hợp lệ");
         test = false;
      } else if(ins_price - ins_cost < 50000) {
         target2.find('td input[name="ins_cost"] ~ p.text-danger').text("Giá gốc phải nhỏ hơn giá bán ít nhất 50.000đ trở lên");
         test = false;
      }

      if(ins_desc == ""){
         target2.find('td textarea[name="ins_desc"] ~ p.text-danger').text("Không được để trống");
         test = false;
      } else if(ins_desc.length > 1800) {
         target2.find('td textarea[name="ins_desc"] ~ p.text-danger').text("Không được quá 1800 ký tự");
         test = false;
      }
      //
      if(ins_img.length == 0){
         target2.find('td input[name="ins_img"]').parent().siblings('p.text-danger').text("Không được để trống hình");
         test = false;
      }
      if(test) {
         let this2 = $(event.currentTarget);
         let formData = new FormData();
         formData.append('status','ins_more');
         formData.append('ins_name',ins_name);
         formData.append('product_type_id',product_type_id);
         formData.append('ins_count',ins_count);
         formData.append('ins_cost',ins_cost);
         formData.append('ins_price',ins_price);
         formData.append('ins_desc',ins_desc);
         formData.append('ins_img',ins_img[0]);
         $.ajax({
            url: window.location.href,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
               console.log(data);
               data = JSON.parse(data);
               if (data.msg == "ok") {
                     $.alert({
                        title: "Thông báo",
                        content: "Bạn đã thêm dữ liệu thành công",
                        buttons: {
                           "Ok": function() {
                                 this2.closest('tr').find('input').val("");
                                 this2.closest('tr').find('textarea').val("");
                                 this2.closest('tr').find('select > option[value=""]').prop("selected", true);
                           }
                        }
                     })
               }
            },
            error: function(data) {
               console.log("Error: " + data);
            }
         })
      }
   }
   function uptMore2(){
      $('span.text-danger').text("");
      let target2 = $(event.currentTarget).closest('tr');
      target2.find('p.text-danger').text("");
      let upt_name = target2.find('td input[name="upt_name"]').val();
      let upt_count = target2.find('td input[name="upt_count"]').val();
      let upt_cost = target2.find('td input[name="upt_cost"]').val();
      let upt_price = target2.find('td input[name="upt_price"]').val();
      let upt_id = $(event.currentTarget).attr('data-id');
      let test = true;
      if(upt_name == ""){
         target2.find('td input[name="upt_name"] ~ span.text-danger').text("Không được để trống");
         test = false;
      } else if(upt_name.length > 200) {
         target2.find('td input[name="upt_name"] ~ span.text-danger').text("Không được quá 200 ký tự");
         test = false;
      }
      //
      if(upt_count == ""){
         target2.find('td input[name="upt_count"] ~ span.text-danger').text("Không được để trống");
         test = false;
      } 
      //
      upt_cost = upt_cost.replace(/\./g,"");
      if(upt_cost == "") {
         target2.find('td input[name="upt_cost"] ~ span.text-danger').text("Không được để trống");
         test = false;
      } else if(upt_cost < 10000){
         target2.find('td input[name="upt_cost"] ~ span.text-danger').text("Không được nhỏ hơn 10.000đ");
         test = false;
      } else if(upt_cost % 1000 != 0 && upt_cost % 1000 != 500){
         target2.find('td input[name="upt_cost"] ~ span.text-danger').text("Định dạng giá gốc không hợp lệ");
         test = false;
      }
      upt_price = upt_price.replace(/\./g,"");
      if(upt_price == "") {
         target2.find('td input[name="upt_price"] ~ span.text-danger').text("Không được để trống");
         test = false;
      } else if(upt_price < 100000){
         target2.find('td input[name="upt_price"] ~ span.text-danger').text("Không được nhỏ hơn 100.000đ");
         test = false;
      } else if(upt_price % 1000 != 0 && upt_price % 1000 != 500){
         target2.find('td input[name="upt_price"] ~ span.text-danger').text("Định dạng giá bán không hợp lệ");
         test = false;
      }

      if(upt_price - upt_cost < 50000) {
         target2.find('td input[name="upt_cost"] ~ span.text-danger').text("Giá gốc phải nhỏ hơn giá bán ít nhất 50.000đ trở lên");
         test = false;
      }
      
      if(test) {
         let this2 = $(event.currentTarget);
         let formData = new FormData();
         formData.append('status','upt_more');
         formData.append('upt_id',upt_id);
         formData.append('upt_name',upt_name);
         formData.append('upt_count',upt_count);
         formData.append('upt_cost',upt_cost);
         formData.append('upt_price',upt_price);
         $.ajax({
            url: window.location.href,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
               console.log(data);
               data = JSON.parse(data);
               if (data.msg == "ok") {
                  $.alert({
                     title: "Thông báo",
                     content: "Bạn đã sửa dữ liệu thành công",
                  })
                  //loadDataComplete();
               }
            },
            error: function(data) {
               console.log("Error: " + data);
            }
         })
      }
   }
   function insAll(){
      let test = true;
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      let count = $('td input[name="ins_name"]').length;
      let cost_price_length = $('td input[name="ins_cost"]').length;
      for(let i = 0 ; i < cost_price_length ; i++) {
         $('td input[name="ins_cost"]').eq(i).siblings("p").text("");
         $('td input[name="ins_price"]').eq(i).siblings("p").text("");
         let a = $('td input[name="ins_cost"]').eq(i).val().replace(/\./g,"");
         let b = $('td input[name="ins_price"]').eq(i).val().replace(/\./g,"");
         if(a == "") {
            $('td input[name="ins_cost"]').eq(i).siblings("p").text('Giá gốc không được để trống');
            test = false;
         } else if(a < 10000) {
            $('td input[name="ins_cost"]').eq(i).siblings("p").text('Giá gốc phải lớn hơn hoặc bằng 10.000đ');
            test = false;
         } else if(a % 1000 != 0 && a % 1000 != 500){
            $('td input[name="ins_cost"]').eq(i).siblings("p").text('Giá gốc không hợp lệ');
            test = false;
         }
         //
         if(b == "") {
            $('td input[name="ins_price"]').eq(i).siblings("p").text('Giá bán không được để trống');
            test = false;
         }  else if(b < 100000) {
            $('td input[name="ins_price"]').eq(i).siblings("p").text('Giá bán phải phải lớn hơn hoặc bằng 100.000đ');
            test = false;
         } else if(b % 1000 != 0 && b % 1000 != 500){
            $('td input[name="ins_price"]').eq(i).siblings("p").text('Giá bán không hợp lệ');
            test = false;
         }
         //
         if(test) {
            if(b - a < 50000) {
               $('td input[name="ins_cost"]').eq(i).siblings("p").text('Giá gốc phải nhỏ hơn hoặc bằng giá bán 50.000đ');
               test = false;
            } else {
               formData.append("ins_cost[]",a);
               formData.append("ins_price[]",b);
            }
         }
      } 
      $('td input[name="ins_name"]').each(function(){
         if($(this).val() == "") {
            $(this).siblings("p").text("Không được để trống");
            test = false;    
         } else if($(this).val().length > 200){
            $(this).siblings("p").text("Tên sản phẩm không được vượt quá 200 ký tự");
            test = false;
         } else {
            formData.append("ins_name[]",$(this).val());
            $(this).siblings("p").text("");
         }
      });
      $('td textarea[name="ins_desc"]').each(function(){
         if($(this).val() == "") {
            $(this).siblings("p").text("Không được để trống");
            test = false;    
         } else if($(this).val().length > 10000){
            $(this).siblings("p").text("Nội dung không được vượt quá 10.000 ký tự");
            test = false;
         } else {
            formData.append("ins_desc[]",$(this).val());
            $(this).siblings("p").text("");
         }
      });
      $('td input[name="ins_count"]').each(function(){
         if($(this).val() != "") {
            formData.append("ins_count[]",$(this).val());
            $(this).siblings("p").text("");
         } else {
            $(this).siblings("p").text("Không được để trống");
            test = false;
         }
      });
      $('td input[name="product_type_id"]').each(function(){
         if($(this).val() != "") {
            formData.append("product_type_id[]",$(this).val());
            $(this).closest('td').find("p").text("");
         } else {
            $(this).closest('td').find("p").text("Phải chọn danh mục");
            test = false;
         }
      });
      $('td input[name="ins_img"]').each(function(){
         if($(this).val() != "") {
            formData.append("ins_img[]",$(this)[0].files[0]);
            $(this).closest('td').find("p").text("");
         } else {
            $(this).closest('td').find("p").text("Không để trống ảnh");
            test = false;
         }
      });
      formData.append("status","ins_all");
      formData.append("len",len);
      if(count == 0) {
        $.alert({
            title:"Thông báo",
            content:"Vui lòng tạo input"
        })
        test = false;
      }
      if(test) {
         $.confirm({
            title: "Thông báo",
            content: `Bạn có chắc chắn muốn thêm ${count} dòng này ?`,
            buttons: {
               "Có": function(){
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
                                    loadDataComplete();
                                 }
                              }
                           });
                        }
                     },
                     error: function(data){
                        console.log("Error: " + data);
                     }
                  })
               },"Không":function(){

               }
            }
         })
      }
   }
   function uptAll(){
      let test = true;
      let formData = new FormData();
      let all_checkbox = getIdCheckbox()['result'].split(",");
      if(all_checkbox.length == 0) {
         $.alert({
            title:"Thông báo",
            content:"Vui lòng chọn dòng cần lưu",
         });
         return;
      }
      for(i = 0 ; i < all_checkbox.length ; i++) {
         formData.append("upt_id[]",all_checkbox[i]);
      }
      $('tr.selected input[name="upt_name"]').each(function(){
         if($(this).val() == "") {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;    
         } else if($(this).val().length > 200) {
            $(this).siblings("span.text-danger").text("Tên sản phẩm không được vượt quá 200 ký tự");
            test = false;    
         } else {
            formData.append("upt_name[]",$(this).val());
            $(this).siblings("span.text-danger").text("");
         }
         
      });
      $('tr.selected input[name="upt_count"]').each(function(){
         if($(this).val() == "") {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;    
         } else {
            let cnt = $(this).val().replace(/\./g,'');
            if(cnt > 1000000000) {
               $(this).siblings("span.text-danger").text("Số lượng sản phẩm phải nhỏ hơn 1.000.000.000");
               test = false;    
            } else {
               formData.append("upt_count[]",$(this).val());
               $(this).siblings("span.text-danger").text("");
            }
         }
      });
      let cost_price_length = $('tr.selected input[name="upt_cost"]').length;
      for(let i = 0 ; i < cost_price_length ; i++) {
         $('tr.selected input[name="upt_cost"]').eq(i).siblings("span").text("");
         $('tr.selected input[name="upt_price"]').eq(i).siblings("span").text("");
         let a = $('tr.selected input[name="upt_cost"]').eq(i).val().replace(/\./g,"");
         let b = $('tr.selected input[name="upt_price"]').eq(i).val().replace(/\./g,"");
         if(a == "") {
            $('tr.selected input[name="upt_cost"]').eq(i).siblings("span").text('Giá gốc không được để trống');
            test = false;
         } else if(a < 10000) {
            $('tr.selected input[name="upt_cost"]').eq(i).siblings("span").text('Giá gốc phải lớn hơn hoặc bằng 10.000đ');
            test = false;
         } else if(a % 1000 != 0 && a % 1000 != 500){
            $('tr.selected input[name="upt_cost"]').eq(i).siblings("span").text('Giá gốc không hợp lệ');
            test = false;
         }
         //
         if(b == "") {
            $('tr.selected input[name="upt_price"]').eq(i).siblings("span").text('Giá bán không được để trống');
            test = false;
         }  else if(b < 100000) {
            $('tr.selected input[name="upt_price"]').eq(i).siblings("span").text('Giá bán phải phải lớn hơn hoặc bằng 100.000đ');
            test = false;
         } else if(b % 1000 != 0 && b % 1000 != 500){
            $('tr.selected input[name="upt_price"]').eq(i).siblings("span").text('Giá bán không hợp lệ');
            test = false;
         }
         //
         if(test) {
            if(b - a < 50000) {
               $('tr.selected input[name="upt_cost"]').eq(i).siblings("span").text('Giá gốc phải nhỏ hơn hoặc bằng giá bán 50.000đ');
               test = false;
            } else {
               formData.append("upt_cost[]",a);
               formData.append("upt_price[]",b);
            }
         }
      }
      //return;
      formData.append("status","upt_all");
      formData.append("len",all_checkbox.length);
      if(test) {
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
                           loadDataComplete();
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
     
   }
   function load_menu(){
      let html =`<?php echo show_menu_3();?>`;
      $('.aaab').empty();
      $(html).appendTo('.aaab');
      $(event.currentTarget).removeAttr('onmouseover');
   }
   function show_menu_root(){
      let child = $(event.currentTarget).find('li').length;
      if(!child){
         let id = $(event.currentTarget).attr('data-id');
         let name = $(event.currentTarget).text();
         name = name.substr(0,name.length - 1);
         let target = $(event.currentTarget)
         console.log(name);
         $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
            target.closest('.ul_menu').find("input[name='product_type_id']").val(id);
            target.closest('.ul_menu').next().empty();
            target.closest('.ul_menu').next().append(data);
         });
      }
   }
</script>
<!--processing crud-->
<script>
   $(document).ready(function(){
      $("#modal-xl").on("hidden.bs.modal",function(){
         arr_list_file_del = [];
         arr_input_file = new Map();
         $("input[name='list_file_del']").val("");
         $('tr').removeClass('bg-color-selected');
      })
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
   include_once("include/pagination.php");
   include_once("include/footer.php");
?>
<?php
   } else if (is_post_method()) {
      //print_r("aaa");
      function getFileUpload($img_order,$id){
         $sql = "select img_id from product_image where product_info_id = '$id' and img_order = '$img_order' limit 1";
         $file_old_name = fetch(sql_query($sql))['img_id'];
         return $file_old_name;
      }
      $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
      $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
      $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
      $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
      $count = isset($_REQUEST["count"]) ? str_replace(".","",$_REQUEST["count"]) : null;
      $description = isset($_REQUEST["description"]) ? $_REQUEST["description"] : null;
      $product_type_id = isset($_REQUEST["product_type_id"]) ? $_REQUEST["product_type_id"] : null;
      $category_name = isset($_REQUEST["category_name"]) ? $_REQUEST["category_name"] : null;
      $price = isset($_REQUEST["price"]) ? str_replace(".","",$_REQUEST["price"]) : null;
      $cost = isset($_REQUEST["cost"]) ? str_replace(".","",$_REQUEST["cost"]) : null;
      $list_file_del = isset($_REQUEST["list_file_del"]) ? $_REQUEST["list_file_del"] : null;
      if($list_file_del){
         $list_file_del = explode(",",$list_file_del);
      } else {
         $list_file_del = [];
      }
      if($status == 'Delete') {
         $success = "Bạn đã xoá dữ liệu thành công";
         $error = "Network has problem. Please try again.";
         $sql_del = "Update product_info set is_delete = 1 where id = ?";
         sql_query($sql_del,[$id]);
         echo_json(["msg" => "ok","success" => $success]);
      } else if($status == "Insert") {
         $sql_check_exist = "select count(*) as 'countt' from product_info where id = ?";
         $row = fetch(sql_query($sql_check_exist,[$id]));
         if($row['countt'] > 0) {
            $error = "Tên sản phẩm này đã tồn tại.";
            echo_json(['msg' => 'not_ok', 'error' => $error]);
         } else {
            $sql_ins = "Insert into product_info(product_type_id,user_id,name,description,count,cost,price,img_name,is_active) values(?,?,?,?,?,?,?,?,?)";
            sql_query($sql_ins,[$product_type_id,$user_id,$name,$description,$count,$cost,$price,1,0]);
            $insert = ins_id();
            $id = $insert;
            if($insert > 0) {
               $image = null;
               //
               $dir = "upload/product/";
               if(!file_exists($dir)) {
                  mkdir($dir, 0777); 
                  chmod($dir, 0777);
               }
               $dir = "upload/product/" . $insert;
               if(!file_exists($dir)) {
                  mkdir($dir, 0777); 
                  chmod($dir, 0777);
               }
               if(array_key_exists('img_sanpham_file',$_FILES)) {
                  $ext = strtolower(pathinfo($_FILES['img_sanpham_file']['name'],PATHINFO_EXTENSION));
                  $file_name = md5(rand(1,999999999)). $id . "." . $ext;
                  $file_name = str_replace("_","",$file_name);
                  $path = $dir . "/" . $file_name ;
                  move_uploaded_file($_FILES['img_sanpham_file']['tmp_name'],$path);
                  $sql_update = "update product_info set img_name='$path' where id = '$insert'";
                  sql_query($sql_update);
               }
               // $sql = "Insert into product_image(product_info_id,img_id,img_order) values";
               // if(count($_FILES['img']['name']) > 0) {
               //    $__arr = [];
               //    $i = 0;
               //    foreach($_FILES['img']['error'] as $key => $error) {
               //       if($error == UPLOAD_ERR_OK) {
               //          $ext = strtolower(pathinfo($_FILES['img']['name'][$key],PATHINFO_EXTENSION));
               //          $file_name = md5(rand(1,999999999)) . $insert . "." . $ext;
               //          $file_name = str_replace("_","",$file_name);
               //          $path = $dir . "/" . $file_name ;
               //          move_uploaded_file($_FILES['img']['tmp_name'][$key],$path);
               //          @chmod($dir, 0777);
               //          $j = $list_file_del[$i];
               //          array_push($__arr,"('$insert','$path',$j)");
               //       }
               //       if($error == UPLOAD_ERR_NO_FILE) {
               //          $i--;
               //       }
               //       $i++;
               //    }
               //    if(count($__arr) > 0) {
               //       $sql .= implode(",",$__arr);
               //       //print_r($sql);
               //       sql_query($sql);
               //    }
               // }
               $list_file_del_length = count($list_file_del);
               for($i = 0 ; $i < count($list_file_del) ; $i++) {
                  if(strpos($list_file_del[$i],"_del") !== false) {
                     $img_order = explode("_",$list_file_del[$i])[0];
                     $file_old_name = getFileUpload($img_order,$id);
                     if(file_exists($file_old_name)) {
                        unlink($file_old_name);
                        chmod($dir, 0777);
                     }
                     $sql_delete_file = "Delete from product_image where product_info_id = '$id' and img_order = $img_order";
                     sql_query($sql_delete_file);
                     array_splice($list_file_del,$i, 1);
                     $i--;
                  }
                  else if(strpos($list_file_del[$i],"_has") !== false) {
                     array_splice($list_file_del,$i, 1);
                     $i--;
                  }
               }
               //print_r($list_file_del);
               if(isset($_FILES['img'])) {
                  if(count($_FILES['img']['name']) > 0) {
                     $file_old_name = "";
                     $__arr = [];
                     $i = 0;
                     $sql = "Insert into product_image(product_info_id,img_id,img_order) values";
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
                              $sql_update_file = "Update product_image set img_id = '$path' where product_info_id='$id' and img_order='$img_order'";
                              sql_query($sql_update_file);
                           }
                        }
                        if($error == UPLOAD_ERR_NO_FILE) {
                           $i--;
                        }
                        $i++;
                     }
                     if(count($__arr) > 0) {
                        $sql .= implode(",",$__arr);
                        //print_r($sql);
                        sql_query($sql);
                     }
                  }
               }
               $success = "Insert dữ liệu thành công.";
               echo_json(["msg" => "ok","success" => $success,"id"=>$insert]);
            }
         }
      } else if($status == "Update") {
         $image = null;
         $dir = "upload/product/" . $id;
         if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
         }
         //file_upload(['file' => 'img_sanpham_file'],'product_info','img_name',$dir,$id,$image);
         // print_r($_FILES);
         // exit();
         if(array_key_exists('img_sanpham_file',$_FILES)) {
            $sql_get_old_file = "select img_name from product_info where id = '$id'";
            $old_file = fetch(sql_query($sql_get_old_file))['img_name'];
            if(file_exists($old_file)){
               unlink($old_file);
            }
            $ext = strtolower(pathinfo($_FILES['img_sanpham_file']['name'],PATHINFO_EXTENSION));
            $file_name = md5(rand(1,999999999)). $id . "." . $ext;
            $file_name = str_replace("_","",$file_name);
            $path = $dir . "/" . $file_name ;
            move_uploaded_file($_FILES['img_sanpham_file']['tmp_name'],$path);
            $sql_update = "Update product_info set img_name='$path' where id = '$id'";
            sql_query($sql_update);
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
               $sql_delete_file = "Delete from product_image where product_info_id = '$id' and img_order = $img_order";
               sql_query($sql_delete_file);
               array_splice($list_file_del,$i, 1);
               $i--;
            }
            else if(strpos($list_file_del[$i],"_has") !== false) {
               array_splice($list_file_del,$i, 1);
               $i--;
            }
         }
         //print_r($list_file_del);
         if(isset($_FILES['img'])) {
            if(count($_FILES['img']['name']) > 0) {
               $file_old_name = "";
               $__arr = [];
               $i = 0;
               $sql = "Insert into product_image(product_info_id,img_id,img_order) values";
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
                        $sql_update_file = "Update product_image set img_id = '$path' where product_info_id='$id' and img_order='$img_order'";
                        sql_query($sql_update_file);
                     }
                  }
                  if($error == UPLOAD_ERR_NO_FILE) {
                     $i--;
                  }
                  $i++;
               }
               if(count($__arr) > 0) {
                  $sql .= implode(",",$__arr);
                  //print_r($sql);
                  sql_query($sql);
               }
            }
         }
         if($image) {
            $sql_update = "Update product_info set name = ?,user_id = ?,product_type_id = ?,description = ?,count = ?,price = ?,img_name = ? where id = ?";
            sql_query($sql_update,[$name,$user_id,$product_type_id,$description,$count,$price,$image,$id]);
         } else {
            $sql_update = "Update product_info set name = ?,user_id = ?,product_type_id = ?,description = ?,count = ?,price = ? where id = ?";
            sql_query($sql_update,[$name,$user_id,$product_type_id,$description,$count,$price,$id]);
         }
         $success = "Sửa dữ liệu thành công.";
         $sql_get_file_name = "select img_name from product_info where id = ?";
         $image = fetch(sql_query($sql_get_file_name,[$id]));
         if($image) {
            $image = $image['img_name'];
         }
         echo_json(["msg" => "ok",'success' => $success]);
      } else if($status == "Active") {
         $sql = "select is_active from product_type where id = '$product_type_id' limit 1";
         $res22 = fetch(sql_query($sql));
         if($res22['is_active'] == 0) { // danh muc cha cua san pham chua active
            echo_json(["msg" => "not_ok","error" => "Danh mục của sản phẩm này chưa được kích hoạt"]);
         } else {
            $sql_upt_is_active = "Update product_info set is_active = 1 where id = '$id'";
            sql_query($sql_upt_is_active);
            echo_json(["msg" => "Active","success" => "Bạn đã kích hoạt sản phẩm này thành công"]);
         }
      } else if($status == "Deactive") {
         $sql_upt_is_active = "Update product_info set is_active = 0 where id = '$id'";
         sql_query($sql_upt_is_active);
         echo_json(["msg" => "Deactive","success" => "Bạn đã huỷ kích hoạt sản phẩm này thành công"]);
      } else if($status == "del_more") {
         $rows = isset($_REQUEST["rows"]) ? $_REQUEST["rows"] : null;
         $rows_arr = explode(",",$rows);
         foreach($rows_arr as $row) {
            $sql = "Update product_info set is_delete = 1 where id = '$row'";
            sql_query($sql);
         }
         echo_json(["msg" => "ok"]);
      } else if($status == "upt_more") {
         $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
         $upt_name = isset($_REQUEST["upt_name"]) ? $_REQUEST["upt_name"] : null;
         $upt_cost = isset($_REQUEST["upt_cost"]) ? str_replace(".","",$_REQUEST["upt_cost"]) : null;
         $upt_count = isset($_REQUEST["upt_count"]) ? str_replace(".","",$_REQUEST["upt_count"]) : null;
         $upt_price = isset($_REQUEST["upt_price"]) ? str_replace(".","",$_REQUEST["upt_price"]) : null;
         $sql = "Update product_info set name = ?,count = ?,cost = ?,price = ? where id = ?";
         sql_query($sql,[$upt_name,$upt_count,$upt_cost,$upt_price,$upt_id]);
         echo_json(["msg" => "ok"]);
      } else if($status == "ins_more") {
         $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
         if($user_id) {
            $ins_name = isset($_REQUEST["ins_name"]) ? $_REQUEST["ins_name"] : null;
            $ins_count = isset($_REQUEST["ins_count"]) ? str_replace(".","",$_REQUEST["ins_count"]) : null;
            $ins_cost = isset($_REQUEST["ins_cost"]) ? str_replace(".","",$_REQUEST["ins_cost"]) : null;
            $ins_price = isset($_REQUEST["ins_price"]) ? str_replace(".","",$_REQUEST["ins_price"]) : null;
            $ins_desc = isset($_REQUEST["ins_desc"]) ? $_REQUEST["ins_desc"] : null;
            $ins_img = isset($_REQUEST["ins_img"]) ? $_REQUEST["ins_img"] : null;
            $product_type_id = isset($_REQUEST["product_type_id"]) ? $_REQUEST["product_type_id"] : null;
            $dir = "upload/product/";
            $sql = "Insert into product_info(product_type_id,user_id,name,img_name,description,count,cost,price) values(?,?,?,?,?,?,?,?)";
            sql_query($sql,[$product_type_id,$user_id,$ins_name,1,$ins_desc,$ins_count,$ins_cost,$ins_price]);
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
            if($_FILES['ins_img']['name'] != "") {
               $ext = strtolower(pathinfo($_FILES['ins_img']['name'],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $id . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['ins_img']['tmp_name'],$path);
               $sql_update = "update product_info set img_name = ? where id = ?";
               sql_query($sql_update,[$path,$insert]);
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "ins_all") {
         $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
         $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
         if($user_id) {
            $ins_name = isset($_REQUEST["ins_name"]) ? $_REQUEST["ins_name"] : null;
            $ins_count = isset($_REQUEST["ins_count"]) ? $_REQUEST["ins_count"] : null;
            $ins_cost = isset($_REQUEST["ins_cost"]) ? $_REQUEST["ins_cost"] : null;
            $ins_price = isset($_REQUEST["ins_price"]) ? $_REQUEST["ins_price"] : null;
            $ins_desc = isset($_REQUEST["ins_desc"]) ? $_REQUEST["ins_desc"] : null;
            $product_type_id = isset($_REQUEST["product_type_id"]) ? $_REQUEST["product_type_id"] : null;
            $ins_img = isset($_REQUEST["ins_img"]) ? $_REQUEST["ins_img"] : null;
            for($i = 0 ; $i < $len ; $i++) {
               $ins_count2 = str_replace(".","",$ins_count[$i]);
               $ins_cost2 = str_replace(".","",$ins_cost[$i]);
               $ins_price2 = str_replace(".","",$ins_price[$i]);
               $dir = "upload/product/";
               $sql = "Insert into product_info(product_type_id,user_id,name,img_name,description,count,cost,price,is_active) values(?,?,?,?,?,?,?,?,?)";
               sql_query($sql,[$product_type_id[$i],$user_id,$ins_name[$i],1,$ins_desc[$i],$ins_count2,$ins_cost2,$ins_price2,0]);
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
               if($_FILES['ins_img']['name'][$i] != "") {
                  $ext = strtolower(pathinfo($_FILES['ins_img']['name'][$i],PATHINFO_EXTENSION));
                  $file_name = md5(rand(1,999999999)). $insert . "." . $ext;
                  $file_name = str_replace("_","",$file_name);
                  $path = $dir . "/" . $file_name ;
                  move_uploaded_file($_FILES['ins_img']['tmp_name'][$i],$path);
                  $sql_update = "update product_info set img_name = ? where id = ?";
                  sql_query($sql_update,[$path,$insert]);
               }
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "upt_all") {
         $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
         $upt_name = isset($_REQUEST["upt_name"]) ? $_REQUEST["upt_name"] : null;
         $upt_count = isset($_REQUEST["upt_count"]) ? $_REQUEST["upt_count"] : null;
         $upt_cost = isset($_REQUEST["upt_cost"]) ? $_REQUEST["upt_cost"] : null;
         $upt_price = isset($_REQUEST["upt_price"]) ? $_REQUEST["upt_price"] : null;
         $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
         if($len && is_numeric($len)) {
            for($i = 0 ; $i < $len ; $i++){
               $upt_count2 = str_replace(".","",$upt_count[$i]);
               $upt_cost2 = str_replace(".","",$upt_cost[$i]);
               $upt_price2 = str_replace(".","",$upt_price[$i]);
               $sql = "Update product_info set name = ?,count = ?,cost = ?,price = ? where id = ?";
               sql_query($sql,[$upt_name[$i],$upt_count2,$upt_cost2,$upt_price2,$upt_id[$i]]);
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "saveTabFilter") {
         $_SESSION['product_tab_id'] = isset($_SESSION['product_tab_id']) ? $_SESSION['product_tab_id'] + 1 : 1;
         $tab_name = isset($_SESSION['product_tab_id']) ? "tab_" . $_SESSION['product_tab_id'] : null;
         $_SESSION['tab_id'] = isset($_SESSION['tab_id']) ? $_SESSION['tab_id'] + 1 : 1;
         $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
         $tab_unique = uniqid("tab_");
         $_SESSION['product_manage_tab'] = isset($_SESSION['product_manage_tab']) ? $_SESSION['product_manage_tab'] : [];
         array_push($_SESSION['product_manage_tab'],[
            "tab_unique" => $tab_unique,
            "tab_name" => $tab_name,
            "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
         ]);
         echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['product_manage_tab'])- 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
      } else if($status == "deleteTabFilter") {
         $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
         $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
         array_splice($_SESSION['product_manage_tab'],$index,1);
         if(trim($is_active_2) == "") {
            echo_json(["msg" => "ok"]);
         }  else if($is_active_2 == 1) {
            if(array_key_exists($index,$_SESSION['product_manage_tab'])) {
               echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['product_manage_tab'][$index]['tab_urlencode']]);
            } else if(array_key_exists($index - 1,$_SESSION['product_manage_tab'])){
               echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['product_manage_tab'][$index - 1]['tab_urlencode']]);
            } else {
               echo_json(["msg" => "ok","tab_urlencode" => "product_manage.php?tab_unique=all"]);
            }
         }
      } else if($status == "changeTabNameFilter") {
         $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
         $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
         $_SESSION['product_manage_tab'][$index]['tab_name'] = $new_tab_name;
         echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['product_manage_tab'][$index]['tab_urlencode']]);
      }
   }
?>