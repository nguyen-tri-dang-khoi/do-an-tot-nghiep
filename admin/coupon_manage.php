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
        $order_by = "ORDER BY id desc";
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
        if($subtotal_min_1 && $subtotal_min_2) {
            if($subtotal_min_1 != "" && $subtotal_min_2 != "") {
                $subtotal_min_1 = str_replace(".","",$subtotal_min_1);
                $subtotal_min_2 = str_replace(".","",$subtotal_min_2);
                $where .= " and (coupon_if_subtotal_min >= '$subtotal_min_1' and coupon_if_subtotal_min <= '$subtotal_min_2')";
            } else if($subtotal_min_1 == "" && $subtotal_min_2 != ""){
                $subtotal_min_2 = str_replace(".","",$subtotal_min_2);
                $where .= " and (coupon_if_subtotal_min <= '$subtotal_min_2')";
            } else if($subtotal_min_1 != "" && $subtotal_min_2 == ""){
                $subtotal_min_1 = str_replace(".","",$subtotal_min_1);
                $where .= " and (coupon_if_subtotal_min >= '$p_min')";
            }
        }
        // số tiền tối đa
        if($subtotal_max_1 && $subtotal_max_2) {
            if($subtotal_max_1 != "" && $subtotal_max_2 != "") {
                $subtotal_max_1 = str_replace(".","",$subtotal_max_1);
                $subtotal_max_2 = str_replace(".","",$subtotal_max_2);
                $where .= " and (coupon_if_subtotal_max >= '$subtotal_max_1' and coupon_if_subtotal_max <= '$subtotal_max_2')";
            } else if($subtotal_max_1 == "" && $subtotal_max_2 != ""){
                $subtotal_max_2 = str_replace(".","",$subtotal_max_2);
                $where .= " and (coupon_if_subtotal_max <= '$subtotal_max_2')";
            } else if($subtotal_max_1 != "" && $subtotal_max_2 == ""){
                $subtotal_max_1 = str_replace(".","",$subtotal_max_1);
                $where .= " and (coupon_if_subtotal_max >= '$p_max')";
            }
        }
        // thời gian bắt đầu
        if($date_start_1 && $date_start_2) {
            if($date_start_1 != "" && $date_start_2 != "") {
                $date_start_1 = Date("Y-m-d",strtotime($date_start_1));
                $date_start_2 = Date("Y-m-d",strtotime($date_start_2));
                $where .= " and (coupon_date_start >= '$date_start_1 00:00:00' and coupon_date_start <= '$date_start_2 23:59:59')";
            } else if($date_start_1 != "" && $date_start_2 == "") {
                $date_start_1 = Date("Y-m-d",strtotime($date_start_1));
                $where .= " and (coupon_date_start >= '$date_start_1 00:00:00')";
            } else if($date_start_1 == "" && $date_start_2 != "") {
                $date_start_2 = Date("Y-m-d",strtotime($date_start_2));
                $where .= " and (dcoupon_ate_start <= '$date_start_2 23:59:59')";
            }
        }
        // thời gian kết thúc
        if($date_end_1 && $date_end_2) {
            if($date_end_1 != "" && $date_end_2 != "") {
                $date_end_1 = Date("Y-m-d",strtotime($date_end_1));
                $date_end_2 = Date("Y-m-d",strtotime($date_end_2));
                $where .= " and (coupon_date_end >= '$date_end_1 00:00:00' and coupon_date_end <= '$date_end_2 23:59:59')";
            } else if($date_end_1 != "" && $date_end_2 == "") {
                $date_end_1 = Date("Y-m-d",strtotime($date_end_1));
                $where .= " and (coupon_date_end >= '$date_end_1 00:00:00')";
            } else if($date_end_1 == "" && $date_end_2 != "") {
                $date_end_2 = Date("Y-m-d",strtotime($date_end_2));
                $where .= " and (coupon_date_end <= '$date_end_2 23:59:59')";
            }
        }
        // ngày tạo
        if($date_created_at_1 && $date_created_at_2) {
            if($date_created_at_1 != "" && $date_created_at_2 != "") {
                $date_created_at_1 = Date("Y-m-d",strtotime($date_created_at_1));
                $date_created_at_2 = Date("Y-m-d",strtotime($date_created_at_2));
                $where .= " and (created_at >= '$date_created_at_1 00:00:00' and created_at <= '$date_created_at_2 23:59:59')";
            } else if($date_created_at_1 != "" && $date_created_at_2 == "") {
                $date_created_at_1 = Date("Y-m-d",strtotime($date_created_at_1));
                $where .= " and (created_at >= '$date_created_at_1 00:00:00')";
            } else if($date_created_at_1 == "" && $date_created_at_2 != "") {
                $date_created_at_2 = Date("Y-m-d",strtotime($date_created_at_2));
                $where .= " and (created_at <= '$date_created_at_2 23:59:59')";
            }
        }
        if($is_active) {
            $where .= " and is_active='$is_active'";
        }
        if($orderStatus && $orderByColumn) {
            $order_by = "ORDER BY $orderByColumn $orderStatus";
            
        }
        $where .= " $order_by";
        // code to be executed get method
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/toastr.min.css">
<style>
    [class^=ele-] {
        display: flex;
        justify-content: space-between;
        width: 100%;
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
                        <h3 class="card-title">Quản lý mã khuyến mãi</h3>
                        <div class="card-tools">
                            <div class="input-group">
                            <div class="input-group-append">
                            <button onclick="openModalInsert()" id="btn-them-khuyen-mai" class="dt-button button-blue">
                                Tạo mã khuyến mãi
                            </button>
                            </div>
                            </div>
                        </div>
                    </div>
                  <!-- /.card-header -->
                    <div class="card-body ok-game-start">
                        <div id="load-all">
                            <link rel="stylesheet" href="css/tab.css">             
                            <div style="padding-right:0px;padding-left:0px;" class="col-12 mb-20 d-flex a-center j-between">
                                <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                                    <?php
                                        $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                        $_SESSION['coupon_manage_tab'] = isset($_SESSION['coupon_manage_tab']) ? $_SESSION['coupon_manage_tab'] : [];
                                        $_SESSION['coupon_tab_id'] = isset($_SESSION['coupon_tab_id']) ? $_SESSION['coupon_tab_id'] : 0;
                                    ?>
                                    <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('coupon_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>
                                    <?php
                                        $ik = 0;
                                        $is_active = false;
                                        if(count($_SESSION['coupon_manage_tab']) > 0) {
                                            foreach($_SESSION['coupon_manage_tab'] as $tab) {
                                            if($tab['tab_unique'] == $tab_unique) {
                                                $_SESSION['coupon_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                            }
                                    ?>
                                        <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                            <button onclick="loadDataInTab('<?=$_SESSION['coupon_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
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
                                    <form style="" id="form-filter" autocomplete="off" action="coupon_manage.php" method="get" onsubmit="searchTabLoad('#form-filter')">
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
                                            <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                                            <button type="submit" class="btn btn-default ml-10" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                                        </div>
                                        <div class="d-flex a-start mt-10">
                                            <div id="s-cols" class="k-select-opt s-all2" style="width:15%;<?=$keyword && $keyword != [""] ? "display:flex;flex-direction: column;": "display:none;";?>">
                                            <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                            <span onclick="selectOptionInsert()" class="k-select-opt-ins"></span>
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
                                            <div id="s-subtotal_min2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($subtotal_min_1 || $subtotal_min_2) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-subtotal_min2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="subtotal_min_1" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" class="form-control" value="<?=$subtotal_min_1 ? number_format($subtotal_min_1,0,".",".") : '';?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="subtotal_min_2" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" class="form-control" value="<?=$subtotal_min_2 ? number_format($subtotal_min_2,0,".",".") : '';?>">
                                                    </div>
                                                </div>
                                                </div>
                                            <div id="s-subtotal_max2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($subtotal_max_1 || $subtotal_max_2) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-subtotal_max2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="subtotal_max_1" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="" class="form-control"  value="<?=$subtotal_max_1 ? number_format($subtotal_max_1,0,".",".") : '';?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="subtotal_max_2" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" placeholder="" class="form-control"  value="<?=$subtotal_max_1 ? number_format($subtotal_max_1,0,".",".") : '';?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="s-date_start2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_start_1 || $date_start_2) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-date2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="date_start_1" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$date_start_1 ? Date("d-m-Y",strtotime($date_start_1)) : ''?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="date_start_2" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$date_start_2 ? Date("d-m-Y",strtotime($date_start_2)) : ''?>">
                                                    </div>
                                                </div>
                                                </div>
                                            <div id="s-date_end2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_end_1 || $date_end_2) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-date2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="date_end_1" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$date_end_1 ? Date("d-m-Y",strtotime($date_end_1)) : ''?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="date_end_2" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$date_end_2 ? Date("d-m-Y",strtotime($date_end_2)) : ''?>">
                                                    </div>
                                                </div>
                                                </div>
                                            <div id="s-date_created_at2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_created_at_1 || $date_created_at_2) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-date2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="date_created_at_1" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$date_created_at_1 ? Date("d-m-Y",strtotime($date_created_at_1)) : ''?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="date_created_at_2" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$date_created_at_2 ? Date("d-m-Y",strtotime($date_created_at_2)) : ''?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex a-start" style="">
                                            <div id="s-publish2" class="k-select-opt col-2 s-all2" style="<?=$is_active != "" ? "display:block;": "display:none;";?>margin-top:10px;">
                                            <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
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
                                <table id="table-coupon_manage" class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="cursor:pointer;">
                                            <th style="width:20px !important;">
                                                <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                            </th>
                                            <th class="w-120 th-so-thu-tu">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-150 th-ma-khuyen-mai">Mã khuyến mãi <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-150 th-khuyen-mai">Khuyến mãi (%) <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-150 th-gia-tri-toi-thieu">Giá trị tối thiểu <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-150 th-gia-tri-toi-da">Giá trị tối đa <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-150 th-ngay-bat-dau">Ngày bắt đầu <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-150 th-ngay-het-han">Ngày hết hạn <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-100">Tình trạng</th>
                                            <th class="w-120 th-ngay-tao">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                            <th class="w-200">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    // query
                                    $cnt = 0;
                                    $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;  
                                    $limit = $_SESSION['paging'];
                                    $start_page = $limit * ($page - 1);
                                    $sql_get_total = "select count(*) as 'countt' from coupon $where";
                                    $total = fetch(sql_query($sql_get_total))['countt'];
                                    $sql_get_product = "select * from coupon n $where limit $start_page,$limit";
                                    ?>
                                    <tbody dt-parent-id  dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-coupon" id="list-khuyen-mai">
                                    <?php
                                    $rows = fetch_all(sql_query($sql_get_product));
                                    foreach($rows as $row) {
                                    ?>
                                        <tr id="<?=$row["id"];?>">
                                            <td>
                                                <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-coupon')" type="checkbox" name="check_id<?=$row["id"];?>">
                                            </td>
                                            <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                                            <td class="ma-khuyen-mai"><?=$upt_more == 1 ? "<input name='c_code' class='form-control' type='text' value='" . $row['coupon_code'] . "'>" : $row['coupon_code'];?></td>
                                            <td class="khuyen-mai"><?=$upt_more == 1 ? "<input name='c_discount_percent' class='form-control' type='text' value='" .$row['coupon_discount_percent']."'>" : $row['coupon_discount_percent'];?></td>
                                            <td class="gia-tri-toi-thieu"><?=$upt_more == 1 ? "<input name='c_if_subtotal_min' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)'  class='form-control' type='text' value='" . number_format($row['coupon_if_subtotal_min'],0,".",".")."'>" : number_format($row['coupon_if_subtotal_min'],0,".",".");?></td>
                                            <td class="gia-tri-toi-da"><?=$upt_more == 1 ? "<input name='c_if_subtotal_max' onpaste='pasteAutoFormat(event)' onkeyup='allow_zero_to_nine(event)' onkeypress='allow_zero_to_nine(event)'  class='form-control' type='text' value='" .number_format($row['coupon_if_subtotal_max'],0,".",".")."'>" : number_format($row['coupon_if_subtotal_max'],0,".",".");?></td>
                                            <td class="ngay-bat-dau">
                                                <?php
                                                    if($upt_more == 1) {
                                                ?>
                                                <?=$row['coupon_date_start'] ? "<input name='c_date_start' class='form-control kh-datepicker2' type='text' value='" . Date("d-m-Y",strtotime($row['coupon_date_start'])) ."'>" : "";?> 
                                                <?php } else { ?>
                                                <?=$row['coupon_date_start'] ? Date("d-m-Y",strtotime($row['coupon_date_start'])) : "";?>
                                                <?php }?>
                                            </td>
                                            <td class="ngay-het-han">
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
                                                    <button onclick="openModalRead()" class="btn-xem-khuyen-mai dt-button button-grey"
                                                    data-id="<?=$row["id"];?>">
                                                    Xem
                                                    </button>
                                                    <button onclick="openModalUpdate()" class="btn-sua-khuyen-mai dt-button button-green"
                                                    data-id="<?=$row["id"];?>" >
                                                    Sửa
                                                    </button>
                                                    <button onclick="processDelete()" class="btn-xoa-khuyen-mai dt-button button-red" data-id="<?=$row["id"];?>">
                                                    Xoá
                                                    </button>
                                                <?php }?>
                                            </td>
                                        </tr>
                                    <?php
                                    $cnt++;
                                    }
                                    ?>
                                    <?php
                                        if(count($rows) == 0) {
                                    ?>
                                    <tr>
                                        <td style="text-align:center;font-size:17px;" colspan="20">Không có dữ liệu</td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="width:20px !important;">
                                                <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                            </th>
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
                            <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Mã khuyến mãi','Khuyến mãi (%)','Giá trị tối thiểu','Giá trị tối đa','Ngày bắt đầu','Ngày hết hạn'],['c_code2','c_discount_percent2','c_if_subtotal_min2','c_if_subtotal_max2','c_date_start2','c_date_end2'])">
                        </div>
                        <div class="file file-excel mr-10">
                            <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Mã khuyến mãi','Khuyến mãi (%)','Giá trị tối thiểu','Giá trị tối đa','Ngày bắt đầu','Ngày hết hạn'],['c_code2','c_discount_percent2','c_if_subtotal_min2','c_if_subtotal_max2','c_date_start2','c_date_end2'])">
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
<script src="js/khoi_all.js"></script>
<!--js section start-->
<script>
    <?=$upt_more != 1 ? "setSortTable();" : null;?>
    function uptMore2(){
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
            this2.find("td input[name='c_date_start']").siblings("span.text-danger").text("");
        }
        if(c_date_end == "") {
            test = false;
            this2.find("td input[name='c_date_end']").siblings("span.text-danger").text("Không được để trống");
        } else {
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
                formData.append("c_date_start[]",c_date_start);
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="c_date_end"]').each(function(){
            if($(this).val() != "") {
                formData.append("c_date_end[]",c_date_end);
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
   function selectOptionRemove(){
    $(event.currentTarget).siblings('select').find('option').prop("selected",false);
    $(event.currentTarget).siblings('select').find("option[value='']").prop("selected",true);
    $(event.currentTarget).siblings('.ele-select').remove()
    $(event.currentTarget).siblings("div").find("input").val("");
    $(event.currentTarget).closest('div').css({"display":"none"});
   }
   function selectOptionInsert(){
    let file_html = ``;
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
   }
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
</script>
<script>
    function validate(){
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
    }
    function openModalInsert(){
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
    }
    function openModalUpdate(){
        let id = $(event.currentTarget).attr('data-id');
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
    }
    function processModalInsert(){
        event.preventDefault();
        let test = true;
        let coupon_code = $("input[name='coupon_code']").val();
        let coupon_content = $("textarea[name='coupon_content']").val();
        let coupon_discount_percent = $("input[name='coupon_discount_percent']").val();
        let coupon_if_subtotal_min = $("input[name='coupon_if_subtotal_min']").val();
        let coupon_if_subtotal_max = $("input[name='coupon_if_subtotal_max']").val();
        let coupon_date_start = $("input[name='coupon_date_start']").val();
        let coupon_date_end = $("input[name='coupon_date_end']").val();
        let id = $("input[name='id']").val();
        $('.coupon-validate').text("");
        if(validate()) {
            $.ajax({
                url:window.location.href,
                type: "POST",
                data: {
                    "status":"Insert",
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
                                    loadDataComplete();
                                },
                            }
                        });
                    }
                    $('#modal-xl').modal('hide');
                },
                error:function(data) {
                    console.log("Error:",data);
                }
            });
        }
    }
    function processModalUpdate(){
        event.preventDefault();
        let test = true;
        let id = $("input[name='id']").val();
        let coupon_code = $("input[name='coupon_code']").val();
        let coupon_content = $("textarea[name='coupon_content']").val();
        let coupon_discount_percent = $("input[name='coupon_discount_percent']").val();
        let coupon_if_subtotal_min = $("input[name='coupon_if_subtotal_min']").val();
        let coupon_if_subtotal_max = $("input[name='coupon_if_subtotal_max']").val();
        let coupon_date_start = $("input[name='coupon_date_start']").val();
        let coupon_date_end = $("input[name='coupon_date_end']").val();
        $('.coupon-validate').text("");
        if(validate()) {
            $.ajax({
                url:window.location.href,
                type: "POST",
                data: {
                    "id": id,
                    "status":"Update",
                    "coupon_code" : coupon_code,
                    "coupon_content" : coupon_content,
                    "coupon_discount_percent": coupon_discount_percent,
                    "coupon_if_subtotal_min": coupon_if_subtotal_min,
                    "coupon_if_subtotal_max": coupon_if_subtotal_max,
                    "coupon_date_start": coupon_date_start,
                    "coupon_date_end": coupon_date_end
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
                                    loadDataComplete();
                                },
                            }
                        });
                    }
                    $('#modal-xl').modal('hide');
                },
                error:function(data) {
                    console.log("Error:",data);
                }
            });
        }
    }
    function openModalRead(){
        let id = $(event.currentTarget).attr('data-id');
        let target = $(event.currentTarget);
        $('#form-khuyen-mai').load("ajax_coupon_manage.php?status=Read&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    }
    function processDelete(){
        let id = $(event.currentTarget).attr('data-id');
        let target = $(event.currentTarget);
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
                            id: id,
                            status: "Delete",
                        },
                        success:function(data){
                            data = JSON.parse(data);
                            if(data.msg == "ok") {
                                $.alert({
                                    title: "Thông báo",
                                    content: data.success,
                                });
                                loadDataComplete();
                            }
                        },
                        error:function(data) {
                            console.log("Error:",data);
                        }
                    });
                },Không: function(){
                    
                }
            }
        });
    }
</script>
<script>
    $(document).ready(function(){
        // mở modal thêm dữ liệu
        var click_number;
        $(document).on('click','#btn-them-khuyen-mai',(e) => {
            
        });
        // mở modal sửa dữ liệu
        $(document).on('click','.btn-sua-khuyen-mai',function(e) {  
            
        });
        // thêm 
        $(document).on('click','#btn-insert',function(e){
            
        });
        // sửa 
        $(document).on('click','#btn-update',function(e){
            
        });
        // xoá 
        $(document).on('click','.btn-xoa-khuyen-mai',function(e){
            
        });
        // xem
        $(document).on('click','.btn-xem-khuyen-mai',function(e){
            
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
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $coupon_code = isset($_REQUEST['coupon_code']) ? $_REQUEST['coupon_code'] : null;
        $coupon_content = isset($_REQUEST['coupon_content']) ? $_REQUEST['coupon_content'] : null;
        $coupon_discount_percent = isset($_REQUEST['coupon_discount_percent']) ? $_REQUEST['coupon_discount_percent'] : null;
        $coupon_if_subtotal_min = isset($_REQUEST['coupon_if_subtotal_min']) ? str_replace(".","",$_REQUEST['coupon_if_subtotal_min']) : null;
        $coupon_if_subtotal_max = isset($_REQUEST['coupon_if_subtotal_max']) ? str_replace(".","",$_REQUEST['coupon_if_subtotal_max']) : null;
        $coupon_date_start = isset($_REQUEST['coupon_date_start']) ? Date("Y-m-d",strtotime($_REQUEST['coupon_date_start'])) : null;
        $coupon_date_end = isset($_REQUEST['coupon_date_end']) ? Date("Y-m-d",strtotime($_REQUEST['coupon_date_end'])) : null;
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
            $c_if_subtotal_min2 = isset($_REQUEST["c_if_subtotal_min2"]) ? str_replace(".","",$_REQUEST["c_if_subtotal_min2"]): null;
            $c_if_subtotal_max2 = isset($_REQUEST["c_if_subtotal_max2"]) ? str_replace(".","",$_REQUEST["c_if_subtotal_max2"]): null;
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
                    $c_if_subtotal_min = str_replace(".","",$c_if_subtotal_min2[$i]);
                    $c_if_subtotal_max = str_replace(".","",$c_if_subtotal_max2[$i]);
                    $c_date_start2[$i] = Date("Y-m-d",strotime($c_date_start2[$i]));
                    $c_date_end2[$i] = Date("Y-m-d",strotime($c_date_end2[$i]));
                    $sql = "Insert into coupon(coupon_code,coupon_discount_percent,coupon_if_subtotal_min,coupon_if_subtotal_max,coupon_date_start,coupon_date_end) values('$c_code2[$i]','$c_discount_percent2[$i]','$c_if_subtotal_min','$c_if_subtotal_max','$c_date_start2[$i]','$c_date_end2[$i]')";
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
            $c_date_start = isset($_REQUEST["c_date_start"]) ? Date("Y-m-d",strtotime($_REQUEST["c_date_start"])) : null;
            $c_date_end = isset($_REQUEST["c_date_end"]) ? Date("Y-m-d",strtotime($_REQUEST["c_date_end"])) : null;
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
                    $c_date_start[$i] = Date("Y-m-d",strotime($c_date_start[$i]));
                    $c_date_end[$i] = Date("Y-m-d",strotime($c_date_end[$i]));
                    $sql = "Update coupon set coupon_code='$c_code[$i]',coupon_discount_percent='$c_discount_percent[$i]',coupon_if_subtotal_min='$c_if_subtotal_min[$i]',coupon_if_subtotal_max='$c_if_subtotal_max[$i]',coupon_date_start='$c_date_start[$i]',coupon_date_end='$c_date_end[$i]' where id = '$c_id[$i]'";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "saveTabFilter") {
            $_SESSION['coupon_tab_id'] = isset($_SESSION['coupon_tab_id']) ? $_SESSION['coupon_tab_id'] + 1 : 1;
            $tab_name = isset($_SESSION['coupon_tab_id']) ? "tab_" . $_SESSION['coupon_tab_id'] : null;
            $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
            $tab_unique = uniqid("tab_");
            $_SESSION['coupon_manage_tab'] = isset($_SESSION['coupon_manage_tab']) ? $_SESSION['coupon_manage_tab'] : [];
            array_push($_SESSION['coupon_manage_tab'],[
               "tab_unique" => $tab_unique,
               "tab_name" => $tab_name,
               "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
            ]);
            echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['coupon_manage_tab'])- 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
        } else if($status == "deleteTabFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
            array_splice($_SESSION['coupon_manage_tab'],$index,1);
            if(trim($is_active_2) == "") {
                echo_json(["msg" => "ok"]);
            }  else if($is_active_2 == 1) {
                if(array_key_exists($index,$_SESSION['coupon_manage_tab'])) {
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['coupon_manage_tab'][$index]['tab_urlencode']]);
                } else if(array_key_exists($index - 1,$_SESSION['coupon_manage_tab'])){
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['coupon_manage_tab'][$index - 1]['tab_urlencode']]);
                } else {
                    echo_json(["msg" => "ok","tab_urlencode" => "coupon_manage.php?tab_unique=all"]);
                }
            }
        } else if($status == "changeTabNameFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
            $_SESSION['coupon_manage_tab'][$index]['tab_name'] = $new_tab_name;
            echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['coupon_manage_tab'][$index]['tab_urlencode']]);
        }
    }
?>