<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // id to be executed get method
        $allow_insert = $allow_read = $allow_update = $allow_delete = true;
        $choose_province_id  = isset($_REQUEST['choose_province_id']) ? $_REQUEST['choose_province_id'] : null;
        $choose_district_id  = isset($_REQUEST['choose_district_id']) ? $_REQUEST['choose_district_id'] : null;
        $choose_ward_id  = isset($_REQUEST['choose_ward_id']) ? $_REQUEST['choose_ward_id'] : null;
        $upt_more  = isset($_REQUEST['upt_more ']) ? $_REQUEST['upt_more '] : null;
        $order_by = "Order by df.id desc";
        $where = "where 1 = 1 and df.is_delete = 0";
        if($choose_province_id) {
            $where .= " and df.province_id = '$choose_province_id'";
        }
        if($choose_district_id) {
            $where .= " and df.district_id = '$choose_district_id'";
        }
        if($choose_ward_id) {
            $where .= " and df.ward_id = '$choose_ward_id'";
        }
        $where .= " $order_by";
?>
<style>
    .sort-asc,.sort-desc {
        display: none;
    }
</style>
<link rel="stylesheet" href="css/toastr.min.css">
<!--html & css section start-->
<div class="container-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quản lý phí vận chuyển</h3>
                            <?php
                                if($allow_insert) {
                            ?>
                            <div class="card-tools">
                                <button onclick="openModalInsert()" id="btn-add-delivery_fee" class="dt-button button-blue">Thêm phí vận chuyển</button>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ok-game-start">
                            <div id="load-all">   
                                <link rel="stylesheet" href="css/tab.css">          
                                <div style="padding-right:0px;padding-left:0px;" class="col-12 mb-20 d-flex a-center j-between">
                                    <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                                    <?php
                                        $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                        $_SESSION['delivery_fee_manage_tab'] = isset($_SESSION['delivery_fee_manage_tab']) ? $_SESSION['delivery_fee_manage_tab'] : [];
                                        $_SESSION['delivery_fee_tab_id'] = isset($_SESSION['delivery_fee_tab_id']) ? $_SESSION['delivery_fee_tab_id'] : 0;
                                    ?>
                                    <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('delivery_fee_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>
                                    <?php
                                        $ik = 0;
                                        $is_active = false;
                                        if(count($_SESSION['delivery_fee_manage_tab']) > 0) {
                                            foreach($_SESSION['delivery_fee_manage_tab'] as $tab) {
                                                if($tab['tab_unique'] == $tab_unique) {
                                                    $_SESSION['delivery_fee_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                                }
                                    ?>
                                        <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                            <button onclick="loadDataInTab('<?=$_SESSION['delivery_fee_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
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
                                    <div class="col-12 search mb-3" style="padding-left:0;padding-right:0;">
                                        <form id="form-filter" action="delivery_fee_manage.php" method="get" class="d-flex a-end" onsubmit="searchTabLoad('#form-filter')">
                                            <div class="col-2" style="padding-left:0;padding-right:0;">
                                                <label for="">Tỉnh / thành phố</label>
                                                <select style="width:100%;" name="choose_province_id" class="form-control select-hi" onchange="loadDistricts2()">
                                                    <option value="">Chọn tỉnh / thành phố</option>
                                                    <?php
                                                        $sql_list_provinces = "select id,full_name from provinces";
                                                        $provinces = fetch_all(sql_query($sql_list_provinces));
                                                        foreach($provinces as $province) {
                                                    ?>
                                                        <option value="<?=$province['id']?>" <?=$province['id'] == $choose_province_id ? "selected" : "";?>><?=$province['full_name']?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-2 ml-10" style="padding-left:0;padding-right:0;">
                                                <label for="">Quận / huyện / thị xã</label>
                                                <select style="width:100%;" name="choose_district_id" class="form-control select-districts" onchange="loadWards2()">
                                                    <option value="">Chọn quận / huyện / thị xã</option>
                                                    <?php
                                                    if($choose_province_id)  {
                                                        $sql_list_districts = "select id,full_name from districts where province_id = '$choose_province_id'";
                                                        $districts = fetch_all(sql_query($sql_list_districts));
                                                        foreach($districts as $district) {
                                                    ?>
                                                        <option value="<?=$district['id']?>" <?=$district['id'] == $choose_district_id ? "selected" : "";?>><?=$district['full_name']?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-2 ml-10" style="padding-left:0;padding-right:0;">
                                                <label for="">Phường / thị trấn / xã</label>
                                                <select style="width:100%;" name="choose_ward_id" class="form-control select-wards">
                                                    <option value="">Chọn phường / thị trấn / xã</option>
                                                    <?php
                                                    if($choose_district_id)  {
                                                        $sql_list_wards = "select id,full_name from wards where district_id = '$choose_district_id'";
                                                        $wards = fetch_all(sql_query($sql_list_wards));
                                                        foreach($wards as $ward) {
                                                    ?>
                                                        <option value="<?=$ward['id']?>" <?=$ward['id'] == $choose_ward_id ? "selected" : "";?>><?=$ward['full_name']?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                                            <button class="btn btn-default ml-10">
                                                <i class="fas fa-search"></i>
                                            </button>
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
                                            if($allow_read) {
                                        ?>
                                        <button onclick="readMore()" class="dt-button button-grey">Xem nhanh</button>
                                        <?php } ?>
                                        </div>
                                    </div>
                                                
                                    <?php
                                        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;  
                                        $limit = $_SESSION['paging'];
                                        $start_page = $limit * ($page - 1);
                                        $sql_get_total = "select count(*) as 'countt' from delivery_fee df inner join provinces pr on df.province_id = pr.id inner join districts di on df.district_id = di.id inner join wards wa on df.ward_id = wa.id $where";
                                        $total = fetch(sql_query($sql_get_total))['countt'];
                                        $sql_get_delivery_fee = "select df.id as 'df_id',df.fee as 'df_fee',df.created_at as 'df_created_at',pr.full_name as 'pr_full_name',di.full_name as 'di_full_name',wa.full_name as 'wa_full_name' from delivery_fee df inner join provinces pr on df.province_id = pr.id inner join districts di on df.district_id = di.id inner join wards wa on df.ward_id = wa.id $where limit $start_page,$limit";
                                        $cnt = 0;
                                        $rows = fetch_all(sql_query($sql_get_delivery_fee));
                                    ?>
                                    <div class="table-responsive table-game-start">
                                        <table id="table-delivery_fee_manage" class="table table-bordered table-striped">
                                            <thead>
                                                <tr style="cursor:pointer;">
                                                    <th style="width:20px !important;">
                                                        <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                                                    </th>
                                                    <th class="w-120 th-so-thu-tu">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                    <th class="w-200 th-province">Thành phố / Tỉnh <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                    <th class="w-300 th-district">Quận / Huyện / Thị xã / Thành phố <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                    <th class="w-200 th-ward">Phường / Thị trấn / Xã <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                    <th class="w-200 th-phi-van-chuyen">Phí vận chuyển <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                    <th class="w-170 th-ngay-tao">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                                    <th class="w-200">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody dt-parent-id dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-delivery-fee">
                                                <?php foreach($rows as $row) { ?>
                                                    <?php $cnt1 = $cnt + 1;?>
                                                    <tr id="<?=$row["df_id"];?>">
                                                        <td>
                                                            <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["df_id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-delivery-fee')" type="checkbox" name="check_id<?=$row["df_id"];?>">
                                                        </td>
                                                        <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                                                        <td class="province"><?=$row['pr_full_name']?></td>
                                                        <td class="district"><?=$row['di_full_name']?></td>
                                                        <td class="ward"><?=$row['wa_full_name']?></td>
                                                        <td class="phi-van-chuyen"><?=number_format($row['df_fee'],0,".",".")."đ";?></td>
                                                        <td class="ngay-tao"><?=$row['df_created_at'] ? Date("d-m-Y",strtotime($row['df_created_at'])) : "";?></td>
                                                        <td>
                                                            <?php
                                                                if($allow_read) {
                                                            ?>
                                                            <button onclick="openModalRead()" class="btn-read-delivery_fee dt-button button-grey"
                                                            data-id="<?=$row["df_id"];?>">Xem</button>
                                                            <?php } ?>
                                                            <?php
                                                                if($allow_update) {
                                                            ?>
                                                            <button onclick="openModalUpdate()" class="btn-update-delivery_fee dt-button button-green"
                                                            data-id="<?=$row["df_id"];?>">Sửa</button>
                                                            <?php } ?>
                                                            <?php
                                                                if($allow_delete) {
                                                            ?>
                                                            <button onclick="processDelete()" class="btn-delete-row dt-button button-red" data-id="<?=$row["df_id"];?>">Xoá
                                                            </button>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php 
                                                        $cnt++;
                                                    } 
                                                ?>
                                                <?php
                                                $count_row_table = count($rows);
                                                if($count_row_table == 0) {
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
                                                    <th class='w-150'>Số thứ tự</th>
                                                    <th>Thành phố / Tỉnh</th>
                                                    <th>Quận / Huyện / Thị xã / Thành phố</th>
                                                    <th>Phường / Thị trấn / Xã</th>
                                                    <th>Phí vận chuyển</th>
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
                <h4 class="modal-title">Thông tin phí vận chuyển</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage_delivery_fee" method="post">
                    
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-xl2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin phí vận chuyển</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="form-delivery_fee2"></div>
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
    <?=$upt_more != 1 && $count_row_table != 0 ? "setSortTable();" : null;?> 
    function loadDistricts(){
        let province_id = $("select[name='province_id'] > option:selected").val();
        $(".select-districts").load("ajax_delivery_fee_manage.php?status=load_districts&province_id=" + province_id ,() => {
            $(".select-wards").load("ajax_delivery_fee_manage.php?status=load_wards&district_id=no" ,() => {
                
            })
        })
    }
    function loadWards(){
        let district_id = $("select[name='district_id'] > option:selected").val();
        if(district_id) {
            $(".select-wards").load("ajax_delivery_fee_manage.php?status=load_wards&district_id=" + district_id ,() => {
 
            })
        }
    }
    function loadDistricts2(){
        let province_id = $("select[name='choose_province_id'] > option:selected").val();
        $(".select-districts").load("ajax_delivery_fee_manage.php?status=load_districts2&choose_province_id=" + province_id ,() => {
            $(".select-wards").load("ajax_delivery_fee_manage.php?status=load_wards2&choose_district_id=" ,() => {
                
            })
        })
    }
    function loadWards2(){
        let district_id = $("select[name='choose_district_id'] > option:selected").val();
        if(district_id) {
            $(".select-wards").load("ajax_delivery_fee_manage.php?status=load_wards2&choose_district_id=" + district_id ,() => {
 
            })
        }
    }
</script>
<script>
    function validate(){
        let test = true;
        let province_id = $("select[name='province_id'] > option:selected").val();
        let district_id = $("select[name='district_id'] > option:selected").val();
        let ward_id = $("select[name='ward_id'] > option:selected").val();
        let fee = $('#fee').val();
        if(province_id == "no") {
            $.alert({
                title: "Thông báo",
                content: "Tỉnh / thành phố không được để trống.",
            });
            test = false;
        } else if(district_id == "no") {
            $.alert({
                title: "Thông báo",
                content: "Quận / huyện / xã không được để trống.",
            });
            test = false;
        } else if(ward_id == "no") {
            $.alert({
                title: "Thông báo",
                content: "Phường / thị trấn không được để trống."
            });
            test = false;
        } else if(fee == "no") {
            $.alert({
                title: "Thông báo",
                content: "Phí vận chuyển không được để trống."
            });
            
            test = false;
        } 
        return test;
    }
    function validateModal(){

    }
    function openModalInsert(){
        $('#manage_delivery_fee').load("ajax_delivery_fee_manage.php?status=Insert",() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    }
    function openModalUpdate(){
        let id = $(event.currentTarget).attr('data-id');
        $('#manage_delivery_fee').load("ajax_delivery_fee_manage.php?status=Update&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    }
    function processModalInsert(){
        event.preventDefault();
        if(validate()) {
            let formData = new FormData($('#manage_delivery_fee')[0]);
            formData.append("status","Insert");
            formData.append("province_id",$("select[name='province_id'] > option:selected").val());
            formData.append("district_id",$("select[name='district_id'] > option:selected").val());
            formData.append("ward_id",$("select[name='ward_id'] > option:selected").val());
            formData.append("fee",$('#fee').val());
            $.ajax({
                url:window.location.href,
                type: "POST",
                cache:false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    data = JSON.parse(data);
                    if(data.msg == "ok") {
                        $.alert({
                            title: "Thông báo",
                            content: data.success,
                        });
                        loadDataComplete("Insert");
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
            let formData = new FormData($('#manage_delivery_fee')[0]);
            formData.append("status","Update");
            formData.append("province_id",$("select[name='province_id'] > option:selected").val());
            formData.append("district_id",$("select[name='district_id'] > option:selected").val());
            formData.append("ward_id",$("select[name='ward_id'] > option:selected").val());
            formData.append("fee",$('#fee').val());
            formData.append("id",$('input[name=id]').val());
            if(validate()) {
                $.ajax({
                    url:window.location.href,
                    type: "POST",
                    cache:false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(data){
                        //console.log(data);
                        data = JSON.parse(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                            });
                            loadDataComplete();
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
    function openModalRead(){
        let id = $(event.currentTarget).attr('data-id');
        let target = $(event.currentTarget);
        $('#manage_delivery_fee').load("ajax_delivery_fee_manage.php?status=Read&id=" + id,() => {
            $('#modal-xl').modal({backdrop: 'static', keyboard: false});
        })
    }
    function processDelete(){
        let id = $(event.currentTarget).attr('data-id');
        $.confirm({
            title: 'Thông báo',
            content: 'Bạn có chắc chắn muốn xoá thông tin phí vận chuyển này ?',
            buttons: {
                Có: function(){
                    $.ajax({
                        url:window.location.href,
                        type:"POST",
                        data: {
                            id: id,
                            status: "Delete",
                        },
                        success:function(data){
                            console.log(data);
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
                    target.closest("tr").removeClass("bg-color-selected");
                }
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
        $province_id = isset($_REQUEST['province_id']) ? $_REQUEST['province_id'] : null;
        $district_id = isset($_REQUEST['district_id']) ? $_REQUEST['district_id'] : null;
        $ward_id = isset($_REQUEST['ward_id']) ? $_REQUEST['ward_id'] : null;
        $fee = isset($_REQUEST['fee']) ? str_replace(".","",$_REQUEST['fee']) : null;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        if($status == "Insert") {
            $sql_insert = "Insert into delivery_fee(province_id,district_id,ward_id,fee) values(?,?,?,?)";
            sql_query($sql_insert,[$province_id,$district_id,$ward_id,$fee]);
            echo_json(["msg" => "ok","success" => "Bạn đã thêm dữ liệu thành công"]);
        } else if($status == "Update") {
            $sql_update =  "Update delivery_fee set province_id = ?,district_id = ?,ward_id = ?,fee = ? where id = ?";
            sql_query($sql_update,[$province_id,$district_id,$ward_id,$fee,$id]);
            echo_json(["msg" => "ok","success" => "Bạn đã sửa dữ liệu thành công"]);
        } else if($status == "Delete") {
            $sql_del =  "Update delivery_fee set is_delete = ? where id = ?";
            sql_query($sql_del,[1,$id]);
            echo_json(["msg" => "ok","success" => "Bạn đã xoá dữ liệu thành công"]);
        } else if($status == "del_more") {
            $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
            $rows = explode(",",$rows);
            foreach($rows as $row) {
                $sql_del = "Update delivery_fee set is_delete = ? where id = ?";
                sql_query($sql_del,[1,$row]);
            }
            echo_json(["msg" => "ok"]);
        } else if($status == "saveTabFilter") {
            $_SESSION['delivery_fee_tab_id'] = isset($_SESSION['delivery_fee_tab_id']) ? $_SESSION['delivery_fee_tab_id'] + 1 : 1;
            $tab_name = isset($_SESSION['delivery_fee_tab_id']) ? "tab_" . $_SESSION['delivery_fee_tab_id'] : null;
            $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
            $tab_unique = uniqid("tab_");
            $_SESSION['delivery_fee_manage_tab'] = isset($_SESSION['delivery_fee_manage_tab']) ? $_SESSION['delivery_fee_manage_tab'] : [];
            array_push($_SESSION['delivery_fee_manage_tab'],[
               "tab_unique" => $tab_unique,
               "tab_name" => $tab_name,
               "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
            ]);
            echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['delivery_fee_manage_tab']) - 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
         } else if($status == "deleteTabFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
            array_splice($_SESSION['delivery_fee_manage_tab'],$index,1);
            if(trim($is_active_2) == "") {
                echo_json(["msg" => "ok"]);
            } else if($is_active_2 == 1) {
                if(array_key_exists($index,$_SESSION['delivery_fee_manage_tab'])) {
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['delivery_fee_manage_tab'][$index]['tab_urlencode']]);
                } else if(array_key_exists($index - 1,$_SESSION['delivery_fee_manage_tab'])){
                    echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['delivery_fee_manage_tab'][$index - 1]['tab_urlencode']]);
                } else {
                    echo_json(["msg" => "ok","tab_urlencode" => "notification_manage.php?tab_unique=all"]);
                }
            }
         } else if($status == "changeTabNameFilter") {
            $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
            $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
            $_SESSION['delivery_fee_manage_tab'][$index]['tab_name'] = $new_tab_name;
            echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['delivery_fee_manage_tab'][$index]['tab_urlencode']]);
        }
    }
?>