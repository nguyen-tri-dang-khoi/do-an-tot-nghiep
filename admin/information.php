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
    $sql_get_info = "select * from user where id = ?";
    $admin_info = fetch_row($sql_get_info,[$session_id]);
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
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Thông tin admin</a></li>
                </ul>
              </div>
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
                        <div class="col-sm-10 d-flex">
                          <input name="email" type="email" class="form-control" value="<?=$admin_info["email"];?>">
                          <?= (empty($admin_info['email_verify_at'])) ? '<button type="button" onclick="openPromptAuthEmail()" class="dt-button w-150">Xác thực email</button>' : "<button type='button' class='btn btn-primary w-150'>Đã xác thực</button>";?>
                        </div>
                        <!-- loi email -->
                        <div id="email_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="pass" class="col-sm-2 col-form-label">Mật khẩu xác thực</label>
                        <div class="col-sm-10">
                          <input name="old_pass" type="password" class="form-control" required>
                        </div>
                        <div id="pass_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="birth" class="col-sm-2 col-form-label">Ngày sinh</label>
                        <div class="col-sm-10">
                          <input name="birthday" type="text" data-date="<?=$admin_info["birthday"]?>" class="form-control" id="ngay_sinh_admin" value="<?=Date("d-m-Y",strtotime($admin_info["birthday"]));?>">
                        </div>
                        <div id="birth_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Ngày tạo tài khoản</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value=<?=Date('d-m-Y',strtotime($admin_info["created_at"]));?> readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Số điện thoại</label>
                        <div class="col-sm-10 d-flex">
                          <input name="phone" type="text" class="form-control" value="<?=$admin_info["phone"];?>" >
                          <?= (empty($admin_info["phone_verify_at"])) ? '<button onclick="openPromptAuthPhone()" type="button" class="dt-button w-150">Xác thực sđt</button>' : "<button type='button' class='btn btn-primary w-150'>Đã xác thực</button>"; ?>
                        </div>
                        <div id="phone_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Địa chỉ</label>
                        <div class="col-sm-10">
                          <input name="address" type="text" class="form-control" value="<?=$admin_info["address"];?>">
                        </div>
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
                        <img width="200" height="200" src="<?=$admin_info["img_name"] ? $admin_info["img_name"] : "upload/image.png";?>" data-img='<?=$admin_info["img_name"]?>' class='img-fluid' id='display-image'/>
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
<script>
  function openPromptAuthEmail(){
    let token = "<?php echo_token();?>";
    $.ajax({
      url:window.location.href,
      type: "POST",
      data: {
        token:token,
        status:"create_token_email",
      },success:function(data){
        console.log(data);
        data = JSON.parse(data);
        if(data.msg == "ok") {
          $.alert({
            title:"Thông báo",
            content:"Hệ thống đã gửi link xác thực email,bạn kiểm tra email của mình và click vào đường link đó",
          });
        }
        console.log(data.link);
      }
    })
  }
  function openPromptAuthPhone(){
    let token = "<?php echo_token();?>";
    $.ajax({
      url: window.location.href,
      type: "POST",
      data: {
        status: "create_otp_phone",
        token: token,
      },success:function(data){
        console.log(data);
        data = JSON.parse(data);
        
        if(data.msg == "ok") {
          console.log(data.otp);
          let html_input_form = `
          <p>Hệ thống đã gửi mã otp vào số điện thoại của bạn, vui lòng nhập mã otp xác thực</p>
          <div class="" style="display:flex;flex-direction:column">
              <label>Nhập mã xác thực:</label>
              <input name="otp_user_input" type="number" class="form-control" value="">
          </div>
          `;
          $.confirm({
            title: "Xác thực số điện thoại",
            content: html_input_form,
            buttons: {
              formSubmit: {
                text: "Gửi",
                btnClass: 'btn-blue',
                action: function(){
                  let otp_user_input = $("input[name='otp_user_input']").val();
                  $.ajax({
                    url:window.location.href,
                    type:"POST",
                    data: {
                      status: "auth_otp_phone",
                      otp_user_input: otp_user_input,
                      token: "<?php echo_token();?>",
                    },success:function(data) {
                      console.log(data);
                      data = JSON.parse(data);
                      if(data.msg == "ok") {
                        $.alert({
                          title:"Thông báo",
                          content:"Bạn đã xác thực số điện thoại thành công",
                          buttons: {
                            "Ok": function(){
                              location.reload();
                            }
                          }
                        })
                      } else {
                        $.alert({
                          title:"Thông báo",
                          content:"Mã otp bạn nhập không chính xác, vui lòng kiểm tra lại",
                        })
                      }
                    }
                  })
                }
              },
              "Huỷ": function(){

              }
            }
          })
        } else if(data.msg == "not_ok") {
          console.log("bbbbb");
        }
      }

    })
    
  }
</script>
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
      if($status == "create_otp_phone") {
        $otp = rand(111111,999999);
        $sql = "Update user set phone_verify_otp = '$otp' where id = '$session_id'";
        $pdo = sql_query($sql);
        if($pdo->rowCount() > 0) {
          $sql_get_phone = "Select phone from user where id='$session_id'";
          $pdo = sql_query($sql_get_phone);
          $result = fetch($pdo);
          /*
          $APIKey="43009D2FC640A4444734FEE529AC01";
          $SecretKey="E97CE9133C9DB6F9D0E4C4E0DB60AB";
          $YourPhone=$result["phone"];
          $Content="Verify is " . $otp;
          $SendContent=urlencode($Content);
          $url="http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_post_json/";
          $curl = curl_init($url);
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $headers = [
            "Content-Type: application/json",
          ];
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          $data = [
            "Phone"=>$YourPhone,
            "Content"=>$Content,
            "ApiKey"=>$APIKey,
            "SecretKey"=>$SecretKey,
            "SmsType"=>8
          ];
          $data_jsn = json_encode($data);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data_jsn);
          //print_r($data_jsn);
          $resp = curl_exec($curl);
          $obj = json_decode($resp,true);
          if($obj['CodeResult']==100)
          {
            echo_json(["msg" => "ok","otp" => $otp]);
          } else {
            echo_json(["msg" => "not_ok","error" => $obj['ErrorMessage']]);
          }
          */
          $phone = $result["phone"];
          if($phone){
            echo_json(["msg" => "ok","otp" => $otp]);
          } else {
            echo_json(["msg" => "not_ok","error" => "not phone"]);
          }
        }
      } else if($status == "auth_otp_phone") {
        $otp_user_input = isset($_REQUEST["otp_user_input"]) ? $_REQUEST["otp_user_input"] : null;
        if($otp_user_input) {
          $sql_check_otp = "select count(*) as 'countt' from user where id='$session_id' and phone_verify_otp = '$otp_user_input' limit 1";
          $pdo = sql_query($sql_check_otp);
          $result = fetch($pdo);
          if($result['countt'] == 1) {
            $date_now = date("Y-m-d H:i:s");
            $sql_update_phone_verify_at = "Update user set phone_verify_at = '$date_now',phone_verify_otp = NULL where id = '$session_id'";
            $pdo = sql_query($sql_update_phone_verify_at);
            echo_json(["msg" => "ok","message" => "Xác thực thành công"]);
          } else {
            $sql_update_phone_verify_at = "Update user set phone_verify_otp = NULL,phone_verify_at = NULL where id = '$session_id'";
            sql_query($sql_update_phone_verify_at);
            echo_json(["msg" => "not_ok","message" => "Xác thực thất bại"]);
          }
        }
      } else if($status == "create_token_email") {
        $time = Date("d-m-Y h:i:s",time());
        $hidden_key = "&!239yhf98@";
        $rand = rand(0,999999);
        $token_email = md5($email.$time.$rand.$hidden_key);
        $sql_update_token = "Update user set email_verify_token = '$token_email' where id = '$session_id'";
        $pdo_email_token = sql_query($sql_update_token);
        if($pdo_email_token->rowCount() > 0) {
          $link = "http://localhost/project/admin/email_verify.php?token=$token_email";
          // send email
          //
          echo_json(["msg" => "ok","link" => $link]);
        } else {
          echo_json(["msg" => "not_ok","link" => ""]);
        }
      } else if($status == "auth_token_email") {

      } else if($status == "change_info") {
        $sql_get_password_hass = "select password,count(*) as 'countt' from user where id = ? limit 1";
        $row = fetch_row($sql_get_password_hass,[$session_id]);
        // Nếu truy vấn thành công
        if($row['countt'] == 1) {
          // Xác thực mật khẩu admin
          if(password_verify($old_pass,$row['password'])){
            $success = "Bạn đã cập nhật thông tin cá nhân thành công";
            $error = "Đã có lỗi xảy ra, vui lòng tải lại trang.";
            $img_admin = "";
            $dir = "upload/user/";
            if(!file_exists($dir)) {
                mkdir($dir, 0777); 
                chmod($dir, 0777);
            }
            $dir = "upload/user/" . $session_id;
            if(!file_exists($dir)) {
                mkdir($dir, 0777); 
                chmod($dir, 0777);
            }
            if($_FILES['img_admin_file']['name'] != "") {
              $ext = strtolower(pathinfo($_FILES['img_admin_file']['name'],PATHINFO_EXTENSION));
              $file_name = md5(rand(1,999999999)). $session_id . "." . $ext;
              $file_name = str_replace("_","",$file_name);
              $path = $dir . "/" . $file_name ;
              $sql_get_old_file = "select img_name from user where id = '$session_id'";
              $old_file=fetch_row($sql_get_old_file)['img_name'];
              if(file_exists($old_file)) {
                unlink($old_file);
                chmod($dir, 0777);
              }
              move_uploaded_file($_FILES['img_admin_file']['tmp_name'],$path);
              $sql_update = "update user set img_name='$path' where id = '$session_id'";
              db_query($sql_update);
            }
            $sql_get_phone_email = "select phone,email from user where id = '$session_id' limit 1";
            $result = fetch(sql_query($sql_get_phone_email));
            // Nếu số điện thoại hoặc email thay đổi thì sẽ kích hoạt button yêu cầu xác thực
            if($result["email"] != $email) {
              $sql_update_verify_email = "Update user set email_verify_token = NULL,email_verify_at = NULL where id = '$session_id'";
              sql_query($sql_update_verify_email);
            }
            if($result["phone"] != $phone) {
              $sql_update_verify_phone = "Update user set phone_verify_otp = NULL,phone_verify_at = NULL where id = '$session_id'";
              sql_query($sql_update_verify_phone);
            }
            ajax_db_update_by_id('user',['full_name'=>$full_name,'email'=>$email,'birthday'=>$birthday,'phone'=>$phone,'address'=>$address],[$session_id],['success' => $success],['error' => $error]);
            $_SESSION["img_name"] = $path;
          } else {
            // Báo lỗi admin nhập sai mật khẩu xác thực
            $error_pass = "Mật khẩu xác thực bạn nhập không chính xác.";
            echo_json(["msg" => "not_ok","error" => $error_pass]);
          }
        }
      }
     
      
    }
?>