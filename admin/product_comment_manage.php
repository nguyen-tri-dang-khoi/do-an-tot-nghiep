<?php
   include_once("../lib/database.php");
   if(is_get_method()) {
      $allow_read = $allow_update = $allow_delete = $allow_insert = $allow_check_product = false; 
      if(check_permission_crud("product_manage.php","read")) {
        $allow_read = true;
      }
      if(check_permission_crud("product_manage.php","update")) {
        $allow_update = true;
      }
      if(check_permission_crud("product_manage.php","delete")) {
        $allow_delete = true;
      }
      if(check_permission_crud("product_manage.php","insert")) {
        $allow_insert = true;
      }
      if(check_permission_crud("product_manage.php","check_product")) {
         $allow_check_product = true;
      }
      include_once("include/head.meta.php");
      include_once("include/left_menu.php");
      $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
      $is_active = isset($_REQUEST['is_active']) ? $_REQUEST['is_active'] : null;
      $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
      $orderByColumn = isset($_REQUEST['orderByColumn']) ? $_REQUEST['orderByColumn'] : null;
      $orderStatus = isset($_REQUEST['orderStatus']) ? $_REQUEST['orderStatus'] : null;
      $order_by = "";
      $where = "where 1=1 and pi.is_delete = 0 ";
      $wh_child = [];
      $arr_search = [];
      if($keyword && is_array($keyword)) {
         $wh_child = [];
         if($search_option == "all") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.name) like lower('%$key%') or lower(pi.count) like lower('%$key%') or lower(pi.price) like lower('%$key%') or lower(pt.name) like lower('%$key%'))");
               }
            }
         } else if($search_option == "name") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.name) like lower('%$key%'))");
               }
            }
         } else if($search_option == "price") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.price) like lower('%$key%'))");
               }
            }
         } else if($search_option == "count") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pi.count) like lower('%$key%'))");
               }
            }
         } else if($search_option == "type") {
            foreach($keyword as $key) {
               if($key != "") {
                  array_push($wh_child,"(lower(pt.name) like lower('%$key%'))");
               }
            }
         }
         
         $wh_child = implode(" or ",$wh_child);
         if($wh_child != "") {
            $where .= " and ($wh_child)";
         }
      }
      if($is_active) {
         if($is_active == "Active") {
            $where .= " and product_comment.is_active = 1";
         } else if($is_active == "Deactive") {
            $where .= " and product_comment.is_active = 0";
         }
      }
      if($orderStatus && $orderByColumn) {
         $order_by .= "ORDER BY $orderByColumn $orderStatus";
         $where .= " $order_by";
      }
      //log_v($where);
?>
<!--html & css section start-->
<link rel="stylesheet" href="css/summernote.min.css">
<link rel="stylesheet" href="css/toastr.min.css">
<style>
	ul.col-md-6 > li.parent:first-child {
      border: 1px solid #dce1e5;
      position: relative;
      height: 39px;
      margin: auto;
      padding-top: 3px;
   }
</style>
<div class="container-wrapper" style="margin-left:250px;">
  <div class="container-fluid" style="padding:0px;">
    <section class="content">
        <div class="row" style="">
            <div class="col-12">
               <div class="card">
                  <div class="card-header" style="display: flex;justify-content: space-between;">
                     <h3 class="card-title">Quản lý bình luận sản phẩm</h3>
                     <div class="card-tools">
                        <div class="input-group">
                        </div>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <div class="col-12" style="padding-right:0px;padding-left:0px;">
                        <form style="" autocomplete="off" action="product_manage.php" method="get" onsubmit="customInpSend()">

                        </form>
                     </div>
                     <table id="m-product-info" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th class="w-20-imp" ></th>
                              <th class="w-100">Số thứ tự</th>
                              <th>Tên sản phẩm</th>
                              <th class="w-200-imp">Thao tác</th>
                           </tr>
                        </thead>
                        <tbody id="list-san-pham">
                        <?php
                           $cnt = 0;
                           $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;  
                           $limit = $_SESSION['paging'];
                           $start_page = $limit * ($page - 1);
                           $sql_get_total = "select count(*) as 'countt' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where";
                           $total = fetch(sql_query($sql_get_total))['countt'];
                           $sql_get_product = "select pi.id,pi.is_active, pi.name as 'pi_name',pi.price,pi.count,pi.img_name as 'pi_img_name',pi.created_at,pt.name as 'pt_name',pi.product_type_id as 'pt_id' from product_info pi left join product_type pt on pi.product_type_id = pt.id $where limit $start_page,$limit";
                           //print_r($sql_get_product);
                           $rows = fetch_all(sql_query($sql_get_product));
                           foreach($rows as $row) {
                           ?>
                              <tr id="<?=$row["id"];?>">
                                 <td></td>
                                 <td><?=$total - ($start_page + $cnt);?></td>
                                 <td><?=$row['pi_name'];?></td>
                                 <td>
                                     <button onclick='showListComment("<?=$row["id"];?>")' class="dt-button button-grey">Xem bình luận</button>
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
                              <th>Tên sản phẩm</th>
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
    </section>
  </div>
</div>
<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
         <h4 id="msg-del" class="modal-title">Thông tin bình luận sản phẩm</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="list-product-comment">

         </div>
      </div>
    </div>
  </div>
</div>
<!--html & css section end-->


<?php
   include_once("include/bottom.meta.php");
   include_once("include/dt_script.php");
?>
<script src="js/khoi_all.js"></script>
<script>
   function showListComment(id,page=1) {
      $('#list-product-comment').load(`ajax_product_comment.php?status=show_list_comment&id=${id}&page=${page}`,() => {
         $('#modal-xl').modal({backdrop: 'static', keyboard: false});
      });
   }
   function showReplyOk(reply_id,product_info_id,type=""){
      $(`.info${reply_id}`).load(`ajax_product_comment.php?status=show_reply_ok&reply_id=${reply_id}&id=${product_info_id}`,() => {
         if(type == "input") {
            $(`.input${reply_id}`).removeClass('d-none').addClass('d-flex');
            $(`.kh-border-vertical${reply_id}`).css({"border-left":"1px solid #ddd","min-height":"100%"});
         }
      });
   }
   function sendComment(reply_id,product_info_id) {
      let test = true;
      let comment = $(`textarea[name="reply${reply_id}"]`).val();
      console.log(comment);
      if(comment.trim() == "") {
         $.alert({
            title : "Thông báo",
            content: "Vui lòng không đê trống nội dung bình luận."
         });
         test = false;
      }
      if(test) {
         $.ajax({
            url:window.location.href,
            type:"POST",
            data: {
               comment: comment,
               status: "Send",
               reply_id: reply_id,
               product_info_id: product_info_id,
            },success:function(data) {
               console.log(data);
               data = JSON.parse(data);
               if(data.msg == "ok") {
                  showReplyOk(reply_id,product_info_id,"input");
                  $(`textarea[name="reply${reply_id}"]`).val("");
               }
            },error:function(data){
               console.log("Error: " + data);
            }
         })
      }
      
   }
   function delComment(comment_id,product_info_id) {
      let evt = $(event.currentTarget);
      $.confirm({
         title: "Thông báo",
         content: "Nếu bạn xoá bình luận này, các phản hồi về bình luận này sẽ bị xoá theo. Bạn có chắc chắn ?",
         buttons: {
            "Có": function(){
               $.ajax({
                  url:window.location.href,
                  type:"POST",
                  data: {
                     status: "Delete",
                     id: comment_id,
                  },success:function(data) {
                     console.log(data);
                     data = JSON.parse(data);
                     if(data.msg == "ok") {
                        evt.closest(`.d-flex.mt-10`).remove();
                     }
                  },error:function(data){
                     console.log("Error: " + data);
                  }
               })
            },"Không":function(){

            }
         }
      })
   }
   function toggleComment(comment_id,product_type_id,status) {
      let evt = $(event.currentTarget);
      $.ajax({
         url:window.location.href,
         type:"POST",
         data: {
            status: status,
            id: comment_id,
         },success:function(data) {
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
               toastr["success"](data.success);
               if(status == "Active") {
                  evt.attr('onchange',`toggleComment('${comment_id}','${product_type_id}','Deactive')`);
               } else if(status == "Deactive") {
                  evt.attr('onchange',`toggleComment('${comment_id}','${product_type_id}','Active')`);
               }
               showReplyOk(comment_id,product_type_id,'input');
            }
         },error:function(data){
            console.log("Error: " + data);
         }
      })
   }
</script>
<!-- datatable and function crud js-->
<script>
   $("#modal-xl2").on("hidden.bs.modal",function(){
      let html = $("#form-product2 table");
      console.log(html.html());
      $("#form-product2 table tbody").remove();
      $("input[name='count2']").val("");
      $("input[name='count2']").attr("data-plus",0);
   })
   $('#modal-xl2').on('hidden.bs.modal', function (e) {
      $('#form-product2 table tbody').remove();
      $('#form-product2 #paging').remove();
      $('[data-plus]').attr('data-plus',0);
    })
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
   } else if (is_post_method()) {
      $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
      $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
      $comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : null;
      $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
      $reply_id = isset($_REQUEST['reply_id']) ? $_REQUEST['reply_id'] : null;
      $product_info_id = isset($_REQUEST['product_info_id']) ? $_REQUEST['product_info_id'] : null;
      $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
      $is_active = isset($_REQUEST['is_active']) ? $_REQUEST['is_active'] : 0;
      if($status == "Send") {
         $sql_ins_comment = "Insert into product_comment(reply_id,user_id,product_info_id,comment,rate,is_active) values(?,?,?,?,?,?)";
         sql_query($sql_ins_comment,[$reply_id,$user_id,$product_info_id,$comment,$rate,$is_active]);
         echo_json(["msg" => "ok"]);
      } else if($status == "Delete") {
         exec_delete_comment(NULL,$id);
         echo_json(["msg" => "ok"]);
      } else if($status == "Active") {
         exec_toggle_comment(NULL,$id,"Active");
         echo_json(["msg" => "ok","success" => "Bạn đã duyệt bình luận thành công."]);
      } else if($status == "Deactive") {
         exec_toggle_comment(NULL,$id,"Deactive");
         echo_json(["msg" => "ok","success" => "Bạn đã bỏ duyệt bình luận thành công."]);
      }
   }
?>