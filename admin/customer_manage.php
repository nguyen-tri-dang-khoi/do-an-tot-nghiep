<?php
    include_once("../lib/database_v2.php");
    logout_session_timeout();
    check_access_token();
    redirect_if_login_status_false();
    if(is_get_method()) {
        // permission crud for customer
        $allow_read = $allow_lock = $allow_unlock = false; 
        if(check_permission_crud("customer_manage.php","read")) {
          $allow_read = true;
        }
        if(check_permission_crud("customer_manage.php","lock")) {
            $allow_lock = true;
        }
        if(check_permission_crud("customer_manage.php","unlock")) {
            $allow_unlock = true;
        }
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $birthday_min = isset($_REQUEST['birthday_min']) ? $_REQUEST['birthday_min'] : null;
        $birthday_max = isset($_REQUEST['birthday_max']) ? $_REQUEST['birthday_max'] : null;
        $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
        $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
        $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $where = "where 1=1 and is_delete = 0 ";
        $order_by = "Order by id desc";
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
        if($birthday_min && $birthday_max) {
            if($birthday_min != "" && $birthday_max != "") {
                $birthday_min = Date("Y-m-d",strtotime($birthday_min));
                $birthday_max = Date("Y-m-d",strtotime($birthday_max));
                $where .= " and (birthday >= '$birthday_min 00:00:00' and birthday <= '$birthday_max 23:59:59')";
            } else if($birthday_min == "" && $birthday_max != ""){
                $birthday_min = Date("Y-m-d",strtotime($birthday_min));
                $where .= " and (birthday >= '$birthday_min 00:00:00')";
            } else if($birthday_min != "" && $birthday_max == ""){
                $birthday_max = Date("Y-m-d",strtotime($birthday_max));
                $where .= " and (birthday <= '$birthday_max 23:59:59')";
            }
        }
        if($date_min && $date_max) {
            if($date_min != "" && $date_max != "") {
                $date_min = Date("Y-m-d",strtotime($date_min));
                $date_max = Date("Y-m-d",strtotime($date_max));
                $where .= " and (created_at >= '$date_min 00:00:00' and created_at <= '$date_max 23:59:59')";
            } else if($date_min != "" && $date_max == "") {
                $date_min = Date("Y-m-d",strtotime($date_min));
                $where .= " and (created_at >= '$date_min 00:00:00')";
            } else if($date_min == "" && $date_max != "") {
                $date_max = Date("Y-m-d",strtotime($date_max));
                $where .= " and (created_at <= '$date_max 23:59:59')";
            }
        }
        if($orderByColumn && $orderStatus) {
            $order_by = "ORDER BY $orderByColumn $orderStatus";
           
        }
        $where .= " $order_by";
        log_v($where);
?>
<!--html & css section start-->

<style>
    .sort-asc,.sort-desc {
        display: none;
    }
</style>
<div class="container-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quản lý khách hàng</h3>
                    </div>
                    <div class="card-body ok-game-start">
                        <div id="load-all">
                            <link rel="stylesheet" href="css/tab.css">             
                            <div style="padding-right:0px;padding-left:0px;" class="col-12 mb-20 d-flex a-center j-between">
                                <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                                    <?php
                                        $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                        $_SESSION['customer_manage_tab'] = isset($_SESSION['customer_manage_tab']) ? $_SESSION['customer_manage_tab'] : [];
                                        $_SESSION['customer_tab_id'] = isset($_SESSION['customer_tab_id']) ? $_SESSION['customer_tab_id'] : 0;
                                    ?>
                                    <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('customer_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>
                                    <?php
                                        $ik = 0;
                                        $is_active = false;
                                        if(count($_SESSION['customer_manage_tab']) > 0) {
                                            foreach($_SESSION['customer_manage_tab'] as $tab) {
                                            if($tab['tab_unique'] == $tab_unique) {
                                                $_SESSION['customer_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                            }
                                    ?>
                                        <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                            <button onclick="loadDataInTab('<?=$_SESSION['customer_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
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
                                <form id="form-filter" action="customer_manage.php" method="get" onsubmit="searchTabLoad('#form-filter')">
                                    <div class="a-star d-flex">
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
                                                    <input type="text" name="birthday_min" placeholder="Ngày sinh 1" class="kh-datepicker2 form-control" value="<?=$birthday_min ? Date("d-m-Y",strtotime($birthday_min)) : '';?>">
                                                </div>
                                                <div class="ml-10" style="display:flex;">
                                                    <input type="text" name="birthday_max" placeholder="Ngày sinh 2" class="kh-datepicker2 form-control" value="<?=$birthday_max ? Date("d-m-Y",strtotime($birthday_max)) : '';?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="s-date2" class="k-select-opt ml-15 col-2 s-all2" style="<?=($date_min || $date_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                            <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                            <div class="ele-date2">
                                                <div class="" style="display:flex;">
                                                    <input type="text" name="date_min" placeholder="Ngày đăng ký 1" class="kh-datepicker2 form-control" value="<?=$date_min ? Date("d-m-Y",strtotime($date_min)) : '';?>">
                                                </div>
                                                <div class="ml-10" style="display:flex;">
                                                    <input type="text" name="date_max" placeholder="Ngày đăng ký 2" class="kh-datepicker2 form-control" value="<?=$date_max ? Date("d-m-Y",strtotime($date_max)) : '';?>">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-default ml-15" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                                    </div>
                                    <div class="d-flex a-start" style="padding-left:0;padding-right:0;display:flex;margin-top:15px;">
                                        <div style="" class="form-group row">
                                            <select name="orderByColumn" class="ml-10 form-control col-5">
                                                <<option value="full_name" <?=$orderByColumn == "full_name" ? "selected" : "";?>>Tên đầy đủ</option>
                                                    <option value="email" <?=$orderByColumn == "email" ? "selected" : "";?>>Email</option>
                                                    <option value="phone" <?=$orderByColumn == "phone" ? "selected" : "";?>>Số điện thoại</option>
                                                    <option value="address" <?=$orderByColumn == "address" ? "selected" : "";?>>Địa chỉ</option>
                                                    <option value="birthday" <?=$orderByColumn == "birthday" ? "selected" : "";?>>Ngày sinh</option>
                                                    <option value="created_at" <?=$orderByColumn == "created_at" ? "selected" : "";?>>Ngày tạo</option>
                                            </select>
                                            <select name="orderStatus" class="ml-10 form-control col-5">
                                                <option value="">Thao tác sắp xếp</option>
                                                <option value="asc" <?=$orderStatus == "asc" ? "selected" : "";?>>Tăng dần (a - z) (1 - 9)</option>
                                                <option value="desc" <?=$orderStatus == "desc" ? "selected" : "";?>>Giảm dần (z - a) (9 - 1)</option>
                                            </select>
                                            <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                                            <button type="submit" class="btn btn-default ml-10"><i class="fas fa-sort"></i></button>
                                        </div>       
                                    </div>
                                </form>
                                <div class="col-12 mb-3 d-flex j-between" style="padding-right:0px;padding-left:0px;">
                                    <div>
                                        <?php
                                            if($allow_read) {
                                        ?>
                                        <button tabindex="-1" onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
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
                                    </div>
                                </div>
                                <?php
                                    // set get
                                    $get = $_GET;
                                    unset($get['page']);
                                    $str_get = http_build_query($get);
                                    // query
                                    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                                    $limit = $_SESSION['paging'];
                                    $start_page = $limit * ($page - 1);
                                    $sql_get_total = "select count(*) as 'countt' from customer $where";
                                    $total = fetch(sql_query($sql_get_total))['countt'];
                                    $sql_get_customer = "select * from customer $where limit $start_page,$limit";
                                    $rows = fetch_all(sql_query($sql_get_customer));
                                    $cnt = 0;
                                ?>
                                <div class="table-game-start">
                                    <table id="table-customer_manage" class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="cursor:pointer;">
                                                <th style="width:20px !important;">
                                                    <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                                </th>
                                                <th class="th-so-thu-tu w-120">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-ten-day-du w-200">Tên đầy đủ <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-email w-170">Email <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-so-dien-thoai w-200">Số điện thoại <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-dia-chi w-300">Địa chỉ <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-ngay-sinh w-120">Ngày sinh <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="th-ngay-tao w-120">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                <th class="w-200">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody dt-parent-id dt-url="<?=$str_get;?>" dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-customer">
                                        <?php foreach($rows as $row) { ?>
                                            <tr id="<?=$row["id"]?>">
                                                <td>
                                                    <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-customer')" type="checkbox" name="check_id<?=$row["id"];?>">
                                                </td>
                                                <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                                                <td class="ten-day-du">
                                                    <?php 
                                                        if($row['is_lock'] == 1){
                                                            echo '<i class="fas fa-lock mr-1"></i>';
                                                        }
                                                        echo $row["full_name"];
                                                    ?>
                                                </td>
                                                <td class="email"><?=$row["email"]?></td>
                                                <td class="so-dien-thoai"><?=$row["phone"]?></td>
                                                <td class="dia-chi"><?=$row["address"]?></td>
                                                <td class="ngay-sinh"><?=Date("d-m-Y",strtotime($row["birthday"]));?></td>
                                                <td class="ngay-tao"><?=Date("d-m-Y",strtotime($row["created_at"]));?></td>
                                                <td>
                                                    <button onclick="openModalRead()" class="btn-read-customer dt-button button-grey"data-id="<?=$row["id"];?>">Xem</button>
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
                                                <th>Tên đầy đủ</th>
                                                <th>Email</th>
                                                <th>Số điện thoại</th>
                                                <th>Địa chỉ</th>
                                                <th>Ngày sinh</th>
                                                <th>Ngày tạo</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div style="justify-content:center;" class="row mt-15">
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
                <h4 class="modal-title">Thông tin khách hàng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage_customer" method="post">
                    
                </form>
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
            dateText = dateText.split("-");
            $(this).attr('data-date2',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
        }
    })
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
    function openModalRead(){
        let id = $(event.currentTarget).attr('data-id');
        $('#manage_customer').load("ajax_customer.php?status=Read&id=" + id,() => {
            console.log("ajax_customer.php?status=Read&id=" + id);
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    }
    function unlockMore(){
        let arr_del = [];
        let _data = dt_customer.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn mở khoá " + _data.length + " tài khoản khách hàng này",
                buttons: {
                    "Có": function(){
                        $.ajax({
                            url: window.location.href,
                            type: "POST",
                            data: {
                                status: "unlock_more",
                                rows: arr_del.join(","),
                            },
                            success: function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok"){
                                    $.alert({
                                        title: "Thông báo",
                                        content: "Bạn đã mở khoá tài khoản khách hàng thành công",
                                        buttons: {
                                            "Ok": function(){
                                                location.href="customer_manage.php";
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
        let _data = dt_customer.rows(".selected").select().data();
        for(i = 0 ; i < _data.length ; i++) {
            arr_del.push(_data[i].DT_RowId);
        }
        if(_data.length > 0) {
            $.confirm({
                title: "Thông báo",
                content: "Bạn có chắc chắn muốn khoá " + _data.length + " tài khoản khách hàng này",
                buttons: {
                    "Có": function(){
                        $.ajax({
                            url: window.location.href,
                            type: "POST",
                            data: {
                                status: "lock_more",
                                rows: arr_del.join(","),
                            },
                            success: function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok"){
                                    $.alert({
                                        title: "Thông báo",
                                        content: "Bạn đã khoá tài khoản khách hàng thành công",
                                        buttons: {
                                            "Ok": function(){
                                                location.href="customer_manage.php";
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
		onPageClick: function(pageNumber,event){
            event.preventDefault();
            loadDataInTab('<?=get_url_current_page();?>' + "?page=" + pageNumber);
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
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        if($status == "lock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update customer set is_lock = 1 where id = '$row'";
                sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "unlock_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql = "Update customer set is_lock = 0 where id = '$row'";
                sql_query($sql);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "saveTabFilter") {
            $_SESSION['customer_tab_id'] = isset($_SESSION['customer_tab_id']) ? $_SESSION['customer_tab_id'] + 1 : 1;
            $tab_name = isset($_SESSION['customer_tab_id']) ? "tab_" . $_SESSION['customer_tab_id'] : null;
            $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
            $tab_unique = uniqid("tab_");
            $_SESSION['customer_manage_tab'] = isset($_SESSION['customer_manage_tab']) ? $_SESSION['customer_manage_tab'] : [];
            array_push($_SESSION['customer_manage_tab'],[
               "tab_unique" => $tab_unique,
               "tab_name" => $tab_name,
               "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
            ]);
            echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['customer_manage_tab'])- 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
        } else if($status == "deleteTabFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
            array_splice($_SESSION['customer_manage_tab'],$index,1);
            if(trim($is_active_2) == "") {
                echo_json(["msg" => "ok"]);
            }  else if($is_active_2 == 1) {
                if(array_key_exists($index,$_SESSION['customer_manage_tab'])) {
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['customer_manage_tab'][$index]['tab_urlencode']]);
                } else if(array_key_exists($index - 1,$_SESSION['customer_manage_tab'])){
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['customer_manage_tab'][$index - 1]['tab_urlencode']]);
                } else {
                    echo_json(["msg" => "ok","tab_urlencode" => "customer_manage.php?tab_unique=all"]);
                }
            }
        } else if($status == "changeTabNameFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
            $_SESSION['customer_manage_tab'][$index]['tab_name'] = $new_tab_name;
            echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['customer_manage_tab'][$index]['tab_urlencode']]);
        }
    }
?>