<?php
    include_once("../lib/database_v2.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $allow_read = $allow_update = $allow_delete = $allow_insert = true;
        //
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        //
        $is_active = isset($_REQUEST['is_active']) ? $_REQUEST['is_active'] : null;

        //
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        //
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        //
        $product_type_id = isset($_REQUEST['product_type_id']) ? $_REQUEST['product_type_id'] : null;
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
                            array_push($wh_child,"(lower(code) like lower('%$key%') or lower(content) like lower('%$key%') or lower(discount_percent) like lower('%$key%') or lower(if_subtotal_min) like lower('%$key%')
                            or lower(if_subtotal_max) like lower('%$key%') or lower(date_start) like lower('%$key%') or lower(date_end) like lower('%$key%') or lower(created_at) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "code") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(code) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "content") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(content) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "discount_percent") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(discount_percent) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "if_subtotal_min") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(if_subtotal_min) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "if_subtotal_max") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(if_subtotal_max) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "date_start") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(date_start) like lower('%$key%'))");
                        }
                    }
                } else if($search_option == "date_end") {
                    foreach($keyword as $key) {
                        if($key != "") {
                            array_push($wh_child,"(lower(date_start) like lower('%$key%'))");
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
        // thời gian bắt đầu
        if($date_start_1 && is_array($date_start_1) && $date_start_2 && is_array($date_start_2)) {
            $wh_child = [];
            foreach(array_combine($date_start_1,$date_start_2) as $d_start_1 => $d_start_2) {
                if($d_start_1 != "" && $d_start_2 != "") {
                    $d_start_1 = Date("Y-m-d",strtotime($d_start_1));
                    $d_start_2 = Date("Y-m-d",strtotime($d_start_2));
                    array_push($wh_child,"(date_start >= '$d_start_1 00:00:00' and date_start <= '$d_start_2 23:59:59')");
                } else if($d_start_1 != "" && $d_start_2 == "") {
                    $d_start_1 = Date("Y-m-d",strtotime($d_start_1));
                    array_push($wh_child,"(date_start >= '$d_start_1 00:00:00')");
                } else if($d_start_1 == "" && $d_start_2 != "") {
                    $d_start_2 = Date("Y-m-d",strtotime($d_start_2));
                    array_push($wh_child,"(date_start <= '$d_start_2 23:59:59')");
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
                    array_push($wh_child,"(date_end >= '$d_end_1 00:00:00' and date_end <= '$d_end_2 23:59:59')");
                } else if($d_end_1 != "" && $d_end_2 == "") {
                    $d_end_1 = Date("Y-m-d",strtotime($d_end_1));
                    array_push($wh_child,"(date_end >= '$d_end_1 00:00:00')");
                } else if($d_end_1 == "" && $d_end_2 != "") {
                    $d_end_2 = Date("Y-m-d",strtotime($d_end_2));
                    array_push($wh_child,"(date_end <= '$d_end_2 23:59:59')");
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
?>
<link rel="stylesheet" href="css/toastr.min.css">
<!--html & css section start-->
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
   table.dataTable tr th.select-checkbox.selected::after {
      content: "\2713";
      margin-top: -11px;
      margin-left: -4px;
      text-align: center;
      color: #9900ff;
   }
   .sort-asc,.sort-desc {
        display: none;
    }
</style>

<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid" style="padding:0px;">
    <section class="content">
        <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý danh mục khuyến mãi</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        <div class="input-group-append">
                           <button id="btn-them-khuyen-mai" class="dt-button button-blue">
                              Tạo danh mục khuyến mãi
                           </button>
                        </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div id="load-all">
                        <link rel="stylesheet" href="css/tab.css">             
                        <div style="padding-right:0px;padding-left:0px;" class="col-12 mb-20 d-flex a-center j-between">
                            <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                                <?php
                                    $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                    $_SESSION['category_discount_manage_tab'] = isset($_SESSION['category_discount_manage_tab']) ? $_SESSION['category_discount_manage_tab'] : [];
                                    $_SESSION['category_discount_tab_id'] = isset($_SESSION['category_discount_tab_id']) ? $_SESSION['category_discount_tab_id'] : 0;
                                ?>
                                <!--<li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('category_discount_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>-->
                                <?php
                                    $ik = 0;
                                    $is_active = false;
                                    if(count($_SESSION['category_discount_manage_tab']) > 0) {
                                        foreach($_SESSION['category_discount_manage_tab'] as $tab) {
                                        if($tab['tab_unique'] == $tab_unique) {
                                            $_SESSION['category_discount_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                        }
                                ?>
                                    <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                        <button onclick="loadDataInTab('<?=$_SESSION['category_discount_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
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
                            <div class="col-12" style="padding-right:0px;padding-left:0px;">
                                <form style="" autocomplete="off" action="category_discount_manage.php" method="get">
                                    <div class="d-flex a-start">
                                        <div class="" style="margin-top:5px;">
                                            <select onchange="choose_type_search()" class="form-control" name="search_option">
                                                <option value="">Bộ lọc tìm kiếm</option>
                                                <option value="keyword" <?=$search_option == 'keyword' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                                                <option value="product_type_id2" <?=$search_option == 'product_type_id2' ? 'selected="selected"' : '' ?>>Danh mục sản phẩm</option>
                                                <option value="date_start2" <?=$search_option == 'date_start2' ? 'selected="selected"' : '' ?>>Phạm vi ngày bắt đầu</option>
                                                <option value="date_end2" <?=$search_option == 'date_end2' ? 'selected="selected"' : '' ?>>Phạm vi ngày kết thúc</option>
                                                <option value="date_created_at2" <?=$search_option == 'date_created_at2' ? 'selected="selected"' : '' ?>>Phạm vi ngày tạo</option>
                                                <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                                        <button type="submit" class="btn btn-default ml-10" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                                    </div>
                                    <div class="d-flex a-start mt-10">
                                        <div id="s-cols" class="k-select-opt s-all2" style="width:15%;<?=$keyword && $keyword != [""] ? "display:flex;flex-direction: column;": "display:none;";?>">
                                        <span class="k-select-opt-remove"></span>
                                        <span class="k-select-opt-ins"></span>
                                        <div class="ele-cols d-flex f-column">
                                            <select name="search_option" class="form-control mb-10">
                                                <option value="">Chọn cột tìm kiếm</option>
                                                <option value="product_type_id" <?=$search_option == 'product_type_id' ? 'selected="selected"' : '' ?>>Danh mục sản phẩm</option>
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
                                            <option value="product_type_id" <?=$orderByColumn == "product_type_id" ? "selected" : "";?>>Danh mục khuyến mãi</option>
                                            <option value="discount_percent" <?=$orderByColumn == "discount_percent" ? "selected" : "";?>>Khuyến mãi (%)</option>
                                            <option value="date_start" <?=$orderByColumn == "date_start" ? "selected" : "";?>>Ngày bắt đầu</option>
                                            <option value="date_end" <?=$orderByColumn == "date_end" ? "selected" : "";?>>Ngày kết thúc</option>
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
                            <table id="m-danh-muc-khuyen-mai" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="cursor:pointer;">
                                        <th style="width:20px !important;">
                                            <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                        </th>
                                        <th class="th-so-thu-tu w-120">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                        <th class="th-danh-muc-khuyen-mai w-300">Danh mục khuyến mãi <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                        <th class="th-khuyen-mai w-150">Khuyến mãi (%) <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                        <th class="th-ngay-bat-dau w-120">Ngày bắt đầu <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                        <th class="th-ngay-het-han w-120">Ngày hết hạn <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                        <th class="w-100">Tình trạng</th>
                                        <th class="w-120 th-ngay-tao">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                        <th class="w-200">Thao tác</th>
                                    </tr>
                                </thead>
                                <?php
                                $get = $_GET;
                                unset($get['page']);
                                $str_get = http_build_query($get);
                                // query
                                $cnt = 0;
                                $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                                $limit = $_SESSION['paging'];
                                $start_page = $limit * ($page - 1);
                                $sql_get_total = "select count(*) as 'countt' from product_type_discount $where";
                                $total = fetch(sql_query($sql_get_total))['countt'];
                                $sql_get_product = "select * from product_type_discount n $where limit $start_page,$limit";
                                ?>
                                <tbody dt-parent-id dt-url="<?=$str_get;?>" dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-category-discount" id="list-khuyen-mai">
                                <?php
                                $rows = fetch_all(sql_query($sql_get_product));
                                foreach($rows as $row) {
                                ?>
                                    <tr id="<?=$row["id"];?>">
                                        <td>
                                            <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-category-discount')" type="checkbox" name="check_id<?=$row["id"];?>">
                                        </td>
                                        <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                                        <td class="danh-muc-khuyen-mai">
                                            <nav id="breadcrumb-menu2" class="" aria-label="breadcrumb">
                                                <?=generate_breadcrumb_menus($row['product_type_id']);?>
                                            </nav>
                                        </td>
                                        <td class="khuyen-mai"><?=$upt_more == 1 ? "<input name='ptd_discount_percent' class='form-control' type='text' value='" .$row['discount_percent']."'>" : $row['discount_percent'];?></td>
                                        <td class="ngay-bat-dau">
                                            <?php
                                                if($upt_more == 1) {
                                            ?>
                                            <?=$row['date_start'] ? "<input name='ptd_date_start' class='form-control kh-datepicker2' type='text' value='" . Date("d-m-Y",strtotime($row['date_start'])) ."'>" : "";?> 
                                            <?php } else { ?>
                                            <?=$row['date_start'] ? Date("d-m-Y",strtotime($row['date_start'])) : "";?>
                                            <?php }?>
                                        </td>
                                        <td class="ngay-het-han">
                                            <?php
                                                if($upt_more == 1) {
                                            ?>
                                            <?=$row['date_end'] ? "<input name='ptd_date_end' class='form-control kh-datepicker2' type='text' value='" . Date("d-m-Y",strtotime($row['date_end'])) ."'>" : "";?> 
                                            <?php } else {?>
                                            <?=$row['date_end'] ? Date("d-m-Y",strtotime($row['date_end'])) : "";?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" onchange="toggleActive('<?=$row['id']?>','<?= $row['is_active'] == 1 ? 'Deactive' : 'Active';?>')" class="custom-control-input" id="customSwitches<?=$row['id'];?>" <?= $row['is_active'] == 1 ? "checked" : "";?>>
                                                <label class="custom-control-label" for="customSwitches<?=$row['id'];?>"></label>
                                            </div>  
                                        </td>
                                        <td class="ngay-tao"><?=$row['created_at'] ? Date("d-m-Y",strtotime($row['created_at'])) : "";?></td>
                                        <td>
                                            <?php
                                            if($upt_more == 1) {
                                            ?>
                                                <button onclick="uptMore2()" dt-count="0" class=" dt-button button-green"
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
                                        <th style="width:20px !important;">
                                            <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                        </th>
                                        <th>Số thứ tự</th>
                                        <th>Danh mục khuyến mãi</th>
                                        <th>Khuyến mãi (%)</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Tình trạng</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <ul id="pagination" style="justify-content:center;display:flex;" class="pagination">
                                
                            </ul>
                        </div>
                  </div>
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
        <h4 id="msg-del" class="modal-title">Thông tin danh mục khuyến mãi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-danh-muc-khuyen-mai" method="post" enctype='multipart/form-data'>
            
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2">
  <div class="modal-dialog modal-xl" style="min-width:1650px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thêm dữ liệu danh mục khuyến mãi nhanh</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="form-danh-muc-khuyen-mai2" class="modal-body">
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
                            <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Danh mục khuyến mãi','Khuyến mãi (%)','Ngày bắt đầu','Ngày hết hạn'],['product_type_id','ptd_discount_percent2','ptd_date_start2','ptd_date_end2'])">
                        </div>
                        <div class="file file-excel mr-10">
                            <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Danh mục khuyến mãi','Khuyến mãi (%)','Ngày bắt đầu','Ngày hết hạn'],['product_type_id','ptd_discount_percent2','ptd_date_start2','ptd_date_end2'])">
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
                    <th>Danh mục khuyến mãi</th>
                    <th>Khuyến mãi (%)</th>
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

<!--js section start-->
<?php
    include_once("include/dt_script.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script src="js/toastr.min.js"></script>
<script src="js/khoi_all.js"></script>

<!--js section end-->
<script>
    setSortTable();    
</script>
<!--js section start-->
<script>
    function uptMore2(){
        let test = true;
        let ptd_discount_percent = $(event.currentTarget).closest("tr").find("td input[name='ptd_discount_percent']").val();
        let ptd_date_start = $(event.currentTarget).closest("tr").find("td input[name='ptd_date_start']").val();
        let ptd_date_end = $(event.currentTarget).closest("tr").find("td input[name='ptd_date_end']").val();
        let ptd_id = $(event.currentTarget).attr('data-id');
        let this2 = $(event.currentTarget).closest("tr");
        if(ptd_discount_percent == "") {
            test = false;
            this2.find("td input[name='ptd_discount_percent']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td input[name='ptd_discount_percent']").siblings("span.text-danger").text("");
        }
        if(ptd_date_start == "") {
            test = false;
            this2.find("td input[name='ptd_date_start']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td input[name='ptd_date_start']").siblings("span.text-danger").text("");
        }
        if(ptd_date_end == "") {
            test = false;
            this2.find("td input[name='ptd_date_end']").siblings("span.text-danger").text("Không được để trống");
        } else {
            this2.find("td input[name='ptd_date_end']").siblings("span.text-danger").text("");
        }

        if(ptd_id == "") {
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
                    ptd_discount_percent: ptd_discount_percent,
                    ptd_date_start: ptd_date_start,
                    ptd_date_end: ptd_date_end,
                    ptd_id : ptd_id,
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
        let _data = dt_ptd.rows(".selected").select().data();
        if(_data.length == 0) {
            $.alert({
                title:"Thông báo",
                content:"Vui lòng chọn dòng cần lưu",
            });
            return;
        }
        for(i = 0 ; i < _data.length ; i++) {
            formData.append("ptd_id[]",_data[i].DT_RowId);
        }
        $('tr.selected input[name="ptd_product_type_id"]').each(function(){
            if($(this).val() != "") {
                formData.append("ptd_product_type_id[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="ptd_discount_percent"]').each(function(){
            if($(this).val() != "") {
                formData.append("ptd_discount_percent[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="ptd_date_start"]').each(function(){
            if($(this).val() != "") {
                let ptd_date_start = $(this).val().split("-");
                ptd_date_start = ptd_date_start[2] + "-" + ptd_date_start[1] + "-" + ptd_date_start[0];
                formData.append("ptd_date_start[]",ptd_date_start);
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="ptd_date_end"]').each(function(){
            if($(this).val() != "") {
                let ptd_date_end = $(this).val().split("-");
                ptd_date_end = ptd_date_end[2] + "-" + ptd_date_end[1] + "-" + ptd_date_end[0];
                formData.append("ptd_date_end[]",ptd_date_end);
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
        });
        if(test) {
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
        $('input[name^="ptd_date"]').datepicker({
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
        let product_type_id = $(event.currentTarget).closest('tr').find('td input[name="product_type_id"]').val();
        let ptd_discount_percent2 = $(event.currentTarget).closest('tr').find('td input[name="ptd_discount_percent2"]').val();
        let ptd_date_start2 = $(event.currentTarget).closest('tr').find('td input[name="ptd_date_start2"]').val();
        let ptd_date_end2 = $(event.currentTarget).closest('tr').find('td input[name="ptd_date_end2"]').val();
        if(product_type_id == "") {
            this2.find('td input[name="product_type_id"]').siblings("p.text-danger").text("Không được để trống");
            test = false;
        } else {
            this2.find('td input[name="product_type_id"]').siblings("p.text-danger").text("");
        }

        if(ptd_discount_percent2 == "") {
            this2.find('td input[name="ptd_discount_percent2"]').siblings("p.text-danger").text("Không được để trống");
            test = false;
        } else {
            this2.find('td input[name="ptd_discount_percent2"]').siblings("p.text-danger").text("");
        }

        if(ptd_date_start2 == "") {
            test = false;
            this2.find('td input[name="ptd_date_start2"]').siblings("p.text-danger").text("Không được để trống");
        } else {
            this2.find('td input[name="ptd_date_start2"]').siblings("p.text-danger").text("");
        }

        if(ptd_date_end2 == "") {
            test = false;
            this2.find('td input[name="ptd_date_end2"]').siblings("p.text-danger").text("Không được để trống");
        } else {
            this2.find('td input[name="ptd_date_end2"]').siblings("p.text-danger").text("");
        }
        if(test) {
            let formData = new FormData();
            formData.append("product_type_id",product_type_id);
            formData.append("ptd_discount_percent2",ptd_discount_percent2);
            formData.append("ptd_date_start2",ptd_date_start2);
            formData.append("ptd_date_end2",ptd_date_end2);
            formData.append("status","ins_more");
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
                    <td>
                        <div style="display:flex;flex-direction:column;position:relative;">
                            <ul tabindex="1" class="col-md-12 ul_menu" style="padding-left:0px;height: 65px;outline:none !important;z-index: ${count_row_z_index--};" id="menu">
                                <li class="parent" style="border: 1px solid #dce1e5;">
                                    <a href="#">Chọn danh mục</a>
                                    <ul class="child" >
                                        <?php echo show_menu();?>
                                    </ul>
                                    <input type="hidden" name="product_type_id">
                                </li>
                            </ul>
                            <nav style="padding-left:0px;" class="col-md-12" aria-label="breadcrumb"></nav>
                            <p class='text-danger'></p>
                        </div>
                   </td>
                    <td><input class='kh-inp-ctrl' name='ptd_discount_percent2' type='text' onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" value='' placeholder="Nhập giá trị khuyến mãi..."><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' name='ptd_date_start2' type='text' value='' placeholder="Nhập ngày bắt đầu..."><p class='text-danger'></p></td>
                    <td><input class='kh-inp-ctrl' name='ptd_date_end2' type='text' value='' placeholder="Nhập ngày kết thúc..."><p class='text-danger'></p></td>
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
                $(html).appendTo('#form-danh-muc-khuyen-mai2 table');
            }
            if(page == 0) {
                let html2 = `<div id="paging" style="justify-content:center;" class="row">
                    <nav id="pagination2">
                    </nav>
                </div>`;
                $(html2).appendTo('#form-danh-muc-khuyen-mai2');
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
      let count = $('td input[name="product_type_id"]').length;
      $('td input[name="product_type_id"]').each(function(){
        if($(this).val() != ""){
          formData.append("product_type_id[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="ptd_discount_percent2"]').each(function(){
        if($(this).val() != ""){
          formData.append("ptd_discount_percent2[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="ptd_date_start2"]').each(function(){
        if($(this).val() != "") {
          formData.append("ptd_date_start2[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;  
        }
      });
      $('td input[name="ptd_date_end2"]').each(function(){
        if($(this).val() != "") {
          formData.append("ptd_date_end2[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
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
    var count_row_z_index = 1000000;
    function showRow(page,apply_dom = true){
      let count = $('[data-plus]').attr('data-plus');
      limit = 7;
      if(apply_dom) {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
        $('#form-danh-muc-khuyen-mai2 table').remove();
        $('#form-danh-muc-khuyen-mai2 #paging').remove();
        let html = `
        <table class='table table-bordered' style="height:auto;">
          <thead>
            <tr>
                <th>Số thứ tự</th>
                <th>Danh mục khuyến mãi</th>
                <th>Khuyến mãi (%)</th>
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
                  <td>
                     <div style="display:flex;flex-direction:column;position:relative;">
                        <ul tabindex="1" class="col-md-12 ul_menu" style="padding-left:0px;height: 65px;outline:none !important;z-index: ${count_row_z_index--};" id="menu">
                           <li class="parent" style="border: 1px solid #dce1e5;">
                              <a href="#">Chọn danh mục</a>
                              <ul class="child" >
                                 <?php echo show_menu();?>
                              </ul>
                              <input type="hidden" name="product_type_id2">
                           </li>
                        </ul>
                        <nav style="padding-left:0px;" class="col-md-12" aria-label="breadcrumb"></nav>
                        <p class='text-danger'></p>
                     </div>
                  </td>
                  <td><input class='kh-inp-ctrl' name='ptd_discount_percent2' onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" type='text' value='' placeholder='Nhập giá trị khuyến mãi...'><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' name='ptd_date_start2' value='' placeholder="Nhập ngày bắt đầu..."><p class='text-danger'></p></td>
                  <td><input class='kh-inp-ctrl' name='ptd_date_end2' type='text' value='' placeholder="Nhập ngày kết thúc..."><p class='text-danger'></p></td>
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
                <td>
                    <div style="display:flex;flex-direction:column;position:relative;">
                        <ul tabindex="1" class="col-md-12 ul_menu" style="padding-left:0px;height: 65px;outline:none !important;z-index: ${count_row_z_index--};" id="menu">
                        <li class="parent" style="border: 1px solid #dce1e5;">
                            <a href="#">Chọn danh mục</a>
                            <ul class="child" >
                                <?php echo show_menu();?>
                            </ul>
                            <input type="hidden" name="product_type_id">
                        </li>
                        </ul>
                        <nav style="padding-left:0px;" class="col-md-12" aria-label="breadcrumb"></nav>
                        <p class='text-danger'></p>
                    </div>
                </td>
                <td><input class='kh-inp-ctrl' name='ptd_discount_percent2' onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" type='text' value='' placeholder="Nhập giá trị khuyến mãi..."><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='ptd_date_start2' value='' placeholder="Nhập ngày bắt đầu..."><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='ptd_date_end2' type='text' placeholder="Nhập ngày kết thúc..." value=''><p class='text-danger'></p></td>
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
        $(html).appendTo('#form-danh-muc-khuyen-mai2');
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
        $('#form-danh-muc-khuyen-mai2 table tbody').remove();
        $('#form-danh-muc-khuyen-mai2 #paging').remove();
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
            target.closest('.ul_menu').find("input[name='product_type_id']").val(id);
            target.closest('.ul_menu').next().empty();
            target.closest('.ul_menu').next().append(data);
         });
      }
   }
    
</script>
<script>
    function toggleActive(id,status) {
        let evt = $(event.currentTarget);
        $.ajax({
            url:window.location.href,
            type:"POST",
            data:{
                id: id,
                status: status 
            },
            success:function(data){
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    toastr["success"](data.success);
                    if(status == "Active") {
                        evt.attr('onchange',`toggleActive(${id},"Deactive")`);
                    } else if(status == "Deactive") {
                        evt.attr('onchange',`toggleActive(${id},"Active")`);
                    }
                }
            }
        })
    }
    $(document).ready(function (e) {
        $('.select-type2').select2();
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
      if($(event.currentTarget).closest('#s-date_start2').length) {
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
    function validate(){

    }
    function validateModal(){
        
    }
    function openModalInsert(){

    }
    function openModalUpdate(){

    }
    function processModalInsertUpdate(){

    }
    function processDelete(){

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
            let code = $("input[name='code']").val();
            let content = $("textarea[name='content']").val();
            let discount_percent = $("input[name='discount_percent']").val();
            let discount_content = $("textare[name='discount_content']").val();
            let date_start = $("input[name='date_start']").val();
            let date_end = $("input[name='date_end']").val();
            let id = $("input[name='id']").val();
            $('.coupon-validate').text("");
            if(code == "") {
                $('#code_err').text("Vui lòng không để trống danh mục khuyến mãi");
                test = false;
            } if(content == "") {
                $('#content_err').text("Vui lòng không để trống nội dung mô tả khuyến mãi");
                test = false;    
            } if(discount_percent == "") {
                $('#discount_percent_err').text("Vui lòng không để trống số phần trăm giảm giá");
                test = false;
            } if(date_start == "") {
                $('#date_start_err').text("Vui lòng không để trống thời gian bắt đầu");
                test = false;
            } if(date_end == "") {
                $('#date_end_err').text("Vui lòng không để trống thời gian kết thúc");
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
        $(document).on('click','#btn-them-khuyen-mai',(e) => {
            $('#form-danh-muc-khuyen-mai').load("ajax_category_discount_manage.php?status=Insert",() => {
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
                $(".parent[data-id]").click(function(e){
                    let child = $(e.currentTarget).find('li').length;
                    if(!child){
                        let id = $(e.currentTarget).attr('data-id');
                        let name = $(e.currentTarget).text();
                        name = name.substr(0,name.length - 1);
                        console.log(name);
                        $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                            $("input[name='product_type_id']").val(id);
                            $("#breadcrumb-menu").empty();
                            $("#breadcrumb-menu").append(data);
                        });
                    }
                })
            })
        });
        // mở modal sửa dữ liệu
        $(document).on('click','.btn-sua-khuyen-mai',function(e) {  
            let id = $(e.currentTarget).attr('data-id');
            $('#form-danh-muc-khuyen-mai').load("ajax_category_discount_manage.php?status=Update&id=" + id,() => {
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
                $(".parent[data-id]").click(function(e){
                    let child = $(e.currentTarget).find('li').length;
                    if(!child){
                        let id = $(e.currentTarget).attr('data-id');
                        let name = $(e.currentTarget).text();
                        name = name.substr(0,name.length - 1);
                        console.log(name);
                        $.get("get_breadcrumb_menu.php?id=" + id,(data) => {
                            $("input[name='product_type_id']").val(id);
                            $("#breadcrumb-menu").empty();
                            $("#breadcrumb-menu").append(data);
                        });
                    }
                })
            })
        });
        // thêm 
        $(document).on('click','#btn-insert',function(e){
            event.preventDefault();
            let test = true;
            let product_type_id = $("input[name='product_type_id']").val();
            let discount_content = $("textarea[name='discount_content']").val();
            let discount_percent = $("input[name='discount_percent']").val();
            let date_start = $("input[name='date_start']").val();
            let date_end = $("input[name='date_end']").val();
            let id = $("input[name='id']").val();
            $('.coupon-validate').text("");
            if(product_type_id == "") {
                $('#product_type_id_err').text("Vui lòng không để trống danh mục khuyến mãi");
                test = false;
            } if(discount_content == "") {
                $('#discount_content_err').text("Vui lòng không để trống nội dung mô tả khuyến mãi");
                test = false;    
            } if(discount_percent == "") {
                $('#discount_percent_err').text("Vui lòng không để trống số phần trăm giảm giá");
                test = false;
            } if(date_start == "") {
                $('#date_start_err').text("Vui lòng không để trống thời gian bắt đầu");
                test = false;
            } if(date_end == "") {
                $('#date_end_err').text("Vui lòng không để trống thời gian kết thúc");
                test = false;
            }
            if(test) {
                $.ajax({
                    url:window.location.href,
                    type: "POST",
                    data: {
                        "status":"Insert",
                        "product_type_id" : product_type_id,
                        "discount_content" : discount_content,
                        "discount_percent": discount_percent,
                        "date_start": date_start,
                        "date_end": date_end
                    },
                    success:function(data){
                        console.log(data);
                        data = JSON.parse(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.href="category_discount_manage.php";
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
            let product_type_id = $("input[name='product_type_id']").val();
            let discount_content = $("textarea[name='discount_content']").val();
            let discount_percent = $("input[name='discount_percent']").val();
            let date_start = $("input[name='date_start']").val();
            let date_end = $("input[name='date_end']").val();
            $('.coupon-validate').text("");
            if(product_type_id == "") {
                $('#product_type_id_err').text("Vui lòng không để trống danh mục khuyến mãi");
                test = false;
            } if(discount_content == "") {
                $('#discount_content_err').text("Vui lòng không để trống nội dung mô tả khuyến mãi");
                test = false;    
            } if(discount_percent == "") {
                $('#discount_percent_err').text("Vui lòng không để trống số phần trăm giảm giá");
                test = false;
            } if(date_start == "") {
                $('#date_start_err').text("Vui lòng không để trống thời gian bắt đầu");
                test = false;
            } if(date_end == "") {
                $('#date_end_err').text("Vui lòng không để trống thời gian kết thúc");
                test = false;
            }
            if(test) {
                $.ajax({
                    url:window.location.href,
                    type: "POST",
                    data: {
                        "id": id,
                        "status":"Update",
                        "product_type_id" : product_type_id,
                        "discount_content" : discount_content,
                        "discount_percent": discount_percent,
                        "date_start": date_start,
                        "date_end": date_end
                    },
                    success:function(data){
                        console.log(data);
                        data = JSON.parse(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.href="category_discount_manage.php";
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
            let id = $(e.currentTarget).attr('data-id');
            let target = $(e.currentTarget);
            target.closest("tr").addClass("bg-color-selected");
            $.confirm({
                title: 'Thông báo',
			    content: 'Bạn có chắc chắn muốn xoá danh mục khuyến mãi này ?',
                buttons: {
                    Có: function(){
                        $.ajax({
                            url:window.location.href,
                            type:"POST",
                            cache:false,
                            data: {
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
            $('#form-danh-muc-khuyen-mai').load("ajax_category_discount_manage.php?status=Read&id=" + id,() => {
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
        // code to be executed post method
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $product_type_id = isset($_REQUEST['product_type_id']) ? $_REQUEST['product_type_id'] : null;
        $discount_content = isset($_REQUEST['discount_content']) ? $_REQUEST['discount_content'] : null;
        $discount_percent = isset($_REQUEST['discount_percent']) ? $_REQUEST['discount_percent'] : null;
        $date_start = isset($_REQUEST['date_start']) ? Date("Y-m-d",strtotime($_REQUEST['date_start'])) : null;
        $date_end = isset($_REQUEST['date_end']) ?  Date("Y-m-d",strtotime($_REQUEST['date_end'])) : null;
        // code to be executed post method
        if($status == "Insert") {
            $sql_check_duplicate_code = "select count(*) as 'cnt' from product_type_discount where product_type_id = '$product_type_id'";
            $count = fetch(sql_query($sql_check_duplicate_code));
            if($count['cnt'] > 0) {
                echo_json(["msg" => "not_ok","error" => "Mã khuyến mãi đã tồn tại"]);    
            }
            $sql_ins = "Insert into product_type_discount(product_type_id,discount_content,discount_percent,date_start,date_end) values ('$product_type_id','$discount_content','$discount_percent','$date_start','$date_end')";
            sql_query($sql_ins);
            echo_json(["msg" => "ok","success" => "Bạn đã thêm dữ liệu thành công"]);
        } else if($status == "Update") {
            $sql_check_duplicate_code = "select count(*) as 'cnt' from product_type_discount where product_type_id = '$product_type_id'";
            $count = fetch(sql_query($sql_check_duplicate_code));
            if($count['cnt'] > 1) {
                echo_json(["msg" => "not_ok","error" => "Ngành hàng này đã khuyến mãi"]);    
            }
            $sql_upt = "Update product_type_discount set product_type_id = '$product_type_id',discount_content = '$discount_content',discount_percent = '$discount_percent',date_start = '$date_start',date_end = '$date_end' where id = '$id'";
            sql_query($sql_upt);
            echo_json(["msg" => "ok","success" => "Bạn đã sửa dữ liệu thành công"]);
        } else if($status == "Delete") {
            $sql_del = "Update product_type_discount set is_delete = 1 where id = '$id'";
            sql_query($sql_del);
            echo_json(["msg" => "ok","success" => "Bạn đã xoá dữ liệu thành công"]);
        } else if($status == "Active") {
            $sql_active_product_type_discount = "Update product_type_discount set is_active = 1 where id = $id";
            sql_query($sql_active_product_type_discount);
            echo_json(["msg" => "ok","success" => "Bạn đã kích hoạt ngành hàng khuyến mãi thành công"]);
        } else if($status == "Deactive") {
            $sql_deactive_product_type_discount = "Update product_type_discount set is_active = 0 where id = $id";
            sql_query($sql_deactive_product_type_discount);
            echo_json(["msg" => "ok","success" => "Bạn đã huỷ kích hoạt ngành hàng khuyến mãi thành công"]);
        } else if($status == "del_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $sql_del_more = "Update product_type_discount set is_delete = 1 where id in ($rows)";
            sql_query($sql_del_more);
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_more") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $product_type_id = isset($_REQUEST["product_type_id"]) ? $_REQUEST["product_type_id"] : null;
            $ptd_discount_percent2 = isset($_REQUEST["ptd_discount_percent2"]) ? $_REQUEST["ptd_discount_percent2"] : null;
            $ptd_discount_content2 = isset($_REQUEST["ptd_discount_content2"]) ? $_REQUEST["ptd_discount_content2"] : null;
            $ptd_date_start2 = isset($_REQUEST["ptd_date_start2"]) ?  Date('Y-m-d',strtotime($_REQUEST["ptd_date_start2"])) : null;
            $ptd_date_end2 = isset($_REQUEST["ptd_date_end2"]) ? Date('Y-m-d',strtotime($_REQUEST["ptd_date_end2"])) : null;
            $sql = "Insert into product_type_discount(product_type_id,discount_content,discount_percent,date_start,date_end) values('$product_type_id','$ptd_discount_content2','$ptd_discount_percent2','$ptd_date_start2','$ptd_date_end2')";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $product_type_id = isset($_REQUEST["product_type_id"]) ? $_REQUEST["product_type_id"] : null;
            $ptd_discount_percent2 = isset($_REQUEST["ptd_discount_percent2"]) ? $_REQUEST["ptd_discount_percent2"] : null;
            $ptd_discount_content2 = isset($_REQUEST["ptd_discount_content2"]) ? $_REQUEST["ptd_discount_content2"] : null;
            $ptd_date_start2 = isset($_REQUEST["ptd_date_start2"]) ? $_REQUEST["ptd_date_start2"] : null;
            $ptd_date_end2 = isset($_REQUEST["ptd_date_end2"]) ? $_REQUEST["ptd_date_end2"] : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {
                    $ptd_date_start = Date("Y-m-d",strtotime($ptd_date_start2[$i]));
                    $ptd_date_end = Date("Y-m-d",strtotime($ptd_date_end2[$i]));
                    $sql = "Insert into product_type_discount(product_type_id,discount_content,discount_percent,date_start,date_end) values('$product_type_id[$i]','$ptd_discount_content2[$i]','$ptd_discount_percent2[$i]','$ptd_date_start','$ptd_date_end')";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "upt_more") {
            $ptd_id = isset($_REQUEST["ptd_id"]) ? $_REQUEST["ptd_id"] : null;
            $ptd_discount_percent = isset($_REQUEST["ptd_discount_percent"]) ? $_REQUEST["ptd_discount_percent"] : null;
            $ptd_date_start = isset($_REQUEST["ptd_date_start"]) ? Date("Y-m-d",strtotime($_REQUEST["ptd_date_start"])) : null;
            $ptd_date_end = isset($_REQUEST["ptd_date_end"]) ? Date("Y-m-d",strtotime($_REQUEST["ptd_date_end"])) : null;
            $sql = "Update product_type_discount set discount_percent='$ptd_discount_percent',date_start='$ptd_date_start',date_end='$ptd_date_end' where id='$ptd_id'";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "upt_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $ptd_id = isset($_REQUEST["ptd_id"]) ? $_REQUEST["ptd_id"] : null;
            $ptd_discount_percent = isset($_REQUEST["ptd_discount_percent"]) ? $_REQUEST["ptd_discount_percent"] : null;
            $ptd_date_start = isset($_REQUEST["ptd_date_start"]) ? $_REQUEST["ptd_date_start"] : null;
            $ptd_date_end = isset($_REQUEST["ptd_date_end"]) ? $_REQUEST["ptd_date_end"]  : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {
                    $ptd_date_start_2 = Date("Y-m-d",strtotime($ptd_date_start[$i]));
                    $ptd_date_end_2 = Date("Y-m-d",strtotime($ptd_date_end[$i]));
                    $sql = "Update product_type_discount set discount_percent='$ptd_discount_percent[$i]',date_start='$ptd_date_start_2',date_end='$ptd_date_end_2' where id = '$ptd_id[$i]'";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "saveTabFilter") {
            $_SESSION['category_discount_tab_id'] = isset($_SESSION['category_discount_tab_id']) ? $_SESSION['category_discount_tab_id'] + 1 : 1;
            $tab_name = isset($_SESSION['category_discount_tab_id']) ? "tab_" . $_SESSION['category_discount_tab_id'] : null;
            $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
            $tab_unique = uniqid("tab_");
            $_SESSION['category_discount_manage_tab'] = isset($_SESSION['category_discount_manage_tab']) ? $_SESSION['category_discount_manage_tab'] : [];
            array_push($_SESSION['category_discount_manage_tab'],[
               "tab_unique" => $tab_unique,
               "tab_name" => $tab_name,
               "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
            ]);
            echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['category_discount_manage_tab']),"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
        } else if($status == "deleteTabFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
            array_splice($_SESSION['category_discount_manage_tab'],$index,1);
            if(trim($is_active_2) == "") {
                echo_json(["msg" => "ok"]);
            }  else if($is_active_2 == 1) {
                if(array_key_exists($index,$_SESSION['category_discount_manage_tab'])) {
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['category_discount_manage_tab'][$index]['tab_urlencode']]);
                } else if(array_key_exists($index - 1,$_SESSION['category_discount_manage_tab'])){
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['category_discount_manage_tab'][$index - 1]['tab_urlencode']]);
                } else {
                    echo_json(["msg" => "ok","tab_urlencode" => "category_discount_manage.php?tab_unique=all"]);
                }
            }
        } else if($status == "changeTabNameFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
            $_SESSION['category_discount_manage_tab'][$index]['tab_name'] = $new_tab_name;
            echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['category_discount_manage_tab'][$index]['tab_urlencode']]);
        }
    }
?>