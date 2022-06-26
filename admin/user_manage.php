<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        // permission crud for user
        $allow_read = $allow_update = $allow_delete = $allow_insert = $allow_role = $allow_lock = $allow_unlock = false; 
        if(check_permission_crud("user_manage.php","read")) {
          $allow_read = true;
        }
        if(check_permission_crud("user_manage.php","update")) {
          $allow_update = true;
        }
        if(check_permission_crud("user_manage.php","delete")) {
          $allow_delete = true;
        }
        if(check_permission_crud("user_manage.php","insert")) {
          $allow_insert = true;
        }
        if(check_permission_crud("user_manage.php","role")) {
            $allow_role = true;
        }
        if(check_permission_crud("user_manage.php","lock")) {
            $allow_lock = true;
        }
        if(check_permission_crud("user_manage.php","unlock")) {
            $allow_unlock = true;
        }
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $birthday_min = isset($_REQUEST['birthday_min']) ? $_REQUEST['birthday_min'] : null;
        $birthday_max = isset($_REQUEST['birthday_max']) ? $_REQUEST['birthday_max'] : null;
        $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
        $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
        $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
        $where = "where 1=1 and is_delete = 0";
        $order_by = "Order by user.id desc";
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
        if($birthday_min){
            $birthday_min = Date("Y-m-d",strtotime($birthday_min));
            $where .= " and (birthday >= '$birthday_min 00:00:00')";
        }
        if($birthday_max){
            $birthday_max = Date("Y-m-d",strtotime($birthday_max));
            $where .= " and (birthday <= '$birthday_max 23:59:59')";
        }
        if($date_min){
            $date_min = Date("Y-m-d",strtotime($date_min));
            $where .= " and (created_at >= '$date_min 00:00:00')";
        }
        if($date_max){
            $date_max = Date("Y-m-d",strtotime($date_max));
            $where .= " and (created_at <= '$date_max 23:59:59')";
        }
        if($str) {
            $where .= " and user.id in ($str)";
        }
        if($orderByColumn && $orderStatus) {
            $order_by = "ORDER BY $orderByColumn $orderStatus";
            
        }
        $where .= " $order_by";
        log_v($where);
?>
<style>
    .sort-asc,.sort-desc {
        display: none;
    }
</style>
<link rel="stylesheet" href="css/toastr.min.css">
<div class="container-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quản lý nhân viên</h3>
                        <?php
                            if($allow_insert) {
                        ?>
                        <div class="card-tools">
                            <button id="btn-add-user" onclick="openModalInsert()" class="dt-button button-blue">Thêm nhân viên</button>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body ok-game-start">
                        <div id="load-all">
                            <link rel="stylesheet" href="css/tab.css">             
                            <div style="padding-right:0px;padding-left:0px" class="col-12 mb-20 d-flex a-center j-between">
                                <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                                    <?php
                                        $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                        $_SESSION['user_manage_tab'] = isset($_SESSION['user_manage_tab']) ? $_SESSION['user_manage_tab'] : [];
                                        $_SESSION['user_tab_id'] = isset($_SESSION['user_tab_id']) ? $_SESSION['user_tab_id'] : 0;
                                    ?>
                                    <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('user_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>
                                    <?php
                                        $ik = 0;
                                        $is_active = false;
                                        if(count($_SESSION['user_manage_tab']) > 0) {
                                            foreach($_SESSION['user_manage_tab'] as $tab) {
                                            if($tab['tab_unique'] == $tab_unique) {
                                                $_SESSION['user_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                            }
                                    ?>
                                        <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                            <button onclick="loadDataInTab('<?=$_SESSION['user_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
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
                                <div class="col-12" style="padding:0;">
                                    <form id="form-filter" action="user_manage.php" method="get" onsubmit="searchTabLoad('#form-filter')">
                                        <div class="d-flex a-start">
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
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <span onclick="selectOptionInsert()" class="k-select-opt-ins"></span>
                                                <div class="ele-cols d-flex f-column">
                                                    <select name="search_option" class="form-control mb-10">
                                                        <option value="">Chọn cột tìm kiếm</option>
                                                        <option value="phone" <?=$search_option == 'phone' ? 'selected="selected"' : '' ?>>Số điện thoại</option>
                                                        <option value="cmnd" <?=$search_option == 'cmnd' ? 'selected="selected"' : '' ?>>Chứng minh nhân dân</option>
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
                                            <div id="s-birthday2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($birthday_min || $birthday_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-date2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="birthday_min" placeholder="Ngày sinh 1" class="kh-datepicker2 form-control" value="<?=$birthday_min;?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="birthday_max" placeholder="Ngày sinh 2" class="kh-datepicker2 form-control" value="<?=$birthday_max;?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="s-date2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_min || $date_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                                <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                                <div class="ele-date2">
                                                    <div class="" style="display:flex;">
                                                        <input type="text" name="date_min" placeholder="Ngày tạo 1" class="kh-datepicker2 form-control" value="<?=$date_min;?>">
                                                    </div>
                                                    <div class="ml-10" style="display:flex;">
                                                        <input type="text" name="date_max" placeholder="Ngày tạo 2" class="kh-datepicker2 form-control" value="<?=$date_max;?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                                            <button type="submit" class="btn btn-default ml-15" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                                        </div>
                                        <div class="d-flex a-start" style="padding-left:0;padding-right:0;display:flex;margin-top:15px;">
                                            <div style="" class="form-group row">
                                                <select name="orderByColumn" class="ml-10 form-control col-5">
                                                    <option value="">Sắp xếp theo cột</option>
                                                    <option value="full_name" <?=$orderByColumn == "full_name" ? "selected" : "";?>>Tên đầy đủ</option>
                                                    <option value="email" <?=$orderByColumn == "email" ? "selected" : "";?>>Email</option>
                                                    <option value="phone" <?=$orderByColumn == "phone" ? "selected" : "";?>>Số điện thoại</option>
                                                    <option value="cmnd" <?=$orderByColumn == "cmnd" ? "selected" : "";?>>Số chứng minh nhân dân</option>
                                                    <option value="address" <?=$orderByColumn == "address" ? "selected" : "";?>>Địa chỉ</option>
                                                    <option value="birthday" <?=$orderByColumn == "birthday" ? "selected" : "";?>>Ngày sinh</option>
                                                    <option value="created_at" <?=$orderByColumn == "created_at" ? "selected" : "";?>>Ngày tạo</option>
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
                                        <button tabindex="-1" onclick="delMore()" id="btn-delete-fast" class="dt-button button-red">Xoá nhanh</button>
                                        <?php } ?>
                                        <?php
                                            if($allow_update) {
                                        ?>
                                        <button tabindex="-1" onclick="uptMore('','<?=$tab_unique;?>')" id="btn-upt-fast" class="dt-button button-green">Sửa nhanh</button>
                                        <?php } ?>
                                        <?php
                                            if($allow_read) {
                                        ?>
                                        <button tabindex="-1" onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                                        <?php } ?>
                                        <?php
                                            if($allow_insert) {
                                        ?>
                                        <button tabindex="-1" onclick="insMore(3)" id="btn-ins-fast" class="dt-button button-blue">Thêm nhanh</button>
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
                                        <?php
                                            if($allow_role){
                                        ?>
                                        <button tabindex="1" onclick="roleMore()" id="btn-role-fast" class="dt-button button-purple">Phân quyền</button>
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
                                <?php
                                    // query
                                    $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;  
                                    $limit = $_SESSION['paging'];
                                    $start_page = $limit * ($page - 1);
                                    $sql_get_total = "select count(*) as 'countt' from user $where";
                                    $total = fetch(sql_query($sql_get_total))['countt'];
                                    $sql_get_user = "select * from user $where limit $start_page,$limit";
                                    $cnt=0;
                                    $rows = fetch_all(sql_query($sql_get_user));
                                ?>
                                <!--Table user-->
                                <div class="table-responsive table-game-start">
                                    <table id="table-user_manage" class="table table-bordered table-striped ">
                                        <thead>
                                            <tr  style="cursor:pointer;">
                                                <th style="width:20px !important;">
                                                    <input <?=$upt_more == 1 ? "checked":"";?> style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                                </th>
                                                <th class="w-120 th-so-thu-tu">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-ten-day-du">Tên đầy đủ <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-170 th-email">Email <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-170 th-so-dien-thoai">Số điện thoại <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-300 th-so-cmnd">Số chứng minh nhân dân <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-300">Địa chỉ</th>
                                                <th class="w-150 th-ngay-sinh">Ngày sinh <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-120 th-ngay-tao">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-200">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody dt-parent-id dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-user" id="m-user-body">
                                            <?php foreach($rows as $row) { ?>
                                                <?php $cnt1 = $cnt + 1;?>
                                                <tr class='<?=$upt_more == 1 ? "selected":"";?>' id="<?=$row["id"];?>">
                                                    <td>
                                                        <input <?=$upt_more == 1 ? "checked":"";?> style="width:16px;height:16px;cursor:pointer" value="<?=$row["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-notification')" type="checkbox" name="check_id<?=$row["id"];?>">
                                                    </td>
                                                    <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                                                    <td class="ten-day-du">
                                                        <?php
                                                            if($upt_more == 1) {
                                                                echo "<input tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='upt_fullname' value='$row[full_name]'><span class='text-danger'></span>";
                                                            } else {
                                                                if($row['is_lock'] == 1){
                                                                    echo '<i class="fas fa-lock mr-1"></i>';
                                                                }
                                                                echo $row["full_name"];
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="email"><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='upt_email' value='$row[email]'><span class='text-danger'></span>" : $row["email"]?></td>
                                                    <td class="so-dien-thoai"><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='number' name='upt_phone' value='$row[phone]'><span class='text-danger'></span>" : $row["phone"]?></td>
                                                    <td class="so-cmnd"><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='number' name='upt_cmnd' value='$row[cmnd]'><span class='text-danger'></span>" : $row["cmnd"]?></td>
                                                    <td><?=$upt_more == 1 ? "<textarea tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='upt_address'>$row[address]</textarea><span class='text-danger'></span>" : $row["address"]?></td>
                                                    <td class="ngay-sinh">
                                                        <?php 
                                                            if($upt_more == 1) {
                                                                if(strlen($row["birthday"]) > 0) {
                                                                    echo "<input tabindex='$cnt1' data-date2='" . Date("Y-m-d",strtotime($row["birthday"])) .  "' style='cursor:pointer;' class='kh-datepicker2 kh-inp-ctrl' type='text' name='upt_birthday' readonly value='" . Date("d-m-Y",strtotime($row["birthday"])) . "'><span class='text-danger'></span>";
                                                                } else {
                                                                    echo "<input tabindex='$cnt1' data-date2='" . "" .  "' style='cursor:pointer;' class='kh-datepicker2 kh-inp-ctrl' type='text' name='upt_birthday' readonly value=''><span class='text-danger'></span>";
                                                                }
                                                            } else {
                                                                if(strlen($row["birthday"]) > 0) {
                                                                    echo Date("d-m-Y",strtotime($row["birthday"]));
                                                                } else {
                                                                    echo "";
                                                                }
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="ngay-tao"><?=$row["created_at"] ? Date("d-m-Y",strtotime($row["created_at"])) : "";?></td>
                                                    <td>
                                                        <?php
                                                            if($upt_more != 1) {
                                                        ?>
                                                        <?php
                                                            if($allow_read) {
                                                        ?>
                                                        <button onclick="openModalRead()" class="btn-read-user dt-button button-grey"
                                                        data-id="<?=$row["id"];?>">Xem</button>
                                                        <?php } ?>
                                                        <?php
                                                            if($allow_update) {
                                                        ?>
                                                        <button onclick="openModalUpdate()" class="btn-update-user dt-button button-green"
                                                        data-id="<?=$row["id"];?>">Sửa</button>
                                                        <?php } ?>
                                                        <?php
                                                            if($allow_delete) {
                                                        ?>
                                                        <button onclick="processDelete()" class="btn-delete-row dt-button button-red" data-id="<?=$row["id"];?>">Xoá
                                                        </button>
                                                        <?php } ?>
                                                        <?php
                                                            } else {
                                                        ?>
                                                        <?php
                                                            if($allow_update) {
                                                        ?>
                                                        <button tabindex="0" dt-count="0" data-id="<?=$row["id"];?>" onclick="uptMore2()" class="dt-button button-green">Sửa</button>
                                                        <?php } ?>
                                                        <?php
                                                            }
                                                        ?>
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
                                                    <input <?=$upt_more == 1 ? "checked":"";?> style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                                </th>
                                                <th>Số thứ tự</th>
                                                <th>Tên đầy đủ</th>
                                                <th>Email</th>
                                                <th>Số điện thoại</th>
                                                <th>Số chứng minh nhân dân</th>
                                                <th>Địa chỉ</th>
                                                <th>Ngày sinh</th>
                                                <th>Ngày tạo</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
    </div>
</div>
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin nhân viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage_user" method="post">
                    
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-xl2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Phân quyền nhân viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="k-role-select">
                    <div class="col-12" style="padding-right:0px;padding-left:0px;">
                        <form class="a-center j-between" style="margin-bottom: 17px;display:flex;" action="<?php echo get_url_current_page();?>" method="get">
                            <div class="d-flex j-between a-center">
                                <div>
                                    <label class="d-block" for="search_option">Họ tên nhân viên: </label>
                                    <select class="form-control k-role-select2" name="search_option" style="width:300px" onchange="roleMoreChange()">
                                    </select>
                                </div>
                                <div class="ml-10">
                                    <label class="d-block" for="search_option">Chức năng trang web: </label>
                                    <select class="form-control" name="menu" style="width:300px">
                                        <option value="">Chọn chức năng</option>
                                        <?php
                                            $sql = "select * from menus";
                                            $result3 = fetch_all(sql_query($sql));
                                            foreach($result3 as $res3) {
                                        ?>
                                                <option value="<?=$res3['id']?>"><?=$res3['name']?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="ml-10">
                                    <label class="d-block" for="search_option">Quyền: </label>
                                    <div class="k-checkbox d-flex j-between">
                                        <div class="d-flex a-center">
                                            <span>Đọc</span>
                                            <input type="checkbox" name="c_role" onchange="setRole('read')">
                                        </div>
                                        <div class="ml-5 d-flex a-center">
                                            <span>Thêm</span>
                                            <input type="checkbox" name="c_role" onchange="setRole('insert')">
                                        </div>
                                        <div class="ml-5 d-flex a-center">
                                            <span>Xoá</span>
                                            <input type="checkbox" name="c_role" onchange="setRole('delete')">
                                        </div>
                                        <div class="ml-5 d-flex a-center" >
                                            <span>Sửa</span>
                                            <input type="checkbox" name="c_role" onchange="setRole('update')">
                                        </div>
                                        <input type="hidden" name="roles" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="ml-10">
                                <button onclick="insRole()" type="button" class="dt-button button-blue">Thêm quyền</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="k-role-set">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-xl3">
    <div class="modal-dialog modal-xl" style="min-width:1700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin nhân viên</h4>
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
                                <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Tên đầy đủ','Email','Số điện thoại','Số cmnd','Địa chỉ','Ngày sinh'],['upt_fullname2','upt_email2','upt_phone2','upt_cmnd2','upt_address2','upt_birthday2'])">
                            </div>
                            <div class="file file-excel mr-10">
                                <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Tên đầy đủ','Email','Số điện thoại','Số cmnd','Địa chỉ','Ngày sinh'],['upt_fullname2','upt_email2','upt_phone2','upt_cmnd2','upt_address2','upt_birthday2'])">
                            </div>
                            <div class="d-empty">
                                <button onclick="delEmpty()" style="font-size:30px;font-weight:bold;width:64px;height:64px;" class="dt-button button-red k-btn-plus">x</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <table class='table table-bordered' style="height:auto;">
                        <thead>
                            <tr>
                                <th class='w-150'>Số thứ tự</th>
                                <th>Tên đầy đủ</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Số cmnd</th>
                                <th>Địa chỉ</th>
                                <th>Ngày sinh</th>
                                <th>Chức vụ</th>
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
<script>
    <?=$upt_more != 1 ? "setSortTable();" : null;?>
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
   }
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
   
</script>
<script>
    function validate(){
        let test = true;
        let full_name = $('#full_name').val();
        let email = $('#email').val();
        let cmnd = $('#cmnd').val();
        let phone = $('#phone').val();
        let address = $('#address').val();
        let birthday = $('#birthday').val();
        let password = $('#password').val();
        let type = $('#type > option:selected').val();
        if(full_name == "") {
            $('#full_name').focus();
            $.alert({
                title: "",
                content: "Họ tên nhân viên không được để trống.",
            });
            //$('#full_name').focus();
            test = false;
        } else if(email == "") {
            $('#email').focus();
            $.alert({
                title: "Thông báo",
                content: "Email nhân viên không được để trống.",
            });
            test = false;
        } else if(phone == "") {
            $('#phone').focus();
            $.alert({
                title: "Thông báo",
                content: "Số điện thoại nhân viên không được để trống."
            });
            test = false;
        } else if(cmnd == "") {
            $('#cmnd').focus();
            $.alert({
                title: "Thông báo",
                content: "Số chứng minh nhân dân của nhân viên không được để trống."
            });
            test = false;
        } else if(birthday == "") {
            $('#birthday').focus();
            $.alert({
                title: "Thông báo",
                content: "Ngày sinh của nhân viên không được để trống."
            });
            test = false;
        } else if(address == "") {
            $('#address').focus();
            $.alert({
                title: "Thông báo",
                content: "Địa chỉ của nhân viên không được để trống."
            });
            test = false;
        } else if(password == "") {
            $('#password').focus();
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu của nhân viên không được để trống.",
            });
            test = false;
        } else if(type == "") {
            $.alert({
                title: "Thông báo",
                content: "Chức vụ của nhân viên không được để trống.",
            });
            test = false;
        }
        return test;
    }
    function readURL(input){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            $('#display-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function openModalInsert(){
        $('#manage_user').load("ajax_user.php?status=Insert",() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy',
                onSelect: function(dateText,inst) {
                    console.log(dateText.split("-"));
                    dateText = dateText.split("-");
                    $('#birthday').attr('data-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                }
            });
            $("#fileInput").on("change",function(){
                $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
                readURL(this); 
            });
        })
    }
    function openModalRead(){
        let id = $(event.currentTarget).attr('data-id');
        let target = $(event.currentTarget);
        target.closest("tr").addClass("bg-color-selected");
        $('#manage_user').load("ajax_user.php?status=Read&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    }
    function openModalUpdate(){
        let id = $(event.currentTarget).attr('data-id');
        $(event.currentTarget).closest("tr").addClass("bg-color-selected");
        $('#manage_user').load("ajax_user.php?status=Update&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy',
                onSelect: function(dateText, inst) {
                    console.log(dateText.split("-"));
                    dateText = dateText.split("-");
                    $('#birthday').attr('data-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                }
            });
            $("#fileInput").on("change",function(){
                $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
                readURL(this); 
            });
        })
    }
    function processModalInsert(){
        event.preventDefault();
        if(validate()) {
            let file = $('input[name="img_name"]')[0].files;
            let formData = new FormData($('#manage_user')[0]);
            formData.append("status","Insert");
            formData.append("full_name",$('#full_name').val());
            formData.append("email",$('#email').val());
            formData.append("phone",$('#phone').val());
            formData.append("cmnd",$('#cmnd').val());
            formData.append("address",$('#address').val());
            
            let birthday = $('#birthday').val().split('-');
            birthday = birthday[2] + "-" + birthday[1] + "-" + birthday[0];
            formData.append("birthday",birthday);
            formData.append("type",$('select[name="type"] > option:selected').val());
            formData.append("password",$('#password').val());  
            if(file.length > 0) {
                formData.append('img_name',file[0]);
            }
            $.ajax({
                url:window.location.href,
                type: "POST",
                cache:false,
                dataType:"json",
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
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
    }
    function processModalUpdate(){
        event.preventDefault();
        if(validate()) {
            let file = $('input[name="img_name"]')[0].files;
            let formData = new FormData($('#manage_user')[0]);
            formData.append("status","Update");
            formData.append("id",$('input[name=id]').val());
            formData.append("full_name",$('#full_name').val());
            formData.append("email",$('#email').val());
            formData.append("phone",$('#phone').val());
            formData.append("cmnd",$('#cmnd').val());
            formData.append("address",$('#address').val());
            let birthday = $('#birthday').val().split('-');
            birthday = birthday[2] + "-" + birthday[1] + "-" + birthday[0];
            formData.append("birthday",birthday);
            formData.append("type",$('select[name="type"] > option:selected').val());
            formData.append("password",$('#password').val());
            if(file.length > 0) {
                formData.append('img_name',file[0]); 
            }
            if(validate()) {
                $.ajax({
                url:window.location.href,
                type: "POST",
                cache:false,
                dataType:"json",
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    //data = JSON.parse(data);
                    console.log(data);
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
            
        }
    }
    function processDelete(){
        let id = $(event.currentTarget).attr('data-id');
        let target = $(event.currentTarget);
        target.closest("tr").addClass("bg-color-selected");
        $.confirm({
            title: 'Thông báo',
            content: 'Bạn có chắc chắn muốn xoá thông tin nhân viên này ?',
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
                                loadDataComplete('Delete');
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
    }
</script>
<script>
    var dt_user;
    $(".kh-datepicker2").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText, inst) {
            console.log(dateText.split("-"));
            dateText = dateText.split("-");
            $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
        }
    })
    $("#modal-xl3").on("hidden.bs.modal",function(){
      $("#form-insert table tbody").remove();
      $("input[name='count2']").val("");
      $("input[name='count2']").attr("data-plus",0);
    })
    $("#modal-xl").on("hidden.bs.modal",function(){
      $("tr").removeClass('bg-color-selected');
    })
    $('#modal-xl3').on('hidden.bs.modal', function (e) {
      $('#form-insert table tbody').remove();
      $('#form-insert #paging').remove();
      $('[data-plus]').attr('data-plus',0);
    })
    function insAll(){
      let test = true;
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      let count = $('td input[name="ins_fullname"]').length;
      $('td input[name="ins_fullname"]').each(function(){
        if($(this).val() != ""){
          formData.append("ins_fullname[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="ins_email"]').each(function(){
        if($(this).val() != ""){
          formData.append("ins_email[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="ins_phone"]').each(function(){
        if($(this).val() != "") {
          formData.append("ins_phone[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td input[name="ins_cmnd"]').each(function(){
        if($(this).val() != "") {
          formData.append("ins_cmnd[]",$(this).val());
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td textarea[name="ins_address"]').each(function(){
        if($(this).val() != "") {
          formData.append("ins_address[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;  
        }
      });
      $('td input[name="ins_birthday"]').each(function(){
        if($(this).val() != "") {
          formData.append("ins_birthday[]",$(this).val());
          $(this).siblings("p.text-danger").text("");
        } else {
          $(this).siblings("p.text-danger").text("Không được để trống");
          test = false;
        }
      });
      $('td select[name="ins_type"]').each(function(){
        if($(this).find('option:selected').val() != "") {
          formData.append("ins_type[]",$(this).val());
          $(this).parent().find("p.text-danger").text("");
        } else {
          $(this).parent().find("p.text-danger").text("Không được để trống");
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
                    });
                    $('#modal-xl3').modal('hide');
                    loadDataComplete('Insert');
                }
            },
            error: function(data){
                console.log("Error: " + data);
            }
        })
      }
      
    }
    function uptAll(){
        let test = true;
        let all_checkbox = getIdCheckbox();
        let list_checkbox = all_checkbox['result'].split(",");
        let formData = new FormData();
        if(list_checkbox.length == 0) {
            $.alert({
                title:"Thông báo",
                content:"Vui lòng chọn dòng cần lưu",
            });
            return;
        }
        for(i = 0 ; i < list_checkbox.length ; i++) {
            formData.append("upt_id[]",list_checkbox[i]);
        }
        $('tr.selected input[name="upt_fullname"]').each(function(){
            if($(this).val() != "") {
                formData.append("upt_fullname[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
        });
        $('tr.selected input[name="upt_email"]').each(function(){
            if($(this).val() != "") {
                formData.append("upt_email[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="upt_phone"]').each(function(){
            if($(this).val() != "") {
                formData.append("upt_phone[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected input[name="upt_cmnd"]').each(function(){
            if($(this).val() != "") {
                formData.append("upt_cmnd[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
            
        });
        $('tr.selected textarea[name="upt_address"]').each(function(){
            if($(this).val() != "") {
                formData.append("upt_address[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
        });
        $('tr.selected input[name="upt_birthday"]').each(function(){
            if($(this).val() != "") {
                formData.append("upt_birthday[]",$(this).val());
                $(this).siblings("span.text-danger").text("");
            } else {
                $(this).siblings("span.text-danger").text("Không được để trống");
                test = false;
            }
        });
        if(test) {
            formData.append("status","upt_all");
            formData.append("len",list_checkbox.length);
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
                        });
                        loadDataComplete();
                        $('.section-save').hide();
                    }
                },
                error: function(data){
                    console.log("Error: " + data);
                }
            })
        }
        
    }
    function unlockMore(){
        let all_checkbox = getIdCheckbox();
        if(all_checkbox['count'] > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn mở khoá " + all_checkbox['count'] + " tài khoản nhân viên này",
                buttons: {
                    "Có": function(){
                        $.ajax({
                            url: window.location.href,
                            type: "POST",
                            data: {
                                status: "unlock_more",
                                rows: all_checkbox['result'],
                            },
                            success: function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok"){
                                    $.alert({
                                        title: "Thông báo",
                                        content: "Bạn đã mở khoá tài khoản nhân viên thành công",
                                    });
                                    loadDataComplete()
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
        let all_checkbox = getIdCheckbox();
        if(all_checkbox['count'] > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn khoá " + all_checkbox['count'] + " tài khoản nhân viên này",
                buttons: {
                    "Có": function(){
                        $.ajax({
                            url: window.location.href,
                            type: "POST",
                            data: {
                                status: "lock_more",
                                rows: all_checkbox['result'],
                            },
                            success: function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok"){
                                    $.alert({
                                        title: "Thông báo",
                                        content: "Bạn đã khoá tài khoản nhân viên thành công",
                                    });
                                    loadDataComplete();
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
    function roleMoreChange(){
        let id = $(".k-role-select2 > option:selected").val();
        $(".k-role-set").load(`ajax_user_role.php?id=${id}`,() => {
            $('#modal-xl2').modal({backdrop: 'static', keyboard: false});
        });
    }
    function setRole(role){
        if($(event.currentTarget).is(':checked') == true) {
            let arr = $("input[name='roles']").val().split(",");
            if(arr.indexOf(role) == -1) {
                arr.push(role);
            }
            $("input[name='roles']").val(arr.join(","));
        } else {
            let arr = $("input[name='roles']").val().split(",");
            let index = arr.indexOf(role);
            if(index != -1) {
                arr.splice(index,1);
            }
            $("input[name='roles']").val(arr.join(","));
        }   

        console.log($("input[name='roles']").val().split(","));
    }
    function setRoleUpt(role,selector){
        if($(event.currentTarget).is(':checked') == true) {
            let arr = $(selector).val().split(",");
            if(arr.indexOf(role) == -1) {
                arr.push(role);
            }
            $(selector).val(arr.join(","));
        } else {
            let arr = $(selector).val().split(",");
            let index = arr.indexOf(role);
            if(index != -1) {
                arr.splice(index,1);
            }
            $(selector).val(arr.join(","));
        }
    }
    function insRole(){
        let user_id = $(".k-role-select2 > option:selected").val();
        let menu = $("select[name='menu'] > option:selected").val();
        let role = $("input[name='roles']").val();
        if(menu == ""){
			toastr["error"]("Vui lòng ko để trống tên chức năng");
            return;
        } else if(user_id == ""){
			toastr["error"]("Vui lòng chọn thông tin người dùng");
            return;
        } else if(role == "") {
			toastr["error"]("Vui lòng ko để trống tên quyền");
            return;
        } 
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                status: "ins_role",
                user_id : user_id,
                role: role,
                menu: menu,
                
            },
            success: function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $('.k-role-set').empty();
                    $('.k-role-set').load("ajax_user_role.php?id=" + user_id,() => {
						toastr["success"]("Bạn đã phân quyền thành công");
                    });
                } else {
					toastr["error"](data.error);
                }
            },
            error: function(data){
                console.log("Error: " + data);
            }
        });
    }
    function uptRole(user_id,menu_id){
        let target = event.currentTarget;
        let role = $(target).closest('tr').find("td input[name='rolesUpt']").val();
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                status: "upt_role",
                user_id: user_id,
                menu_id: menu_id,
                role: role,
                
            },
            success:function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $('.k-role-set').load("ajax_user_role.php?id=" + user_id,() => {
						toastr["success"]("Bạn đã sửa quyền thành công");
                    });
                }
            },
            error:function(data){
                console.log("Error: " + data);
            }
        })
    }
    function delRole(user_id,menu_id){
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                status: "del_role",
                user_id: user_id,
                menu_id: menu_id,
            },
            success:function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $('.k-role-set').load("ajax_user_role.php?id=" + user_id,() => {
						toastr["success"]("Bạn đã xoá quyền thành công");
                    });
                }
            },
            error:function(data){
                console.log("Error: " + data);
            }
        })
    }
    function roleMore(){
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                status: "role_load",
                roles: getIdCheckbox()['result'],
            },
            success: function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    let html = "";
                    html += `<option value="">Chọn người dùng</option>`;
                    data["result"].forEach((ele,ind) => {
                        html += `<option value="${ele.id}">${ele.full_name}</option>`;
                    });
                    $(html).appendTo(".k-role-select2");
                    $(".k-role-select2").select2();
                    $('#modal-xl2').modal({backdrop: 'static', keyboard: false});
                    $('#modal-xl2').on('hidden.bs.modal',function(){
                        $(".k-role-select2").empty();
                        $(".k-role-set table").empty();
                    })
                }
            },
            error: function(data){
                console.log("Error:" + data);
            }
        });
    }
</script>
<!--js section end-->
<?php
    include_once("include/pagination.php");
    include_once("include/footer.php");
?>
<?php
    } else if (is_post_method()) {
        // code to be executed post method
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $full_name = isset($_REQUEST["full_name"]) ? $_REQUEST["full_name"] : null;
        $cmnd = isset($_REQUEST["cmnd"]) ? $_REQUEST["cmnd"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
        $birthday = isset($_REQUEST["birthday"]) ? Date("Y-m-d",strtotime($_REQUEST["birthday"])) : null;
        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null ;
        if($status == "Delete") {
            $success = "Bạn đã xoá dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng reload lại trang";
            $sql = "Update user set is_delete = ? where id = ?";
            sql_query($sql,[1,$id]);
            echo_json(['msg' => 'ok',"success" => $success]);
        } else if($status == "Update") {
            $success = "Bạn đã sửa dữ liệu thành công";
            $dir = "upload/user/";
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            $dir = "upload/user/" . $id;
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            if($_FILES['img_name']['name'] != "") {
               $sql_get_old_file = "select img_name from user where id = '$id'";
               $old_file = fetch(sql_query($sql_get_old_file))['img_name'];
               if(file_exists($old_file)) {
                   unlink($old_file);
                   chmod($dir, 0777);
               }
               $ext = strtolower(pathinfo($_FILES['img_name']['name'],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $id . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['img_name']['tmp_name'],$path);
               $sql_update = "update user set img_name = ? where id = ?";
               sql_query($sql_update,[$path,$id]);
            }
            $sql = "Update user set full_name = ?,type = ?,email = ?,phone = ?,cmnd = ?,address = ?,birthday = ? where id = ?";
            sql_query($sql,[$full_name,$type,$email,$phone,$cmnd,$address,$birthday,$id]);
            echo_json(["msg" => "ok","success" => $success]);
        } else if($status == "Insert") {
            $success = "Bạn đã thêm dữ liệu thành công";
            $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
            $sql = "Insert into user(full_name,type,email,phone,cmnd,address,birthday,password) values(?,?,?,?,?,?,?,?)";
            sql_query($sql,[$full_name,$type,$email,$phone,$cmnd,$address,$birthday,$password]);
            $insert = ins_id();
            if($insert > 0) {
                $success = "Cập nhật dữ liệu thành công";
                $error = "Đã có lỗi xảy ra. Vui lòng tải lại trang";
                $image = null;
                $dir = "upload/user/";
                if(!file_exists($dir)) {
                    mkdir($dir, 0777); 
                    chmod($dir, 0777);
                }
                $dir = "upload/user/" . $insert;
                if(!file_exists($dir)) {
                    mkdir($dir, 0777); 
                    chmod($dir, 0777);
                }
                if($_FILES['img_name']['name'] != "") {
                    $ext = strtolower(pathinfo($_FILES['img_name']['name'],PATHINFO_EXTENSION));
                    $file_name = md5(rand(1,999999999)). $insert . "." . $ext;
                    $file_name = str_replace("_","",$file_name);
                    $path = $dir . "/" . $file_name ;
                    move_uploaded_file($_FILES['img_name']['tmp_name'],$path);
                    $sql_update = "update user set img_name = ? where id = ?";
                    sql_query($sql_update,[$path,$insert]);
                }
                $__arr['id'] = $insert;
            }
            echo_json(["msg" => "ok","success" => $success]);
        } else if($status == "upt_more") {
            $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
            $upt_fullname = isset($_REQUEST["upt_fullname"]) ? $_REQUEST["upt_fullname"] : null;
            $upt_email = isset($_REQUEST["upt_email"]) ? $_REQUEST["upt_email"] : null;
            $upt_phone = isset($_REQUEST["upt_phone"]) ? $_REQUEST["upt_phone"] : null;
            $upt_cmnd = isset($_REQUEST["upt_cmnd"]) ? $_REQUEST["upt_cmnd"] : null;
            $upt_address = isset($_REQUEST["upt_address"]) ? $_REQUEST["upt_address"] : null;
            $upt_birthday = isset($_REQUEST["upt_birthday"]) ? Date("Y-m-d",strtotime($_REQUEST["upt_birthday"])) : null;
            $sql = "Update user set full_name = ?,email = ?,phone = ?,cmnd = ?,address = ?,birthday = ? where id = ?";
            sql_query($sql,[$upt_fullname,$upt_email,$upt_phone,$upt_cmnd,$upt_address,$upt_birthday,$upt_id]);
            echo_json(["msg" => "ok"]);
        } else if($status == "del_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update user set is_delete = ? where id = ?";
                sql_query($sql,[1,$row]);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "role_load") {
            $rows = isset($_REQUEST['roles']) ? $_REQUEST['roles'] : null;
            if($rows) {
                $sql = "select * from user where id in ($rows) and is_delete = 0";
            } else {
                $sql = "select * from user where is_delete = 0";
            }
            $result = sql_query($sql);
            $result = fetch_all($result);
            $result2 = [];
            foreach($result as $res) {
                array_push($result2,[
                    "id" => $res['id'],
                    "full_name" => $res['full_name'],
                ]);
            }
            echo_json(["msg" => "ok","result" => $result2]);    
        } else if($status == "ins_role") {
            $user_id = isset($_REQUEST["user_id"]) ? $_REQUEST["user_id"] : null;
            $menu = isset($_REQUEST["menu"]) ? $_REQUEST["menu"] : null;
            $role = isset($_REQUEST["role"]) ? $_REQUEST["role"] : null;
            $sql_check = "select count(user_id) as 'cnt' from user_role where user_id='$user_id' and menu_id='$menu'";
            if(fetch(sql_query($sql_check))['cnt'] > 0) {
                echo_json(["msg" => "not_ok","error" => "Chức năng này đã được thêm"]);
            }
            $sql = "Insert into user_role(user_id,menu_id,permission) values(?,?,?)";
            sql_query($sql,[$user_id,$menu,$role]);
            echo_json(["msg" => "ok"]);
        } else if($status == "upt_role") {
            $user_id = isset($_REQUEST["user_id"]) ? $_REQUEST["user_id"] : null;
            $menu = isset($_REQUEST["menu_id"]) ? $_REQUEST["menu_id"] : null;
            $role = isset($_REQUEST["role"]) ? $_REQUEST["role"] : null;
            $sql = "Update user_role set permission = ? where user_id = ? and menu_id = ?";
            sql_query($sql,[$role,$user_id,$menu]);
            echo_json(["msg" => "ok"]);
        } else if($status == "del_role") {
            $user_id = isset($_REQUEST["user_id"]) ? $_REQUEST["user_id"] : null;
            $menu = isset($_REQUEST["menu_id"]) ? $_REQUEST["menu_id"] : null;
            $sql = "Delete from user_role where user_id='$user_id' and menu_id='$menu'";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "lock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update user set is_lock = ? where id = ?";
                sql_query($sql,[1,$row]);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "unlock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update user set is_lock = ? where id = ?";
                sql_query($sql,[0,$row]);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_more") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $ins_fullname = isset($_REQUEST["ins_fullname"]) ? $_REQUEST["ins_fullname"] : null;
            $ins_type = isset($_REQUEST["ins_type"]) ? $_REQUEST["ins_type"] : null;
            $ins_email = isset($_REQUEST["ins_email"]) ? $_REQUEST["ins_email"] : null;
            $ins_phone = isset($_REQUEST["ins_phone"]) ? $_REQUEST["ins_phone"] : null;
            $ins_address = isset($_REQUEST["ins_address"]) ? $_REQUEST["ins_address"] : null;
            $ins_cmnd = isset($_REQUEST["ins_cmnd"]) ? $_REQUEST["ins_cmnd"] : null;
            $ins_birthday = isset($_REQUEST["ins_birthday"]) ? Date('Y-m-d',strtotime($_REQUEST["ins_birthday"])) : null;
            $sql = "Insert into user(full_name,type,email,phone,cmnd,address,birthday) values(?,?,?,?,?,?,?)";
            sql_query($sql,[$ins_fullname,$ins_type,$ins_email,$ins_phone,$ins_cmnd,$ins_address,$ins_birthday]);
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $ins_fullname = isset($_REQUEST["ins_fullname"]) ? $_REQUEST["ins_fullname"] : null;
            $ins_email = isset($_REQUEST["ins_email"]) ? $_REQUEST["ins_email"] : null;
            $ins_phone = isset($_REQUEST["ins_phone"]) ? $_REQUEST["ins_phone"] : null;
            $ins_address = isset($_REQUEST["ins_address"]) ? $_REQUEST["ins_address"] : null;
            $ins_cmnd = isset($_REQUEST["ins_cmnd"]) ? $_REQUEST["ins_cmnd"] : null;
            $ins_birthday = isset($_REQUEST["ins_birthday"]) ? $_REQUEST["ins_birthday"]  : null;
            $ins_type = isset($_REQUEST["ins_type"]) ? $_REQUEST["ins_type"] : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {
                    $birth = $ins_birthday[$i] ? Date('Y-m-d',strtotime($ins_birthday[$i])) : null;
                    $sql = "Insert into user(full_name,type,email,phone,cmnd,address,birthday) values(?,?,?,?,?,?,?)";
                    sql_query($sql,[$ins_fullname[$i],$ins_type[$i],$ins_email[$i],$ins_phone[$i],$ins_cmnd[$i],$ins_address[$i],$birth]);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "upt_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
            $upt_fullname = isset($_REQUEST["upt_fullname"]) ? $_REQUEST["upt_fullname"] : null;
            $upt_email = isset($_REQUEST["upt_email"]) ? $_REQUEST["upt_email"] : null;
            $upt_phone = isset($_REQUEST["upt_phone"]) ? $_REQUEST["upt_phone"] : null;
            $upt_address = isset($_REQUEST["upt_address"]) ? $_REQUEST["upt_address"] : null;
            $upt_cmnd = isset($_REQUEST["upt_cmnd"]) ? $_REQUEST["upt_cmnd"] : null;
            $upt_birthday = isset($_REQUEST["upt_birthday"]) ? $_REQUEST["upt_birthday"]  : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {  
                    $birth = $upt_birthday[$i] ? Date('Y-m-d',strtotime($upt_birthday[$i])) : null;                 
                    $sql = "Update user set full_name=?,email=?,phone=?,cmnd=?,address=?,birthday=? where id = ?";
                    sql_query($sql,[$upt_fullname[$i],$upt_email[$i],$upt_phone[$i],$upt_cmnd[$i],$upt_address[$i],$birth,$upt_id[$i]]);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "saveTabFilter") {
            $_SESSION['user_tab_id'] = isset($_SESSION['user_tab_id']) ? $_SESSION['user_tab_id'] + 1 : 1;
            $tab_name = isset($_SESSION['user_tab_id']) ? "tab_" . $_SESSION['user_tab_id'] : null;
            $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
            $tab_unique = uniqid("tab_");
            $_SESSION['user_manage_tab'] = isset($_SESSION['user_manage_tab']) ? $_SESSION['user_manage_tab'] : [];
            array_push($_SESSION['user_manage_tab'],[
               "tab_unique" => $tab_unique,
               "tab_name" => $tab_name,
               "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
            ]);
            echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['user_manage_tab']) - 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
         } else if($status == "deleteTabFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
            array_splice($_SESSION['user_manage_tab'],$index,1);
            if(trim($is_active_2) == "") {
               echo_json(["msg" => "ok"]);
            }  else if($is_active_2 == 1) {
               if(array_key_exists($index,$_SESSION['user_manage_tab'])) {
                  echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['user_manage_tab'][$index]['tab_urlencode']]);
               } else if(array_key_exists($index - 1,$_SESSION['user_manage_tab'])){
                  echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['user_manage_tab'][$index - 1]['tab_urlencode']]);
               } else {
                  echo_json(["msg" => "ok","tab_urlencode" => "user_manage.php?tab_unique=all"]);
               }
            }
         } else if($status == "changeTabNameFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
            $_SESSION['user_manage_tab'][$index]['tab_name'] = $new_tab_name;
            echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['user_manage_tab'][$index]['tab_urlencode']]);
         }
    }
?>