<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        $allow_read = $allow_update = $allow_delete = $allow_insert = true;
        //
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        //
        $is_active = isset($_REQUEST['is_active']) ? $_REQUEST['is_active'] : null;
        //
        $subtotal_min_1 = isset($_REQUEST['subtotal_min_1']) ? $_REQUEST['subtotal_min_1'] : null;
        $subtotal_min_2 = isset($_REQUEST['subtotal_min_2']) ? $_REQUEST['subtotal_min_2'] : null;
        //
        $subtotal_max_1 = isset($_REQUEST['subtotal_max_1']) ? $_REQUEST['subtotal_max_1'] : null;
        $subtotal_max_2 = isset($_REQUEST['subtotal_max_2']) ? $_REQUEST['subtotal_max_2'] : null;
        //
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        //
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        //
        $date_start_1 = isset($_REQUEST['date_start_1']) ? $_REQUEST['date_start_1'] : null;
        $date_start_2 = isset($_REQUEST['date_start_2']) ? $_REQUEST['date_start_2'] : null;
        //
        $date_end_1 = isset($_REQUEST['date_end_1']) ? $_REQUEST['date_end_1'] : null;
        $date_end_2 = isset($_REQUEST['date_end_2']) ? $_REQUEST['date_end_2'] : null;
        //
        $date_created_at_1 = isset($_REQUEST['date_created_at_1']) ? $_REQUEST['date_created_at_1'] : null;
        $date_created_at_2 = isset($_REQUEST['date_created_at_2']) ? $_REQUEST['date_created_at_2'] : null;
        //
        $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
        $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
        //
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $order_by = "";
        $where = "where 1=1 and is_delete = 0 ";
        $wh_child = [];
        $arr_search = [];
        // từ khoá tìm kiếm
        if($keyword && is_array($keyword)) {
            $wh_child = [];
            if($search_option) {
                if($search_option == "all") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_code) like lower('%$key%') or lower(coupon_content) like lower('%$key%') or lower(coupon_discount_percent) like lower('%$key%') or lower(coupon_if_subtotal_min) like lower('%$key%')
                            or lower(coupon_if_subtotal_max) like lower('%$key%') or lower(coupon_date_start) like lower('%$key%') or lower(coupon_date_end) like lower('%$key%') or lower(created_at) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_code") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_code) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_content") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_content) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_discount_percent") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_discount_percent) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_if_subtotal_min") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_if_subtotal_min) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_if_subtotal_max") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_if_subtotal_max) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_date_start") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_date_start) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "coupon_date_end") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(coupon_date_start) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "created_at") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(created_at) like lower('%$key%'))");
                        }
                    }
                }
            } 
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        // số tiền tối thiểu
        if($subtotal_min_1 && is_array($subtotal_min_1) && $subtotal_min_2 && is_array($subtotal_min_2)) {
            $wh_child = [];
            foreach(array_combine($subtotal_min_1,$subtotal_min_2) as $s_min_1 => $s_min_2) {
                if($s_min_1 != "" && $s_min_2 != "") {
                    $s_min_1 = str_replace(".","",$s_min_1);
                    $s_min_2 = str_replace(".","",$s_min_2);
                    array_push($wh_child,"(coupon_if_subtotal_min >= '$s_min_1' and coupon_if_subtotal_min <= '$s_min_2')");
                } else if($s_min_1 == "" && $s_min_2 != ""){
                    $s_min_2 = str_replace(".","",$s_min_2);
                    array_push($wh_child,"(coupon_if_subtotal_min <= '$s_min_2')");
                } else if($s_min_1 != "" && $s_min_2 == ""){
                    $s_min_1 = str_replace(".","",$s_min_1);
                    array_push($wh_child,"(coupon_if_subtotal_min >= '$p_min')");
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        // số tiền tối đa
        if($subtotal_max_1 && is_array($subtotal_max_1) && $subtotal_max_2 && is_array($subtotal_max_2)) {
            $wh_child = [];
            foreach(array_combine($subtotal_max_1,$subtotal_max_2) as $s_max_1 => $s_max_2) {
                if($s_max_1 != "" && $s_max_2 != "") {
                    $s_max_1 = str_replace(".","",$s_max_1);
                    $s_max_2 = str_replace(".","",$s_max_2);
                    array_push($wh_child,"(coupon_if_subtotal_max >= '$s_max_1' and coupon_if_subtotal_max <= '$s_max_2')");
                } else if($s_max_1 == "" && $s_max_2 != ""){
                    $s_max_2 = str_replace(".","",$s_max_2);
                    array_push($wh_child,"(coupon_if_subtotal_max <= '$s_max_2')");
                } else if($s_max_1 != "" && $s_max_2 == ""){
                    $s_max_1 = str_replace(".","",$s_max_1);
                    array_push($wh_child,"(coupon_if_subtotal_max >= '$p_max')");
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        // thời gian bắt đầu
        if($date_start_1 && is_array($date_start_1) && $date_start_2 && is_array($date_start_2)) {
            $wh_child = [];
            foreach(array_combine($date_start_1,$date_start_2) as $d_start_1 => $d_start_2) {
                if($d_start_1 != "" && $d_start_2 != "") {
                    $d_start_1 = Date("Y-m-d",strtotime($d_start_1));
                    $d_start_2 = Date("Y-m-d",strtotime($d_start_2));
                    array_push($wh_child,"(coupon_date_start >= '$d_start_1 00:00:00' and coupon_date_start <= '$d_start_2 23:59:59')");
                } else if($d_start_1 != "" && $d_start_2 == "") {
                    $d_start_1 = Date("Y-m-d",strtotime($d_start_1));
                    array_push($wh_child,"(coupon_date_start >= '$d_start_1 00:00:00')");
                } else if($d_start_1 == "" && $d_start_2 != "") {
                    $d_start_2 = Date("Y-m-d",strtotime($d_start_2));
                    array_push($wh_child,"(dcoupon_ate_start <= '$d_start_2 23:59:59')");
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        // thời gian kết thúc
        if($date_end_1 && is_array($date_end_1) && $date_end_2 && is_array($date_end_2)) {
            $wh_child = [];
            foreach(array_combine($date_end_1,$date_end_1) as $d_end_1 => $d_end_2) {
                if($d_end_1 != "" && $d_end_2 != "") {
                    $d_end_1 = Date("Y-m-d",strtotime($d_end_1));
                    $d_end_2 = Date("Y-m-d",strtotime($d_end_2));
                    array_push($wh_child,"(coupon_date_end >= '$d_end_1 00:00:00' and coupon_date_end <= '$d_end_2 23:59:59')");
                } else if($d_end_1 != "" && $d_end_2 == "") {
                    $d_end_1 = Date("Y-m-d",strtotime($d_end_1));
                    array_push($wh_child,"(coupon_date_end >= '$d_end_1 00:00:00')");
                } else if($d_end_1 == "" && $d_end_2 != "") {
                    $d_end_2 = Date("Y-m-d",strtotime($d_end_2));
                    array_push($wh_child,"(coupon_date_end <= '$d_end_2 23:59:59')");
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        // ngày tạo
        if($date_created_at_1 && is_array($date_created_at_1) && $date_created_at_2 && is_array($date_created_at_2)) {
            $wh_child = [];
            foreach(array_combine($date_created_at_1,$date_created_at_2) as $d_created_at_1 => $d_created_at_2) {
                if($d_created_at_1 != "" && $d_created_at_2 != "") {
                    $d_created_at_1 = Date("Y-m-d",strtotime($d_created_at_1));
                    $d_created_at_2 = Date("Y-m-d",strtotime($d_created_at_2));
                    array_push($wh_child,"(created_at >= '$d_created_at_1 00:00:00' and created_at <= '$d_created_at_2 23:59:59')");
                } else if($d_created_at_1 != "" && $d_created_at_2 == "") {
                    $d_created_at_1 = Date("Y-m-d",strtotime($d_created_at_1));
                    array_push($wh_child,"(created_at >= '$d_created_at_1 00:00:00')");
                } else if($d_created_at_1 == "" && $d_created_at_2 != "") {
                    $d_created_at_2 = Date("Y-m-d",strtotime($d_created_at_2));
                    array_push($wh_child,"(created_at <= '$d_created_at_2 23:59:59')");
                }
            }
            $wh_child = implode(" or ",$wh_child);
            if($wh_child != "") {
                $where .= " and ($wh_child)";
            }
        }
        if($is_active) {
            $where .= " and is_active='$is_active'";
        }
        if($orderStatus && $orderByColumn) {
            $order_by .= "ORDER BY $orderByColumn $orderStatus";
            $where .= " $order_by";
        }
        // code to be executed get method
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/select.dataTables.min.css">
<link rel="stylesheet" href="css/colReorder.dataTables.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="css/toastr.min.css">
<style>
    [class^=ele-] {
        display: flex;
        justify-content: space-between;
        width: 100%;
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
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid" style="padding:0px;">
    <section class="content">
        <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý mã khuyến mãi</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        <div class="input-group-append">
                           <button id="btn-them-khuyen-mai" class="dt-button button-blue">
                              Tạo mã khuyến mãi
                           </button>
                        </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <div class="col-12" style="padding-right:0px;padding-left:0px;">
                        <form style="" autocomplete="off" action="coupon_manage.php" method="get">
                            <div class="d-flex a-start">
                                <div class="" style="margin-top:5px;">
                                    <select onchange="choose_type_search()" class="form-control" name="search_option">
                                        <option value="">Bộ lọc tìm kiếm</option>
                                        <option value="keyword" <?=$search_option == 'keyword' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                                        <option value="subtotal_min2" <?=$search_option == 'subtotal_min2' ? 'selected="selected"' : '' ?>>Khoảng số tiền tối thiểu</option>
                                        <option value="subtotal_max2" <?=$search_option == 'subtotal_max2' ? 'selected="selected"' : '' ?>>Khoảng số tiền tối đa</option>
                                        <option value="date_start2" <?=$search_option == 'date_start2' ? 'selected="selected"' : '' ?>>Phạm vi ngày bắt đầu</option>
                                        <option value="date_end2" <?=$search_option == 'date_end2' ? 'selected="selected"' : '' ?>>Phạm vi ngày kết thúc</option>
                                        <option value="date_created_at2" <?=$search_option == 'date_created_at2' ? 'selected="selected"' : '' ?>>Phạm vi ngày tạo</option>
                                        <option value="is_active2" <?=$search_option == 'is_active2' ? 'selected="selected"' : '' ?>>Tình trạng</option>
                                        <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default ml-10" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                            </div>
                            <div class="d-flex a-start mt-10">
                                <div id="s-cols" class="k-select-opt s-all2" style="width:15%;<?=$keyword && $keyword != [""] ? "display:flex;flex-direction: column;": "display:none;";?>">
                                <span class="k-select-opt-remove"></span>
                                <span class="k-select-opt-ins"></span>
                                <div class="ele-cols d-flex f-column">
                                    <select name="search_option" class="form-control mb-10">
                                        <option value="">Chọn cột tìm kiếm</option>
                                        <option value="coupon_code" <?=$search_option == 'coupon_code' ? 'selected="selected"' : '' ?>>Mã khuyến mãi</option>
                                        <option value="coupon_if_subtotal_min" <?=$search_option == 'coupon_if_subtotal_min' ? 'selected="selected"' : '' ?>>Số tiền tối thiểu</option>
                                        <option value="coupon_if_subtotal_max" <?=$search_option == 'coupon_if_subtotal_max' ? 'selected="selected"' : '' ?>>Số tiền tối đa</option>
                                        <option value="date_start" <?=$search_option == 'date_start' ? 'selected="selected"' : '' ?>>Ngày bắt đầu</option>
                                        <option value="date_end" <?=$search_option == 'date_end' ? 'selected="selected"' : '' ?>>Ngày kết thúc</option>
                                        <option value="created_at" <?=$search_option == 'created_at' ? 'selected="selected"' : '' ?>>Ngày tạo</option>
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
                                <div id="s-subtotal_min2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($subtotal_min_1 && $subtotal_min_1 != [""] || $subtotal_min_2 && $subtotal_min_2 != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <p style="text-align:center;font-weight:bold;margin:0;margin-top:-2px;">Gía trị tối thiểu</p> 
                                <span class="k-select-opt-remove"></span>
                                <span class="k-select-opt-ins"></span>
                                <div class="ele-subtotal_min2">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="subtotal_min_1[]" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="VD: 1.000" class="form-control" value=""  >
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="subtotal_min_2[]" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="VD: 2.000" class="form-control" value="" >
                                    </div>
                                </div>
                                <?php
                                    if(is_array($subtotal_min_1) && is_array($subtotal_min_2)) {
                                        foreach(array_combine($subtotal_min_1,$subtotal_min_2) as $s_min_1 => $s_min_2){
                                ?>
                                    <?php
                                    if($s_min_1 != "" || $s_min_2 != "") {
                                    ?>
                                    <div class="ele-select ele-subtotal_min2 mt-10">
                                        <div class="" style="display:flex;">
                                            <input type="text" min="0" name="subtotal_min_1[]" placeholder="Tiền tối thiểu 1" class="form-control" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$p_min;?>"  >
                                        </div>
                                        <div class="ml-10" style="display:flex;">
                                            <input type="text" min="0" name="subtotal_min_2[]" placeholder="Tiền tối thiểu 2" class="form-control" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$p_max;?>"  >
                                        </div>
                                        <span onclick="select_remove_child('.ele-subtotal_min2')" class="kh-select-child-remove"></span>
                                    </div>
                                    <?php
                                    }?>
                                <?php 
                                        }
                                    }
                                ?>
                                </div>
                                <div id="s-subtotal_max2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($subtotal_max_1 && $subtotal_max_1 != [""] || $subtotal_max_2 && $subtotal_max_2 != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span class="k-select-opt-remove"></span>
                                <span class="k-select-opt-ins"></span>
                                <div class="ele-subtotal_max2">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="subtotal_max_1[]" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="" class="form-control" value=""  >
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="subtotal_max_2[]" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="" class="form-control" value="" >
                                    </div>
                                </div>
                                <?php
                                    if(is_array($subtotal_max_1) && is_array($subtotal_max_2)) {
                                        foreach(array_combine($subtotal_max_1,$subtotal_max_2) as $s_max_1 => $s_max_2){
                                ?>
                                    <?php
                                    if($s_max_1 != "" || $s_max_2 != "") {
                                    ?>
                                    <div class="ele-select ele-subtotal_max2 mt-10">
                                        <div class="" style="display:flex;">
                                            <input type="text" min="0" name="subtotal_max_1[]" placeholder="Tiền tối dda 1" class="form-control" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$p_min;?>"  >
                                        </div>
                                        <div class="ml-10" style="display:flex;">
                                            <input type="text" min="0" name="subtotal_max_2[]" placeholder="Tiền tối dda 2" class="form-control" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)" value="<?=$p_max;?>"  >
                                        </div>
                                        <span onclick="select_remove_child('.ele-subtotal_max2')" class="kh-select-child-remove"></span>
                                    </div>
                                    <?php
                                    }?>
                                <?php 
                                        }
                                    }
                                ?>
                                </div>
                                <div id="s-date_start2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_start_1 && $date_start_1 != [""] || $date_start_2 && $date_start_2 != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span class="k-select-opt-remove"></span>
                                <span class="k-select-opt-ins"></span>
                                <div class="ele-date2">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="date_start_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="date_start_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
                                    </div>
                                </div>
                                <?php
                                    if(is_array($date_start_1) && is_array($date_start_2)) {
                                        foreach(array_combine($date_start_1,$date_start_2) as $d_start_1 => $d_start_2){
                                ?>
                                <?php
                                    if($d_start_1 != "" || $d_start_2 != "") {
                                ?>
                                <div class="ele-select ele-date2 mt-10">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="date_start_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$d_start_1 ? Date("d-m-Y",strtotime($d_start_1)) : "";?>">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="date_start_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$d_start_2 ? Date("d-m-Y",strtotime($d_start_2)) : "";?>">
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
                                <div id="s-date_end2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_end_1 && $date_end_1 != [""] || $date_end_2 && $date_end_2 != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span class="k-select-opt-remove"></span>
                                <span class="k-select-opt-ins"></span>
                                <div class="ele-date2">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="date_end_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="date_end_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
                                    </div>
                                </div>
                                <?php
                                    if(is_array($date_end_1) && is_array($date_end_2)) {
                                        foreach(array_combine($date_end_1,$date_end_2) as $d_end_1 => $d_end_2){
                                ?>
                                <?php
                                    if($d_end_1 != "" || $d_end_2 != "") {
                                ?>
                                <div class="ele-select ele-date_end2 mt-10">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="date_end_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$d_end_1 ? Date("d-m-Y",strtotime($d_end_1)) : "";?>">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="date_end_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$d_end_2 ? Date("d-m-Y",strtotime($d_end_2)) : "";?>">
                                    </div>
                                    <span onclick="select_remove_child('.ele-date_end2')" class="kh-select-child-remove"></span>
                                </div>
                                <?php
                                }
                                ?>
                                <?php 
                                        }
                                    }
                                ?>
                                </div>
                                <div id="s-date_created_at2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_start_1 && $date_start_1 != [""] || $date_start_2 && $date_start_2 != [""]) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                <span class="k-select-opt-remove"></span>
                                <span class="k-select-opt-ins"></span>
                                <div class="ele-date2">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="date_created_at_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="date_created_at_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
                                    </div>
                                </div>
                                <?php
                                    if(is_array($date_start_1) && is_array($date_start_2)) {
                                        foreach(array_combine($date_start_1,$date_start_2) as $d_min_1 => $d_min_2){
                                ?>
                                <?php
                                    if($d_min_1 != "" || $d_min_2 != "") {
                                ?>
                                <div class="ele-select date_created_at2 mt-10">
                                    <div class="" style="display:flex;">
                                        <input type="text" name="date_created_at_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$d_min_1 ? Date("d-m-Y",strtotime($d_min_1)) : "";?>">
                                    </div>
                                    <div class="ml-10" style="display:flex;">
                                        <input type="text" name="date_created_at_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$d_min_2 ? Date("d-m-Y",strtotime($d_min_2)) : "";?>">
                                    </div>
                                    <span onclick="select_remove_child('.date_created_at2')" class="kh-select-child-remove"></span>
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
                                
                            </div>
                            <div class="d-flex a-start" style="">
                                <div id="s-publish2" class="k-select-opt col-2 s-all2" style="<?=$is_active != "" ? "display:block;": "display:none;";?>margin-top:10px;">
                                <span class="k-select-opt-remove"></span>
                                <select name="is_active" class="form-control">
                                    <option value="">Tình trạng kích hoạt</option>
                                    <option value="1" <?=$is_active == 1 ? "selected='selected'" : "";?>>Đã kích hoạt</option>
                                    <option value="00" <?=$is_active == "00" ? "selected='selected'" : "";?>>Chưa kích hoạt</option>
                                </select>
                                </div>
                            </div> 
                            <div class="d-flex a-start" style="padding-left:0;padding-right:0;display:flex;margin-top:15px;">
                                <div style="" class="form-group row" style="flex-direction:row;align-items:center;">
                                <!--<label for="">Sắp xếp:</label>-->
                                <select name="orderByColumn" class="ml-10 form-control col-5">
                                    <option value="">Sắp xếp theo cột</option>
                                    <option value="coupon_code" <?=$orderByColumn == "coupon_code" ? "selected" : "";?>>Mã khuyến mãi</option>
                                    <option value="coupon_discount_percent" <?=$orderByColumn == "coupon_discount_percent" ? "selected" : "";?>>Khuyến mãi (%)</option>
                                    <option value="coupon_if_subtotal_min" <?=$orderByColumn == "coupon_if_subtotal_min" ? "selected" : "";?>>Số tiền tối thiểu</option>
                                    <option value="coupon_if_subtotal_max" <?=$orderByColumn == "coupon_if_subtotal_max" ? "selected" : "";?>>Số tiền tối đa</option>
                                    <option value="coupon_date_start" <?=$orderByColumn == "coupon_date_start" ? "selected" : "";?>>Ngày bắt đầu</option>
                                    <option value="coupon_date_end" <?=$orderByColumn == "coupon_date_end" ? "selected" : "";?>>Ngày kết thúc</option>
                                    <option value="created_at" <?=$orderByColumn == "created_at" ? "selected" : "";?>>Ngày đăng</option>
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
                     <table id="m-khuyen-mai" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th></th>
                              <th>Số thứ tự</th>
                              <th>Mã khuyến mãi</th>
                              <th>Khuyến mãi (%)</th>
                              <th>Giá trị tối thiểu</th>
                              <th>Giá trị tối đa</th>
                              <th>Ngày bắt đầu</th>
                              <th>Ngày hết hạn</th>
                              <th>Tình trạng</th>
                              <th>Ngày tạo</th>
                              <th>Thao tác</th>
                           </tr>
                        </thead>
                        <tbody id="list-khuyen-mai">
                        <?php
                        $get = $_GET;
                        unset($get['page']);
                        $str_get = http_build_query($get);
                        // query
                        $cnt = 0;
                        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                        $limit = $_SESSION['paging'];
                        $start_page = $limit * ($page - 1);
                        $sql_get_total = "select count(*) as 'countt' from coupon $where";
                        $total = fetch_row($sql_get_total)['countt'];
                        $sql_get_product = "select * from coupon n $where limit $start_page,$limit";
                        $rows = db_query($sql_get_product);
                        foreach($rows as $row) {
                        ?>
                           <tr id="<?=$row["id"];?>">
                                <td></td>
                                <td><?=$total - ($start_page + $cnt);?></td>
                                <td><?=$upt_more == 1 ? "<input name='c_code' class='form-control' type='text' value='" . $row['coupon_code'] . "'>" : $row['coupon_code'];?></td>
                                <td><?=$upt_more == 1 ? "<input name='c_discount_percent' class='form-control' type='text' value='" .$row['coupon_discount_percent']."'>" : $row['coupon_discount_percent'];?></td>
                                <td><?=$upt_more == 1 ? "<input name='c_if_subtotal_min' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)'  class='form-control' type='text' value='" . number_format($row['coupon_if_subtotal_min'],0,".",".")."'>" : number_format($row['coupon_if_subtotal_min'],0,".",".");?></td>
                                <td><?=$upt_more == 1 ? "<input name='c_if_subtotal_max' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)'  class='form-control' type='text' value='" .number_format($row['coupon_if_subtotal_max'],0,".",".")."'>" : number_format($row['coupon_if_subtotal_min'],0,".",".");?></td>
                                <td>
                                    <?php
                                        if($upt_more == 1) {
                                    ?>
                                    <?=$row['coupon_date_start'] ? "<input name='c_date_start' class='form-control kh-datepicker2' type='text' value='" . Date("d-m-Y",strtotime($row['coupon_date_start'])) ."'>" : "";?> 
                                    <?php } else { ?>
                                    <?=$row['coupon_date_start'] ? Date("d-m-Y",strtotime($row['coupon_date_start'])) : "";?>
                                    <?php }?>
                                </td>
                                <td>
                                    <?php
                                        if($upt_more == 1) {
                                    ?>
                                    <?=$row['coupon_date_end'] ? "<input name='c_date_end' class='form-control kh-datepicker2' type='text' value='" . Date("d-m-Y",strtotime($row['coupon_date_end'])) ."'>" : "";?> 
                                    <?php } else {?>
                                    <?=$row['coupon_date_end'] ? Date("d-m-Y",strtotime($row['coupon_date_end'])) : "";?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" onchange="toggleActiveCoupon('<?=$row['id']?>','<?= $row['is_active'] == 1 ? 'Deactive' : 'Active';?>')" class="custom-control-input" id="customSwitches<?=$row['id'];?>" <?= $row['is_active'] == 1 ? "checked" : "";?>>
                                        <label class="custom-control-label" for="customSwitches<?=$row['id'];?>"></label>
                                    </div>  
                                </td>
                                <td><?=$row['created_at'] ? Date("d-m-Y",strtotime($row['created_at'])) : "";?></td>
                                <td>
                                    <?php
                                    if($upt_more == 1) {
                                    ?>
                                        <button onclick="uptThisRow()" dt-count="0" class=" dt-button button-green"
                                        data-id="<?=$row["id"];?>" >
                                        Sửa
                                        </button>
                                    <?php } else {?>
                                        <button class="btn-xem-khuyen-mai dt-button button-grey"
                                        data-id="<?=$row["id"];?>">
                                        Xem
                                        </button>
                                        <button class="btn-sua-khuyen-mai dt-button button-green"
                                        data-id="<?=$row["id"];?>" >
                                        Sửa
                                        </button>
                                        <button class="btn-xoa-khuyen-mai dt-button button-red" data-id="<?=$row["id"];?>">
                                        Xoá
                                        </button>
                                    <?php }?>
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
                              <th>Mã khuyến mãi</th>
                              <th>Khuyến mãi (%)</th>
                              <th>Giá trị tối thiểu</th>
                              <th>Giá trị tối đa</th>
                              <th>Ngày bắt đầu</th>
                              <th>Ngày hết hạn</th>
                              <th>Tình trạng</th>
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
        <h4 id="msg-del" class="modal-title">Thông tin mã khuyến mãi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-khuyen-mai" method="post" enctype='multipart/form-data'>
            
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2">
  <div class="modal-dialog modal-xl" style="min-width:1650px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thêm dữ liệu mã khuyến mãi nhanh</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="form-khuyen-mai2" class="modal-body">
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
                            <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this)">
                        </div>
                        <div class="file file-excel mr-10">
                            <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this)">
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
                    <th>Mã khuyến mãi</th>
                    <th>Khuyến mãi (%)</th>
                    <th>Giá trị tối thiểu</th>
                    <th>Giá trị tối đa</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày hết hạn</th>
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
<script>
    function xlsx2input(input) {
        if(input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[workbook.SheetNames[0]]);
                console.log(XL_row_object);
                setDataFromXLSX(XL_row_object,['Mã khuyến mãi','Khuyến mãi (%)','Giá trị tối thiểu','Giá trị tối đa','Ngày bắt đầu','Ngày hết hạn'],['c_code2','c_discount_percent2','c_if_subtotal_min2','c_if_subtotal_max2','c_date_start2','c_date_end2']);
            };
            reader.onerror = function(ex) {
                console.log(ex);
            };
            reader.readAsBinaryString(input.files[0]);
        }
    }
    function csv2input(input) {
      let arr = [];
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload=function(e){
              arr = reader.result.split(/\r\n|\n/);
              console.log(arr);
              // step 1
              let columns = arr[0].split(/\,/);
              let arr_csv = [];
              arr.shift();
              for(i = 0 ; i < arr.length ; i++) {
                  let new_arr = arr[i].split(/\,/);
                  //console.log(new_arr);
                  let new_obj = {};
                  for(j = 0 ; j < columns.length ; j++) {
                      new_obj[columns[j]] = new_arr[j];
                      //console.log(new_obj);
                  }
                  arr_csv.push(new_obj);
              }
              console.log(arr_csv);
              setDataFromCSV(arr_csv,['Tên đầy đủ','Email','Giá trị tối thiểu','Giá trị tối đa','Địa chỉ','Ngày bắt đầu','Ngày hết hạn'],['c_code2','c_discount_percent2','c_if_subtotal_min2','c_if_subtotal_max2','c_date_start2','c_date_end2']);
          }
          reader.readAsText(input.files[0]);
      }
    }
    function setDataFromCSV(arr_csv,arr_csv_columns,arr_input_names) {
        if(arr_csv_columns.every(key => Object.keys(arr_csv[0]).includes(key))) {
            $("[data-plus]").attr("data-plus",arr_csv.length);
            showRow(1);
            let i = 0;
            arr_csv_columns.forEach(function(ele,ind){
                $(`td [name='${arr_input_names[ind]}'].kh-inp-ctrl`).each(function(){
                    $(this).val(arr_csv[i][ele]);
                    i++;
                });
                i = 0; 
            });
        } else {
            $.alert({
                title:"Thông báo",
                content: "Vui lòng nhập đúng tên cột khi đổ dữ liệu"
            });
        }
        $("input[name='read_csv']").val("");
    }
    function setDataFromXLSX(arr_xlsx,arr_excel_columns,arr_input_names){
      if(arr_excel_columns.every(key => Object.keys(arr_xlsx[0]).includes(key))) {
          $("[data-plus]").attr("data-plus",arr_xlsx.length);
          showRow(1);
          let i = 0;
          arr_excel_columns.forEach(function(ele,ind){
            $(`td [name='${arr_input_names[ind]}'].kh-inp-ctrl`).each(function(){
                $(this).val(arr_xlsx[i][ele]);
                i++;
            });
            i = 0; 
          });
      } else {
          $.alert({
              title:"Thông báo",
              content: "Vui lòng nhập đúng tên cột khi đổ dữ liệu"
          });
      }
      $("input[name='read_excel']").val("");
    }
</script>
<!--js section start-->
<script>
    // xem nhanh
    function readMore(){
        let arr_del = [];
        let _data = dt_coupon.rows(".selected").select().data();
        let count4 = _data.length;
        for(i = 0 ; i < count4 ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        let str_arr_upt = arr_del.join(",");
        console.log(str_arr_upt);
        if(arr_del.length == 0) {
            $.alert({
                title: "Thông báo",
                content: "Bạn vui lòng chọn dòng cần xem",
            });
            return;
        }
        $('#form-khuyen-mai').load(`ajax_coupon_manage.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
            let html2 = `
            <div id="paging" style="justify-content:center;" class="row">
                <nav id="pagination3">
                </nav>
            </div>
            `;
            $(html2).appendTo('#form-khuyen-mai');
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
    // xoá nhanh
    function delMore(){
        let arr_del = [];
        let _data = dt_coupon.rows(".selected").select().data();
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
                                        location.href="coupon_manage.php";
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
    // sửa nhanh
    function uptMore(){
        let arr_del = [];
        let _data = dt_coupon.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        let str_arr_upt = arr_del.join(",");
        location.href="coupon_manage.php?upt_more=1&str=" + str_arr_upt;
    }
    function uptThisRow(){
        let test = true;
        let c_code = $(event.currentTarget).closest("tr").find("td input[name='c_code']").val();
        let c_discount_percent = $(event.currentTarget).closest("tr").find("td input[name='c_discount_percent']").val();
        let c_if_subtotal_min = $(event.currentTarget).closest("tr").find("td input[name='c_if_subtotal_min']").val();
        let c_if_subtotal_max = $(event.currentTarget).closest("tr").find("td input[name='c_if_subtotal_max']").val();
        let c_date_start = $(event.currentTarget).closest("tr").find("td input[name='c_date_start']").val();
        
        let c_date_end = $(event.currentTarget).closest("tr").find("td input[name='c_date_end']").val();
        let c_id = $(event.currentTarget).attr('data-id');
        let this2 = $(event.currentTarget).closest("tr");
        if(c_code == "") {
            test = false;
            this2.find("td input[name='c_code']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td input[name='c_code']").siblings("span.text-danger").text("");
        }
        if(c_discount_percent == "") {
            test = false;
            this2.find("td input[name='c_discount_percent']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td input[name='c_discount_percent']").siblings("span.text-danger").text("");
        }
        if(c_if_subtotal_min == "") {
            test = false;
            this2.find("td input[name='c_if_subtotal_min']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td input[name='c_if_subtotal_min']").siblings("span.text-danger").text("");
        }
        if(c_if_subtotal_max == "") {
            test = false;
            this2.find("td textarea[name='c_if_subtotal_max']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td textarea[name='c_if_subtotal_max']").siblings("span.text-danger").text("");
        }
        if(c_date_start == "") {
            test = false;
            this2.find("td input[name='c_date_start']").siblings("span.text-danger").text("Không được để trống");
        } else {
            c_date_start = c_date_start.split("-")
            c_date_start = c_date_start[2] + "-" + c_date_start[1] + "-" + c_date_start[0];
            this2.find("td input[name='c_date_start']").siblings("span.text-danger").text("");
        }
        if(c_date_end == "") {
            test = false;
            this2.find("td input[name='c_date_end']").siblings("span.text-danger").text("Không được để trống");
        } else {
            c_date_end = c_date_end.split("-")
            c_date_end = c_date_end[2] + "-" + c_date_end[1] + "-" + c_date_end[0];
            this2.find("td input[name='c_date_end']").siblings("span.text-danger").text("");
        }

        if(c_id == "") {
            test = false;
        }
        console.log(name);
        this2 = $(event.currentTarget);
        if(test) {
            $.ajax({
                url: window.location.href,
                type: "POST",
                data: {
                    status: "upt_more",
                    c_code: c_code,
                    c_discount_percent: c_discount_percent,
                    c_if_subtotal_min: c_if_subtotal_min,
                    c_if_subtotal_max: c_if_subtotal_max,
                    c_date_start: c_date_start,
                    c_date_end: c_date_end,
                    c_id : c_id,
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
    }
    function uptAll(){
        let test = true;
        let formData = new FormData();
        let _data = dt_coupon.rows(".selected").select().data();
        if(_data.length == 0) {
            $.alert({
                title:"Thông báo",
                content:"Vui lòng chọn dòng cần lưu",
            });
            return;
        }
        for(i = 0 ; i < _data.length ; i++) {
            formData.append("c_id[]",_data[i].DT_RowId);
        }
        $('tr.selected input[name="c_code"]').each(function(){
            if($(this).val() != "") {
                formData.append("c_code[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="c_discount_percent"]').each(function(){
            if($(this).val() != "") {
                formData.append("c_discount_percent[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="c_if_subtotal_min"]').each(function(){
            if($(this).val() != "") {
                formData.append("c_if_subtotal_min[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="c_if_subtotal_max"]').each(function(){
            if($(this).val() != "") {
                formData.append("c_if_subtotal_max[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="c_date_start"]').each(function(){
            if($(this).val() != "") {
                let c_date_start = $(this).val().split("-");
                c_date_start = c_date_start[2] + "-" + c_date_start[1] + "-" + c_date_start[0];
                formData.append("c_date_start[]",c_date_start);
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="c_date_end"]').each(function(){
            if($(this).val() != "") {
                let c_date_end = $(this).val().split("-");
                c_date_end = c_date_end[2] + "-" + c_date_end[1] + "-" + c_date_end[0];
                formData.append("c_date_end[]",c_date_end);
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
        });
        if(test) {
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
        
    }
    // thêm nhanh
    function showPicker(){
        $('input[name^="c_date"]').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
            onSelect: function(dateText,inst) {
                console.log(dateText.split("-"));
                dateText = dateText.split("-");
                $(this).attr('data-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
            }
        });
    }
    function insMore(){
        $('#modal-xl2').modal({backdrop: 'static', keyboard: false});
    }
    function insMore2(){
        let test = true;
        let this2 = $(event.currentTarget).closest('tr');
        let c_code2 = $(event.currentTarget).closest('tr').find('td input[name="c_code2"]').val();
        let c_discount_percent2 = $(event.currentTarget).closest('tr').find('td input[name="c_discount_percent2"]').val();
        let c_if_subtotal_min2 = $(event.currentTarget).closest('tr').find('td input[name="c_if_subtotal_min2"]').val();
        let c_if_subtotal_max2 = $(event.currentTarget).closest('tr').find('td input[name="c_if_subtotal_max2"]').val();
        let c_date_start2 = $(event.currentTarget).closest('tr').find('td input[name="c_date_start2"]').attr('data-date');
        let c_date_end2 = $(event.currentTarget).closest('tr').find('td input[name="c_date_end2"]').attr('data-date');
        if(c_code2 == "") {
            this2.find('td input[name="c_code2"]').siblings("p.text-danger").text("Không được để trống");
            test = false;
        } else {
            this2.find('td input[name="c_code2"]').siblings("p.text-danger").text("");
        }

        if(c_discount_percent2 == "") {
            this2.find('td input[name="c_discount_percent2"]').siblings("p.text-danger").text("Không được để trống");
            test = false;
        } else {
            this2.find('td input[name="c_discount_percent2"]').siblings("p.text-danger").text("");
        }

        if(c_if_subtotal_min2 == "") {
            test = false;
            this2.find('td input[name="c_if_subtotal_min2"]').siblings("p.text-danger").text("Không được để trống");
        } else {
            this2.find('td input[name="c_if_subtotal_min2"]').siblings("p.text-danger").text("");
        }

        if(c_if_subtotal_max2 == "") {
            test = false;
            this2.find('td input[name="c_if_subtotal_max2"]').siblings("p.text-danger").text("Không được để trống");
        } else {
            this2.find('td input[name="c_if_subtotal_max2"]').siblings("p.text-danger").text("");
        }

        if(c_date_start2 == "") {
            test = false;
            this2.find('td input[name="c_date_start2"]').siblings("p.text-danger").text("Không được để trống");
        } else {
            this2.find('td input[name="c_date_start2"]').siblings("p.text-danger").text("");
        }

        if(c_date_end2 == "") {
            test = false;
            this2.find('td input[name="c_date_end2"]').siblings("p.text-danger").text("Không được để trống");
        } else {
            this2.find('td input[name="c_date_end2"]').siblings("p.text-danger").text("");
        }
        if(test) {
            let formData = new FormData();
            formData.append("c_code2",c_code2);
            formData.append("c_discount_percent2",c_discount_percent2);
            formData.append("c_if_subtotal_min2",c_if_subtotal_min2);
            formData.append("c_if_subtotal_max2",c_if_subtotal_max2);
            formData.append("c_date_start2",c_date_start2);
            formData.append("c_date_end2",c_date_end2);
            formData.append("status","ins_more");
            formData.append("token","<?php echo_token();?>");
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
    }
    function insRow(){
        num_of_row_insert = $('input[name="count3"]').val();
        if(num_of_row_insert == "") {
            $.alert({
                title: "Thông báo",
                content: "Vui lòng không để trống số dòng cần thêm",
            })
            return;
        } 
        for(i = 0 ; i < num_of_row_insert ; i++) {
            let page = $('[data-plus]').attr('data-plus');
            let html = "";
            let count2 = parseInt(page / 7) + 1;
            html = `
                <tr data-row-id='${parseInt(page) + 1}'>
                    <td>${parseInt(page) + 1}</td>
                    <td><input class='kh-inp-ctrl' name='c_code2' type='text' value=''><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' name='c_discount_percent2' type='text' value=''><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' name='c_if_subtotal_min2' type='text' value=''><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' name='c_if_subtotal_max2' type='text' value=''><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' data-date='' name='c_date_start2' type='text' value=''><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' data-date='' name='c_date_end2' type='text' value=''><p class='text-danger'></p></td>
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
                $(html).appendTo('#form-khuyen-mai2 table');
            }
            if(page == 0) {
                let html2 = `<div id="paging" style="justify-content:center;" class="row">
                    <nav id="pagination2">
                    </nav>
                </div>`;
                $(html2).appendTo('#form-khuyen-mai2');
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
            showPicker();
        }
    }
    function delRow(){  
        let count_del = $("input[name=count3]").val();
        if(count_del == "") {
            $.alert({
                title: "Thông báo",
                content: "Vui lòng không để trống số dòng cần xoá",
            })
            return;
        }
        for(i = 0 ; i < count_del ; i++) {
            let page = $('[data-plus]').attr('data-plus');
            if(page < 0) {
                $('[data-plus]').attr('data-plus',0);
                return;
            }
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
    }
    function insAll(){
      let test = true;
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      let count = $('td input[name="c_code2"]').length;
      $('td input[name="c_code2"]').each(function(){
        if($(this).val() != ""){
          formData.append("c_code2[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="c_discount_percent2"]').each(function(){
        if($(this).val() != ""){
          formData.append("c_discount_percent2[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="c_if_subtotal_min2"]').each(function(){
        if($(this).val() != "") {
          formData.append("c_if_subtotal_min2[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="c_if_subtotal_max2"]').each(function(){
        if($(this).val() != "") {
          formData.append("c_if_subtotal_max2[]",$(this).val());
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="c_date_start2"]').each(function(){
        if($(this).val() != "") {
          let date2 = $(this).val().split(/\/|\-/);
          date2 = date2[2] + "-" + date2[1] + "-" + date2[0];
          formData.append("c_date_start2[]",date2);
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;  
        }
      });
      $('td input[name="c_date_end2"]').each(function(){
        if($(this).val() != "") {
          let date2 = $(this).val().split(/\/|\-/);
          date2 = date2[2] + "-" + date2[1] + "-" + date2[0];
          formData.append("c_date_end2[]",date2);
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      formData.append("token","<?php echo_token(); ?>");
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
      
    }
    function showRow(page,apply_dom = true){
      let count = $('[data-plus]').attr('data-plus');
      /*if(count == "") {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng không để trống số dòng thêm",
        })
        return;
      }
      if(count < 1) {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng nhập số dòng lớn hơn 
        })
        return;
      }*/
      limit = 7;
      if(apply_dom) {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
        $('#form-khuyen-mai2 table').remove();
        $('#form-khuyen-mai2 #paging').remove();
        let html = `
        <table class='table table-bordered' style="height:auto;">
          <thead>
            <tr>
                <th>Số thứ tự</th>
                <th>Mã khuyến mãi</th>
                <th>Khuyến mãi (%)</th>
                <th>Giá trị tối thiểu</th>
                <th>Giá trị tối đa</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày hết hạn</th>
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
                  <td><input class='kh-inp-ctrl' name='c_code2' type='text' value=''><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' name='c_discount_percent2' type='text' value=''><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' name='c_if_subtotal_min2' type='text' value=''><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' name='c_if_subtotal_max2' type='text' value=''><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' data-date='' name='c_date_start2' value=''><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' data-date='' name='c_date_end2' type='text' value=''><p class='text-danger'></p></td>
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
                <td><input class='kh-inp-ctrl' name='c_code2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='c_discount_percent2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='c_if_subtotal_min2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='c_if_subtotal_max2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' data-date='' name='c_date_start2' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' data-date='' name='c_date_end2' type='text' value=''><p class='text-danger'></p></td>
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
        $(html).appendTo('#form-khuyen-mai2');
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
      showPicker();
      $('#modal-xl2').on('hidden.bs.modal', function (e) {
        $('#form-khuyen-mai2 table tbody').remove();
        $('#form-khuyen-mai2 #paging').remove();
        $('input[name="count2"]').val("");
      })
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
<script>
    function toggleActiveCoupon(id,status) {
        let evt = $(event.currentTarget);
        $.ajax({
            url:window.location.href,
            type:"POST",
            data:{
                token: "<?php echo_token();?>",
                id: id,
                status: status 
            },
            success:function(data){
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    toastr["success"](data.success);
                    if(status == "Active") {
                        evt.attr('onchange',`toggleActiveCoupon(${id},"Deactive")`);
                    } else if(status == "Deactive") {
                        evt.attr('onchange',`toggleActiveCoupon(${id},"Active")`);
                    }
                }
            }
        })
    }
    $(document).ready(function (e) {
        $('.select-type2').select2();
        $.fn.dataTable.moment('DD-MM-YYYY');
        $('#first_tab').on('focus', function() {
            $('input[tabindex="1"].kh-inp-ctrl').first().focus();
        });
        $('#btn-role-fast').on('focus',function(){
            $('input[tabindex="<?=$cnt;?>"]').focus();
        });
        dt_coupon = $("#m-khuyen-mai").DataTable({
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
                    "targets": 9
                }, 
            ],
            "select": {
                style: 'multi+shift',
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
            "paging":true,
            "responsive": true, 
            "oColReorder": {
                "bAddFixed":false
            },
            "lengthChange": false, 
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
                    "filename": "danh_sach_nhan_vien_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu danh sách nhân viên trích xuất ngày <?=Date("d-m-Y",time());?>",
                    "exportOptions":{
                        columns: ':visible:not(.select-checkbox):not(.manipulate)'
                    },
                },{
                    "extend": "pdf",
                    "text": "PDF (3)",
                    "key": {
                        "key": '3',
                    },
                    "filename": "danh_sach_nhan_vien_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu danh sách nhân viên trích xuất ngày <?=Date("d-m-Y",time());?>",
                    "exportOptions":{
                        columns: ':visible:not(.select-checkbox):not(.manipulate)'
                    },
                },{
                    "extend": "csv",
                    "text": "CSV (4)",
                    "charset": 'UTF-8',
                    "bom": true,
                    "filename": "danh_sach_nhan_vien_ngay_<?=Date("d-m-Y",time());?>",
                    "key": {
                        "key": '4',
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
                
            ],
        });
        dt_coupon.buttons.exportData( {
            columns: ':visible'
        });
        dt_coupon.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                dt_coupon.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                dt_coupon.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function() {
            if (dt_coupon.rows({
                    selected: true
                }).count() !== dt_coupon.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });
        //
        // php auto select all rows when focus update all function execute
        //$upt_more == 1 ? 'dt_coupon.rows().select();' . PHP_EOL . '$("th.select-checkbox").addClass("selected");'.PHP_EOL  : "";
    });
</script>
<!--searching filter-->
<script>
   function choose_type_search(){
      let _option = $("select[name='search_option'] > option:selected").val();
      if(_option.indexOf("2") > -1) {
         if(_option.indexOf("all") > -1) {
            $(".s-all2").css({"display": "flex","flex-direction":"column"});
         } else {
            $(`#s-${_option}`).css({"display": "flex","flex-direction":"column"});
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
      let file_html = ``;
      if($(event.currentTarget).closest('#s-subtotal_min2').length) {
         file_html = `
            <div class="ele-select ele-subtotal_min22 mt-10">
               <div class="" style="display:flex;">
                  <input type="text" name="subtotal_min_1[]" placeholder="" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
               </div>
               <div class="ml-10" style="display:flex;">
                  <input type="text" name="subtotal_min_2[]" placeholder="" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
               </div>
               <span onclick="select_remove_child('.ele-subtotal_min2')" class="kh-select-child-remove"></span>
            </div>
         `
      } else if($(event.currentTarget).closest('#s-subtotal_max2').length) {
         file_html = `
            <div class="ele-select ele-subtotal_max2 mt-10">
               <div class="" style="display:flex;">
                  <input type="text" name="subtotal_max_1[]" placeholder="" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
               </div>
               <div class="ml-10" style="display:flex;">
                  <input type="text" name="subtotal_max_2[]" placeholder="" class="form-control" value="" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)">
               </div>
               <span onclick="select_remove_child('.ele-subtotal_max2')" class="kh-select-child-remove"></span>
            </div>
         `
      } else if($(event.currentTarget).closest('#s-date_start2').length) {
         file_html = `
         <div class="ele-select ele-date_start2 mt-10">
            <div class="" style="display:flex;">
               <input type="text" name="date_start_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
            </div>
            <div class="ml-10" style="display:flex;">
               <input type="text" name="date_start_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
            </div>
            <span onclick="select_remove_child('.ele-date_start2')" class="kh-select-child-remove"></span>
         </div>
         `;
      } else if($(event.currentTarget).closest('#s-date_end2').length) {
         file_html = `
         <div class="ele-select ele-date_end2 mt-10">
            <div class="" style="display:flex;">
               <input type="text" name="date_end_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
            </div>
            <div class="ml-10" style="display:flex;">
               <input type="text" name="date_end_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
            </div>
            <span onclick="select_remove_child('.ele-date_end2')" class="kh-select-child-remove"></span>
         </div>
         `;
      } else if($(event.currentTarget).closest('#s-date_created_at2').length) {
         file_html = `
         <div class="ele-select ele-date_created_at2 mt-10">
            <div class="" style="display:flex;">
               <input type="text" name="date_created_at_1[]" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="">
            </div>
            <div class="ml-10" style="display:flex;">
               <input type="text" name="date_created_at_2[]" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="">
            </div>
            <span onclick="select_remove_child('.ele-date_created_at2')" class="kh-select-child-remove"></span>
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
                    console.log(dateText.split("-"));
                    dateText = dateText.split("-");
                    $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
            }
        });
      $('.select-type2').select2();
   });
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
</script>
<script>
    $(document).ready(function(){
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
        // validate
        const validate = () => {
            let test = true;
            let coupon_code = $("input[name='coupon_code']").val();
            let coupon_content = $("textarea[name='coupon_content']").val();
            let coupon_discount_percent = $("input[name='coupon_discount_percent']").val();
            let coupon_if_subtotal_min = $("input[name='coupon_if_subtotal_min']").val();
            let coupon_if_subtotal_max = $("input[name='coupon_if_subtotal_min']").val();
            let coupon_date_start = $("input[name='coupon_date_start']").val();
            let coupon_date_end = $("input[name='coupon_date_end']").val();
            let id = $("input[name='id']").val();
            $('.coupon-validate').text("");
            if(coupon_code == "") {
                $('#coupon_code_err').text("Vui lòng không để trống mã khuyến mãi");
                test = false;
            } if(coupon_content == "") {
                $('#coupon_content_err').text("Vui lòng không để trống nội dung mô tả khuyến mãi");
                test = false;    
            } if(coupon_discount_percent == "") {
                $('#coupon_discount_percent_err').text("Vui lòng không để trống số phần trăm giảm giá");
                test = false;
            } if(coupon_if_subtotal_min == "") {
                $('#coupon_if_subtotal_min_err').text("Vui lòng không để trống điều kiện tổng tiền hoá đơn tối thiểu");
                test = false;
            } if(coupon_if_subtotal_max == "") {
                $('#coupon_if_subtotal_max_err').text("Vui lòng không để trống điều kiện tổng tiền hoá đơn tối đa");
                test = false;
            } if(coupon_date_start == "") {
                $('#coupon_date_start_err').text("Vui lòng không để trống thời gian bắt đầu");
                test = false;
            } if(coupon_date_end == "") {
                $('#coupon_date_end_err').text("Vui lòng không để trống thời gian kết thúc");
                test = false;
            }
            return test;
        };
        // show image
        const readURL = (input) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                $('#display-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        };
        // mở modal thêm dữ liệu
        var click_number;
        $(document).on('click','#btn-them-khuyen-mai',(e) => {
            $('#form-khuyen-mai').load("ajax_coupon_manage.php?status=Insert",() => {
                $('#modal-xl').modal({backdrop: 'static', keyboard: false});
                $(".is-date-coupon-begin").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText,inst) {
                        console.log(dateText.split("-"));
                        dateText = dateText.split("-");
                        $('.is-date-coupon-start').attr('data-coupon-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                    }
                });
                $(".is-date-coupon-end").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText,inst) {
                        console.log(dateText.split("-"));
                        dateText = dateText.split("-");
                        $('.is-date-coupon-end').attr('data-coupon-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                    }
                });
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
            })
        });
        // mở modal sửa dữ liệu
        $(document).on('click','.btn-sua-khuyen-mai',function(e) {  
            let id = $(e.currentTarget).attr('data-id');
            $('#form-khuyen-mai').load("ajax_coupon_manage.php?status=Update&id=" + id,() => {
                $('#modal-xl').modal({backdrop: 'static', keyboard: false});
                $(".is-date-coupon-start").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText,inst) {
                        console.log(dateText.split("-"));
                        dateText = dateText.split("-");
                        $('.is-date-coupon-start').attr('data-coupon-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                    }
                });
                $(".is-date-coupon-end").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText,inst) {
                        console.log(dateText.split("-"));
                        dateText = dateText.split("-");
                        $('.is-date-coupon-end').attr('data-coupon-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                    }
                });
            })
        });
        // thêm 
        $(document).on('click','#btn-insert',function(e){
            event.preventDefault();
            let test = true;
            let coupon_code = $("input[name='coupon_code']").val();
            let coupon_content = $("textarea[name='coupon_content']").val();
            let coupon_discount_percent = $("input[name='coupon_discount_percent']").val();
            let coupon_if_subtotal_min = $("input[name='coupon_if_subtotal_min']").val();
            let coupon_if_subtotal_max = $("input[name='coupon_if_subtotal_max']").val();
            let coupon_date_start = $("input[name='coupon_date_start']").attr('data-coupon-date');
            let coupon_date_end = $("input[name='coupon_date_end']").attr('data-coupon-date');
            let id = $("input[name='id']").val();
            $('.coupon-validate').text("");
            if(coupon_code == "") {
                $('#coupon_code_err').text("Vui lòng không để trống mã khuyến mãi");
                test = false;
            } if(coupon_content == "") {
                $('#coupon_content_err').text("Vui lòng không để trống nội dung mô tả khuyến mãi");
                test = false;    
            } if(coupon_discount_percent == "") {
                $('#coupon_discount_percent_err').text("Vui lòng không để trống số phần trăm giảm giá");
                test = false;
            } if(coupon_if_subtotal_min == "") {
                $('#coupon_if_subtotal_min_err').text("Vui lòng không để trống điều kiện tổng tiền hoá đơn tối thiểu");
                test = false;
            } if(coupon_if_subtotal_max == "") {
                $('#coupon_if_subtotal_max_err').text("Vui lòng không để trống điều kiện tổng tiền hoá đơn tối đa");
                test = false;
            } if(coupon_date_start == "") {
                $('#coupon_date_start_err').text("Vui lòng không để trống thời gian bắt đầu");
                test = false;
            } if(coupon_date_end == "") {
                $('#coupon_date_end_err').text("Vui lòng không để trống thời gian kết thúc");
                test = false;
            }
            if(test) {
                $.ajax({
                    url:window.location.href,
                    type: "POST",
                    data: {
                        "status":"Insert",
                        "token":"<?php echo_token();?>",
                        "coupon_code" : coupon_code,
                        "coupon_content" : coupon_content,
                        "coupon_discount_percent": coupon_discount_percent,
                        "coupon_if_subtotal_min": coupon_if_subtotal_min,
                        "coupon_if_subtotal_max": coupon_if_subtotal_max,
                        "coupon_date_start": coupon_date_start,
                        "coupon_date_end": coupon_date_end
                    },
                    success:function(data){
                        data = JSON.parse(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.href="coupon_manage.php";
                                    },
                                }
                            });
                        } else {
                            $.alert({
                                title: "Thông báo",
                                content: data.error
                            });
                        }
                        $('#modal-xl').modal('hide');
                    },
                    error:function(data) {
                        console.log("Error:",data);
                    }
                });
            }
            
        });
        // sửa 
        $(document).on('click','#btn-update',function(e){
            event.preventDefault();
            let test = true;
            let id = $("input[name='id']").val();
            let coupon_code = $("input[name='coupon_code']").val();
            let coupon_content = $("textarea[name='coupon_content']").val();
            let coupon_discount_percent = $("input[name='coupon_discount_percent']").val();
            let coupon_if_subtotal_min = $("input[name='coupon_if_subtotal_min']").val();
            let coupon_if_subtotal_max = $("input[name='coupon_if_subtotal_max']").val();
            let coupon_date_start = $("input[name='coupon_date_start']").attr('data-coupon-date');
            let coupon_date_end = $("input[name='coupon_date_end']").attr('data-coupon-date');
            $('.coupon-validate').text("");
            if(coupon_code == "") {
                $('#coupon_code_err').text("Vui lòng không để trống mã khuyến mãi");
                test = false;
            } if(coupon_content == "") {
                $('#coupon_content_err').text("Vui lòng không để trống nội dung mô tả khuyến mãi");
                test = false;    
            } if(coupon_discount_percent == "") {
                $('#coupon_discount_percent_err').text("Vui lòng không để trống số phần trăm giảm giá");
                test = false;
            } if(coupon_if_subtotal_min == "") {
                $('#coupon_if_subtotal_min_err').text("Vui lòng không để trống điều kiện tổng tiền hoá đơn tối thiểu");
                test = false;
            } if(coupon_if_subtotal_max == "") {
                $('#coupon_if_subtotal_max_err').text("Vui lòng không để trống điều kiện tổng tiền hoá đơn tối đa");
                test = false;
            } if(coupon_date_start == "") {
                $('#coupon_date_start_err').text("Vui lòng không để trống thời gian bắt đầu");
                test = false;
            } if(coupon_date_end == "") {
                $('#coupon_date_end_err').text("Vui lòng không để trống thời gian kết thúc");
                test = false;
            }
            if(test) {
                $.ajax({
                    url:window.location.href,
                    type: "POST",
                    data: {
                        "id": id,
                        "status":"Update",
                        "token":"<?php echo_token();?>",
                        "coupon_code" : coupon_code,
                        "coupon_content" : coupon_content,
                        "coupon_discount_percent": coupon_discount_percent,
                        "coupon_if_subtotal_min": coupon_if_subtotal_min,
                        "coupon_if_subtotal_max": coupon_if_subtotal_max,
                        "coupon_date_start": coupon_date_start,
                        "coupon_date_end": coupon_date_end
                    },
                    success:function(data){
                        data = JSON.parse(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.href="coupon_manage.php";
                                    },
                                }
                            });
                        } else {
                            $.alert({
                                title: "Thông báo",
                                content: data.error
                            });
                        }
                        $('#modal-xl').modal('hide');
                    },
                    error:function(data) {
                        console.log("Error:",data);
                    }
                });
            }
        });
        // xoá 
        $(document).on('click','.btn-xoa-khuyen-mai',function(e){
            /*click_number = $(this).closest('tr');
            console.log(click_number);*/
            let id = $(e.currentTarget).attr('data-id');
            let target = $(e.currentTarget);
            target.closest("tr").addClass("bg-color-selected");
            $.confirm({
                title: 'Thông báo',
			    content: 'Bạn có chắc chắn muốn xoá mã khuyến mãi này ?',
                buttons: {
                    Có: function(){
                        $.ajax({
                            url:window.location.href,
                            type:"POST",
                            cache:false,
                            data: {
                                token: "<?php echo_token();?>",
                                id: id,
                                status: "Delete",
                            },
                            success:function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok") {
                                    $.alert({
                                        title: "Thông báo",
                                        content: data.success,
                                        buttons: {
                                            "Ok": function(){
                                                location.reload();
                                            },
                                        }
                                    });
                                    //$('#coupon-' + id).remove();
                                    /*console.log(click_number);
                                    dt_coupon.row(click_number).remove().draw();*/
                                } else {
                                    $.alert({
                                        title: "Thông báo",
                                        content: data.error,
                                    });
                                }
                            },
                            error:function(data) {
                                console.log("Error:",data);
                            }
                        });
                    },Không: function(){
                        target.closest("tr").removeClass("bg-color-selected");
                    }
                }
            });
        });
        // xem
        $(document).on('click','.btn-xem-khuyen-mai',function(e){
            let id = $(e.currentTarget).attr('data-id');
            let target = $(e.currentTarget);
           // target.closest("tr").addClass("bg-color-selected");
            $('#form-khuyen-mai').load("ajax_coupon_manage.php?status=Read&id=" + id,() => {
                $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            })
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
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $coupon_code = isset($_REQUEST['coupon_code']) ? $_REQUEST['coupon_code'] : null;
        $coupon_content = isset($_REQUEST['coupon_content']) ? $_REQUEST['coupon_content'] : null;
        $coupon_discount_percent = isset($_REQUEST['coupon_discount_percent']) ? $_REQUEST['coupon_discount_percent'] : null;
        $coupon_if_subtotal_min = isset($_REQUEST['coupon_if_subtotal_min']) ? $_REQUEST['coupon_if_subtotal_min'] : null;
        $coupon_if_subtotal_max = isset($_REQUEST['coupon_if_subtotal_max']) ? $_REQUEST['coupon_if_subtotal_max'] : null;
        $coupon_date_start = isset($_REQUEST['coupon_date_start']) ? $_REQUEST['coupon_date_start'] : null;
        $coupon_date_end = isset($_REQUEST['coupon_date_end']) ? $_REQUEST['coupon_date_end'] : null;
        // code to be executed post method
        if($status == "Insert") {
            $sql_check_duplicate_coupon_code = "select count(*) as 'cnt' from coupon where coupon_code = '$coupon_code'";
            $count = fetch(sql_query($sql_check_duplicate_coupon_code));
            if($count['cnt'] > 0) {
                echo_json(["msg" => "not_ok","error" => "Mã khuyến mãi đã tồn tại"]);    
            }
            $sql_ins = "Insert into coupon(coupon_code,coupon_content,coupon_discount_percent,coupon_if_subtotal_min,coupon_if_subtotal_max,coupon_date_start,coupon_date_end) values ('$coupon_code','$coupon_content','$coupon_discount_percent','$coupon_if_subtotal_min','$coupon_if_subtotal_max','$coupon_date_start','$coupon_date_end')";
            sql_query($sql_ins);
            echo_json(["msg" => "ok","success" => "Bạn đã thêm dữ liệu thành công"]);
        } else if($status == "Update") {
            $sql_check_duplicate_coupon_code = "select count(*) as 'cnt' from coupon where coupon_code = '$coupon_code'";
            $count = fetch(sql_query($sql_check_duplicate_coupon_code));
            if($count['cnt'] > 1) {
                echo_json(["msg" => "not_ok","error" => "Mã khuyến mãi đã tồn tại"]);    
            }
            $sql_upt = "Update coupon set coupon_code = '$coupon_code',coupon_content = '$coupon_content',coupon_discount_percent = '$coupon_discount_percent',coupon_if_subtotal_min = '$coupon_if_subtotal_min',coupon_if_subtotal_max = '$coupon_if_subtotal_max',coupon_date_start = '$coupon_date_start',coupon_date_end = '$coupon_date_end' where id = '$id'";
            sql_query($sql_upt);
            echo_json(["msg" => "ok","success" => "Bạn đã sửa dữ liệu thành công"]);
        } else if($status == "Delete") {
            $sql_del = "Update coupon set is_delete = 1 where id = '$id'";
            sql_query($sql_del);
            echo_json(["msg" => "ok","success" => "Bạn đã xoá dữ liệu thành công"]);
        } else if($status == "Active") {
            $sql_active_coupon = "Update coupon set is_active = 1 where id = $id";
            sql_query($sql_active_coupon);
            echo_json(["msg" => "ok","success" => "Bạn đã kích hoạt mã khuyến mãi thành công"]);
        } else if($status == "Deactive") {
            $sql_deactive_coupon = "Update coupon set is_active = 0 where id = $id";
            sql_query($sql_deactive_coupon);
            echo_json(["msg" => "ok","success" => "Bạn đã huỷ kích hoạt mã khuyến mãi thành công"]);
        } else if($status == "del_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $sql_del_more = "Update coupon set is_delete = 1 where id in ($rows)";
            sql_query($sql_del_more);
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_more") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $c_code2 = isset($_REQUEST["c_code2"]) ? $_REQUEST["c_code2"] : null;
            $c_discount_percent2 = isset($_REQUEST["c_discount_percent2"]) ? $_REQUEST["c_discount_percent2"] : null;
            $c_if_subtotal_min2 = isset($_REQUEST["c_if_subtotal_min2"]) ? $_REQUEST["c_if_subtotal_min2"] : null;
            $c_if_subtotal_max2 = isset($_REQUEST["c_if_subtotal_max2"]) ? $_REQUEST["c_if_subtotal_max2"] : null;
            $c_date_start2 = isset($_REQUEST["c_date_start2"]) ? $_REQUEST["c_date_start2"] : null;
            $c_date_end2 = isset($_REQUEST["c_date_end2"]) ? Date('Y-m-d',strtotime($_REQUEST["c_date_end2"])) : null;
            $sql = "Insert into coupon(coupon_code,coupon_discount_percent,coupon_if_subtotal_min,coupon_if_subtotal_max,coupon_date_start,coupon_date_end) values('$c_code2','$c_discount_percent2','$c_if_subtotal_min2','$c_if_subtotal_max2','$c_date_start2','$c_date_end2')";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $c_code2 = isset($_REQUEST["c_code2"]) ? $_REQUEST["c_code2"] : null;
            $c_discount_percent2 = isset($_REQUEST["c_discount_percent2"]) ? $_REQUEST["c_discount_percent2"] : null;
            $c_if_subtotal_min2 = isset($_REQUEST["c_if_subtotal_min2"]) ? $_REQUEST["c_if_subtotal_min2"] : null;
            $c_if_subtotal_max2 = isset($_REQUEST["c_if_subtotal_max2"]) ? $_REQUEST["c_if_subtotal_max2"] : null;
            $c_date_start2 = isset($_REQUEST["c_date_start2"]) ? $_REQUEST["c_date_start2"] : null;
            $c_date_end2 = isset($_REQUEST["c_date_end2"]) ? $_REQUEST["c_date_end2"]  : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {
                    $sql = "Insert into coupon(coupon_code,coupon_discount_percent,coupon_if_subtotal_min,coupon_if_subtotal_max,coupon_date_start,coupon_date_end) values('$c_code2[$i]','$c_discount_percent2[$i]','$c_if_subtotal_min2[$i]','$c_if_subtotal_max2[$i]','$c_date_start2[$i]','$c_date_end2[$i]')";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "upt_more") {
            $c_id = isset($_REQUEST["c_id"]) ? $_REQUEST["c_id"] : null;
            $c_code = isset($_REQUEST["c_code"]) ? $_REQUEST["c_code"] : null;
            $c_discount_percent = isset($_REQUEST["c_discount_percent"]) ? $_REQUEST["c_discount_percent"] : null;
            $c_if_subtotal_min = isset($_REQUEST["c_if_subtotal_min"]) ? $_REQUEST["c_if_subtotal_min"] : null;
            $c_if_subtotal_max = isset($_REQUEST["c_if_subtotal_max"]) ? $_REQUEST["c_if_subtotal_max"] : null;
            $c_date_start = isset($_REQUEST["c_date_start"]) ? $_REQUEST["c_date_start"] : null;
            $c_date_end = isset($_REQUEST["c_date_end"]) ? $_REQUEST["c_date_end"] : null;
            $c_if_subtotal_min = str_replace(".","",$c_if_subtotal_min);
            $c_if_subtotal_max = str_replace(".","",$c_if_subtotal_max);
            $sql = "Update coupon set coupon_code='$c_code',coupon_discount_percent='$c_discount_percent',coupon_if_subtotal_min='$c_if_subtotal_min',coupon_if_subtotal_max='$c_if_subtotal_max',coupon_date_start='$c_date_start',coupon_date_end='$c_date_end' where id='$c_id'";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "upt_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $c_id = isset($_REQUEST["c_id"]) ? $_REQUEST["c_id"] : null;
            $c_code = isset($_REQUEST["c_code"]) ? $_REQUEST["c_code"] : null;
            $c_discount_percent = isset($_REQUEST["c_discount_percent"]) ? $_REQUEST["c_discount_percent"] : null;
            $c_if_subtotal_min = isset($_REQUEST["c_if_subtotal_min"]) ? $_REQUEST["c_if_subtotal_min"] : null;
            $c_if_subtotal_max = isset($_REQUEST["c_if_subtotal_max"]) ? $_REQUEST["c_if_subtotal_max"] : null;
            $c_date_start = isset($_REQUEST["c_date_start"]) ? $_REQUEST["c_date_start"] : null;
            $c_date_end = isset($_REQUEST["c_date_end"]) ? $_REQUEST["c_date_end"]  : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {
                    $c_if_subtotal_min[$i] = str_replace(".","",$c_if_subtotal_min[$i]);
                    $c_if_subtotal_max[$i] = str_replace(".","",$c_if_subtotal_max[$i]);
                    $sql = "Update coupon set coupon_code='$c_code[$i]',coupon_discount_percent='$c_discount_percent[$i]',coupon_if_subtotal_min='$c_if_subtotal_min[$i]',coupon_if_subtotal_max='$c_if_subtotal_max[$i]',coupon_date_start='$c_date_start[$i]',coupon_date_end='$c_date_end[$i]' where id = '$c_id[$i]'";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        }
    }
?>