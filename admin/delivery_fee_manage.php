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
?>
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
                                <button id="btn-add-delivery_fee" class="dt-button button-blue">Thêm phí vận chuyển</button>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-12 search mb-3" style="padding-left:0;padding-right:0;">
                                <form action="delivery_fee_manage.php" method="get" class="d-flex a-end">
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
                        
                            <?php
                                $get = $_GET;
                                unset($get['page']);
                                $str_get = http_build_query($get);
                                $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                                $limit = $_SESSION['paging'];
                                $start_page = $limit * ($page - 1);
                                $sql_get_total = "select count(*) as 'countt' from delivery_fee df inner join provinces pr on df.province_id = pr.id inner join districts di on df.district_id = di.id inner join wards wa on df.ward_id = wa.id $where";
                                $total = fetch_row($sql_get_total)['countt'];
                                $sql_get_delivery_fee = "select df.id as 'df_id',df.fee as 'df_fee',df.created_at as 'df_created_at',pr.full_name as 'pr_full_name',di.full_name as 'di_full_name',wa.full_name as 'wa_full_name' from delivery_fee df inner join provinces pr on df.province_id = pr.id inner join districts di on df.district_id = di.id inner join wards wa on df.ward_id = wa.id $where limit $start_page,$limit";
                                $cnt = 0;
                                $rows = db_query($sql_get_delivery_fee);
                            ?>
                            <div class="table-responsive">
                                <table id="m-delivery-fee-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class='w-150'>Số thứ tự</th>
                                            <th>Thành phố / Tỉnh</th>
                                            <th>Quận / Huyện / Thị xã / Thành phố</th>
                                            <th>Phường / Thị trấn / Xã</th>
                                            <th>Phí vận chuyển</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="m-delivery-fee-body">
                                        <?php foreach($rows as $row) { ?>
                                            <?php $cnt1 = $cnt + 1;?>
                                            <tr id="<?=$row["df_id"];?>">
                                                <td></td>
                                                <td><?=$total - ($start_page + $cnt);?></td>
                                                <td><?=$row['pr_full_name']?></td>
                                                <td><?=$row['di_full_name']?></td>
                                                <td><?=$row['wa_full_name']?></td>
                                                <td><?=number_format($row['df_fee'],0,".",".")."đ";?></td>
                                                <td><?=$row['df_created_at'] ? Date("d-m-Y",strtotime($row['df_created_at'])) : "";?></td>
                                                <td>
                                                    <?php
                                                        if($allow_read) {
                                                    ?>
                                                    <button class="btn-read-delivery_fee dt-button button-grey"
                                                    data-id="<?=$row["df_id"];?>">Xem</button>
                                                    <?php } ?>
                                                    <?php
                                                        if($allow_update) {
                                                    ?>
                                                    <button class="btn-update-delivery_fee dt-button button-green"
                                                    data-id="<?=$row["df_id"];?>">Sửa</button>
                                                    <?php } ?>
                                                    <?php
                                                        if($allow_delete) {
                                                    ?>
                                                    <button class="btn-delete-row dt-button button-red" data-id="<?=$row["df_id"];?>">Xoá
                                                    </button>
                                                    <?php } ?>
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
<div class="modal fade" id="modal-xl3">
    <div class="modal-dialog modal-xl" style="min-width:1700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin tính phí vận chuyển</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="form-delivery_fee2" class="modal-body">
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
                    <table class='table table-bordered' style="height:auto;">
                        <thead>
                            <tr>
                                <th class='w-150'>Số thứ tự</th>
                                <th>Thành phố / Tỉnh</th>
                                <th>Quận / Huyện / Xã</th>
                                <th>Phường / Thị trấn</th>
                                <th>Phí vận chuyển</th>
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
<!--js section start-->
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
              setDataFromXLSX(XL_row_object,['Tên đầy đủ','Email','Số điện thoại','Số cmnd','Địa chỉ','Ngày sinh'],['u_fullname2','u_email2','u_phone2','u_cmnd2','u_address2','u_birthday2']);
          };
          reader.onerror = function(ex) {
              console.log(ex);
          };
          reader.readAsBinaryString(input.files[0]);
          //console.log("aaa");
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
              setDataFromCSV(arr_csv,['Tên đầy đủ','Email','Số điện thoại','Số cmnd','Địa chỉ','Ngày sinh'],['u_fullname2','u_email2','u_phone2','u_cmnd2','u_address2','u_birthday2']);
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
<script>
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
    //$('.select-hi').select2();
</script>
<script>
    $(document).ready(function (e) {
        $.fn.dataTable.moment('DD-MM-YYYY');
        $('#first_tab').on('focus', function() {
            $('input[tabindex="1"].kh-inp-ctrl').first().focus();
        });
        $('#btn-role-fast').on('focus',function(){
            $('input[tabindex="<?=$cnt;?>"]').focus();
        });
        dt_delivery_fee = $("#m-delivery-fee-table").DataTable({
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
                    "targets": 7
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
        dt_delivery_fee.buttons.exportData( {
            columns: ':visible'
        });
        dt_delivery_fee.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                dt_delivery_fee.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                dt_delivery_fee.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function() {
            if (dt_delivery_fee.rows({
                    selected: true
                }).count() !== dt_delivery_fee.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });
        // validate
        const validate = () => {
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
        };
        // mở modal thêm dữ liệu
        $(document).on('click','#btn-add-delivery_fee',(e) => {
            $('#manage_delivery_fee').load("ajax_delivery_fee_manage.php?status=Insert",() => {
                $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            })
        });
        // mở modal sửa dữ liệu
        $(document).on('click','.btn-update-delivery_fee',function(e) {  
            let id = $(e.currentTarget).attr('data-id');
            $('#manage_delivery_fee').load("ajax_delivery_fee_manage.php?status=Update&id=" + id,() => {
                $('#modal-xl').modal({backdrop: 'static', keyboard: false});
            })
        });
        // thêm 
        $(document).on('click','#btn-insert',function(e){
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
                                        location.href="delivery_fee_manage.php";
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
                                            location.reload();
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
        });
        // xoá 
        $(document).on('click','.btn-delete-row',function(e){
            let id = $(e.currentTarget).attr('data-id');
            console.log(id);
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
        $(document).on('click','.btn-read-delivery_fee',function(e){
            let id = $(e.currentTarget).attr('data-id');
            let target = $(e.currentTarget);
            $('#manage_delivery_fee').load("ajax_delivery_fee_manage.php?status=Read&id=" + id,() => {
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
        $province_id = isset($_REQUEST['province_id']) ? $_REQUEST['province_id'] : null;
        $district_id = isset($_REQUEST['district_id']) ? $_REQUEST['district_id'] : null;
        $ward_id = isset($_REQUEST['ward_id']) ? $_REQUEST['ward_id'] : null;
        $fee = isset($_REQUEST['fee']) ? str_replace(".","",$_REQUEST['fee']) : null;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        if($status == "Insert") {
            $sql_insert = "Insert into delivery_fee(province_id,district_id,ward_id,fee) values('$province_id','$district_id','$ward_id','$fee')";
            sql_query($sql_insert);
            echo_json(["msg" => "ok","success" => "Bạn đã thêm dữ liệu thành công"]);
        } else if($status == "Update") {
            $sql_update =  "Update delivery_fee set province_id = '$province_id',district_id = '$district_id',ward_id = '$ward_id',fee = '$fee' where id = '$id'";
            sql_query($sql_update);
            echo_json(["msg" => "ok","success" => "Bạn đã sửa dữ liệu thành công"]);
        } else if($status == "Delete") {
            $sql_del =  "Update delivery_fee set is_delete = 1 where id = '$id'";
            sql_query($sql_del);
            echo_json(["msg" => "ok","success" => "Bạn đã xoá dữ liệu thành công"]);
        }
        // id to be executed post method
    }
?>