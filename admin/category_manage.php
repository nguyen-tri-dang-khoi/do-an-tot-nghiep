<?php
    include_once("../lib/database.php");
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
                                  <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all"  onchange="checkedAll()" <?=$upt_more == 1 ? "checked" : "";?>>
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
                            <tr class="parent-type <?=$upt_more == 1 ? "selected" : "";?>" style="cursor:pointer;" id="<?=$product_type["id"];?>">
                              <td>
                                <input <?=$upt_more == 1 ? "checked" : "";?> style="width:16px;height:16px;cursor:pointer" value="<?=$product_type["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange()" type="checkbox" name="check_id<?=$product_type["id"];?>">
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
                              <td class="danh-muc"><input tabindex="<?=$cnt+1;?>" class='kh-inp-ctrl' type="text" name="upt_name" value="<?=$product_type['name'];?>"><span class="text-danger"></span></td>
                              <?php
                                }
                              ?>
                              
                              <td class="ngay-tao" onclick="loadDataInTab('category_manage.php?upt_more=<?=$upt_more;?>&parent_id=<?=$product_type['id'];?>&tab_unique=<?=$tab_unique;?>')"><?=Date("d-m-Y",strtotime($product_type["created_at"]));?></td>
                              <td>
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" onchange="toggleStatus('<?=$product_type['id'];?>','<?= $product_type['is_active'] == 1 ? 'Deactive' : 'Active';?>')" class="custom-control-input" id="customSwitches<?=$product_type['id'];?>" <?= $product_type['is_active'] == 1 ? "checked" : "";?>>
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
                          <?php
                            $count_row_table = count($product_types);
                            if($count_row_table == 0) {
                          ?>
                          <tr>
                            <td style="text-align:center;font-size:17px;" colspan="6">Không có dữ liệu</td>
                          </tr>
                          <?php } ?>
                          <tfoot>
                            <tr>
                              <th><input <?=$upt_more == 1 ? "checked" : "";?> style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()"></th>
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
      <div class="modal-body">
        <form id="form-product-type" action="" method="post">

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
              <!-- <div class="d-flex f-column form-group">
                <div style="cursor:pointer;" class="d-flex list-file-read mt-10 mb-10">
                  <div class="file file-csv mr-10">
                    <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Tên danh mục'],['ins_name'])">
                  </div>
                  <div class="file file-excel mr-10">
                    <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Tên danh mục'],['ins_name'])">
                  </div>
                  <div class="d-empty">
                    <button onclick="delEmpty()" style="font-size:30px;font-weight:bold;width:64px;height:64px;" class="dt-button button-red k-btn-plus">x</button>
                  </div>
                </div>
              </div> -->
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

<script src="js/khoi_all.js"></script>
<script>
  <?=$upt_more != 1 && $count_row_table != 0 ? 'setSortTable();' : null;?>
  $('#select-type2').select2();
  function readURLok(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#where-replace').css({
          'background-image': `url('${e.target.result}')`,
          'background-size':'cover',
          'height':'300px',
        });
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  function uptMore2(){
    $('span.text-danger').text('');
    let test = true;
    let target2 = $(event.currentTarget).closest('tr');
    target2.find('span.text-danger').text('');
    let upt_name = target2.find('td input[name="upt_name"]').val();
    //console.log(upt_name);
    let upt_id = $(event.currentTarget).attr('data-id');
    //console.log(upt_id);
    if(upt_name == "") {
      target2.find('span.text-danger').text("Không được để trống");
      test = false;
    } else if(upt_name.length > 200) {
      target2.find('span.text-danger').text("Không được quá 200 ký tự");
      test = false;
    }
    if(test) {
      let this2 = $(event.currentTarget);
      let formData = new FormData();
      formData.append('status','upt_more')
      formData.append('upt_id',upt_id);
      formData.append('upt_name',upt_name);
      $.ajax({
          url: window.location.href,
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          success: function(data) {
              console.log(data);
              data = JSON.parse(data);
              if (data.msg == "ok") {
                  $.alert({
                    title: "Thông báo",
                    content: "Bạn đã sửa dữ liệu thành công",
                  })
                  //loadDataComplete();
              }
          },
          error: function(data) {
              console.log("Error: " + data);
          }
      })
    }
  }
  function insMore2(){
    let test = true;
    let target2 = $(event.currentTarget).closest('tr');
    target2.find('p.text-danger').text('');
    let ins_name = target2.find('td input[name="ins_name"]').val();
    if(ins_name == "") {
      target2.find('p.text-danger').text("Không được để trống");
      test = false;
    } else if(ins_name.length > 200) {
      target2.find('p.text-danger').text("Không được quá 200 ký tự");
      test = false;
    }
    if(test) {
      let this2 = $(event.currentTarget);
      let formData = new FormData();
      formData.append('status','ins_more')
      formData.append('ins_name',ins_name);
      $.ajax({
          url: window.location.href,
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          success: function(data) {
              console.log(data);
              data = JSON.parse(data);
              if (data.msg == "ok") {
                  $.alert({
                      title: "Thông báo",
                      content: "Bạn đã thêm dữ liệu thành công",
                      buttons: {
                          "Ok": function() {
                              this2.closest('tr').find('input').val("");
                              this2.closest('tr').find('textarea').val("");
                              this2.closest('tr').find('select > option[value=""]').prop("selected", true);
                          }
                      }
                  })
                  loadDataComplete("Insert");
              }
          },
          error: function(data) {
              console.log("Error: " + data);
          }
      })
    }
  }
  function uptAll(){
    let all_checkbox = getIdCheckbox();
    let list_checkbox = all_checkbox['result'].split(",");
    let formData = new FormData();
    formData.append('status','upt_all');
    $('tr.selected td input[name="upt_name"]').each(function(){
      if($(this).val() != ""){
        formData.append('upt_name[]',$(this).val());
      } else {  
        $(this).siblings('span.text-danger').text("Danh mục sản phẩm không để trống");
      }
    })
    for(i = 0 ; i < list_checkbox.length ; i++){
      formData.append('upt_id[]',list_checkbox[i]);
    }
    if(all_checkbox['count'] > 0) {
      $.ajax({
        url: window.location.href,
        type: "POST",
        cache:false,
        contentType: false,
        processData: false,
        data: formData,
        success: function(data){
          console.log(data);
          data = JSON.parse(data);
          if(data.msg == "ok") {
            $.alert({
              title: "Thông báo",
              content: "Bạn đã sửa dữ liệu thành công",
              buttons:{
                "Ok":function(){
                  location.reload();
                }
              }
            })
          }
        },
        error: function(data){
          console.log("Error: " + data);
        }
      })
    } else {
      $.alert({
        title:"Thông báo",
        content:"Bạn chưa chọn dòng cần sửa."
      });
    }
  }
  function insAll(){
    let test = true;
    let arr_ins_all = [];
    let formData = new FormData();
    let len = $('[data-plus]').attr('data-plus');
    formData.append('status','ins_all');
    formData.append('len',len);
    $("td input[name='ins_name']").each(function(){
      if($(this).val() != ""){
        formData.append('ins_name[]',$(this).val());
      } else {
        $(this).siblings('p.text-danger').text("Vui lòng không để trống tên danh mục");
        test = false;
      }
    });
    if(len == 0) {
      $.alert({
        title:"Thông báo",
        content:"Vui lòng tạo input"
      })
      test = false;
    }
    if(test) {
      $.confirm({
        title: "Thông báo",
        content: `Bạn có chắc chắn muốn thêm ${len} dòng này ?`,
        buttons: {
          "Có": function(){
            $.ajax({
              url: window.location.href,
              type: "POST",
              cache:false,
              contentType: false,
              processData: false,
              data: formData,
              success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                  $.alert({
                    title: "Thông báo",
                    content: "Bạn đã thêm dữ liệu thành công",
                    buttons:{
                      "Ok":function(){
                        location.reload();
                      }
                    }
                  });
                  loadDataComplete('Insert');
                  $('#modal-xl2').modal('hide');
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
    let parent_id = parameters.get('parent_id');
    //console.log(parent_id);
    if(parent_id) {
      $('#form-product-type').load(`ajax_category_manage.php?parent_id=${parent_id}&status=Insert`,() => {
        $('#modal-xl').modal({backdrop: 'static', keyboard: false});
      });
    } else {
      $('#form-product-type').load(`ajax_category_manage.php?status=Insert`,() => {
        $('#modal-xl').modal({backdrop: 'static', keyboard: false});
      });
    }
  }
  function readModal(){
    let id = $(event.currentTarget).attr('data-id');
    $(event.currentTarget).closest("tr").addClass("bg-color-selected");
    $('#form-product-type').load("ajax_category_manage.php?id=" + id + "&status=Read",() => {
      $('#modal-xl').modal({backdrop: 'static', keyboard: false});
    });
  }
  function openModalUpdate(){
    let id = $(event.currentTarget).attr('data-id');
    $(event.currentTarget).closest("tr").addClass("bg-color-selected");
    $('#form-product-type').load("ajax_category_manage.php?id=" + id + "&status=Update",() => {
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
    let test = true;
    let name = $('input[name=ten_loai_san_pham]').val();
    let file_img = $('input[name="img_category_file"]')[0].files;
    if(name == "") {
      $('#name_err').text("Vui lòng không để trống tên danh mục");
      test = false;
    } else if(name.length > 200) {
      $('#name_err').text("Vui lòng nhập tên danh mục nhỏ hơn hoặc bằng 200 ký tự");
      test = false;
    }

    if(file_img.length == 0) {
      if($('#where-replace > img').length == 0) {
        $('#img_name_err').text("Vui lòng không để trống hình ảnh");
        test = false;
      } 
    }
    if(test) {
      let formData = new FormData($('#form-product-type')[0]);
      formData.append('id',$('input[name=id]').val());
      formData.append('name',$('input[name=ten_loai_san_pham]').val());
      formData.append('parent_id',$('input[name=parent_id]').val());
      formData.append('status',$('#btn-luu-loai-san-pham').attr("data-status"));
      if($('input[name="img_category_file"]')[0].files.length > 0) {
        formData.append('img_category_file',$('input[name="img_category_file"]')[0].files[0]);
      }
      console.log(formData.getAll('img_category_file'));
      $.ajax({
        url:window.location.href,
        type:"POST",
        cache:false,
        contentType:false,
        processData:false,
        data: formData,
        success:function(res){
          console.log(res);
          let res_json = JSON.parse(res);
          $('#form-product-type').trigger('reset');
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
      })
    }
    /*if(!$('input[name=ten_loai_san_pham]').val()) {
      $.alert({
        title: "Thông báo",
        content: "Vui lòng không để trống tên danh mục"
      });
      return;
    }
    ;*/
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
      $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
      $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
      $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
      if($status == "Delete") {
        $success = "Xoá dữ liệu thành công.";
        $error = "Network has problem. Please reload this page.";
        exec_del_pi_when_del_pt(NULL,$id);
        echo_json(['msg' => 'ok','success' => $success]);
      } else if($status == "Update") {
        $sql_upt = "Update product_type set name = ? where id = ?";
        sql_query($sql_upt,[$name,$id]);
        $dir = "upload/category";
        if(!file_exists($dir)) {
          mkdir($dir, 0777); 
          chmod($dir, 0777);
        }
        $dir = "upload/category/" . $id;
        if(!file_exists($dir)) {
          mkdir($dir, 0777); 
          chmod($dir, 0777);
        }
        if($_FILES['img_category_file']['name'] != "") {
          $sql_get_old_file = "select img_name from product_type where id = '$id'";
          $old_file = fetch(sql_query($sql_get_old_file))['img_name'];
          if(file_exists($old_file)){
            unlink($old_file);
          }
          $ext = strtolower(pathinfo($_FILES['img_category_file']['name'],PATHINFO_EXTENSION));
          $file_name = md5(rand(1,999999999)). $id . "." . $ext;
          $file_name = str_replace("_","",$file_name);
          $path = $dir . "/" . $file_name ;
          move_uploaded_file($_FILES['img_category_file']['tmp_name'],$path);
          $sql_update = "update product_type set img_name = ? where id = ?";
          sql_query($sql_update,[$path,$id]);
        }
        echo_json(["msg" => "ok"]);
      } else if($status == "Insert") {
          //print_r($parent_id);
          if($parent_id && $parent_id != "undefined") {
            $sql_get_active = "select is_active from product_type where id='$parent_id'";
            $row = fetch(sql_query($sql_get_active));
            $is_active = $row['is_active'] ? $row['is_active'] : 0;
            $sql = "Insert into product_type(name,parent_id,is_active) values(?,?,?)";
            sql_query($sql,[$name,$parent_id,$is_active]);
          } else {
            
            $sql = "Insert into product_type(name,is_active) values(?,?)";
            sql_query($sql,[$name,1]);
          }
          $insert = ins_id();
          $dir = "upload/category";
          if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
          }
          $dir = "upload/category/" . $insert;
          if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
          }
          if($_FILES['img_category_file']['name'] != "") {
            $ext = strtolower(pathinfo($_FILES['img_category_file']['name'],PATHINFO_EXTENSION));
            $file_name = md5(rand(1,999999999)). $id . "." . $ext;
            $file_name = str_replace("_","",$file_name);
            $path = $dir . "/" . $file_name ;
            move_uploaded_file($_FILES['img_category_file']['tmp_name'],$path);
            $sql_update = "update product_type set img_name = ? where id = ?";
            sql_query($sql_update,[$path,$insert]);
          }
          echo_json(["msg" => "ok"]);
      } else if($status == "Active") {
        exec_active_all(NULL,$id);
        echo_json(['msg' => 'Active','success' => 'Bạn đã kích hoạt danh mục thành công']);
      } else if($status == "Deactive") {
        exec_deactive_all(NULL,$id);
        echo_json(['msg' => 'Deactive','success' => 'Bạn đã huỷ kích hoạt danh mục thành công']);
      } else if($status == "upt_more") {
        $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
        $upt_name = isset($_REQUEST["upt_name"]) ? $_REQUEST["upt_name"] : null;
        $sql = "Update product_type set name = ? where id = ?";
        sql_query($sql,[$upt_name,$upt_id]);
        echo_json(["msg" => "ok"]);
      } else if($status == "del_more") {
        $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
        $rows = explode(",",$rows);
        foreach($rows as $row) {
          exec_del_pi_when_del_pt(NULL,$row);
        }
        echo_json(["msg" => "ok"]);
      } else if($status == "ins_more") {
        $ins_name = isset($_REQUEST["ins_name"]) ? $_REQUEST["ins_name"] : null;
        $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
        $sql_get_active = "select is_active from product_type where id='$parent_id'";
        $row = fetch(sql_query($sql_get_active));
        $is_active = $row['is_active'] ? $row['is_active'] : 0;
        if(!$parent_id || $parent_id == ""){
          $parent_id = null;
        }
        $sql = "Insert into product_type(name,parent_id,is_active) values(?,?,?)";
        sql_query($sql,[$ins_name,$parent_id,$is_active]);
        echo_json(["msg" => "ok"]);
      } else if($status == "ins_all") {
        $ins_name = isset($_REQUEST["ins_name"]) ? $_REQUEST["ins_name"] : null;
        $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
        $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
        $sql_get_active = "select is_active from product_type where id='$parent_id'";
        $row = fetch(sql_query($sql_get_active));
        $is_active = $row['is_active'] ? $row['is_active'] : 0;
        if($ins_name) {
          if(!$parent_id || $parent_id == ""){
            $parent_id = null;
          }
          for($i = 0 ; $i < $len ; $i++) {
            $sql = "Insert into product_type(name,parent_id,is_active) values(?,?,?)";
            sql_query($sql,[$ins_name[$i],$parent_id,$is_active]);
          }
        }
        echo_json(["msg" => "ok"]);
      } else if($status == "upt_all") {
        $upt_name = isset($_REQUEST["upt_name"]) ? $_REQUEST["upt_name"] : null;
        $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
        $len = count($upt_id);
        if($upt_id) {
          for($i = 0 ; $i < $len ; $i++){
            $sql_update = "Update product_type set name = ? where id = ?";
            sql_query($sql_update,[$upt_name[$i],$upt_id[$i]]);
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