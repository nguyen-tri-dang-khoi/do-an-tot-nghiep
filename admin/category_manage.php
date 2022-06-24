<?php
    include_once("../lib/database_v2.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        $allow_read = $allow_update = $allow_delete = $allow_insert = false; 
        if(check_permission_crud("category_manage.php","read")) {
          $allow_read = true;
        }
        if(check_permission_crud("category_manage.php","update")) {
          $allow_update = true;
        }
        if(check_permission_crud("category_manage.php","delete")) {
          $allow_delete = true;
        }
        if(check_permission_crud("category_manage.php","insert")) {
          $allow_insert = true;
        }
        $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
        
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
        $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
        $parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : null;
        $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
        $order_by =  "order by id desc";
        $where = "where 1 = 1 and is_delete = 0";
        if($parent_id) {
          $where .= " and parent_id = '$parent_id'";
        } 
        else if(!$parent_id){
          $where .= " and parent_id is null";
        }

        if($str) {
          $where .= " and product_type.id in ($str)";
        }
        if($orderByColumn && $orderStatus) {
          if(in_array($orderByColumn,['name','created_at'])) {
            $order_by = "ORDER BY $orderByColumn $orderStatus";
          } 
        }
        $where .= " $order_by";
        // code to be executed get method
?>
<!--html & css section start-->
<style>
  .breadcrumb-item+.breadcrumb-item::before {
    display: inline-block;
    padding-right: 0.7rem;
    color: #9c27b0;
    content: "\203A\203A";
    font-weight:bold;
   }
   .breadcrumb-item-aaa:last-child a{
    text-decoration: underline;
    color:#9c27b0 !important;
    border-radius:5px;
   }
  .sort-asc,.sort-desc {
    display: none;
  }
</style>
<link rel="stylesheet" href="css/toastr.min.css">
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid">
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header" style="display: flex;justify-content: space-between;">
              <h3 class="card-title">Quản lý danh mục</h3>
              <div class="card-tools">
                <div class="input-group">
                  <div class="input-group-append">
                    <?php
                      if($allow_insert) {
                    ?>
                    <button onclick="openModalInsert()" id="btn-them-loai-san-pham" class="dt-button button-blue">
                      Thêm danh mục
                    </button>
                    <?php
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body ok-game-start">
              <div id="load-all">
                <link rel="stylesheet" href="css/tab.css">         
                <div style="padding-right:0px;padding-left:0px;" class="col-12 mb-20 d-flex a-start j-between">
                  <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                      <?php
                        $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                        $_SESSION['category_manage_tab'] = isset($_SESSION['category_manage_tab']) ? $_SESSION['category_manage_tab'] : [];
                        $_SESSION['category_tab_id'] = isset($_SESSION['category_tab_id']) ? $_SESSION['category_tab_id'] : 0;
                      ?>
                      <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('category_manage.php')" class="tab tab-1">Tất cả</button></li>

                      <?php
                        $ik = 0;
                        $is_active = false;
                        if(count($_SESSION['category_manage_tab']) > 0) {
                            foreach($_SESSION['category_manage_tab'] as $tab) {
                              if($tab['tab_unique'] == $tab_unique) {
                                $_SESSION['category_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                              }
                      ?>
                        <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                            <button onclick="loadDataInTab('<?=$_SESSION['category_manage_tab'][$ik]['tab_urlencode'];?>')"  class="tab"><?=$tab['tab_name'];?></button>
                            
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
                <div style="display:block;" id="is-load">
                  <div id="breadcrump-type" class="row">
                    <div class="col-sm-12">
                    <ol style="background: #fff;border: 1px solid #9c27b0;" class="breadcrumb float-sm-left">
                      <li class="breadcrumb-item"><a style="cursor:pointer;color: #9c27b0;" href="category_manage.php">Quản lý menu</a></li>
                      <?=generate_breadcrumb_menus_3($parent_id);?>
                      </ol>
                    </div>
                  </div>
                  <div id="btn-file" class="row">
                    <div class="col-12">
                      <div class="col-12" style="padding-right:0px;padding-left:0px;">
                      </div>
                      <div class="col-12" style="padding-right:0px;padding-left:0px;">
                          <form id="form-main" action="category_manage.php" method="get">
                              <div class="" >
                                <select onchange="searchTabLoad('#form-main')" id="select-type2" style="width:50%;" class="form-control" name="parent_id">
                                  <option value="">Chọn danh mục cần tìm</option>
                                  <?php
                                    $sql = "select * from product_type where is_delete = 0";
                                    $rows2 = fetch_all(sql_query($sql));
                                    foreach($rows2 as $row2) {
                                  ?>
                                    <option value="<?=$row2['id']?>" <?=$parent_id == $row2['id'] ? "selected" : ""; ?>><?=generate_breadcrumb_menus_4($row2['id']);?></option>
                                  <?php
                                    }
                                  ?>
                                </select>
                              </div>
                              <div class="d-flex a-start" style="padding-left:0;padding-right:0;display:flex;margin-top:15px;">
                                <div style="" class="row">
                                    <select name="orderByColumn" class="ml-10 form-control col-5">
                                        <option value="">Sắp xếp theo cột</option>
                                        <option value="name" <?=$orderByColumn == "name" ? "selected" : "";?>>Tên danh mục</option>
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
                              <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                          </form>
                        </div>
                      <div class="mb-3 mt-15 col-12 d-flex j-between" style="padding-right:0px;padding-left:0px;">
                        <div>
                          <?php
                            if($allow_delete) {
                          ?>
                          <button tabindex="-1" onclick="delMore()" id="btn-delete-fast" class="dt-button button-red">Xoá nhanh</button>
                          <?php } ?>
                          <?php
                            if($allow_update) {
                          ?>
                          <button tabindex="-1" onclick="uptMore('<?=$parent_id;?>','<?=$tab_unique?>')" id="btn-upt-fast" class="dt-button button-green">Sửa nhanh</button>
                          <?php }?>
                          <?php
                            if($allow_read) {
                          ?>
                          <button tabindex="1" onclick="readMore()" id="btn-read-fast" class="dt-button button-grey">Xem nhanh</button>
                          <?php } ?>
                          <?php
                            if($allow_insert) {
                          ?>
                          <button tabindex="1" onclick="insMore()" id="btn-ins-fast" class="dt-button button-blue">Thêm nhanh</button>
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
                        <table id="table-category_manage" class="table table-bordered table-striped">
                          <thead>
                            <tr style="cursor:pointer;">
                              <th style="width:20px !important;">
                                  <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()">
                              </th>
                              <th class="th-so-thu-tu w-150">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                              <th class="th-danh-muc">Tên danh mục <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                              <th class="th-ngay-tao w-150">Ngày thêm <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                              <th class="w-100">Tình trạng</th>
                              <th class="w-200">Thao tác</th>
                            </tr>
                          </thead>
                          <?php
                            $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
                            $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1; 
                            $limit = $_SESSION['paging'];
                            $start_page = $limit * ($page - 1);
                            $sql_get_total = "select count(*) as 'countt' from product_type $where";
                            $total = fetch(sql_query($sql_get_total))['countt'];
                            $cnt = 0;
                            $sql_get_product_type = "select * from product_type $where limit $start_page,$limit";
                            $product_types = fetch_all(sql_query($sql_get_product_type));
                          ?>
                          <tbody dt-parent-id="<?=$parent_id ? $parent_id : NULL;?>" dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" class="list-product-type">
                          <?php foreach($product_types as $product_type) {?>
                            <tr class="parent-type" style="cursor:pointer;" id="<?=$product_type["id"];?>">
                              <td>
                                <input style="width:16px;height:16px;cursor:pointer" value="<?=$product_type["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-product-type')" type="checkbox" name="check_id<?=$product_type["id"];?>">
                              </td>
                              <td class="so-thu-tu" onclick="loadDataInTab('category_manage.php?upt_more=<?=$upt_more;?>&parent_id=<?=$product_type['id'];?>&tab_unique=<?=$tab_unique;?>')">
                                <?=$total - ($start_page + $cnt);?>
                            </td>
                              <?php
                                if($upt_more != 1){
                              ?>
                              <td class="danh-muc" onclick="loadDataInTab('category_manage.php?upt_more=<?=$upt_more;?>&parent_id=<?=$product_type['id'];?>&tab_unique=<?=$tab_unique;?>')"><?=$product_type["name"];?></td>
                              <?php
                                } else {
                              ?>
                              <td class="danh-muc"><input tabindex="<?=$cnt+1;?>" class='kh-inp-ctrl' type="text" name="pt_name" value="<?=$product_type['name'];?>"><span class="text-danger"></span></td>
                              <?php
                                }
                              ?>
                              
                              <td class="ngay-tao" onclick="loadDataInTab('category_manage.php?upt_more=<?=$upt_more;?>&parent_id=<?=$product_type['id'];?>&tab_unique=<?=$tab_unique;?>')"><?=Date("d-m-Y",strtotime($product_type["created_at"]));?></td>
                              <td>
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" onchange="toggleActiveCategory()" class="custom-control-input" id="customSwitches<?=$product_type['id'];?>" <?= $product_type['is_active'] == 1 ? "checked" : "";?>>
                                  <label class="custom-control-label" for="customSwitches<?=$product_type['id'];?>"></label>
                                </div>
                              </td>
                              <td>
                                <?php
                                  if($upt_more != 1) {
                                ?>
                                <?php
                                  if($allow_read) {
                                ?>
                                <button onclick="readModal()" class="btn-xem-loai-san-pham dt-button button-grey"
                                data-id="<?=$product_type["id"];?>">
                                Xem
                                </button>
                                <?php } ?>
                                <?php if($allow_update) {?>
                                <button onclick="openModalUpdate()" class="btn-sua-loai-san-pham dt-button button-green"
                                data-id="<?=$product_type["id"];?>" data-number="<?=$total - ($start_page + $cnt);?>">
                                Sửa
                                </button>
                                <?php 
                                  } 
                                  if($allow_delete) {
                                ?>
                                <button onclick="processDelete()" class="btn-xoa-loai-san-pham dt-button button-red" data-id="<?=$product_type["id"];?>">
                                Xoá
                                </button>
                                <?php } ?>
                                <?php
                                  } else {
                                ?>
                                <button tabindex="-1" data-id="<?=$product_type["id"];?>" onclick="uptMore2()" class="dt-button button-green">Sửa</button>
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
                              <th><input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()"></th>
                              <th>Số thứ tự</th>
                              <th>Tên danh mục</th>
                              <th>Ngày thêm</th>
                              <th>Tình trạng</th>
                              <th>Thao tác</th>
                            </tr>
                          </tfoot>
                        </table>
                        
                      </div>
                      <div style="justify-content:center;" class="row">
                          <nav id="pagination" aria-label="Page navigation example">

                          </nav>
                        </div>
                    </div>
                  </div>
                </div>      
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<!-- /.modal load-->
<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Thông tin danh mục</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="form-product-type" class="modal-body">
        <form id="form-loai-san-pham" action="" method="post">

        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content" style="height:auto;min-height:600px;">
      <div class="modal-header">
        <h4 class="modal-title">Thêm dữ liệu nhanh</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
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
                    <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Tên danh mục'],['name2'])">
                  </div>
                  <div class="file file-excel mr-10">
                    <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Tên danh mục'],['name2'])">
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
                <th>Số thứ tự</th>
                <th>Tên danh mục</th>
                <th>Thao tác</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl3">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Thông tin danh mục</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="form-product-type3" class="modal-body">
          
      </div>
    </div>
  </div>
</div>
<?php
  include_once("include/bottom.meta.php");
?>
<?php
    include_once("include/dt_script.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script src="js/toastr.min.js"></script>
<script src="js/khoi_all.js"></script>
<script>
  setSortTable();
  function toggleActiveCategory(){
    //event.preventDefault();
    let id = $(event.currentTarget).closest("tr").attr("id");
    let status = !$(event.currentTarget).is(":checked") ? "deactive" : "active";
    let target = $(event.currentTarget);
    $.ajax({
      url:window.location.href,
      type:"POST",
      data: {
        
        id:id,
        status:status,
      },success:function(data){
        console.log(data);
        data = JSON.parse(data);
        if(data.msg == "active") {
          toastr["success"](data.success);
          target.prop("checked",true);
        } else if(data.msg == "not_ok"){
          toastr["error"](data.error);
          target.prop("checked",false);
        } else if(data.msg == "deactive") {
          toastr["success"](data.success);
          target.prop("checked",false);
        } 
      }
    })
  }
  function createDataUptAll(){
    let test = true;
    let arr_id = [];
    let arr_pt_name = [];
    let count = $('.list-product-type td input[name*="check_id"]:checked').length;
    $('.list-product-type td input[name*="check_id"]:checked').each(function(){
      arr_id.push($(this).val());
    });
    if(count == 0) {
      $.alert({
        title: "Thông báo",
        content: "Bạn cần chọn dòng để sửa",
      })
      return false;
    }
    $("tr.selected input[name='pt_name']").each(function(){
      if($(this).val() != "") {
        arr_pt_name.push($(this).val());
        $(this).siblings("span.text-danger").text("");
      } else {
        $(this).siblings("span.text-danger").text("Không được để trống");
        test = false;
      }
    });
    if(!test) return;
    let result = {};
    arr_id.forEach((id,i) => result[id] = arr_pt_name[i]);
    console.log(result);
    return JSON.stringify(result);
  }
  function uptAll(){
    let json = createDataUptAll();
    if(json == false) {
      return;
    }
    $.ajax({
      url: window.location.href,
      type: "POST",
      data: {
        "status": "upt_all",
        "json": json,
      },
      success: function(data){
        console.log(data);
        data = JSON.parse(data);
        if(data.msg == "ok") {
          $.alert({
            title: "Thông báo",
            content: "Bạn đã sửa thành công",
          })
        }
      },
      error: function(data){
        console.log("Error: " + data);
      }
    })
  }
  function insAll(){
    let test = true;
    let arr_ins_all = [];
    let count = $("td input[name='name2']").length;
    $("td input[name='name2']").each(function(){
      let temp = $(this).val();
      if(temp != "" && temp != null) {
        arr_ins_all.push(temp);
        $(this).siblings("p.text-danger").text("");
      } else {
        $(this).siblings("p.text-danger").text("Không được để trống");
        test = false;
      }
    });
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
              data: {
                status: "ins_all",
                rows: arr_ins_all.join(","),
              },
              success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                  $.alert({
                    title: "Thông báo",
                    content: "Bạn đã thêm dữ liệu thành công",
                  });
                  $("#form-insert table tbody").remove();
                  $("input[name='count2']").val("");
                  $("input[name='count3']").val("");
                  $("input[name='count2']").attr("data-plus",0);
                  $("input[name='count3']").attr("data-plus",0);
                  $('#form-insert #paging').remove();
                  $('#modal-xl2').modal('hide');
                  loadDataComplete('Insert');
                }
              },
              error: function(data){
                console.log("Error: " + data);
              }
            });
          },"Không": function(){

          }
        },
      })
      console.log(arr_ins_all);
    }
    
  }
  function openModalInsert(){
    let parameters = new URLSearchParams(location.search);
    //console.log(location.search);
    let parent_id = parameters.get('parent_id');
    console.log(parent_id);
    $('#form-loai-san-pham').load(`ajax_category_manage.php?parent_id=${parent_id}&status=Insert`,() => {
      $('#modal-xl').modal({backdrop: 'static', keyboard: false});
    });
  }
  function readModal(){
    let id = $(event.currentTarget).attr('data-id');
    $(event.currentTarget).closest("tr").addClass("bg-color-selected");
    $('#form-loai-san-pham').load("ajax_category_manage.php?id=" + id + "&status=Read",() => {
      $('#modal-xl').modal({backdrop: 'static', keyboard: false});
    });
  }
  function openModalUpdate(){
    let id = $(event.currentTarget).attr('data-id');
    $(event.currentTarget).closest("tr").addClass("bg-color-selected");
    $('#form-loai-san-pham').load("ajax_category_manage.php?id=" + id + "&status=Update",() => {
        $('#modal-xl').modal({backdrop: 'static', keyboard: false});
    });
  }
  function processDelete(){
    let id = $(event.currentTarget).attr('data-id');
    let c_target = $(event.currentTarget);
    c_target.closest("tr").addClass("bg-color-selected");
    // lấy số lượng sản phẩm để show confirm
    $.ajax({
      url:"ajax_category_manage.php",
      type:"POSt",
      data: {
        id:id,
        status:"get_count_pi",
      },
      success:function(data){
        console.log(data);
        data = JSON.parse(data);
        $.confirm({
          title: 'Thông báo',
          content: `Nếu bạn xoá danh mục này, ${data['result']} sản phẩm và các danh mục con thuộc danh mục này sẽ bị xoá theo. Bạn có muốn tiếp tục ?`,
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
                  console.log(res);
                  res_json = JSON.parse(res);
                  if(res_json.msg == "ok") {
                    $.alert({
                      title: "Thông báo",
                      content: res_json.success,
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
              c_target.closest("tr").removeClass("bg-color-selected");
            },
          }
        });
      },
      error:function(data){
        console.log("Error: " + data);
      }
    })
  }
  function processModalInsertUpdate(){
    event.preventDefault();
    if(!$('input[name=ten_loai_san_pham]').val()) {
      $.alert({
        title: "Thông báo",
        content: "Vui lòng không để trống tên danh mục"
      });
      return;
    }
    $.ajax({
      url:window.location.href,
      type:"POST",
      cache:false,
      data:{
        id: $('input[name=id]').val(),
        name:$('input[name=ten_loai_san_pham]').val(),
        status: $('#btn-luu-loai-san-pham').attr("data-status"),
      },
      success:function(res){
        console.log(res);
        let res_json = JSON.parse(res);
        $('#form-loai-san-pham').trigger('reset');
        $('#modal-xl').modal('hide');
        if(res_json.msg == "ok"){
          let status = $('#btn-luu-loai-san-pham').attr("data-status");
          if(status == "Insert"){
            $.alert({
              title: "Thông báo",
              content: "Thêm danh mục thành công",
            });
            loadDataComplete('Insert');
          } else if(status == "Update") {
            $.alert({
              title: "Thông báo",
              content: "Sửa danh mục thành công",
            });
            loadDataComplete();
          }
        } else {
          $.alert({
            title: "Thông báo",
            content: res_json.error,
          });
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
      $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
      $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : null;
      $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
      $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
      $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
      if($status == "Delete") {
        $success = "Xoá dữ liệu thành công.";
        $error = "Network has problem. Please reload this page.";
        exec_del_pi_when_del_pt(NULL,$id);
        echo_json(['msg' => 'ok','success' => $success]);
      } else if($status == "Update") {
          ajax_db_update_by_id('product_type', ['name' => $name],[$id],["id" => $id,"name"=>$name]);
      } else if($status == "Insert") {
          if($parent_id) {
            $sql_get_active = "select is_active from product_type where id='$parent_id'";
            $row = fetch(sql_query($sql_get_active));
            $is_active = $row['is_active'] ? $row['is_active'] : 0;
            $sql = "Insert into product_type(name,parent_id,is_active) values(?,?,?)";
            sql_query($sql,[$name,$parent_id,$is_active]);
          } else {
            $sql = "Insert into product_type(name,is_active) values(?,?)";
            sql_query($sql,[$name,1]);
          }
          echo_json(["msg" => "ok"]);
      } else if($status == "active") {
        exec_active_all(NULL,$id);
        echo_json(['msg' => 'active','success' => 'Bạn đã kích hoạt danh mục thành công']);
      } else if($status == "deactive") {
        exec_deactive_all(NULL,$id);
        echo_json(['msg' => 'deactive','success' => 'Bạn đã huỷ kích hoạt danh mục thành công']);
      } else if($status == "upt_more") {
        $pt_id = isset($_REQUEST["pt_id"]) ? $_REQUEST["pt_id"] : null;
        $pt_name = isset($_REQUEST["pt_name"]) ? $_REQUEST["pt_name"] : null;
        $sql = "Update product_type set name = ? where id = ?";
        sql_query($sql,[$pt_name,$pt_id]);
        echo_json(["msg" => "ok"]);
      } else if($status == "del_more") {
        $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
        
        $rows = explode(",",$rows);
        foreach($rows as $row) {
          $sql = "Update product_type set is_delete = 1 where id = ?";
          sql_query($sql,[$row]);
        }
        echo_json(["msg" => "ok"]);
      } else if($status == "ins_more") {
        $name2 = isset($_REQUEST["name2"]) ? $_REQUEST["name2"] : null;
        $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
        $sql_get_active = "select is_active from product_type where id='$parent_id'";
        $row = fetch(sql_query($sql_get_active));
        $is_active = $row['is_active'] ? $row['is_active'] : 0;;
        if(!$parent_id || $parent_id == ""){
          $parent_id = null;
        }
        $sql = "Insert into product_type(name,parent_id,is_active) values(?,?,?)";
        sql_query($sql,[$name2,$parent_id,$is_active]);
        echo_json(["msg" => "ok"]);
      } else if($status == "ins_all") {
        $rows = isset($_REQUEST["rows"]) ? $_REQUEST["rows"] : null;
        $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
        $sql_get_active = "select is_active from product_type where id='$parent_id'";
        $row = fetch(sql_query($sql_get_active));
        $is_active = $row['is_active'] ? $row['is_active'] : 0;
        if($rows) {
          if(!$parent_id || $parent_id == ""){
            $parent_id = null;
          }
          foreach(explode(",",$rows) as $row) {
            $sql = "Insert into product_type(name,parent_id,is_active) values(?,?,?)";
            sql_query($sql,[$row,$parent_id,$is_active]);
          }
        }
        echo_json(["msg" => "ok"]);
      } else if($status == "upt_all") {
        $json = isset($_REQUEST["json"]) ? $_REQUEST["json"] : null;
        if($json) {
          //print_r($json);
          $rows = (array)json_decode($json);
          foreach($rows as $key => $value) {
            $sql_update = "Update product_type set name = ? where id = ?";
            sql_query($sql_update,[$value,$key]);
          }
          echo_json(["msg" => "ok"]);
        }
      } else if($status == "saveTabFilter") {
        $_SESSION['category_tab_id'] = isset($_SESSION['category_tab_id']) ? $_SESSION['category_tab_id'] + 1 : 1;
        $tab_name = isset($_SESSION['category_tab_id']) ? "tab_" . $_SESSION['category_tab_id'] : null;
        $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
        $tab_unique = uniqid("tab_");
        $_SESSION['category_manage_tab'] = isset($_SESSION['category_manage_tab']) ? $_SESSION['category_manage_tab'] : [];
        array_push($_SESSION['category_manage_tab'],[
            "tab_unique" => $tab_unique,
            "tab_name" => $tab_name,
            "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
        ]);
        echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['category_manage_tab']),"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
      } else if($status == "deleteTabFilter") {
        $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
        $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
        array_splice($_SESSION['category_manage_tab'],$index,1);
        if(trim($is_active_2) == "") {
            echo_json(["msg" => "ok"]);
        }  else if($is_active_2 == 1) {
            if(array_key_exists($index,$_SESSION['category_manage_tab'])) {
              echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['category_manage_tab'][$index]['tab_urlencode']]);
            } else if(array_key_exists($index - 1,$_SESSION['category_manage_tab'])){
              echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['category_manage_tab'][$index - 1]['tab_urlencode']]);
            } else {
              echo_json(["msg" => "ok","tab_urlencode" => "category_manage.php?tab_unique=all"]);
            }
        }
      } else if($status == "changeTabNameFilter") {
        $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
        $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
        $_SESSION['category_manage_tab'][$index]['tab_name'] = $new_tab_name;
        echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['category_manage_tab'][$index]['tab_urlencode']]);
      }
    }
    
?>