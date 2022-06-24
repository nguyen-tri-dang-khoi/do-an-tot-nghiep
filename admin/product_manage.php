<?php
   include_once("../lib/database.php");
   if(is_get_method()) {
      $allow_read = $allow_update = $allow_delete = $allow_insert = $allow_check_product = false;
      include_once("include/head.meta.php");
      include_once("include/left_menu.php"); 
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
      if(check_permission_crud("product_manage.php","check_product")) {
         $allow_check_product = true;
      }
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
                                       <input type="hidden" name="is_search" value="true">
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
                                    <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                    </th>
                                    <th class="w-100 th-so-thu-tu">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                    <th class="th-ten-san-pham">Tên sản phẩm <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                    <th class="w-120 th-so-luong">Số lượng <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
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
                                 $sql_get_product = "select pi.id,pi.is_active, pi.name as 'pi_name',pi.price,pi.count,pi.img_name as 'pi_img_name',pi.created_at,pt.name as 'pt_name',pi.product_type_id as 'pt_id' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where limit $start_page,$limit";
                                 $rows = fetch_all(sql_query(($sql_get_product)));
                              ?>
                              <tbody dt-parent-id dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" id="list-san-pham" class="list-product">
                              <?php
                                 foreach($rows as $row) {
                              ?>
                                    <tr id="<?=$row["id"];?>">
                                       <td>
                                          <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-product')" type="checkbox" name="check_id<?=$row["id"];?>">
                                       </td>
                                       <td class="so-thu-tu w-150"><?=$total - ($start_page + $cnt);?></td>
                                       <td class="ten-san-pham">
                                          <?= ($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' name='pi_name' value='" . $row['pi_name'] . "'><span class='text-danger'></span>" : $row['pi_name'];?>
                                       </td>
                                       <td class="so-luong">
                                          <?=($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)' name='pi_count' style='' value='" . number_format($row['count'],0,'','.') . "'><span class='text-danger'></span>" : number_format($row['count'],0,'','.');?>
                                       </td>
                                       <td class="don-gia">
                                          <?=($upt_more == 1) ? "<input class='kh-inp-ctrl' type='text' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)' name='pi_price' style='' value='" . number_format($row['price'],0,'','.') . "'><span class='text-danger'></span>" : number_format($row['price'],0,'','.') . "đ";?>
                                       </td>
                                       <td class="danh-muc"><?=$row['pt_name']?></td>
                                       <td>
                                          <div class="custom-control custom-switch">
                                             <input type="checkbox" onchange="toggleActiveProduct('<?=$row['pt_id']?>')" class="custom-control-input" id="customSwitches<?=$row['id'];?>" <?= $row['is_active'] == 1 ? "checked" : "";?>>
                                             <label class="custom-control-label" for="customSwitches<?=$row['id'];?>"></label>
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
                                          data-id="<?=$row["id"];?>" >
                                          Xem
                                          </button>
                                          <?php } ?>
                                          <?php
                                             if($allow_update) {
                                          ?>
                                          <button onclick="openModalUpdate()" class="btn-sua-san-pham dt-button button-green" data-number="<?=$total - ($start_page + $cnt);?>"
                                          data-id="<?=$row["id"];?>" >
                                          Sửa
                                          </button>
                                          <?php } ?>
                                          <?php
                                             if($allow_delete) {
                                          ?>
                                          <button onclick="processDelete()" class="btn-xoa-san-pham dt-button button-red" data-id="<?=$row["id"];?>">
                                          Xoá
                                          </button>
                                          <?php } ?>
                                          <?php
                                             } else {
                                          ?>
                                             <button dt-count="0" onclick="uptMore2()" class="btn-upt-more-1 dt-button button-green" data-id="<?=$row["id"];?>">
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
                              <tfoot>
                                 <tr>
                                    <th style="width:20px !important;">
                                    <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                    </th>
                                    <th>Số thứ tự</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
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
               <div class="d-flex f-column form-group">
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
               </div>
            </div>
            <!--table-->
            <table class='table table-bordered' style="height:auto;">
               <thead>
               <tr>
                  <th>Số thứ tự</th>
                  <th>Tên sp</th>
                  <th class="w-300">Danh mục</th>
                  <th>Số lượng</th>
                  <th>Đơn giá</th>
                  <th>Mô tả sp</th>
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
<script src="js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script src="js/khoi_all.js"></script>
<script>
   $('.select-type2').select2();
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
			   //$(target).siblings('.kh-custom-remove-img').css({'display': 'block'});
            //$(target).first().siblings('.kh-custom-remove-img').css({'display': 'none'});
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
      let test = true
      let name = $('input[name=ten_san_pham]').val();
      let category = $('input[name="category_id"]').val();
      let count = $('input[name=so_luong]').val();
      let price = $('input[name=don_gia]').val();
      let description = $('#summernote').summernote('code');
      if(name.trim() == "") {
         $('input[name=ten_san_pham]').focus();
         $.alert({
            title: "Thông báo",
            content: "Tên sản phẩm không được để trống"
         });
         test = false;
      } else if(count.trim() == "") {
         $('input[name=so_luong]').focus();
         $.alert({
            title: "Thông báo",
            content: "Số lượng không được để trống"
         });
         test = false;
      } else if(category.trim() == "") {
         $('#menu').focus();
         $.alert({
            title: "Thông báo",
            content: "Danh mục sản phẩm không được để trống"
         });
         test = false;
      } else if(price.trim() == "") {
         $('input[name=don_gia]').focus();
         $.alert({
            title: "Thông báo",
            content: "Đơn giá không được để trống"
         });
         test = false;
      } else if(description.trim() == "<p><br></p>") {
         $('.note-editable.card-block').focus();
         $.alert({
            title: "Thông báo",
            content: "Mô tả sản phẩm không được để trống"
         });
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
               $('#summernote').summernote({height: 120,lang: 'vi-VN'});
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
                     /*$("#breadcrumb-menu").parent().css({"margin-top":"-25px"});*/
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
               $('#summernote').summernote({height: 120,lang: 'vi-VN'});
            },100);
            $(".parent[data-id]").click(function(e){
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
      let formData = new FormData($('#form-san-pham')[0]);
      let number = 1;
      formData.append('id',$('input[name=id]').val());
      formData.append('name',$('input[name=ten_san_pham]').val());
      formData.append('description',$('#summernote').summernote('code'));
      formData.append('count',$('input[name=so_luong]').val());
      formData.append('number',$('input[name=number]').val());
      formData.append('price',$('input[name=don_gia]').val());
      formData.append('category_id',$("input[name='category_id']").val());
      formData.append('category_name',$("input[name='category_name']").val());
      formData.append('status',$('#btn-luu-san-pham').attr('data-status').trim());
      if(status == "Insert"){
         game();
      } else {
         gameChange();
      }
      formData.append('list_file_del',$('input[name="list_file_del"]').val());
      let img = document.getElementsByName('img[]');
      let file = $('input[name=img_sanpham_file]')[0].files;
      //console.log(file);
      if(file.length > 0) {
         formData.append('img_sanpham_file',file[0]); 
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
                     });
                     loadDataComplete('Insert');
                     if($('#display-image').length){
                        $('#display-image').replaceWith('<div data-img="" class="img-fluid" id="where-replace">' + "<span></span>" + "</div>");
                     }
                  } else if(status == "Update") {
                     msg = "Sửa dữ liệu thành công.";
                     $.alert({
                        title: "Thông báo",
                        content: msg,
                     });
                     loadDataComplete();
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
                           content: res_json.success
                        });
                        loadDataComplete();
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
   function insAll(){
      let test = true;
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      let count = $('td input[name="name_p2"]').length;
      $('td input[name="name_p2"]').each(function(){
         if($(this).val() != "") {
            formData.append("name_p2[]",$(this).val());
            $(this).siblings("p").text("");

         } else {
            $(this).siblings("p").text("Không được để trống");
            test = false;
         }
      });
      $('td input[name="price_p2"]').each(function(){
         if($(this).val() != "") {
            formData.append("price_p2[]",$(this).val());
            $(this).siblings("p").text("");
         } else {
            $(this).siblings("p").text("Không được để trống");
            test = false;
         }
      });
      $('td textarea[name="desc_p2"]').each(function(){
         if($(this).val() != "") {
            formData.append("desc_p2[]",$(this).val());
            $(this).siblings("p").text("");
         } else {
            $(this).siblings("p").text("Không được để trống");
            test = false;
         }
      });
      $('td input[name="count_p2"]').each(function(){
         if($(this).val() != "") {
            formData.append("count_p2[]",$(this).val());
            $(this).siblings("p").text("");
         } else {
            $(this).siblings("p").text("Không được để trống");
            test = false;
         }
      });
      $('td input[name="category_id"]').each(function(){
         if($(this).val() != "") {
            formData.append("type_p2[]",$(this).val());
            $(this).closest('td').find("p").text("");
         } else {
            $(this).closest('td').find("p").text("Phải chọn danh mục");
            test = false;
         }
      });
      $('td input[name="img2[]"]').each(function(){
         if($(this).val() != "") {
            formData.append("img2[]",$(this)[0].files[0]);
            $(this).closest('td').find("p").text("");
         } else {
            $(this).closest('td').find("p").text("Ko để trống ảnh");
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
      let _data = dt_pi.rows(".selected").select().data();
      if(_data.length == 0) {
         $.alert({
            title:"Thông báo",
            content:"Vui lòng chọn dòng cần lưu",
         });
         return;
      }
      for(i = 0 ; i < _data.length ; i++) {
         formData.append("pi_id[]",_data[i].DT_RowId);
      }
      $('tr.selected input[name="pi_name"]').each(function(){
         if($(this).val() != "") {
            formData.append("pi_name[]",$(this).val());
            $(this).siblings("span.text-danger").text("");
         } else {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;
         }
         
      });
      $('tr.selected input[name="pi_count"]').each(function(){
         if($(this).val() != "") {
            formData.append("pi_count[]",$(this).val());
            $(this).siblings("span.text-danger").text("");
         } else {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;
         }
         
      });
      $('tr.selected input[name="pi_price"]').each(function(){
         if($(this).val() != "") {
            formData.append("pi_price[]",$(this).val());
            $(this).siblings("span.text-danger").text("");
         } else {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;
         }
         
      });
      $("tr.selected .t-summernote").each(function(){
         if($(this).val() != "") {
            formData.append("pi_desc[]",$(this).summernote('code'));
            $(this).siblings("span.text-danger").text("");
         } else {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;
         }
      });
      formData.append("status","upt_all");
      formData.append("len",_data.length);
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
     
   }
   function uptMore2(){
      let test = true;
      let this2 = $(event.currentTarget).closest("tr");
      let name = $(event.currentTarget).closest("tr").find("td input[name='pi_name']").val();
      let count = $(event.currentTarget).closest("tr").find("td input[name='pi_count']").val();
      let price = $(event.currentTarget).closest("tr").find("td input[name='pi_price']").val();
      let description = $(event.currentTarget).closest("tr").find("td .t-summernote").summernote('code');
      let id = $(event.currentTarget).attr('data-id');

      // validate 
      if(name == "") {
         this2.find('td input[name="pi_name"]').siblings("span.text-danger").text("Không được để trống");
         test = false;
      } else {
         this2.find('td input[name="pi_name"]').siblings("span.text-danger").text("");
      }
      //
      if(price == "") {
         this2.find('td input[name="pi_price"]').siblings("span.text-danger").text("Không được để trống");
         test = false;
      } else {
         this2.find('td input[name="pi_price"]').siblings("span.text-danger").text("");
      }
      //
      if(count == "") {
         this2.find('td input[name="pi_count"]').siblings("span.text-danger").text("Không được để trống");
         test = false;
      } else  {
         this2.find('td input[name="pi_count"]').siblings("span.text-danger").text("");
      } 
      //
      if(description == "") {
         this2.find("td .t-summernote").siblings("span.text-danger").text("Không được để trống");
         test = false;
      } else  {
         this2.find("td .t-summernote").siblings("span.text-danger").text("");
      } 
      this2 = $(event.currentTarget);
      if(test) {
         $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
               status: "upt_more",
               pi_name: name,
               pi_count: count,
               pi_price: price,
               pi_description: description,
               pi_id: id,
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
   }
   function insMore2(){
      let test = true;
      let this2 = $(event.currentTarget);
      let name_p2 = $(event.currentTarget).closest('tr').find('td input[name="name_p2"]').val();
      let price_p2 = $(event.currentTarget).closest('tr').find('td input[name="price_p2"]').val();
      let count_p2 = $(event.currentTarget).closest('tr').find('td input[name="count_p2"]').val();
      let desc_p2 = $(event.currentTarget).closest('tr').find('td textarea[name="desc_p2"]').val();
      let type_p2 = $(event.currentTarget).closest('tr').find('td input[name="category_id"]').val();
      let file = $(event.currentTarget).closest('tr').find('input[name="img2[]"]')[0].files;
      console.log(file);
      // validate 
      if(name_p2 == "") {
         this2.closest('tr').find('td input[name="name_p2"]').siblings("p.text-danger").text("Không được để trống");
         test = false;
      } else {
         this2.closest('tr').find('td input[name="name_p2"]').siblings("p.text-danger").text("");
      }
      //
      if(price_p2 == "") {
         this2.closest('tr').find('td input[name="price_p2"]').siblings("p.text-danger").text("Không được để trống");
         test = false;
      } else {
         this2.closest('tr').find('td input[name="price_p2"]').siblings("p.text-danger").text("");
      }
      //
      if(count_p2 == "") {
         this2.closest('tr').find('td input[name="count_p2"]').siblings("p.text-danger").text("Không được để trống");
         test = false;
      } else  {
         this2.closest('tr').find('td input[name="count_p2"]').siblings("p.text-danger").text("");
      } 
      //
      if(desc_p2 == "") {
         this2.closest('tr').find('td textarea[name="desc_p2"]').siblings("p.text-danger").text("Không được để trống");
         test = false;
      } else  {
         this2.closest('tr').find('td textarea[name="desc_p2"]').siblings("p.text-danger").text("");
      } 
      //
      if(type_p2 == "") {
         this2.closest('tr').find("td input[name='category_id']").closest("ul.ul_menu").siblings("p.text-danger").text("Phải chọn danh mục");
         test = false;
      } else {
         this2.closest('tr').find("td input[name='category_id']").closest("ul.ul_menu").siblings("p.text-danger").text("");
      }
      //
      if(file.length == 0) {
         this2.closest('tr').find('td input[name="img2[]"]').parent().siblings("p.text-danger").text("Ko để trống ảnh");
         test = false;
      } else {
         this2.closest('tr').find('td input[name="img2[]"]').parent().siblings("p.text-danger").text("");
      }
      //
      console.log(test);
      if(test) {
         let formData = new FormData();
         formData.append("name_p2",name_p2);
         formData.append("price_p2",price_p2);
         formData.append("count_p2",count_p2);
         formData.append("type_p2",type_p2);
         formData.append("desc_p2",desc_p2);
         formData.append("status","ins_more");
         if(file.length > 0) {
            formData.append('file_p2',file[0]); 
         }
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
   }
   function load_menu(){
      let html =`<?php echo show_menu_3();?>`;
      console.log(html);
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
            target.closest('.ul_menu').find("input[name='category_id']").val(id);
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
      $category_id = isset($_REQUEST["category_id"]) ? $_REQUEST["category_id"] : null;
      $category_name = isset($_REQUEST["category_name"]) ? $_REQUEST["category_name"] : null;
      $price = isset($_REQUEST["price"]) ? str_replace(".","",$_REQUEST["price"]) : null;
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
         $sql_is_active = "select is_active from product_type where id = '$category_id'";
         $res33 = fetch(sql_query($sql_is_active));
         $is_active = $res33['is_active'];
         if($row['countt'] > 0) {
            $error = "Tên sản phẩm này đã tồn tại.";
            echo_json(['msg' => 'not_ok', 'error' => $error]);
         } else {
            $insert = db_insert_id('product_info',['name'=>$name,'user_id'=>$user_id,'product_type_id'=>$category_id,'description'=>$description,'count'=>$count,'price'=>$price,'img_name'=>null,'is_active' => $is_active]);
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
               //
               //file_upload(['file' => 'img_sanpham_file'],'product_info','img_name',$dir,$insert,$image);
               if($_FILES['img_sanpham_file']['name'] != "") {
                  $ext = strtolower(pathinfo($_FILES['img_sanpham_file']['name'],PATHINFO_EXTENSION));
                  $file_name = md5(rand(1,999999999)). $id . "." . $ext;
                  $file_name = str_replace("_","",$file_name);
                  $path = $dir . "/" . $file_name ;
                  move_uploaded_file($_FILES['img_sanpham_file']['tmp_name'],$path);
                  $sql_update = "update product_info set img_name='$path' where id = '$insert'";
                  sql_query($sql_update);
               }
               $sql = "Insert into product_image(product_info_id,img_id,img_order) values";
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
         if($_FILES['img_sanpham_file']['name'] != "") {
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
            sql_query($sql_update,[$name,$user_id,$category_id,$description,$count,$price,$image,$id]);
         } else {
            $sql_update = "Update product_info set name = ?,user_id = ?,product_type_id = ?,description = ?,count = ?,price = ? where id = ?";
            sql_query($sql_update,[$name,$user_id,$category_id,$description,$count,$price,$id]);
         }
         $success = "Sửa dữ liệu thành công.";
         $sql_get_file_name = "select img_name from product_info where id = ?";
         $image = fetch(sql_query($sql_get_file_name,[$id]));
         if($image) {
            $image = $image['img_name'];
         }
         echo_json(["msg" => "ok",'success' => $success]);
      } else if($status == "active") {
         $sql = "select is_active from product_type where id = '$category_id' limit 1";
         $res22 = fetch(sql_query($sql));
         if($res22['is_active'] == 0) { // danh muc cha cua san pham chua active
            echo_json(["msg" => "not_ok","error" => "Danh mục của sản phẩm này chưa được kích hoạt"]);
         } else {
            $sql_upt_is_active = "Update product_info set is_active = 1 where id = '$id'";
            sql_query($sql_upt_is_active);
            echo_json(["msg" => "active","success" => "Bạn đã kích hoạt sản phẩm này thành công"]);
         }
      } else if($status == "deactive") {
         $sql_upt_is_active = "Update product_info set is_active = 0 where id = '$id'";
         sql_query($sql_upt_is_active);
         echo_json(["msg" => "deactive","success" => "Bạn đã huỷ kích hoạt sản phẩm này thành công"]);
      } 
      else if($status == "del_more") {
         $rows = isset($_REQUEST["rows"]) ? $_REQUEST["rows"] : null;
         $rows_arr = explode(",",$rows);
         foreach($rows_arr as $row) {
            $sql = "Update product_info set is_delete = 1 where id = '$row'";
            sql_query($sql);
         }
         echo_json(["msg" => "ok"]);
      } else if($status == "upt_more") {
         $pi_id = isset($_REQUEST["pi_id"]) ? $_REQUEST["pi_id"] : null;
         $pi_name = isset($_REQUEST["pi_name"]) ? $_REQUEST["pi_name"] : null;
         $pi_count = isset($_REQUEST["pi_count"]) ? str_replace(".","",$_REQUEST["pi_count"]) : null;
         $pi_price = isset($_REQUEST["pi_price"]) ? str_replace(".","",$_REQUEST["pi_price"]) : null;
         $pi_description = isset($_REQUEST["pi_description"]) ? $_REQUEST["pi_description"] : null;
         $sql = "Update product_info set name='$pi_name',count='$pi_count',price='$pi_price',description='$pi_description' where id='$pi_id'";
         sql_query($sql);
         echo_json(["msg" => "ok"]);
      } else if($status == "ins_more") {
         $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
         if($user_id) {
            $name_p2 = isset($_REQUEST["name_p2"]) ? $_REQUEST["name_p2"] : null;
            $count_p2 = isset($_REQUEST["count_p2"]) ? str_replace(".","",$_REQUEST["count_p2"]) : null;
            $price_p2 = isset($_REQUEST["price_p2"]) ? str_replace(".","",$_REQUEST["price_p2"]) : null;
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
               sql_query($sql_update);
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
               $sql_is_active = "select is_active from product_type where id = '$type_p2[$i]'";
               $res44 = fetch(sql_query($sql_is_active));
               $is_active = $res44['is_active'];
               $count_p22 = str_replace(".","",$count_p2[$i]);
               $price_p22 = str_replace(".","",$price_p2[$i]);
               $dir = "upload/product/";
               $sql = "Insert into product_info(product_type_id,user_id,name,img_name,description,count,price,is_active) values('$type_p2[$i]','$user_id','$name_p2[$i]','1','$desc_p2[$i]','$count_p22','$price_p22','$is_active')";
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
               $pi_count2 = str_replace(".","",$pi_count[$i]);
               $pi_price2 = str_replace(".","",$pi_price[$i]);
               $sql = "Update product_info set name='$pi_name[$i]',count='$pi_count2',price='$pi_price2',description='$pi_desc[$i]' where id='$pi_id[$i]'";
               sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "check_all") {
         $pi_id = isset($_REQUEST["pi_id"]) ? $_REQUEST["pi_id"] : null;
         foreach($pi_id as $id) {
            $sql = "update product_info set is_active='1' where id = '$id'";
            sql_query($sql);
         }
         echo_json(["msg" => "ok"]);
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