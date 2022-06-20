<?php
  include_once("../lib/database.php");
  logout_session_timeout();
  check_access_token();
  redirect_if_login_status_false();
  if(is_get_method()) {
    include_once("include/head.meta.php");
    include_once("include/left_menu.php");
    // code to be executed get method
?>
<!--html & css section start-->
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Đổi mật khẩu</h1>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Đổi mật khẩu</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div style="display:block;" class="tab-pane" id="settings">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" id="form-admin" method="post" class="form-horizontal" enctype='multipart/form-data'>
                      <div class="form-group row">
                        <label for="old_pass" class="col-sm-2 col-form-label">Mật khẩu xác thực</label>
                        <div class="col-sm-10">
                          <input name="old_pass" type="password" class="form-control" required>
                        </div>
                        <div id="old_pass_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="new_pass" class="col-sm-2 col-form-label">Mật khẩu mới</label>
                        <div class="col-sm-10">
                          <input name="new_pass" type="password" class="form-control" required>
                        </div>
                        <div id="new_pass_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="confirm_new_pass" class="col-sm-2 col-form-label">Xác nhận mật khẩu mới</label>
                        <div class="col-sm-10">
                          <input name="confirm_new_pass" type="password" class="form-control" required>
                        </div>
                        <!-- loi xac nhan mat khau -->
                        <div id="confirm_new_pass_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button id="btn-doi-mat-khau-admin" type="submit" class="btn btn-danger">Cập nhật</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>  
<!--html & css section end-->
<?php
    // js lib
    include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script>
    // cập nhật mật khẩu admin
    $(document).on('click','#btn-doi-mat-khau-admin',function(event){
        event.preventDefault();
        let old_pass = $('input[name=old_pass]').val();
        let new_pass = $('input[name=new_pass]').val();
        let confirm_new_pass = $('input[name=confirm_new_pass]').val();
        let url = window.location.href;
        $.ajax({
            url:url,
            type:"POST",
            data:{
              old_pass: old_pass,
              new_pass: new_pass,
              confirm_new_pass: confirm_new_pass,
              token: "<?php echo_token() ;?>"
            },
            success:function(res_json){
                res_json = JSON.parse(res_json);
                if(res_json.msg == "ok"){
                    $.alert({
                      title: "Thông báo",
                      content: "Cập nhật dữ liệu thành công."
                    });
                    //alert("Cập nhật dữ liệu thành công.");
                } else {
                  $.alert({
                    title: "Thông báo",
                    content: res.error
                  });
                } 
            },
            error: function (data) {
                console.log('Error:', data);
            }
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
        $old_pass = isset($_REQUEST["old_pass"]) ? $_REQUEST["old_pass"] : null;
        $new_pass = isset($_REQUEST["new_pass"]) ? $_REQUEST["new_pass"] : null;
        $session_id = $_SESSION["id"];
        $sql = "select password from user where id = '$session_id' limit 1";
        $row = fetch(sql_query($sql));
        if($old_pass && $new_pass && $session_id) {
          if(password_verify($old_pass,$row["password"])){
            $success = "Bạn đã đổi mật khẩu thành công.";
            $new_pass = password_hash($new_pass,PASSWORD_DEFAULT);
            //ajax_db_update_by_id('user',['password'=>$new_pass],[$session_id]);
            $sql_update = "Update user set password = '$new_pass' where id = '$session_id'";
            sql_query($sql_update);
            echo_json(["msg"=>"not_ok","success"=>$success]);
            exit();
          } else {
            $msg_error = "Bạn nhập mật khẩu cũ không chính xác";
            echo_json(["msg"=>"not_ok","error"=>$msg_error]);
            exit();
          }
        }
    }
?>