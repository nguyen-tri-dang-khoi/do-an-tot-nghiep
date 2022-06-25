<?php
   include_once("../lib/database.php");
   logout_session_timeout();
   check_access_token();
   redirect_if_login_status_false();
   if(is_get_method()) {
      // permission crud for user
      $allow_read = $allow_update = $allow_delete = $allow_insert = false; 
      if(check_permission_crud("notification_manage.php","read")) {
        $allow_read = true;
      }
      if(check_permission_crud("notification_manage.php","update")) {
        $allow_update = true;
      }
      if(check_permission_crud("notification_manage.php","delete")) {
        $allow_delete = true;
      }
      if(check_permission_crud("notification_manage.php","insert")) {
        $allow_insert = true;
      }
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
      // code to be executed get method
      $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $date_min = isset($_REQUEST['date_min']) ? $_REQUEST['date_min'] : null;
      $date_max = isset($_REQUEST['date_max']) ? $_REQUEST['date_max'] : null;
      $view_min = isset($_REQUEST['view_min']) ? $_REQUEST['view_min'] : null;
      $view_max = isset($_REQUEST['view_max']) ? $_REQUEST['view_max'] : null;
      $upt_more = isset($_REQUEST['upt_more']) ? $_REQUEST['upt_more'] : null;
      $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
      $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
      $str = isset($_REQUEST['str']) ? $_REQUEST['str'] : null;
      $where = "where 1=1 and is_delete = 0";
      $order_by = "Order by n.id desc";
      $wh_child = [];
      $arr_search = [];
      if($keyword && is_array($keyword)) {
         $wh_child = [];
         if($search_option == "all") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(n.title) like lower('%$key%') or lower(n.content) like lower('%$key%'))");
               }
            }
         } else if($search_option == "title") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(n.title) like lower('%$key%'))");
               }
            }
         }
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }
      
      if($date_min) {
         $date_min = Date("Y-m-d",strtotime($date_min));
         $where .= " and (n.created_at >= '$date_min 00:00:00')";
      }
      if($date_max) {
         $date_max = Date("Y-m-d",strtotime($date_max));
         $where .= " and (n.created_at <= '$date_max 23:59:59')";
      }

      if($view_min) {
         $view_min = str_replace(".","",$view_min);
         $where .= " and (n.views >= '$view_min')";
      }
      if($view_max) {
         $view_max = str_replace(".","",$view_max);
         $where .= " and (n.views <= '$view_max')";
      }
      if($str) {
         $where .= " and n.id in ($str)";
      }
      if($orderByColumn && $orderStatus) {
         $order_by = "ORDER BY $orderByColumn $orderStatus";
      }
      $where .= " $order_by";
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/summernote.min.css">
<style>
   .dt-buttons {
      float:left;
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
                     <h3 class="card-title">Quản lý bảng tin</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        <div class="input-group-append">
                           <?php
                              if($allow_insert) {
                           ?>
                           <button id="btn-them-bang-tin" onclick="openModalInsert()" class="dt-button button-blue">
                              Tạo bảng tin
                           </button>
                           <?php } ?>
                        </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body ok-game-start">
                     <div id="load-all">
                        <link rel="stylesheet" href="css/tab.css">             
                        <div style="padding-right:0px;padding-left:0px" class="col-12 mb-20 d-flex a-center j-between">
                           <ul style="width:1456px !important;overflow-x: auto;overflow-y: hidden;padding-right:0px;padding-left:0px;list-style-type:none;" id="ul-tab-id" class="d-flex ul-tab">
                              <?php
                                 $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
                                 
                                 $_SESSION['notification_manage_tab'] = isset($_SESSION['notification_manage_tab']) ? $_SESSION['notification_manage_tab'] : [];
                                 $_SESSION['notification_tab_id'] = isset($_SESSION['notification_tab_id']) ? $_SESSION['notification_tab_id'] : 0;
                              ?>
                              <li class="li-tab <?=$tab_unique == 'all' ||  $tab_unique == null ? 'tab-active' : ''?>"><button onclick="loadDataInTab('notification_manage.php?tab_unique=all')" class="tab tab-1">Tất cả</button></li>
                              <?php
                                 $ik = 0;
                                 $is_active = false;
                                 if(count($_SESSION['notification_manage_tab']) > 0) {
                                    foreach($_SESSION['notification_manage_tab'] as $tab) {
                                    if($tab['tab_unique'] == $tab_unique) {
                                       $_SESSION['notification_manage_tab'][$ik]['tab_urlencode'] = get_url_current_page();
                                    }
                              ?>
                                 <li data-index='<?=$ik;?>' oncontextmenu="focusInputTabName(this)" class="li-tab <?=$tab['tab_unique'] == $tab_unique ? 'tab-active' : '';?>">
                                    <button onclick="loadDataInTab('<?=$_SESSION['notification_manage_tab'][$ik]['tab_urlencode'];?>')" class="tab"><?=$tab['tab_name'];?></button>
                                    
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
                              <form id="form-filter" action="notification_manage.php" method="get" onsubmit="searchTabLoad('#form-filter')">
                                 <div class="d-flex a-start">
                                    <div class="" style="margin-top:5px;">
                                       <select onchange="choose_type_search()" class="form-control" name="search_option">
                                          <option value="">Bộ lọc tìm kiếm</option>
                                          <option value="keyword" <?=$search_option == 'keyword' ? 'selected="selected"' : '' ?>>Từ khoá</option>
                                          <option value="view2" <?=$search_option == 'view2' ? 'selected="selected"' : '' ?>>Lượt xem</option>
                                          <option value="date2" <?=$search_option == 'date2' ? 'selected="selected"' : '' ?>>Phạm vi ngày</option>
                                          <option value="all2" <?=$search_option == 'all2' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                       </select>
                                    </div>
                                    <div id="s-cols" class="k-select-opt ml-10 col-2 s-all2" style="<?=$keyword && $keyword != [""] ? "display:flex;flex-direction:column": "display:none;";?>">
                                       <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                       <span onclick="selectOptionInsert()" class="k-select-opt-ins"></span>
                                       <div class="ele-cols d-flex f-column">
                                          <select name="search_option" class="form-control mb-10">
                                             <option value="">Chọn cột tìm kiếm</option>
                                             <option value="title" <?=$search_option == 'title' ? 'selected="selected"' : '' ?>>Tiêu đề bài viết</option>
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
                                    <div id="s-date2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($date_min || $date_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                       <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                       <div class="ele-date2">
                                          <div class="" style="display:flex;">
                                             <input type="text" name="date_min" placeholder="Ngày 1" class="kh-datepicker2 form-control" value="<?=$date_min ? Date("d-m-Y",strtotime($date_min)) : '';?>">
                                          </div>
                                          <div class="ml-10" style="display:flex;">
                                             <input type="text" name="date_max" placeholder="Ngày 2" class="kh-datepicker2 form-control" value="<?=$date_max ? Date("d-m-Y",strtotime($date_max)) : '';?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div id="s-view2" class="k-select-opt ml-10 col-2 s-all2" style="<?=($view_min || $view_max) ? "display:flex;flex-direction:column;": "display:none;";?>">
                                       <span onclick="selectOptionRemove()" class="k-select-opt-remove"></span>
                                       <div class="ele-count2">
                                          <div class="" style="display:flex;">
                                             <input type="text" name="view_min" placeholder="Lượt xem 1" class="form-control" value="<?=$view_min ? number_format($view_min,0,".",".") : '';?>" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                          </div>
                                          <div class="ml-10" style="display:flex;">
                                             <input type="text" name="view_max" placeholder="Lượt xem 2" class="form-control" value="<?=$view_max ? number_format($view_max,0,".",".") : '';?>" onpaste="pasteAutoFormat(event)" onkeypress="allow_zero_to_nine(event)" onkeyup="allow_zero_to_nine(event)">
                                          </div>
                                          <!--<span onclick="select_remove_child()" class="kh-select-child-remove"></span>-->
                                       </div>
                                    </div>
                                    <input type="hidden" name="is_search" value="true">
                                    <input type="hidden" name="tab_unique" value="<?=$tab_unique;?>">
                                    <button type="submit" class="btn btn-default ml-10" style="margin-top:5px;"><i class="fas fa-search"></i></button>
                                 </div>
                                 <div class="col-12 d-flex a-start" style="padding-left:0;padding-right:0;display:flex;margin-top:15px;">
                                    <div style="" class="form-group row" style="flex-direction:row;align-items:center;">
                                       <!--<label for="">Sắp xếp:</label>-->
                                       <select name="orderByColumn" class="ml-10 form-control col-5">
                                          <option value="">Sắp xếp theo cột</option>
                                          <option value="title" <?=$orderByColumn == "title" ? "selected" : "";?>>Tiêu đề</option>
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
                           <table id="table-notification_manage" class="table table-bordered table-striped">
                              <thead>
                                 <tr style="cursor:pointer;">
                                    <th style="width:20px !important;">
                                       <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()" <?=$upt_more == 1 ? "checked" : "";?>>
                                    </th>
                                    <th class="w-120 th-so-thu-tu">Số thứ tự <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                    <th class="th-tieu-de">Tiêu đề <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                    <th class="w-150 th-luot-xem">Lượt xem <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                    <th class="w-150 th-ngay-tao">Ngày tạo <span class="sort ml-10"><i class="sort-asc fas fa-arrow-up"></i><i class="sort-desc fas fa-arrow-down"></i></span></th>
                                    <th class="w-200">Thao tác</th>
                                 </tr>
                              </thead>
                              <?php
                                 $get = $_GET;
                                 unset($get['page']);
                                 $str_get = http_build_query($get);
                                 // query
                                 $cnt = 0;
                                 $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;  
                                 $limit = $_SESSION['paging'];
                                 $start_page = $limit * ($page - 1);
                                 $sql_get_total = "select count(*) as 'countt' from notification n $where";
                                 $total = fetch(sql_query($sql_get_total))['countt'];
                                 $sql_get_product = "select * from notification n $where limit $start_page,$limit";
                              ?>
                              <tbody dt-parent-id dt-url="<?=$str_get;?>" dt-items="<?=$total;?>" dt-limit="<?=$limit;?>" dt-page="<?=$page?>" id="list-bang-tin" class="list-notification">
                              <?php
                              $rows = fetch_all(sql_query($sql_get_product));
                              foreach($rows as $row) {
                              ?>
                                 <tr id="<?=$row["id"];?>">
                                    <td>
                                       <input style="width:16px;height:16px;cursor:pointer" value="<?=$row["id"];?>" data-shift="<?=$cnt?>" onclick="shiftCheckedRange('.list-notification')" type="checkbox" name="check_id<?=$row["id"];?>" <?=$upt_more == 1 ? "checked" : "";?>>
                                    </td>
                                    <td class="so-thu-tu"><?=$total - ($start_page + $cnt);?></td>
                                    <td class="tieu-de"><?=$upt_more == 1 ? "<input class='kh-inp-ctrl' type='text' name='upt_title' value='$row[title]'><span class='text-danger'></span>" : $row['title'];?></td>
                                    <td class="luot-xem"><?=$row['views']?></td>
                                    <td class="ngay-tao"><?=$row['created_at'] ? Date("d-m-Y",strtotime($row['created_at'])) : "";?></td>
                                    <td>
                                       <?php
                                          if($upt_more != 1) {
                                       ?>
                                       <?php
                                          if($allow_read) {
                                       ?>
                                          <button onclick="openModalRead()" class="btn-xem-bang-tin dt-button button-grey"
                                          data-id="<?=$row["id"];?>">
                                          Xem
                                          </button>
                                       <?php } ?>
                                       <?php 
                                          if($allow_update) {
                                       ?>
                                          <button onclick="openModalUpdate()" class="btn-sua-bang-tin dt-button button-green" data-number="<?=$total - ($start_page + $cnt);?>"
                                          data-id="<?=$row["id"];?>" >
                                          Sửa
                                          </button>
                                       <?php } ?>
                                       <?php
                                          if($allow_delete) {
                                       ?>
                                          <button onclick="processDelete()" class="btn-xoa-bang-tin dt-button button-red" data-id="<?=$row["id"];?>">
                                          Xoá
                                          </button>
                                       <?php } ?>
                                       <?php
                                          } else {
                                       ?>
                                       <?php
                                          if($allow_update) {
                                       ?>
                                          <button dt-count="0" data-id="<?=$row["id"];?>" onclick="uptMore2()" class="dt-button button-green">Sửa</button>
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
                                 <td style="text-align:center;font-size:17px;" colspan="6">Không có dữ liệu</td>
                              </tr>
                              <?php } ?>
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th style="width:20px !important;">
                                       <input style="width:16px;height:16px;cursor:pointer" type="checkbox" name="check_all" id="" onchange="checkedAll()" <?=$upt_more == 1 ? "checked" : "";?>>
                                    </th>
                                    <th>Số thứ tự</th>
                                    <th>Tiêu đề</th>
                                    <th>Lượt xem</th>
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
        <h4 id="msg-del" class="modal-title">Thông tin bảng tin</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-bang-tin" method="post" enctype='multipart/form-data'>
            
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-xl2">
  <div class="modal-dialog modal-xl" style="min-width:1650px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="msg-del" class="modal-title">Thêm dữ liệu bảng tin nhanh</h4>
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
                        <input type="file" name="read_csv" accept=".csv" onchange="csv2input(this,['Tiêu đề','Nội dung'],['n_title2','n_content2'])">
                     </div>
                     <div class="file file-excel mr-10">
                        <input type="file" name="read_excel" accept=".xls,.xlsx" onchange="xlsx2input(this,['Tiêu đề','Nội dung'],['n_title2','n_content2'])">
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
                     <th>Tiêu đề</th>
                     <th>Nội dung</th>
                     <th>Ảnh đại diện</th>
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
<script src="js/summernote.min.js"></script>
<script src="js/summernote-vi-VN.js"></script>
<!--js section start-->
<?php
    include_once("include/dt_script.php");
?>
<script src="js/khoi_all.js"></script>
<!--searching filter-->
<script>
   <?=$upt_more != 1 ? "setSortTable();" : null;?>
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
      $(file_html).appendTo($(this).parent());
      $(this).parent().css({
         "flex-direction": "column",
         "justify-content": "space-between",
      });
      if($(event.currentTarget).closest('#s-date2').length) {
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
      } 
   }
   function select_remove_child(_class){
      $(event.currentTarget).closest(_class).remove();
   }
   
</script>
<script>
   function validate(){
      let test = true
      let title = $('input[name="title"]').val();
      let content2 = $('#summernote').summernote('code');
      if(title.trim() == "") {
         $.alert({
            title: "Thông báo",
            content: "Tiêu đề không được để trống"
         });
         test = false;
      } else if(content2.trim() == "") {
         $.alert({
            title: "Thông báo",
            content: "Nội dung bảng tin không được để trống"
         });
         test = false;
      }
      return test;
   }
   function readURL2(input){
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
            $('#display-image').attr('src', e.target.result);
         }
         reader.readAsDataURL(input.files[0]);
      }
   }
   function readURL(input,key) {
      let target = event.currentTarget;
      console.log(input.files);
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
            $(target).parent().css({
            'background-image' : 'url("' + e.target.result + '")',
            'background-size': 'cover',
            'background-position': '50%'
            });
         }
         reader.readAsDataURL(input.files[0]);
      }
	}
   function openModalRead(){
      let id = $(event.currentTarget).attr('data-id');
      let target = $(event.currentTarget);
      target.closest("tr").addClass("bg-color-selected");
      $('#form-bang-tin').load("ajax_notification.php?status=Read&id=" + id,() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
      });
   }
   function openModalInsert(){
      $('#form-bang-tin').load("ajax_notification.php?status=Insert",() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
         $('#btn-luu-bang-tin').text("Thêm");
         $(function(){
            setTimeout(() => {
               $('#summernote').summernote({height: 120,lang: 'vi-VN'});
            },100)
         });
         $("#fileInput").on("change",function(){
            $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
            readURL2(this); 
         });
      });
   }
   function openModalUpdate(){
      let id = $(event.currentTarget).attr('data-id');
      $(event.currentTarget).closest("tr").addClass("bg-color-selected");
      click_number = $(this).closest('tr');
      $('#form-bang-tin').load("ajax_notification.php?status=Update&id=" + id,() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
         $('#btn-luu-bang-tin').text("Sửa");
         $(function(){
            setTimeout(() => {
               $('#summernote').summernote({height: 120,lang: 'vi-VN'});
            },100);
         });
         $("#fileInput").on("change",function(){
            $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
            readURL2(this); 
         });
      });
   }
   function processModalInsertUpdate(){
      event.preventDefault();
      let formData = new FormData($('#form-bang-tin')[0]);
      let number = 1;
      formData.append('id',$('input[name=id]').val());
      formData.append('title',$('input[name=title]').val());
      formData.append('content',$('#summernote').summernote('code'));
      formData.append('status',$('#btn-luu-bang-tin').attr('data-status').trim());
      let file = $('input[name=img_bangtin_file]')[0].files;
      //console.log(file);
      if(file.length > 0) {
         formData.append('img_bangtin_file',file[0]); 
      }
      if(validate()) {
         $.ajax({
            url:window.location.href,
            type:"POST",
            cache:false,
            dataType:"json",
            contentType: false,
            processData: false,
            data:formData,
            success:function(res_json){
               // console.log(res_json);
               if(res_json.msg == 'ok'){
                  let status = $('#btn-luu-bang-tin').attr('data-status').trim();
                  let msg ="";
                  if(status == "Insert"){
                     msg = "Thêm dữ liệu thành công.";
                     $.alert({
                        title: "Thông báo",
                        content: msg,
                     });
                     loadDataComplete('Insert');
                  } else if(status == "Update") {
                     console.log(res_json);
                     msg = "Sửa dữ liệu thành công.";
                     $.alert({
                        title: "Thông báo",
                        content: msg,
                     });
                     loadDataComplete();
                  }
                  $('#form-bang-tin').trigger('reset');
                  $("#msg_style").removeAttr('style');
                  $("#msg").text(msg);
                  $('#modal-xl').modal('hide');
               } else if(res_json.msg == 'not_ok') {
                  $.alert({
                     title: "Thông báo",
                     content: res_json.error
                  });
               }
            },
            error: function (data) {
               console.log('Error:', data);
            }
         });
      }
   }
   function processDelete(){
      let id = $(event.currentTarget).attr('data-id');
      let target = $(event.currentTarget);
      $.confirm({
         title: 'Thông báo',
         content: 'Bạn có chắc chắn muốn xoá bảng tin này ?',
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
                     console.log(id);
                     res_json = JSON.parse(res);
                     if(res_json.msg == "ok") {
                        arr_input_file = new Map();
                        arr_list_file_del = [];
                        $.alert({
                           title: "Thông báo",
                           content: res_json.success
                        });
                        loadDataComplete("Delete");
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
               target.closest("tr").removeClass("bg-color-selected");
            },
         }
      });
   }
</script>
<script>
   function insAll(){
      let test = true;
      let formData = new FormData();
      let len = $('[data-plus]').attr('data-plus');
      let count = $('td input[name="n_title2"]').length;
      $('td input[name="n_title2"]').each(function(){
         if($(this).val() != "") {
            formData.append("n_title2[]",$(this).val());
            $(this).siblings("p.text-danger").text("");
         } else {
            $(this).siblings("p.text-danger").text("Không được để trống");
            test = false;
         }
      });
      $('td textarea[name="n_content2"]').each(function(){
         if($(this).val() != "") { 
            formData.append("n_content2[]",$(this).val());
            $(this).siblings("p.text-danger").text("");
         } else {
            $(this).siblings("p.text-danger").text("Không được để trống");
            test = false;
         }
      });
      $('td input[name="img3[]"]').each(function(){
         if($(this).val() != "") {
            formData.append("img3[]",$(this)[0].files[0]);
            $(this).parent().siblings("p.text-danger").text("");
         } else {
            $(this).parent().siblings("p.text-danger").text("Phải upload hình");
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
                  loadDataComplete("Insert");
               }
            },
            error: function(data){
               console.log("Error: " + data);
            }
         })
      }
      
   }
   function insMore2(){
      let test = true;
      let this2 = $(event.currentTarget).closest('tr');
      let n_title2 = $(event.currentTarget).closest('tr').find('td input[name="n_title2"]').val();
      let n_content2 = $(event.currentTarget).closest('tr').find('td textarea[name="n_content2"]').val();
      let img3 = $(event.currentTarget).closest('tr').find('input[name="img3[]"]')[0].files;
      //
      if(n_title2 == "") {
         this2.find('td input[name="n_title2"]').siblings("p.text-danger").text("Không được để trống");
         test = false;
      } else {
         this2.find('td input[name="n_title2"]').siblings("p.text-danger").text("");
      }
      //
      if(n_content2 == "") {
         this2.find('td textarea[name="n_content2"]').siblings("p.text-danger").text("Không được để trống");
         test = false;
      } else {
         this2.find('td textarea[name="n_content2"]').siblings("p.text-danger").text("");
      }
      //
      if(this2.find('td input[name="img3[]"]').val() == "") {
         this2.find('td input[name="img3[]"]').parent().siblings("p.text-danger").text("Phải upload hình");
         test = false;
      } else {
         this2.find('td input[name="img3[]"]').parent().siblings("p.text-danger").text("");
      }
      //
      if(test) {
         let formData = new FormData();
         formData.append("n_title2",n_title2);
         formData.append("n_content2",n_content2);
         formData.append("img3",img3);
         formData.append("status","ins_more");
         if(img3.length > 0) {
            formData.append('img3',img3[0]); 
         }
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
   function uptAll(){
      let test = true;
      let formData = new FormData();
      let _data = dt_n.rows(".selected").select().data();
      if(_data.length == 0) {
         $.alert({
            title:"Thông báo",
            content:"Vui lòng chọn dòng cần lưu",
         });
         return;
      }
      for(i = 0 ; i < _data.length ; i++) {
         formData.append("n_id[]",_data[i].DT_RowId);
      }
      $('tr.selected input[name="n_title"]').each(function(){
         if($(this).val() != "") {
            formData.append("n_title[]",$(this).val());
            $(this).siblings("span.text-danger").text("");
         } else {
            $(this).siblings("span.text-danger").text("Không được để trống");
            test = false;
         }
      });
      $('tr.selected textarea[name="n_content"]').each(function(){
         if($(this).summernote('code') != "") {
            formData.append("n_content[]",$(this).summernote('code'));
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
   /*function uptMore2(){
      let test = true;
      let title = $(event.currentTarget).closest("tr").find("td input[name='n_title']").val();
      let content = $(event.currentTarget).closest("tr").find("td .t-summernote").summernote('code');
      let id = $(event.currentTarget).attr('data-id');
      let this2 = $(event.currentTarget);
      if(title == "") {
         test = false;
         this2.find("td input[name='n_title']").siblings("span.text-danger").text("Không được để trống");
      } else {
         this2.find("td input[name='n_title']").siblings("span.text-danger").text("");
      }

      if(content == "") {
      test = false;
      this2.find("td input[name='n_title']").siblings("span.text-danger").text("Không được để trống");
      } else {
      this2.find("td input[name='n_title']").siblings("span.text-danger").text("");
      }
      if(test) {
      $.ajax({
         url: window.location.href,
         type: "POST",
         data: {
            status: "upt_more",
            n_title: title,
            n_content: content,
            n_id: id,
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
      
   }*/
</script>
<script>
   $(function(){
      $('.breadcrumb-item').click(function(){
         $('.kh-submenu').toggleClass('.kh-submenu-active');
      });
   });
</script>
<!--js section end-->
<?php
   include_once("include/pagination.php");
   include_once("include/footer.php");
?>
<?php
	
?>
<?php
   } else if (is_post_method()) {
      $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
      $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
      $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
      $title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : null;
      $content = isset($_REQUEST["content"]) ? $_REQUEST["content"] : null;
      if($status == 'Delete') {
         $success = "Bạn đã xoá dữ liệu thành công";
         $error = "Network has problem. Please try again.";
         $sql_del = "Update notification set is_delete = 1 where id = ?";
         sql_query($sql_del,[$id]);
         echo_json(["id" => $id,"success" => $success,"error" => $error]);
      } else if($status == "Insert") {
         $sql_check_exist = "select count(*) as 'countt' from notification where id = '$id'";
         $row = fetch(sql_query($sql_check_exist));
         if($row['countt'] > 0) {
            $error = "Tiêu đề bảng tin này đã tồn tại.";
            echo_json(['msg' => 'not_ok', 'error' => $error]);
         } else {
            $sql_insert = "Insert into notification(title,content,img_name) values(?,?,?)";
            sql_query($sql_insert,[$title,$content,1]);
            $insert = ins_id();
            $dir = "upload/notify";
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            $dir = "upload/notify/" . $insert;
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            if($_FILES['img_bangtin_file']['name'] != "") {
               $ext = strtolower(pathinfo($_FILES['img_bangtin_file']['name'],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $id . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['img_bangtin_file']['tmp_name'],$path);
               $sql_update = "update notification set img_name = ? where id = ?";
               sql_query($sql_update,[$path,$insert]);
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "Update") {
         $image = null;
         $dir = "upload/notify/" . $id;
         if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
         }
         if($_FILES['img_bangtin_file']['name'] != "") {
            $sql_get_old_file = "select img_name from notification where id = '$id'";
            $old_file = fetch(sql_query($sql_get_old_file))['img_name'];
            if(file_exists($old_file)){
               unlink($old_file);
            }
            $ext = strtolower(pathinfo($_FILES['img_bangtin_file']['name'],PATHINFO_EXTENSION));
            $file_name = md5(rand(1,999999999)). $id . "." . $ext;
            $file_name = str_replace("_","",$file_name);
            $path = $dir . "/" . $file_name ;
            move_uploaded_file($_FILES['img_bangtin_file']['tmp_name'],$path);
            $sql_update = "Update notification set img_name = ? where id = ?";
            sql_query($sql_update,[$path,$id]);
         }
         if($image) {
            $sql_update = "Update notification set title = '$title',content = '$content',img_name='$image' where id = '$id'";
            sql_query($sql_update);
         } else {
            $sql_update = "Update notification set title = '$title',content = '$content' where id = '$id'";
            sql_query($sql_update);
         }
         $success = "Update dữ liệu thành công.";
         $sql_get_file_name = "select img_name from notification where id = '$id'";
         $image = fetch(sql_query($sql_get_file_name));
         if($image) {
            $image = $image['img_name'];
         }
         echo_json(["msg" => "ok",'success' => $success,"id" => $id,"title"=>$title,"content"=>$content]);
      } else if($status == "upt_more") {
         $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
         $upt_title = isset($_REQUEST["upt_title"]) ? $_REQUEST["upt_title"] : null;
         $sql = "Update notification set title = ? where id = ?";
         sql_query($sql,[$upt_title,$upt_id]);
         echo_json(["msg" => "ok"]);
      } else if($status == "del_more") {
         $rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : null;
         $rows = explode(",",$rows);
         foreach($rows as $row) {
            $sql = "Update notification set is_delete = 1 where id = '$row'";
            sql_query($sql);
         }
         echo_json(["msg" => "ok"]);
      } else if($status == "ins_more") {
         $ins_title = isset($_REQUEST["ins_title"]) ? $_REQUEST["ins_title"] : null;
         $ins_content = isset($_REQUEST["ins_content"]) ? $_REQUEST["ins_content"] : null;
         $ins_img = isset($_REQUEST["ins_img"]) ? $_REQUEST["ins_img"] : null;
         $dir = "upload/notify/";
         $sql = "Insert into notification(title,content,img_name) values(?,?,?)";
         sql_query($sql,[$ins_title,$ins_content,1]);
         $insert = ins_id();
         if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
         }
         $dir = "upload/notify/" . $insert;
         if(!file_exists($dir)) {
            mkdir($dir, 0777); 
            chmod($dir, 0777);
         }
         if($_FILES['ins_img']['name'] != "") {
            $ext = strtolower(pathinfo($_FILES['ins_img']['name'],PATHINFO_EXTENSION));
            $file_name = md5(rand(1,999999999)). $id . "." . $ext;
            $file_name = str_replace("_","",$file_name);
            $path = $dir . "/" . $file_name ;
            move_uploaded_file($_FILES['ins_img']['tmp_name'],$path);
            $sql_update = "update notification set img_name = ? where id = ?";
            sql_query($sql_update,[$path,$insert]);
         }
         echo_json(["msg" => "ok"]);
      } else if($status == "ins_all") {
         $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
         $ins_title = isset($_REQUEST["ins_title"]) ? $_REQUEST["ins_title"] : null;
         $ins_content = isset($_REQUEST["ins_content"]) ? $_REQUEST["ins_content"] : null;
         $ins_img = isset($_FILES["ins_img"]) ? $_FILES["ins_img"] : null;
         $dir = "upload/notify/";
         for($i = 0 ; $i < $len ; $i++) {
            $sql = "Insert into notification(title,content,img_name) values(?,?,?)";
            sql_query($sql,[$ins_title[$i],$ins_content[$i],1]);
            $insert = ins_id();
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            $dir = "upload/notify/" . $insert;
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            if($_FILES['ins_img']['name'][$i] != "") {
               $ext = strtolower(pathinfo($_FILES['ins_img']['name'][$i],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $insert . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['ins_img']['tmp_name'][$i],$path);
               $sql_update = "update notification set img_name = ? where id = ?";
               sql_query($sql_update,[$path,$insert]);
            }
         }
         echo_json(["msg" => "ok"]);
      } else if($status == "upt_all") {
         $upt_id = isset($_REQUEST["upt_id"]) ? $_REQUEST["upt_id"] : null;
         $upt_title = isset($_REQUEST["upt_title"]) ? $_REQUEST["upt_title"] : null;
         $len = isset($_REQUEST["len"]) ? $_REQUEST["len"] : null;
         if($len && is_numeric($len)) {
            for($i = 0 ; $i < $len ; $i++){
               $sql = "Update notification set title = ? where id = ?";
               sql_query($sql,[$upt_title[$i],$upt_id[$i]]);
            }
            echo_json(["msg" => "ok"]);
         }
      } else if($status == "saveTabFilter") {
         $_SESSION['notification_tab_id'] = isset($_SESSION['notification_tab_id']) ? $_SESSION['notification_tab_id'] + 1 : 1;
         $tab_name = isset($_SESSION['notification_tab_id']) ? "tab_" . $_SESSION['notification_tab_id'] : null;
         $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
         $tab_unique = uniqid("tab_");
         $_SESSION['notification_manage_tab'] = isset($_SESSION['notification_manage_tab']) ? $_SESSION['notification_manage_tab'] : [];
         array_push($_SESSION['notification_manage_tab'],[
            "tab_unique" => $tab_unique,
            "tab_name" => $tab_name,
            "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
         ]);
         echo_json(["msg" => "ok","tab_name" => $tab_name,"tab_index" => count($_SESSION['notification_manage_tab'])- 1,"tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
      } else if($status == "deleteTabFilter") {
         $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
         $is_active_2 = isset($_REQUEST['is_active_2']) ? $_REQUEST['is_active_2'] : null;
         array_splice($_SESSION['notification_manage_tab'],$index,1);
         if(trim($is_active_2) == "") {
             echo_json(["msg" => "ok"]);
         }  else if($is_active_2 == 1) {
             if(array_key_exists($index,$_SESSION['notification_manage_tab'])) {
                 echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['notification_manage_tab'][$index]['tab_urlencode']]);
             } else if(array_key_exists($index - 1,$_SESSION['notification_manage_tab'])){
                 echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['notification_manage_tab'][$index - 1]['tab_urlencode']]);
             } else {
                 echo_json(["msg" => "ok","tab_urlencode" => "notification_manage.php?tab_unique=all"]);
             }
         }
      } else if($status == "changeTabNameFilter") {
         $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
         $new_tab_name = isset($_REQUEST['new_tab_name']) ? $_REQUEST['new_tab_name'] : null;
         $_SESSION['notification_manage_tab'][$index]['tab_name'] = $new_tab_name;
         echo_json(["msg" => "ok","tab_urlencode" => $_SESSION['notification_manage_tab'][$index]['tab_urlencode']]);
     }
   }
?>