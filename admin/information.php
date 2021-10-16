<?php
    include_once("../lib/database_v2.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
?>
<!--html & css section start-->
<?php
    $session_id = $_SESSION["id"];
    $sql_get_info = "select * from user where id = ?";
    $admin_info = fetch_row($sql_get_info,[$session_id]);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Hồ sơ</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Thông tin admin</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div style="display:block;" class="tab-pane" id="settings">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" id="form-admin" method="post" class="form-horizontal" enctype='multipart/form-data'>
                      <div class="form-group row">
                        <label for="full_name" class="col-sm-2 col-form-label">Họ tên</label>
                        <div class="col-sm-10">
                          <input name="full_name" type="text" class="form-control" value="<?=$admin_info["full_name"];?>"">
                        </div>
                        <!-- loi ho ten -->
                        <div id="name_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input name="email" type="email" class="form-control" value="<?=$admin_info["email"];?>">
                        </div>
                        <!-- loi email -->
                        <div id="email_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Tên đăng nhập</label>
                        <div class="col-sm-10">
                          <input name="username" type="text" class="form-control" value="<?=$admin_info["username"];?>">
                        </div>
                        <!-- loi ho ten -->
                        <div id="name_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="pass" class="col-sm-2 col-form-label">Mật khẩu xác thực</label>
                        <div class="col-sm-10">
                          <input name="old_pass" type="password" class="form-control" required>
                        </div>
                        <!-- loi mat khau -->
                        <div id="pass_err" class="text-danger"></div>
                      </div>

                      <div class="form-group row">
                        <label for="birth" class="col-sm-2 col-form-label">Ngày sinh</label>
                        <div class="col-sm-10">
                          <input name="birthday" type="text" class="form-control" id="ngay_sinh_admin" value="<?=Date("d-m-y",strtotime($admin_info["birthday"]));?>">
                        </div>
                        <!-- loi ngay sinh -->
                        <div id="birth_err" class="text-danger"></div>
                      </div>


                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Ngày tạo tài khoản</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value=<?=$admin_info["created_at"];?> readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Số điện thoại</label>
                        <div class="col-sm-10">
                          <input name="phone" type="text" class="form-control" value="<?=$admin_info["phone"];?>" >
                        </div>
                        <!-- loi so dien thoai -->
                        <div id="phone_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Địa chỉ</label>
                        <div class="col-sm-10">
                          <input name="address" type="text" class="form-control" value="<?=$admin_info["address"];?>">
                        </div>
                        <!-- loi dia chi -->
                        <div id="address_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="exampleInputFile" class="col-sm-2 col-form-label">Ảnh đại diện</label>
                        <div class="col-sm-10">
                            <div class="custom-file">
                                <input id="fileInput" name="img_admin_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Upload ảnh đại diện</label>
                            </div>
                        </div>
                        <?php
                          if(trim($admin_info["img_name"]) == "") {
                        ?>
                            <div class="img-fluid" id="where-replace">
                                <span></span>
                            </div>
                            <img width="200" height="200" src="upload/image.png" class='img-fluid' id='display-image'/>
                        <?php
                          } else {
                        ?>
                            <img width="200" height="200" src="upload/user/present/<?=$admin_info["img_name"];?>" data-img='<?=$admin_info["img_name"]?>' class='img-fluid' id='display-image'/>
                        <?php
                          }
                        ?>
                        <!-- loi hinh anh -->
                        <div id="image_err" class="text-danger"></div>
                        <input type="hidden" name="token" value="<?php echo_token();?>">
                    </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button id="btn-cap-nhat-admin" type="submit" class="btn btn-danger">Cập nhật</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
            dateFormat: 'dd-mm-y'
        });
        // cập nhật thông tin admin
        $(document).on('click','#btn-cap-nhat-admin',function(event){
            event.preventDefault();
            let username = $('input[name=username]').val();
            let full_name = $('input[name=full_name]').val();
            let old_pass = $('input[name=old_pass]').val();
            let email = $('input[name=email]').val();
            let phone = $('input[name=phone]').val();
            let birthday = $('input[name=birthday]').val();
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
            formData.append('username',username);
            formData.append('full_name',full_name);
            formData.append('email',email);
            formData.append('phone',phone);
            formData.append('birthday',birthday);
            formData.append('address',address);
            formData.append('old_pass',old_pass);
            formData.append('token',token);
            /*console.log(name);
            console.log(email);
            console.log(phone);
            console.log(birth);
            console.log(address);
            console.log(old_pass);*/
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
							content: res_json.success
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
      $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : null;
      $full_name = isset($_REQUEST["full_name"]) ? $_REQUEST["full_name"] : null;
      $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
      $birthday = isset($_REQUEST["birthday"]) ? $_REQUEST["birthday"] : null;
      $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
      $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
      $old_pass = isset($_REQUEST["old_pass"]) ? $_REQUEST["old_pass"] : null;
      $sql_get_password_hass = "select password,count(*) as 'countt' from user where id = ? limit 1";
      $row = fetch_row($sql_get_password_hass,[$session_id]);
      // Nếu truy vấn thành công
      if($row['countt'] == 1) {
          // Xác thực mật khẩu admin
          if(password_verify($old_pass,$row['password'])){
              $success = "Bạn đã cập nhật thông tin cá nhân thành công";
              $error = "Đã có lỗi xảy ra, vui lòng tải lại trang.";
              $img_admin = "";
              file_upload(['file' => 'img_admin_file'],'user','img_name',"upload/user/present/",$session_id,$img_admin,);
              if($img_admin != "") {
                  $_SESSION["img_name"] = $img_admin;
                  ajax_db_update_by_id('user',['full_name'=>$full_name,'email'=>$email,'birthday'=>$birthday,'username'=>$username,'phone'=>$phone,'address'=>$address,'img_name'=>$img_admin],[$session_id],['success' => $success],['error' => $error]);
              } else {
                  ajax_db_update_by_id('user',['full_name'=>$full_name,'email'=>$email,'birthday'=>$birthday,'username'=>$username,'phone'=>$phone,'address'=>$address],[$session_id],['success' => $success],['error' => $error]);
              }
              
          } else {
              // Báo lỗi admin nhập sai mật khẩu xác thực
              $error_pass = "Mật khẩu xác thực bạn nhập không chính xác.";
              echo_json(["msg" => "not_ok","error" => $error_pass]);
          }
      }
    }
?>