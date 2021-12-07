<?php
    include_once("../lib/database.php");
    redirect_if_login_status_false();
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
<!--<link rel="stylesheet" href="css/fixedColumns.dataTables.min.css">-->
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
                            <button id="btn-add-user" class="dt-button button-blue">Thêm nhân viên</button>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form style="margin-bottom: 17px;display:flex;align-items:flex-start;" action="user_manage.php" method="get">
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
                                    <input type="text" name="date_min[]" placeholder="Ngày tạo 1" class="kh-datepicker2 form-control" value="">
                                 </div>
                                 <div class="ml-10" style="display:flex;">
                                    <input type="text" name="date_max[]" placeholder="Ngày tạo 2" class="kh-datepicker2 form-control" value="">
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
                                    <input type="text" name="date_min[]" placeholder="Ngày tạo 1" class="kh-datepicker2 form-control" value="<?=$d_min ? Date("d-m-Y",strtotime($d_min)) : "";?>">
                                 </div>
                                 <div class="ml-10" style="display:flex;">
                                    <input type="text" name="date_max[]" placeholder="Ngày tạo 2" class="kh-datepicker2 form-control" value="<?=$d_max ? Date("d-m-Y",strtotime($d_max)) : "";?>">
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
                                    if($allow_delete) {
                                ?>
                                <button tabindex="-1" onclick="delMore()" id="btn-delete-fast" class="dt-button button-red">Xoá nhanh</button>
                                <?php } ?>
                                <?php
                                    if($allow_update) {
                                ?>
                                <button tabindex="-1" onclick="uptMore()" id="btn-upt-fast" class="dt-button button-green">Sửa nhanh</button>
                                <?php } ?>
                                <?php
                                    if($allow_read) {
                                ?>
                                <button tabindex="-1" onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                                <?php } ?>
                                <?php
                                    if($allow_insert) {
                                ?>
                                <button tabindex="-1" onclick="insMore()" id="btn-ins-fast" class="dt-button button-blue">Thêm nhanh</button>
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
							// set get
							$get = $_GET;
							unset($get['page']);
							$str_get = http_build_query($get);
							// query
                            $arr_paras = [];
                            $where .= " and is_delete = 0";
                            if($str) {
                                $where .= " and user.id in ($str)";
                            }
                            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                            $limit = $_SESSION['paging'];
                            $start_page = $limit * ($page - 1);
                            $sql_get_total = "select count(*) as 'countt' from user $where";
                            $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                            array_push($arr_paras,$start_page);
                            array_push($arr_paras,$limit);
                            $sql_get_user = "select * from user $where order by id desc limit ?,? ";
                            //print_r($sql_get_user);
                            /*print_r($arr_paras);*/
							$cnt=0;
                            $rows = db_query($sql_get_user,$arr_paras);
                        ?>
                        <!--Table user-->
                        <div class="table-responsive">
                            <table id="m-user-table" class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="w-100">Số thứ tự</th>
                                        <th>Tên đầy đủ</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th class="w-200">Số chứng minh nhân dân</th>
                                        <th class="w-150">Địa chỉ</th>
                                        <th class="w-100">Ngày sinh</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Ngày tạo</th>
                                        <th class="w-200">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="m-user-body">
                                    <?php foreach($rows as $row) { ?>
                                        <?php $cnt1 = $cnt + 1;?>
                                        <tr id="<?=$row["id"];?>">
                                            <td></td>
                                            <td><?=$total - ($start_page + $cnt);?></td>
                                            <td>
                                                <?php
                                                    if($upt_more == 1) {
                                                        echo "<input tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='u_fullname' value='$row[full_name]'>";
                                                    } else {
                                                        if($row['is_lock'] == 1){
                                                            echo '<i class="fas fa-lock mr-1"></i>';
                                                        }
                                                        echo $row["full_name"];
                                                    }
                                                ?>
                                            </td>
                                            <td><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='u_email' value='$row[email]'>" : $row["email"]?></td>
                                            <td><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='number' name='u_phone' value='$row[phone]'>" : $row["phone"]?></td>
                                            <td><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='number' name='u_cmnd' value='$row[cmnd]'>" : $row["cmnd"]?></td>
                                            <td><?=$upt_more == 1 ? "<textarea tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='u_address'>$row[address]</textarea>" : $row["address"]?></td>
                                            <td>
                                                <?php 
                                                    if($upt_more == 1) {
                                                        if(strlen($row["birthday"]) > 0) {
                                                            echo "<input tabindex='$cnt1' data-date2='" . Date("Y-m-d",strtotime($row["birthday"])) .  "' style='cursor:pointer;' class='kh-datepicker2 kh-inp-ctrl' type='text' name='u_birthday' readonly value='" . Date("d-m-Y",strtotime($row["birthday"])) . "'>";
                                                        } else {
                                                            echo "<input tabindex='$cnt1' data-date2='" . "" .  "' style='cursor:pointer;' class='kh-datepicker2 kh-inp-ctrl' type='text' name='u_birthday' readonly value=''>";
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
                                            <td><?=$upt_more == 1 ? "<input tabindex='$cnt1' class='kh-inp-ctrl' type='text' name='u_username' value='$row[username]'>" : $row["username"]?></td>
                                            <td><?=$row["created_at"] ? Date("d-m-Y H:i:s",strtotime($row["created_at"])) : "";?></td>
                                            <td>
                                                <?php
                                                    if($upt_more != 1) {
                                                ?>
                                                <?php
                                                    if($allow_read) {
                                                ?>
                                                <button class="btn-read-user dt-button button-grey"
                                                data-id="<?=$row["id"];?>">Xem</button>
                                                <?php } ?>
                                                <?php
                                                    if($allow_update) {
                                                ?>
                                                <button class="btn-update-user dt-button button-green"
                                                data-id="<?=$row["id"];?>">Sửa</button>
                                                <?php } ?>
                                                <?php
                                                    if($allow_delete) {
                                                ?>
                                                <button class="btn-delete-row dt-button button-red" data-id="<?=$row["id"];?>">Xoá
                                                </button>
                                                <?php } ?>
                                                <?php
                                                    } else {
                                                ?>
                                                <?php
                                                    if($allow_update) {
                                                ?>
                                                <button tabindex="0" dt-count="0" data-id="<?=$row["id"];?>" onclick="uptThisRow()" class="dt-button button-green">Sửa</button>
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
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Số thứ tự</th>
                                        <th>Tên đầy đủ</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Số chứng minh nhân dân</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày sinh</th>
                                        <th>Tên đăng nhập</th>
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
                                    <select class="form-control k-role-select2" name="search_option" style="width:300px" onclick="roleMoreChange()">
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
                <!--<div class="card-footer">
                    <button id="btn-role" type="submit" class="dt-button button-purple">Lưu thay đổi</button>
                </div>-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-xl3">
    <div class="modal-dialog modal-xl" style="min-width:1700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin nhân viên</h4>
                <button onclick="insAll()" class="dt-button button-blue">Lưu dữ liệu</button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="form-user2" class="modal-body">
                    <div class="row j-between">
                        <div style="margin-left: 7px;" class="form-group">
                            <label for="">Nhập số dòng cần thêm: </label>
                            <input style="margin-left:5px;width: auto;" class="kh-inp-ctrl" type="number" name='count2'>
                            <button onclick="showRow(1)" class="dt-button button-blue">Ok</button>
                        </div>
                        <div class="d-flex j-between">
                            <div class="k-plus">
                                <button data-plus="1" onclick="insRow()" style="font-size:15px;" class="dt-button button-blue k-btn-plus">+</button>
                            </div>
                            <div class="k-minus">
                                <button data-minus="1" onclick="delRow()" style="font-size:15px;" class="dt-button button-blue k-btn-minus">-</button>
                            </div>
                        </div>
                        </div>
                    </div>
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
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/dataTables.select.min.js"></script>
<script src="js/dataTables.fixedColumns.min.js"></script>
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
<script src="js/select2.min.js"></script>
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
                <input type="text" name="date_min[]" placeholder="Ngày tạo 1" class="kh-datepicker2 form-control" value="">
                </div>
                <div class="ml-10" style="display:flex;">
                <input type="text" name="date_max[]" placeholder="Ngày tạo 2" class="kh-datepicker2 form-control" value="">
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
    });
    $(document).ready(function (e) {
        $('#first_tab').on('focus', function() {
            $('input[tabindex="1"].kh-inp-ctrl').first().focus();
        });
        $('#btn-role-fast').on('focus',function(){
            $('input[tabindex="<?=$cnt;?>"]').focus();
        });
        dt_user = $("#m-user-table").DataTable({
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
                    "targets": 10
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
                    "extend": "copy",
                    "text": "Sao chép bảng (1)",
                    "key": {
                        "key": '1',
                    },
                    "filename": "danh_sach_nhan_vien_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu danh sách nhân viên trích xuất ngày <?=Date("d-m-Y",time());?>",
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
                    "extend": "print",
                    "text": "In bảng (5)",
                    "key": {
                        "key": '5',
                    },
                    "filename": "danh_sach_nhan_vien_ngay_<?=Date("d-m-Y",time());?>",
                    "title": "Dữ liệu danh sách nhân viên trích xuất ngày <?=Date("d-m-Y",time());?>",
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
        dt_user.buttons.exportData( {
            columns: ':visible'
        });
        dt_user.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                dt_user.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                dt_user.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function() {
            if (dt_user.rows({
                    selected: true
                }).count() !== dt_user.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });
        //
        // php auto select all rows when focus update all function execute
        <?=$upt_more == 1 ? 'dt_user.rows().select();' . PHP_EOL . '$("th.select-checkbox").addClass("selected");'.PHP_EOL  : "";?>
    });
    function showPicker(){
        $('input[name="u_birthday2"]').datepicker({
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
      //$('#modal-xl2').modal('show');
      $('#modal-xl3').modal({backdrop: 'static', keyboard: false});
    }
    function insMore2(){
        let u_fullname2 = $(event.currentTarget).closest('tr').find('td input[name="u_fullname2"]').val();
        let u_email2 = $(event.currentTarget).closest('tr').find('td input[name="u_email2"]').val();
        let u_phone2 = $(event.currentTarget).closest('tr').find('td input[name="u_phone2"]').val();
        let u_cmnd2 = $(event.currentTarget).closest('tr').find('td input[name="u_cmnd2"]').val();
        let u_address2 = $(event.currentTarget).closest('tr').find('td textarea[name="u_address2"]').val();
        let u_birthday2 = $(event.currentTarget).closest('tr').find('td input[name="u_birthday2"]').attr('data-date');
        let u_username2 = $(event.currentTarget).closest('tr').find('td input[name="u_username2"]').val();
        let formData = new FormData();
        formData.append("u_fullname2",u_fullname2);
        formData.append("u_email2",u_email2);
        formData.append("u_phone2",u_phone2);
        formData.append("u_cmnd2",u_cmnd2);
        formData.append("u_address2",u_address2);
        formData.append("u_birthday2",u_birthday2);
        formData.append("u_username2",u_username2);
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
    function insAll(){
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      $('td input[name="u_fullname2"]').each(function(){
         formData.append("u_fullname2[]",$(this).val());
      });
      $('td input[name="u_email2"]').each(function(){
         formData.append("u_email2[]",$(this).val());
      });
      $('td input[name="u_phone2"]').each(function(){
         formData.append("u_phone2[]",$(this).val());
      });
      $('td input[name="u_cmnd2"]').each(function(){
         formData.append("u_cmnd2[]",$(this).val());
      });
      $('td textarea[name="u_address2"]').each(function(){
         formData.append("u_address2[]",$(this).val());
      });
      $('td input[name="u_username2"]').each(function(){
         formData.append("u_username2[]",$(this).val());
      });
      $('td input[name="u_birthday2"]').each(function(){
         formData.append("u_birthday2[]",$(this).attr('data-date'));
      });
      formData.append("token","<?php echo_token(); ?>");
      formData.append("status","ins_all");
      formData.append("len",len);
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
    function insRow(){
      let page = $('[data-plus]').attr('data-plus');
      let html = "";
      let count2 = parseInt(page / 7) + 1;
      html = `
        <tr data-row-id='${parseInt(page) + 1}'>
            <td>${parseInt(page) + 1}</td>
            <td><input class='kh-inp-ctrl' name='u_fullname2' type='text' value=''></td>
            <td><input class='kh-inp-ctrl' name='u_email2' type='text' value=''></td>
            <td><input class='kh-inp-ctrl' name='u_phone2' type='text' value=''></td>
            <td><input class='kh-inp-ctrl' name='u_cmnd2' type='text' value=''></td>
            <td><textarea class='kh-inp-ctrl' name='u_address2' value=''></textarea></td>
            <td><input class='kh-inp-ctrl' data-date='' name='u_birthday2' type='text' value=''></td>
            <td><input class='kh-inp-ctrl' name='u_username2' type='text' value=''></td>
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
         $(html).appendTo('#form-user2 table');
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
    function delRow(){
      let page = $('[data-plus]').attr('data-plus');
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
      count_row_z_index++;
    }
    function showRow(page,apply_dom = true){
      let count = $('input[name="count2"]').val();
      if(count == "") {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng không để trống số dòng thêm",
        })
        return;
      }
      if(count < 1) {
        $.alert({
          title: "Thông báo",
          content: "Vui lòng nhập số dòng lớn hơn 0",
        })
        return;
      }
      limit = 7;
      if(apply_dom) {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
        $('#form-user2 table').remove();
        $('#form-user2 #paging').remove();
        let html = `
        <table class='table table-bordered' style="min-height:100px;height:auto;">
          <thead>
            <tr>
                <th class='w-150'>Số thứ tự</th>
                <th>Tên đầy đủ</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Số cmnd</th>
                <th>Địa chỉ</th>
                <th>Ngày sinh</th>
                <th>Tên đăng nhập</th>
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
                  <td><input class='kh-inp-ctrl' name='u_fullname2' type='text' value=''></td>
                  <td><input class='kh-inp-ctrl' name='u_email2' type='text' value=''></td>
                  <td><input class='kh-inp-ctrl' name='u_phone2' type='text' value=''></td>
                  <td><input class='kh-inp-ctrl' name='u_cmnd2' type='text' value=''></td>
                  <td><textarea class='kh-inp-ctrl' name='u_address2' value=''></textarea></td>
                  <td><input class='kh-inp-ctrl' data-date="" name='u_birthday2' type='text' value=''></td>
                  <td><input class='kh-inp-ctrl' name='u_username2' type='text' value=''></td>
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
                <td><input class='kh-inp-ctrl' name='u_fullname2' type='text' value=''></td>
                <td><input class='kh-inp-ctrl' name='u_email2' type='text' value=''></td>
                <td><input class='kh-inp-ctrl' name='u_phone2' type='text' value=''></td>
                <td><input class='kh-inp-ctrl' name='u_cmnd2' type='text' value=''></td>
                <td><textarea class='kh-inp-ctrl' name='u_address2' value=''></textarea></td>
                <td><input class='kh-inp-ctrl' data-date='' name='u_birthday2' type='text' value=''></td>
                <td><input class='kh-inp-ctrl' name='u_username2' type='text' value=''></td>
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
        $(html).appendTo('#form-user2');
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
      $('#modal-xl3').on('hidden.bs.modal', function (e) {
        $('#form-user2 table').remove();
        $('#form-user2 #paging').remove();
        $('input[name="count2"]').val("");
      })
    } 
    function uptAll(){
        let formData = new FormData();
        let _data = dt_user.rows(".selected").select().data();
        if(_data.length == 0) {
            $.alert({
                title:"Thông báo",
                content:"Vui lòng chọn dòng cần lưu",
            });
            return;
        }
        for(i = 0 ; i < _data.length ; i++) {
            formData.append("user_id[]",_data[i].DT_RowId);
        }
        $('tr.selected input[name="u_fullname"]').each(function(){
            formData.append("u_fullname2[]",$(this).val());
        });
        $('tr.selected input[name="u_email"]').each(function(){
            formData.append("u_email2[]",$(this).val());
        });
        $('tr.selected input[name="u_phone"]').each(function(){
            formData.append("u_phone2[]",$(this).val());
        });
        $('tr.selected input[name="u_cmnd"]').each(function(){
            formData.append("u_cmnd2[]",$(this).val());
        });
        $('tr.selected textarea[name="u_address"]').each(function(){
            formData.append("u_address2[]",$(this).val());
        });
        $('tr.selected input[name="u_username"]').each(function(){
            formData.append("u_username2[]",$(this).val());
        });
        $('tr.selected input[name="u_birthday"]').each(function(){
            formData.append("u_birthday2[]",$(this).attr('data-date2'));
        });
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
    function unlockMore(){
        let arr_del = [];
        let _data = dt_user.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn mở khoá " + _data.length + " tài khoản nhân viên này",
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
                                        content: "Bạn đã mở khoá tài khoản nhân viên thành công",
                                        buttons: {
                                            "Ok": function(){
                                                location.href="user_manage.php";
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
        let _data = dt_user.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn khoá " + _data.length + " tài khoản nhân viên này",
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
                                        content: "Bạn đã khoá tài khoản nhân viên thành công",
                                        buttons: {
                                            "Ok": function(){
                                                location.href="user_manage.php";
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
            $.alert({
                title:"Thông báo",
                content: "Vui lòng ko để trống tên chức năng",
            });
            return;
        } else if(user_id == ""){
            $.alert({
                title:"Thông báo",
                content: "Vui lòng chọn thông tin người dùng",
            });
            return;
        } else if(role == "") {
            $.alert({
                title:"Thông báo",
                content: "Vui lòng ko để trống tên quyền",
            });
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
                token: "<?php echo_token();?>",
            },
            success: function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $('.k-role-set').empty();
                    $('.k-role-set').load("ajax_user_role.php?id=" + user_id,() => {
                        $.alert({
                            title: "Thông báo",
                            content: "Bạn đã phân quyền thành công",
                        });
                    });
                } else {
                    $.alert({
                        title: "Thông báo",
                        content: data.error,
                    });
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
                token: "<?php echo_token();?>",
            },
            success:function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $('.k-role-set').load("ajax_user_role.php?id=" + user_id,() => {
                        $.alert({
                            title: "Thông báo",
                            content: "Bạn đã sửa quyền thành công",
                        });
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
                token: "<?php echo_token();?>",
            },
            success:function(data){
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $('.k-role-set').load("ajax_user_role.php?id=" + user_id,() => {
                        $.alert({
                            title: "Thông báo",
                            content: "Bạn đã xoá quyền thành công",
                        });
                    });
                }
            },
            error:function(data){
                console.log("Error: " + data);
            }
        })
    }
    function readMore(){
        let arr_del = [];
        let _data = dt_user.rows(".selected").select().data();
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
        $('#manage_user').load(`ajax_user.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
            let html2 = `
            <div id="paging" style="justify-content:center;" class="row">
                <nav id="pagination3">
                </nav>
            </div>
            `;
            $(html2).appendTo('#manage_user');
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
    function roleMore(){
        let arr_del = [];
        let _data = dt_user.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        let str_arr_upt = arr_del.join(",");
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                status: "role_load",
                roles: str_arr_upt,
                token: '<?php echo_token();?>',
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
    function delMore(){
        let arr_del = [];
        let _data = dt_user.rows(".selected").select().data();
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
                                        location.href="user_manage.php";
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
    function uptMore(){
        let arr_del = [];
        let _data = dt_user.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        let str_arr_upt = arr_del.join(",");
        location.href="user_manage.php?upt_more=1&str=" + str_arr_upt;
    }
    function uptThisRow(){
        let name = $(event.currentTarget).closest("tr").find("td input[name='u_fullname']").val();
        let email = $(event.currentTarget).closest("tr").find("td input[name='u_email']").val();
        let phone = $(event.currentTarget).closest("tr").find("td input[name='u_phone']").val();
        let address = $(event.currentTarget).closest("tr").find("td textarea[name='u_address']").val();
        let cmnd = $(event.currentTarget).closest("tr").find("td input[name='u_cmnd']").val();
        let birthday = $(event.currentTarget).closest("tr").find("td input[name='u_birthday']").attr('data-date2');
        let username = $(event.currentTarget).closest("tr").find("td input[name='u_username']").val();
        let id = $(event.currentTarget).attr('data-id');
        let this2 = $(event.currentTarget);
        console.log(name);
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                status: "upt_more",
                u_fullname: name,
                u_email: email,
                u_phone: phone,
                u_address: address,
                u_cmnd: cmnd,
                u_id: id,
                u_username: username,
                u_birthday: birthday,
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
</script>
<script>
    $(document).ready(function(){
        // validate
        const validate = () => {
            let test = true;
            let full_name = $('#full_name').val();
            let email = $('#email').val();
            let cmnd = $('#cmnd').val();
            let phone = $('#phone').val();
            let address = $('#address').val();
            let birthday = $('#birthday').val();
            let username = $('#username').val();
            let password = $('#password').val();
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
            } else if(username == "") {
                $('#username').focus();
                $.alert({
                    title: "Thông báo",
                    content: "Username của nhân viên không được để trống."
                });
                test = false;
            } else if(password == "") {
                $('#password').focus();
                $.alert({
                    title: "Thông báo",
                    content: "Mật khẩu của nhân viên không được để trống.",
                });
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
        $(document).on('click','#btn-add-user',(e) => {
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
        });
        // mở modal sửa dữ liệu
        $(document).on('click','.btn-update-user',function(e) {  
            let id = $(e.currentTarget).attr('data-id');
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
        });
        // thêm 
        $(document).on('click','#btn-insert',function(e){
            event.preventDefault();
            if(validate()) {
                let file = $('input[name="img_name"]')[0].files;
                let formData = new FormData($('#manage_user')[0]);
                formData.append("token","<?php echo_token();?>");
                formData.append("status","Insert");
                formData.append("full_name",$('#full_name').val());
                formData.append("email",$('#email').val());
                formData.append("phone",$('#phone').val());
                formData.append("cmnd",$('#cmnd').val());
                formData.append("address",$('#address').val());
                formData.append("birthday",$('#birthday').val());
                formData.append("username",$('#username').val());
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
                            let html = "";
                            html += `<tr style='background-color:#ef7a1752;' id='user-${data.id}'>`;
                            html += `<td>${data.number}</td>`;
                            html += `<td>${data.full_name}</td>`;
                            html += `<td>${data.email}</td>`;
                            html += `<td>${data.phone}</td>`;
                            html += `<td>${data.cmnd}</td>`;
                            html += `<td>${data.address}</td>`;
                            html += `<td>${data.birthday}</td>`;
                            html += `<td>${data.username}</td>`;
                            html += `<td>${data.created_at}</td>`;
                            html += `<td>`;
                            html += `<button class="btn-update-user btn btn-primary" data-id="${data.id}" data-number="${data.number}">Sửa</button>`;
                            html += `<button style="margin-left:3px;" class="btn-delete-row btn btn-danger" data-id="${data.id}" data-number="${data.number}">Xoá</button>`;
                            html += `</td>`;
                            html += `</tr>`;
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.href="user_manage.php";
                                    },
                                }
                            });
                            /*html = $(html);
                            //$('#m-user-body').append(html);
                            dt_user.row.add(html[0]).draw();*/
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
            console.log($('input[name=number]').val());
            if(validate()) {
                let file = $('input[name="img_name"]')[0].files;
                let formData = new FormData($('#manage_user')[0]);
                formData.append("token","<?php echo_token();?>");
                formData.append("number",$('input[name=number]').val());
                formData.append("status","Update");
                formData.append("id",$('input[name=id]').val());
                formData.append("full_name",$('#full_name').val());
                formData.append("email",$('#email').val());
                formData.append("phone",$('#phone').val());
                formData.append("cmnd",$('#cmnd').val());
                formData.append("address",$('#address').val());
                formData.append("birthday",$('#birthday').val());
                formData.append("username",$('#username').val());
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
                            let id = $('input[name=id]').val();
                            let html = "";
                            html += `<tr style="background-color:#91c08552;" id='user-${id}'>`;
                            html += `<td>${data.number}</td>`;
                            html += `<td>${data.full_name}</td>`;
                            html += `<td>${data.email}</td>`;
                            html += `<td>${data.phone}</td>`;
                            html += `<td>${data.cmnd}</td>`;
                            html += `<td>${data.address}</td>`;
                            html += `<td>${data.birthday}</td>`;
                            html += `<td>${data.username}</td>`;
                            html += `<td>${data.created_at}</td>`;
                            html += `<td>`;
                            html += `<button class="btn-update-user btn btn-primary" data-id="${id}" data-number="${data.number}">Sửa</button>`;
                            html += `<button style="margin-left:3px;" class="btn-delete-row btn btn-danger" data-id="${id}" data-number="${data.number}">Xoá</button>`;
                            html += `</td>`;
                            html += "</tr>";
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.reload();
                                    },
                                }
                            });
                            //$("#user-" + id).replaceWith(html);
                            /*let one_row = dt_user.row(click_number).data();
                            console.log(one_row);
                            one_row[0] = `${data.number}`;
                            one_row[1] = `${data.full_name}`;
                            one_row[2] = `${data.email}`;
                            one_row[3] = `${data.phone}`;
                            one_row[4] = `${data.cmnd}`;
                            one_row[5] = `${data.address}`;
                            if(data.birthday != undefined) {
                                one_row[6] = `${data.birthday}`;
                            }
                            one_row[7] = `${data.username}`;
                            dt_user.row(click_number).data(one_row).draw();*/
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
        });
        // xoá 
        $(document).on('click','.btn-delete-row',function(e){
            /*click_number = $(this).closest('tr');
            console.log(click_number);*/
            let id = $(e.currentTarget).attr('data-id');
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
                                    //$('#user-' + id).remove();
                                    /*console.log(click_number);
                                    dt_user.row(click_number).remove().draw();*/
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

                    }
                }
            });
        });
        // xem
        $(document).on('click','.btn-read-user',function(e){
            let id = $(e.currentTarget).attr('data-id');
            $('#manage_user').load("ajax_user.php?status=Read&id=" + id,() => {
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
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
        $full_name = isset($_REQUEST["full_name"]) ? $_REQUEST["full_name"] : null;
        $cmnd = isset($_REQUEST["cmnd"]) ? $_REQUEST["cmnd"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
        $birthday = isset($_REQUEST["birthday"]) ? $_REQUEST["birthday"] : null;
        $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null ;
        if($status == "Delete") {
            $success = "Bạn đã xoá dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng reload lại trang";
            ajax_db_update_by_id('user',["is_delete" => 1],[$id],["success" => $success],["error" => $error]);
        } else if($status == "Update") {
            $sql_check_exist = "Select cmnd,email,phone,count(*) as 'countt' from user where (email = ? or cmnd = ? or phone = ?) and id <> ? limit 1";
            $success = "Bạn đã sửa dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng reload lại trang";
            $row = fetch_row($sql_check_exist,[$email,$cmnd,$phone,$id]);
            if(2 == 1) {
                if($row['cmnd'] != "") {
                    $error = "Số chứng minh nhân dân bị trùng ";
                } else if($row['email'] != "") {
                    $error = "Email bị trùng";
                } else if($row['phone'] != "") {
                    $error = "Phone bị trùng";
                }
                echo_json(["msg" => "not_ok","error" => $error]);
            }
            $image = null;
            //file_upload(['file' => 'img_cmnd_file'],'user','img_name',"upload/user/identify/",$id,$image,'cmnd_');
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
               $old_file = fetch_row($sql_get_old_file)['img_name'];
               if(file_exists($old_file)) {
                   unlink($old_file);
                   chmod($dir, 0777);
               }
               $ext = strtolower(pathinfo($_FILES['img_name']['name'],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $id . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['img_name']['tmp_name'],$path);
               $sql_update = "update user set img_name='$path' where id = '$id'";
               db_query($sql_update);
            }
            $password = password_hash($password,PASSWORD_DEFAULT);
            if($birthday) {
                $__arr = [
                    "full_name" => $full_name,
                    "email" => $email,
                    "phone" => $phone,
                    "cmnd" => $cmnd,
                    "address" => $address,
                    "birthday" => Date('Y-m-d',strtotime($birthday)),
                    "username" => $username,
                    "password" => $password,
                ];
            } else {
                $__arr = [
                    "full_name" => $full_name,
                    "email" => $email,
                    "phone" => $phone,
                    "cmnd" => $cmnd,
                    "address" => $address,
                    "username" => $username,
                    "password" => $password,
                ];
            }
            db_update_by_id('user',$__arr,[$id]);
            if($birthday) {
                $__arr['birthday'] = Date('d-m-Y',strtotime($birthday));
            }
            //print_r($__arr);
            echo_json(array_merge(['msg' => 'ok',"number" => $number,'success' => $success],$__arr));
        } else if($status == "Insert") {
            //validate zone
            //print_r('Insert');
            $sql_check_exist = "Select cmnd,email,phone,count(*) as 'countt' from user where (email = ? or cmnd = ? or phone = ?) limit 1";
            $success = "Bạn đã thêm dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng tải lại trang";
            $row = fetch_row($sql_check_exist,[$email,$cmnd,$phone]);
            if(2 == 1) {
                if($row['cmnd'] != "") {
                    $error = "Số chứng minh nhân dân bị trùng";
                } else if($row['email'] != "") {
                    $error = "Email bị trùng";
                } else if($row['phone'] != "") {
                    $error = "Phone bị trùng";
                }
                echo_json(["msg" => "not_ok","error" => $error]);
            }
            else if(1 == 1) {
                //print_r('Insert');
                $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
                $__arr = [
                    "full_name" => $full_name,
                    "email" => $email,
                    "phone" => $phone,
                    "cmnd" => $cmnd,
                    "address" => $address,
                    "birthday" => Date('Y-m-d',strtotime($birthday)),
                    "username" => $username,
                    "password" => $password,
                    "created_at"=>date('Y-m-d H-i-s',time())
                ];
                $insert = db_insert_id('user',$__arr);
                
                if($insert > 0) {
                    // insert
                    $success = "Cập nhật dữ liệu thành công";
                    $error = "Đã có lỗi xảy ra. Vui lòng tải lại trang";
                    $image = null;
                    //file_upload(['file' => 'img_cmnd_file'],'user','img_name',"upload/user/identify/",$insert,$image,'cmnd_');
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
                        $sql_update = "update user set img_name='$path' where id = '$insert'";
                        db_query($sql_update);
                    }
                    $__arr['id'] = $insert;
                    echo_json(array_merge(['msg' => 'ok',"number" => $number,'success' => $success],$__arr));
                    /*if(db_update_by_id('user',['img_cmnd' => $image],[$insert])) {
                        $__arr['img_cmnd'] = $image;
                        $__arr['id'] = $insert;
                        echo_json(array_merge(['msg' => 'ok','success' => $success],$__arr));
                    } else {
                        echo_json(['msg' => 'not_ok','error' => $error]);
                    }*/
                }
            }
        } else if($status == "upt_more") {
            $u_id = isset($_REQUEST["u_id"]) ? $_REQUEST["u_id"] : null;
            $u_fullname = isset($_REQUEST["u_fullname"]) ? $_REQUEST["u_fullname"] : null;
            $u_username = isset($_REQUEST["u_username"]) ? $_REQUEST["u_username"] : null;
            $u_email = isset($_REQUEST["u_email"]) ? $_REQUEST["u_email"] : null;
            $u_phone = isset($_REQUEST["u_phone"]) ? $_REQUEST["u_phone"] : null;
            $u_cmnd = isset($_REQUEST["u_cmnd"]) ? $_REQUEST["u_cmnd"] : null;
            $u_address = isset($_REQUEST["u_address"]) ? $_REQUEST["u_address"] : null;
            $u_birthday = isset($_REQUEST["u_birthday"]) ? $_REQUEST["u_birthday"] : null;
            $sql = "Update user set full_name='$u_fullname',email='$u_email',phone='$u_phone',cmnd='$u_cmnd',address='$u_address',birthday='$u_birthday',username='$u_username' where id='$u_id'";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "del_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update user set is_delete = 1 where id = '$row'";
                sql_query($sql);
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
            echo json_encode(["msg" => "ok","result" => $result2]);    
            exit();
        } else if($status == "ins_role") {
            $user_id = isset($_REQUEST["user_id"]) ? $_REQUEST["user_id"] : null;
            $menu = isset($_REQUEST["menu"]) ? $_REQUEST["menu"] : null;
            $role = isset($_REQUEST["role"]) ? $_REQUEST["role"] : null;
            $sql_check = "select count(user_id) as 'cnt' from user_role where user_id='$user_id' and menu_id='$menu'";
            if(fetch(sql_query($sql_check))['cnt'] > 0) {
                echo_json(["msg" => "not_ok","error" => "Chức năng này đã được thêm"]);
            }
            $sql = "Insert into user_role(user_id,menu_id,permission) values('$user_id','$menu','$role')";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "upt_role") {
            $user_id = isset($_REQUEST["user_id"]) ? $_REQUEST["user_id"] : null;
            $menu = isset($_REQUEST["menu_id"]) ? $_REQUEST["menu_id"] : null;
            $role = isset($_REQUEST["role"]) ? $_REQUEST["role"] : null;
            $sql = "Update user_role set permission='$role' where user_id='$user_id' and menu_id='$menu'";
            sql_query($sql);
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
                $sql = "Update user set is_lock = 1 where id = '$row'";
                sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "unlock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update user set is_lock = 0 where id = '$row'";
                sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_more") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $u_fullname2 = isset($_REQUEST["u_fullname2"]) ? $_REQUEST["u_fullname2"] : null;
            $u_email2 = isset($_REQUEST["u_email2"]) ? $_REQUEST["u_email2"] : null;
            $u_phone2 = isset($_REQUEST["u_phone2"]) ? $_REQUEST["u_phone2"] : null;
            $u_address2 = isset($_REQUEST["u_address2"]) ? $_REQUEST["u_address2"] : null;
            $u_cmnd2 = isset($_REQUEST["u_cmnd2"]) ? $_REQUEST["u_cmnd2"] : null;
            $u_username2 = isset($_REQUEST["u_username2"]) ? $_REQUEST["u_username2"] : null;
            $u_birthday2 = isset($_REQUEST["u_birthday2"]) ? Date('Y-m-d',strtotime($_REQUEST["u_birthday2"])) : null;
            $sql = "Insert into user(full_name,email,phone,cmnd,address,birthday,username) values('$u_fullname2','$u_email2','$u_phone2','$u_cmnd2','$u_address2','$u_birthday2','$u_username2')";
            sql_query($sql);
            echo_json(["msg" => "ok"]);
        } else if($status == "ins_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $u_fullname2 = isset($_REQUEST["u_fullname2"]) ? $_REQUEST["u_fullname2"] : null;
            $u_email2 = isset($_REQUEST["u_email2"]) ? $_REQUEST["u_email2"] : null;
            $u_phone2 = isset($_REQUEST["u_phone2"]) ? $_REQUEST["u_phone2"] : null;
            $u_address2 = isset($_REQUEST["u_address2"]) ? $_REQUEST["u_address2"] : null;
            $u_cmnd2 = isset($_REQUEST["u_cmnd2"]) ? $_REQUEST["u_cmnd2"] : null;
            $u_username2 = isset($_REQUEST["u_username2"]) ? $_REQUEST["u_username2"] : null;
            $u_birthday2 = isset($_REQUEST["u_birthday2"]) ? $_REQUEST["u_birthday2"]  : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {
                    $birth = $u_birthday2[$i] ? Date('Y-m-d',strtotime($u_birthday2[$i])) : null;
                    $sql = "Insert into user(full_name,email,phone,cmnd,address,birthday,username) values('$u_fullname2[$i]','$u_email2[$i]','$u_phone2[$i]','$u_cmnd2[$i]','$u_address2[$i]','$birth','$u_username2[$i]')";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        } else if($status == "upt_all") {
            $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
            $user_id = isset($_REQUEST["user_id"]) ? $_REQUEST["user_id"] : null;
            $u_fullname2 = isset($_REQUEST["u_fullname2"]) ? $_REQUEST["u_fullname2"] : null;
            $u_email2 = isset($_REQUEST["u_email2"]) ? $_REQUEST["u_email2"] : null;
            $u_phone2 = isset($_REQUEST["u_phone2"]) ? $_REQUEST["u_phone2"] : null;
            $u_address2 = isset($_REQUEST["u_address2"]) ? $_REQUEST["u_address2"] : null;
            $u_cmnd2 = isset($_REQUEST["u_cmnd2"]) ? $_REQUEST["u_cmnd2"] : null;
            $u_username2 = isset($_REQUEST["u_username2"]) ? $_REQUEST["u_username2"] : null;
            $u_birthday2 = isset($_REQUEST["u_birthday2"]) ? $_REQUEST["u_birthday2"]  : null;
            if($len) {
                for($i = 0 ; $i < $len ; $i++) {  
                    $birth = $u_birthday2[$i] ? Date('Y-m-d',strtotime($u_birthday2[$i])) : null;                 
                    $sql = "Update user set full_name='$u_fullname2[$i]',email='$u_email2[$i]',phone='$u_phone2[$i]',cmnd='$u_cmnd2[$i]',address='$u_address2[$i]',birthday='$birth',username='$u_username2[$i]' where id = '$user_id[$i]'";
                    sql_query($sql);
                }
                echo_json(["msg" => "ok"]);
            }
        }
    }
?>