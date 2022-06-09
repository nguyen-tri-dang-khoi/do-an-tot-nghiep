<?php
   include_once("../lib/database.php");
   logout_session_timeout();
   check_access_token();
   redirect_if_login_status_false();
   if(is_get_method()) {
      
      $allow_read = $allow_update = $allow_delete = $allow_insert = $allow_check_product = false; 
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
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
      $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
      $select_publish = isset($_REQUEST['select_publish']) ? $_REQUEST['select_publish'] : null;
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
      $order_by = "";
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
      if($price_min && is_array($price_min) && $price_max && is_array($price_max)) {
         $wh_child = [];
         foreach(array_combine($price_min,$price_max) as $p_min => $p_max) {
            if($p_min != "" && $p_max != "") {
               $p_min = str_replace(".","",$p_min);
               $p_max = str_replace(".","",$p_max);
               array_push($wh_child,"(pi.price >= '$p_min' and pi.price <= '$p_max')");
            } else if($p_min == "" && $p_max != ""){
               $p_max = str_replace(".","",$p_max);
               array_push($wh_child,"(pi.price <= '$p_max')");
            } else if($p_min != "" && $p_max == ""){
               $p_min = str_replace(".","",$p_min);
               array_push($wh_child,"(pi.price >= '$p_min')");
            }
         }
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }
      if($count_min && is_array($count_min) && $count_max && is_array($count_max)) {
         $wh_child = [];
         foreach(array_combine($count_min,$count_max) as $c_min => $c_max) {
            if($c_min != "" && $c_max != "") {
               $c_min = str_replace(".","",$c_min);
               $c_max = str_replace(".","",$c_max);
               array_push($wh_child,"(pi.count >= '$c_min' and pi.count <= '$c_max')");
            } else if($c_min == "" && $c_max != ""){
               $c_max = str_replace(".","",$c_max);
               array_push($wh_child,"(pi.count <= '$c_max')");
            } else if($c_min != "" && $c_max == ""){
               $c_min = str_replace(".","",$c_min);
               array_push($wh_child,"(pi.count >= '$c_min')");
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
               array_push($wh_child,"(pi.created_at >= '$d_min 00:00:00' and pi.created_at <= '$d_max 23:59:59')");
            } else if($d_min != "" && $d_max == "") {
               $d_min = Date("Y-m-d",strtotime($d_min));
               array_push($wh_child,"(pi.created_at >= '$d_min 00:00:00')");
            } else if($d_min == "" && $d_max != "") {
               $d_max = Date("Y-m-d",strtotime($d_max));
               array_push($wh_child,"(pi.created_at <= '$d_max 23:59:59')");
            }
         }
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }
      if($select_publish != "") {
         $where .= " and pi.is_active='$select_publish'";
      }
      if($orderStatus && $orderByColumn) {
         $order_by .= "ORDER BY $orderByColumn $orderStatus";
         $where .= " $order_by";
      }
      //log_v($where);
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/summernote.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
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
    /*border-radius: 5px;
    text-align: center;*/
    color: red;
    font-weight:600;
    border:none;
    /*border-bottom: 1px solid #17a2b8;*/
    /*padding: 0px 1px;*/
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
   table.dataTable tr th.select-checkbox.selected::after {
      content: "\2713";
      margin-top: -11px;
      margin-left: -4px;
      text-align: center;
      color: #9900ff;
   }
</style>
<link rel="stylesheet" href="css/select.dataTables.min.css">
<link rel="stylesheet" href="css/colReorder.dataTables.min.css">
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid" style="padding:0px;">
    <section class="content">
        <div class="row" style="">
            <div class="col-12">
               <div class="card">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý bình luận sản phẩm</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <div class="col-12" style="padding-right:0px;padding-left:0px;">
                        <form style="" autocomplete="off" action="product_manage.php" method="get" onsubmit="customInpSend()">
                              <div class="d-flex a-start">
                                 <div class="" style="margin-top:5px;">
                                    <select onchange="choose_type_search()" class="form-control" name="search_option">
                                       <option value="">Bộ lọc tìm kiếm</option>
                                       <option value="keyword" <?=$search_option == 'type' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                                       <option value="price2" <?=$search_option == 'price2' ? 'selected="selected"' : '' ?>>Khoảng giá</option>
                                       <option value="count2" <?=$search_option == 'count2' ? 'selected="selected"' : '' ?>>Khoảng số lượng</option>
                                       <option value="date2" <?=$search_option == 'date2' ? 'selected="selected"' : '' ?>>Phạm vi ngày</option>
                                       <option value="type2" <?=$search_option == 'type2' ? 'selected="selected"' : '' ?>>Danh mục</option>
                                       <option value="publish2" <?=$search_option == 'publish2' ? 'selected="selected"' : '' ?>>Tình trạng xuất bản</option>
                                       <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                    </select>
                                 </div>
                                 <div id="s-cols" class="k-select-opt ml-10 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column": "display:none;";?>">
                                    <span class="k-select-opt-remove"></span>
                                    <span class="k-select-opt-ins"></span>
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
                                 <div id="s-price2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($price_min && $price_min != [""] || $price_max && $price_max != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                    <span class="k-select-opt-remove"></span>
                                    <span class="k-select-opt-ins"></span>
                                    <div class="ele-price2">
                                       <div class="" style="display:flex;">
                                          <input type="text" name="price_min[]" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Giá 1" class="form-control" value=""  >
                                       </div>
                                       <div class="ml-10" style="display:flex;">
                                          <input type="text" name="price_max[]" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="Giá 2" class="form-control" value="" >
                                       </div>
                                    </div>
                                    <?php
                                       if(is_array($price_min) && is_array($price_max)) {
                                          foreach(array_combine($price_min,$price_max) as $p_min => $p_max){
                                    ?>
                                       <?php
                                       if($p_min != "" || $p_max != "") {
                                       ?>
                                       <div class="ele-select ele-price2 mt-10">
                                          <div class="" style="display:flex;">
                                             <input type="text" min="0" name="price_min[]" placeholder="Giá 1" class="form-control" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$p_min;?>"  >
                                          </div>
                                          <div class="ml-10" style="display:flex;">
                                             <input type="text" min="0" name="price_max[]" placeholder="Giá 2" class="form-control" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$p_max;?>"  >
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
                                 <div id="s-count2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($count_min && $count_min != [""] || $count_max && $count_max != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                    <span class="k-select-opt-remove"></span>
                                    <span class="k-select-opt-ins"></span>
                                    <div class="ele-count2">
                                       <div class="" style="display:flex;">
                                          <input type="text" name="count_min[]" placeholder="Sl 1" class="form-control" value="" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                       </div>
                                       <div class="ml-10" style="display:flex;">
                                          <input type="text" name="count_max[]" placeholder="Sl 2" class="form-control" value="" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                       </div>
                                       <!--<span onclick="select_remove_child()" class="kh-select-child-remove"></span>-->
                                    </div>
                                    <?php
                                       if(is_array($count_min) && is_array($count_max)) {
                                          foreach(array_combine($count_min,$count_max) as $c_min => $c_max){
                                    ?>
                                       <?php
                                       if($c_min != "" || $c_max != "") {
                                       ?>
                                       <div class="ele-select ele-count2 mt-10">
                                          <div class="" style="display:flex;">
                                             <input type="text" min="0" name="count_min[]" placeholder="Sl 1" class="form-control" value="<?=$c_min;?>" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                          </div>
                                          <div class="ml-10" style="display:flex;">
                                             <input type="text" min="0" name="count_max[]" placeholder="Sl 2" class="form-control" value="<?=$c_max;?>" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                          </div>
                                          <span onclick="select_remove_child('.ele-count2')" class="kh-select-child-remove"></span>
                                          <!--<span onclick="select_remove_child()" class="kh-select-child-remove"></span>-->
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
                                 <div id="s-type2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($pt_type && $pt_type != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                    <span class="k-select-opt-remove"></span>
                                    <span class="k-select-opt-ins"></span>
                                    <div class="ele-type2">
                                       <select class="select-type2" style="width:100%;" class="form-control" name="pt_type[]">
                                          <option value="">Chọn danh mục cần tìm</option>
                                          <?php
                                             $sql = "select * from product_type where is_delete = 0 and id in (select distinct product_type_id from product_info where is_delete = 0)";
                                             $rows2 = db_query($sql);
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
                                             $rows2 = db_query($sql);
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
                              <div class="d-flex a-start" style="">
                                 <div id="s-publish2" class="k-select-opt col-2 s-all2" style="<?=$select_publish != "" ? "display:block;": "display:none;";?>margin-top:10px;">
                                    <span class="k-select-opt-remove"></span>
                                    <select name="select_publish" class="form-control">
                                       <option value="">Tình trạng xuất bản</option>
                                       <option value="1" <?=$select_publish == 1 ? "selected='selected'" : "";?>>Đã xuất bản</option>
                                       <option value="00" <?=$select_publish == "00" ? "selected='selected'" : "";?>>Chưa xuất bản</option>
                                    </select>
                                 </div>
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
                        </form>
                     </div>
                     <table id="m-product-info" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th class="w-20-imp" ></th>
                              <th class="w-100">Số thứ tự</th>
                              <th>Tên sản phẩm</th>
                              <th class="w-200-imp">Thao tác</th>
                           </tr>
                        </thead>
                        <tbody id="list-san-pham">
                        <?php
                            // set get
                            $get = $_GET;
                            unset($get['page']);
                            $str_get = http_build_query($get);
                            // query
                            if($str) {
                                $where .= " and pi.id in ($str)";
                            }
                            $cnt = 0;
                            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                            $limit = $_SESSION['paging'];
                            $start_page = $limit * ($page - 1);
                            $sql_get_total = "select count(*) as 'countt' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where";
                            $total = fetch_row($sql_get_total)['countt'];
                            $sql_get_product = "select pi.id,pi.is_active, pi.name as 'pi_name',pi.price,pi.count,pi.img_name as 'pi_img_name',pi.created_at,pt.name as 'pt_name',pi.product_type_id as 'pt_id' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where limit $start_page,$limit";
                           //print_r($sql_get_product);
                           $rows = db_query($sql_get_product);
                           foreach($rows as $row) {
                           ?>
                              <tr id="<?=$row["id"];?>">
                                 <td></td>
                                 <td><?=$total - ($start_page + $cnt);?></td>
                                 <td><?=$row['pi_name'];?></td>
                                 <td>
                                     <button onclick='showListComment("<?=$row["id"];?>")' class="dt-button button-grey">Xem bình luận</button>
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
                              <th>Tên sản phẩm</th>
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
         <h4 id="msg-del" class="modal-title">Thông tin bình luận sản phẩm</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="list-product-comment">

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
<script>
	toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
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
   $('.k-select-opt-remove').click(function(){
      $(event.currentTarget).siblings('select').find('option').prop("selected",false);
      $(event.currentTarget).siblings('select').find("option[value='']").prop("selected",true);
      $(event.currentTarget).siblings('.ele-select').remove()
      $(event.currentTarget).siblings("div").find("input").val("");
      $(event.currentTarget).closest('div').css({"display":"none"});
   });
   $('.k-select-opt-ins').click(function(){
      let file_html = "";
      if($(event.currentTarget).closest('#s-count2').length) {
         file_html = `
            <div class="ele-select ele-count2 mt-10">
               <div class="" style="display:flex;">
                  <input type="text" name="count_min[]" placeholder="Sl 1" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
               </div>
               <div class="ml-10" style="display:flex;">
                  <input type="text" name="count_max[]" placeholder="Sl 2" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
               </div>
               <span onclick="select_remove_child('.ele-count2')" class="kh-select-child-remove"></span>
            </div>
         `
      } else if($(event.currentTarget).closest('#s-type2').length) {
         file_html = `
         <div class="ele-select ele-type2 mt-10">
            <select class="select-type2" style="width:100%" class="form-control" name="pt_type[]">
               <option value="">Chọn danh mục cần tìm</option>
               <?php
                  $sql = "select * from product_type where is_delete = 0 and id in (select distinct product_type_id from product_info where is_delete = 0)";
                  $rows2 = db_query($sql);
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
      } else if($(event.currentTarget).closest('#s-date2').length) {
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
      } else if($(event.currentTarget).closest('#s-price2').length) {
         file_html = `
         <div class="ele-select ele-price2 mt-10">
            <div class="" style="display:flex;">
               <input type="text" name="price_min[]" placeholder="Giá 1" class="form-control" value="" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
            </div>
            <div class="ml-10" style="display:flex;">
               <input type="text" name="price_max[]" placeholder="Giá 2" class="form-control" value="" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
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
      $('.select-type2').select2();
   });
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
</script>
<script>
   function showListComment(id,page=1) {
      $('#list-product-comment').load(`ajax_product_comment.php?status=show_list_comment&id=${id}&page=${page}`,() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
         let total_all = $('#pagination-comment').attr('data-total');
         let page = $('#pagination-comment').attr('data-page');
         let limit = $('#pagination-comment').attr('data-item');
         $('#pagination-comment').pagination({
            items: total_all,
            itemsOnPage: limit,
            currentPage: page,
            hrefTextPrefix: "<?php echo '?page='; ?>",
            hrefTextSuffix: "<?php echo '&' . $str_get;?>",
            prevText: "<",
            nextText: ">",
            onPageClick: function(pageNumber,event){
               event.preventDefault();
               showListComment(id,pageNumber);
            },
            cssStyle: 'light-theme'
         });
      });
   }
   function showReplyOk(reply_id,product_info_id,type=""){
      $(`.info${reply_id}`).load(`ajax_product_comment.php?status=show_reply_ok&reply_id=${reply_id}&id=${product_info_id}`,() => {
         if(type == "input") {
            $(`.input${reply_id}`).removeClass('d-none').addClass('d-flex');
            $(`.kh-border-vertical${reply_id}`).css({"border-left":"1px solid #ddd","min-height":"100%"});
         }
      });
   }
   function sendComment(reply_id,product_info_id) {
      let test = true;
      let comment = $(`textarea[name="reply${reply_id}"]`).val();
      console.log(comment);
      if(comment.trim() == "") {
         $.alert({
            title : "Thông báo",
            content: "Vui lòng không đê trống nội dung bình luận."
         });
         test = false;
      }
      if(test) {
         $.ajax({
            url:window.location.href,
            type:"POST",
            data: {
               token : "<?php echo_token();?>",
               comment: comment,
               status: "Send",
               reply_id: reply_id,
               product_info_id: product_info_id,
            },success:function(data) {
               console.log(data);
               data = JSON.parse(data);
               if(data.msg == "ok") {
                  showReplyOk(reply_id,product_info_id,"input");
                  $(`textarea[name="reply${reply_id}"]`).val("");
               }
            },error:function(data){
               console.log("Error: " + data);
            }
         })
      }
      
   }
   function delComment(comment_id,product_info_id) {
      let evt = $(event.currentTarget);
      $.confirm({
         title: "Thông báo",
         content: "Nếu bạn xoá bình luận này, các phản hồi về bình luận này sẽ bị xoá theo. Bạn có chắc chắn ?",
         buttons: {
            "Có": function(){
               $.ajax({
                  url:window.location.href,
                  type:"POST",
                  data: {
                     token : "<?php echo_token();?>",
                     status: "Delete",
                     id: comment_id,
                  },success:function(data) {
                     console.log(data);
                     data = JSON.parse(data);
                     if(data.msg == "ok") {
                        evt.closest(`.d-flex`).remove();
                     }
                  },error:function(data){
                     console.log("Error: " + data);
                  }
               })
            },"Không":function(){

            }
         }
      })
   }
   function toggleComment(comment_id,product_type_id,status) {
      let evt = $(event.currentTarget);
      $.ajax({
         url:window.location.href,
         type:"POST",
         data: {
            token : "<?php echo_token();?>",
            status: status,
            id: comment_id,
         },success:function(data) {
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
               toastr["success"](data.success);
               if(status == "Active") {
                  evt.attr('onchange',`toggleComment('${comment_id}','${product_type_id}','Deactive')`);
               } else if(status == "Deactive") {
                  evt.attr('onchange',`toggleComment('${comment_id}','${product_type_id}','Active')`);
               }
               showReplyOk(comment_id,product_type_id,'input');
            }
         },error:function(data){
            console.log("Error: " + data);
         }
      })
   }
</script>
<!-- datatable and function crud js-->
<script>
   var dt_pi;
   $(document).ready(function (e) {
      $('.select-type2').select2();
      $.fn.dataTable.moment('DD-MM-YYYY');
      dt_pi = $("#m-product-info").DataTable({
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
            }
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
         "responsive": false, 
         "lengthChange": true, 
         "autoWidth": false,
         "paging":false,
         "searchHighlight": true,
         "buttons": [
            {
               "extend": "excel",
               "text": "Excel (2)",
               "key": {
                  "key": '2',
               },
               "autoFilter": true,
               "filename": "danh_sach_san_pham_ngay_<?=Date("d-m-Y",time());?>",
               "title": "Dữ liệu sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
               "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
               "extend": "pdfHtml5",
               "text": "PDF (3)",
               "key": {
                  "key": '3',
               },
               "filename": "danh_sach_san_pham_ngay_<?=Date("d-m-Y",time());?>",
               "title": "Dữ liệu sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
               "exportOptions":{
                  columns: ':visible:not(.select-checkbox):not(.manipulate)'
               },
            },{
               "extend": "csv",
               "text": "CSV (4)",
               "charset": 'UTF-8',
               "bom": true,
               "key": {
                  "key": '4',
               },
               "filename": "danh_sach_san_pham_ngay_<?=Date("d-m-Y",time());?>",
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
      })
      dt_pi.buttons().container().appendTo('#m-product-info_wrapper .col-md-6:eq(0)');
      dt_pi.search('').draw();
      //
      dt_pi.on("click", "th.select-checkbox", function() {
         if ($("th.select-checkbox").hasClass("selected")) {
            dt_pi.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
         } else {
            dt_pi.rows().select();
            $("th.select-checkbox").addClass("selected");
         }
      }).on("select deselect", function() {
         if (dt_pi.rows({
                  selected: true
            }).count() !== dt_pi.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
         } else {
            $("th.select-checkbox").addClass("selected");
         }
      });
      //
      // php auto select all rows when focus update all function execute
      <?=$upt_more == 1 ? 'dt_pi.rows().select();' . PHP_EOL . '$("th.select-checkbox").addClass("selected");'.PHP_EOL  : "";?>
   });
   $("#modal-xl2").on("hidden.bs.modal",function(){
      let html = $("#form-product2 table");
      console.log(html.html());
      $("#form-product2 table tbody").remove();
      $("input[name='count2']").val("");
      $("input[name='count2']").attr("data-plus",0);
   })
   $('#modal-xl2').on('hidden.bs.modal', function (e) {
      $('#form-product2 table tbody').remove();
      $('#form-product2 #paging').remove();
      $('[data-plus]').attr('data-plus',0);
    })
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
   } else if (is_post_method()) {
      $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
      $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
      $comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : null;
      $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
      $reply_id = isset($_REQUEST['reply_id']) ? $_REQUEST['reply_id'] : null;
      $product_info_id = isset($_REQUEST['product_info_id']) ? $_REQUEST['product_info_id'] : null;
      $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
      $is_active = isset($_REQUEST['is_active']) ? $_REQUEST['is_active'] : null;
      if($status == "Send") {
         $sql_ins_comment = "Insert into product_comment(reply_id,user_id,product_info_id,comment,rate,is_active) values('$reply_id',$user_id,$product_info_id,'$comment','$rate','$is_active')";
         sql_query($sql_ins_comment);
         echo_json(["msg" => "ok"]);
      } else if($status == "Delete") {
         $sql_del = "Delete from product_comment where id = '$id'";
         sql_query($sql_del);
         echo_json(["msg" => "ok"]);
      } else if($status == "Active") {
         exec_toggle_comment(NULL,$id,"Active");
         echo_json(["msg" => "ok","success" => "Bạn đã duyệt bình luận thành công."]);
      } else if($status == "Deactive") {
         exec_toggle_comment(NULL,$id,"Deactive");
         echo_json(["msg" => "ok","success" => "Bạn đã bỏ duyệt bình luận thành công."]);
      }
   }
?>