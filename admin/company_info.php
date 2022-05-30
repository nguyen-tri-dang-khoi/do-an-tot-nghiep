<?php
    include_once("../lib/database_v2.php");
    logout_session_timeout();
    check_access_token();
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
?>
<!--html & css section start-->
<?php
    $session_id = $_SESSION["id"];
    $sql_get_info = "select * from company_info limit 1";
    $cp_info = fetch(sql_query($sql_get_info));

?>
<div class="container-wrapper" style="margin-left:250px;">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Hồ sơ</h1>
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
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Thông tin công ty</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div style="display:block;" class="tab-pane" id="settings">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" id="form-admin" method="post" class="form-horizontal" enctype='multipart/form-data'>
                      <div class="form-group row">
                        <label for="full_name" class="col-sm-2 col-form-label">Họ tên</label>
                        <div class="col-sm-10">
                          <input name="full_name" type="text" class="form-control" value="<?=$cp_info["company_name"];?>"">
                        </div>
                        <!-- loi ho ten -->
                        <div id="name_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10 d-flex">
                          <input name="email" type="email" class="form-control" value="<?=$cp_info["company_email"];?>">
                        </div>
                        <!-- loi email -->
                        <div id="email_err" class="text-danger"></div>
                        </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Ngày tạo</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value=<?=Date('d-m-Y',strtotime($cp_info["created_at"]));?> readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Số điện thoại</label>
                        <div class="col-sm-10 d-flex">
                          <input name="phone" type="text" class="form-control" value="<?=$cp_info["company_phone"];?>" >
                        </div>
                        <div id="phone_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Địa chỉ</label>
                        <div class="col-sm-10">
                          <input name="address" type="text" class="form-control" value="<?=$cp_info["company_address"];?>">
                        </div>
                        <div id="address_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Mã số thuế</label>
                        <div class="col-sm-10">
                        <input name="address" type="text" class="form-control" value="<?=$cp_info["company_tax_code"];?>">
                        </div>
                        <div id="address_err" class="text-danger"></div>
                      </div>
                    </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button id="btn-cap-nhat-admin" type="submit" class="btn btn-danger">Cập nhật</button>
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
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script>
    $(document).ready(function(){
        const readURL = (input) => {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            $('#display-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
          }
        };
        $("#fileInput").on("change",function(){
          $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
          readURL(this); 
        });
        // kích hoạt datepicker của jquery ui
        $( "#ngay_sinh_admin" ).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd-mm-yy',
          onSelect: function(dateText,inst) {
            console.log(dateText.split("-"));
            dateText = dateText.split("-")
            $('input[name=birthday]').attr('data-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
          }
        });
        // cập nhật thông tin admin
        $(document).on('click','#btn-cap-nhat-admin',function(event){
            event.preventDefault();
            let username = $('input[name=username]').val();
            let full_name = $('input[name=full_name]').val();
            let old_pass = $('input[name=old_pass]').val();
            let email = $('input[name=email]').val();
            let phone = $('input[name=phone]').val();
            let birthday = $('input[name=birthday]').attr('data-date');
            let address = $('input[name=address]').val();
            // let img = $('#display-image').attr('data-img');
            let token = "<?php echo_token();?>";
            if(old_pass == ""){
              $.alert({
                title: "Thông báo",
                content: "Vui lòng không để trống mật khẩu xác thực."
              });
              return;
            } 
            var formData = new FormData($('#form-admin')[0]);
            // xu ly du lieu
            formData.append('status','change_info');
            formData.append('username',username);
            formData.append('full_name',full_name);
            formData.append('email',email);
            formData.append('phone',phone);
            formData.append('birthday',birthday);
            formData.append('address',address);
            formData.append('old_pass',old_pass);
            formData.append('token',token);
            let url = window.location.href;
            // xu ly anh
            let file = $('input[name=img_admin_file]')[0].files;
            if(file.length > 0){
              formData.append('img_admin_file',file[0]);
            }
            // xử lý ajax
            $.ajax({
                url:url,
                type:"POST",
                cache:false,
                dataType:"json",
                contentType: false,
                processData: false,
                data:formData,
                success:function(res_json){
                  if(res_json.msg == 'ok'){
                    $.alert({
                      title: "Thông báo",
                      content: res_json.success,
                      buttons: {
                        "Ok":function(){
                          location.reload();
                        }
                      }
                    });
                  } else {
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
        });
        
    });
</script>
<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        $session_id = $_SESSION["id"];
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $full_name = isset($_REQUEST["full_name"]) ? $_REQUEST["full_name"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $birthday = isset($_REQUEST["birthday"]) ? $_REQUEST["birthday"] : null;
        $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
        $old_pass = isset($_REQUEST["old_pass"]) ? $_REQUEST["old_pass"] : null;
        if($status == "change_info") {
            $success = "Bạn đã cập nhật thông tin cá nhân thành công";
            $error = "Đã có lỗi xảy ra, vui lòng tải lại trang.";
            ajax_db_update_by_id('user',['full_name'=>$full_name,'email'=>$email,'birthday'=>$birthday,'phone'=>$phone,'address'=>$address],[$session_id],['success' => $success],['error' => $error]);
            $_SESSION["img_name"] = $path;
        }
    }
?>