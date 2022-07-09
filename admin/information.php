<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
?>
<!--html & css section start-->
<?php
    $session_id = $_SESSION["id"];
    $sql_get_info = "select * from user where id = '$session_id' and (type = 'admin' or type = 'officer')" ;
    $result = fetch(sql_query($sql_get_info));
?>
<div class="container-wrapper" style="margin-left:250px;">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row d-flex j-between">
          <div class="col-6">
            <h1>Thông tin tài khoản</h1>
          </div>
          <div class="">
            <button onclick="showModalChangeInfo('<?=$session_id?>')" class="dt-button button-blue">
              Sửa thông tin tài khoản
            </button>
            <button onclick="showModalChangePass('<?=$session_id?>')" class="dt-button button-blue ml-10">
            Đổi mật khẩu
            </button>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row kh-padding">
                <div class="col-12">
                    <table class="table table-bordered kh-bg-th">
                        <tr>
                            <th class="w-300">Họ tên: </th>
                            <td><?=$result['full_name'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Ngày sinh:</th>
                            <td><?=Date("d-m-Y",strtotime($result['birthday']));?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Số điện thoại:</th>
                            <td><?=$result['phone'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Email liên hệ:</th>
                            <td><?=$result['email'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Địa chỉ:</th>
                            <td><?=$result['address'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Ảnh đại diên:</th>
                            <td><img style="width:200px;height:200px;" src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg";?>" alt=""></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin tài khoản</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="change_info" method="post">
                    
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-xl2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi mật khẩu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="change_pass" method="post">
                    
                </form>
            </div>
        </div>
    </div>
</div>
<!--html & css section end-->
<?php
  include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script>
  function validatePass(){
    $('p.text-danger').text('');
    let old_pass = $('input[name=old_pass]').val();
    let new_pass = $('input[name=new_pass]').val();
    let confirm_new_pass = $('input[name=confirm_new_pass]').val();
    if(old_pass == "") {
      $('#old_pass_err').text('Không để trống mật khẩu xác thực');
      test = false;
    } 

    if(new_pass == ""){
      $('#new_pass_err').text('Không để trống mật khẩu mới');
      test = false;
    }

    if(confirm_new_pass == "") {
      $('#confirm_new_pass_err').text('Không để trống xác nhận mật khẩu mới');
      test = false;
    } else if(new_pass != confirm_new_pass) {
      $.alert({
        title:"Thông báo",
        content:"Mật khẩu mới không khớp với xác nhận mật khẩu mới",
      })
      test = false;
    }
    return test;
  }
  function validateInfo(){
    $('p.text-danger').text('');
    let test = true;
    let full_name = $('input[name=full_name]').val();
    let phone_reg = /^\d{10}$/;
    let email_reg = /^[A-Za-z0-9+_.-]+@(.+)/;
    let email = $('input[name=email]').val();
    let phone = $('input[name=phone]').val();
    let birthday = $('input[name=birthday]').val();
    let address = $('input[name=address]').val();
    if(full_name == "") {
      $('#full_name_err').text('Không để trống tên đầy đủ');
      test = false;
    } else if(full_name.length > 200) {
      $('#full_name_err').text('Tên đầy đủ phải có độ dài nhỏ hơn hoặc bằng 200 ký tự');
      test = false;
    }
    //
    if(email == "") {
      $('#email_err').text('Không để trống email');
      test = false;
    } else if(!email.match(email_reg)) {
      $('#email_err').text('Định dạng email không hợp lệ');
      test = false;
    }
    //
    if(phone == "") {
      $('#phone_err').text('Không để trống số điện thoại');
      test = false;
    } else if(!phone.match(phone_reg)) {
      $('#phone_err').text('Định dạng số điện thoại không hợp lệ');
      test = false;
    }
    //
    if(birthday == "") {
      $('#birthday_err').text('Không để trống ngày sinh');
      test = false;
    } else {
      birthday = birthday.split('-');
      birthday = birthday[2] + "-" + birthday[1] + "-" + birthday[0];
      console.log(Date.parse(new Date().toISOString().slice(0,10)));
      console.log(Date.parse(birthday));
      if(Date.parse(new Date().toISOString().slice(0,10)) - Date.parse(birthday) < 568024668000) {
        $('#birthday_err').text("Bạn phải có độ tuổi từ 18 tuổi trở lên");
        test = false;
      }
    }
    //
    if(address == "") {
      $('#address_err').text('Không để trống địa chỉ');
      test = false;
    } else if(address.length > 1800) {
      $('#address_err').text('Địa chỉ phải có độ dài nhỏ hơn hoặc bằng 200 ký tự');
      test = false;
    }
    return test;
  }
  function showModalChangeInfo(id){
    $('#change_info').load(`ajax_information.php?id=${id}&status=changeInfo`,() => {
      $('#modal-xl').modal('show');
      showPicker();
    });
  }
  function showModalChangePass(id){
    $('#change_pass').load(`ajax_information.php?id=${id}&status=ChangePass`,() => {
      $('#modal-xl2').modal('show');
    });
  }
  function processChangeInfo(){
    event.preventDefault();
    if(validateInfo()) {
      let full_name = $('input[name=full_name]').val();
      let email = $('input[name=email]').val();
      let phone = $('input[name=phone]').val();
      let birthday = $('input[name=birthday]').val();
      let address = $('input[name=address]').val();
      var formData = new FormData($('#change_info')[0]);
      // xu ly du lieu
      formData.append('status','change_info');
      formData.append('full_name',full_name);
      formData.append('email',email);
      formData.append('phone',phone);
      formData.append('birthday',birthday);
      formData.append('address',address);
      let file = $('#fileInput')[0].files;
      if(file.length > 0){
        formData.append('img_admin_file',file[0]);
      }
      $.ajax({
          url:window.location.href,
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
    }
  }
  function processChangePass(){
    event.preventDefault();
    if(validatePass()) {
      let old_pass = $('input[name=old_pass]').val();
      let new_pass = $('input[name=new_pass]').val();
      let confirm_new_pass = $('input[name=confirm_new_pass]').val();
      let formData = new FormData();
      formData.append('status','change_pass');
      formData.append('old_pass',old_pass);
      formData.append('new_pass',new_pass);
      $.ajax({
          url:window.location.href,
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
    }
    
  }
  
  function showPicker(){
    console.log("aaa");
    $('.kh-datepicker').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy',
    }); 
  }
  function openPromptAuthEmail(){
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
  function readURL(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
        $('#display-image').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
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
      $birthday = isset($_REQUEST["birthday"]) ? Date("Y-m-d",strtotime($_REQUEST["birthday"])) : nuldl;
      $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
      $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
      $old_pass = isset($_REQUEST["old_pass"]) ? $_REQUEST["old_pass"] : null;
      $img_admin_file = isset($_REQUEST["img_admin_file"]) ? $_REQUEST["img_admin_file"] : null;
      /*if($status == "create_token_email") {
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
      } else*/ 
      if($status == "change_info") {
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
          $old_file=fetch(sql_query($sql_get_old_file))['img_name'];
          if(file_exists($old_file)) {
            unlink($old_file);
            chmod($dir, 0777);
          }
          move_uploaded_file($_FILES['img_admin_file']['tmp_name'],$path);
          $sql_update = "update user set img_name='$path' where id = ?";
          sql_query($sql_update,[$session_id]);
        }
        $sql_upt = "Update user set full_name = ?,email = ?,birthday = ?,phone = ?,address = ? where id = ?";
        sql_query($sql_upt,[$full_name,$email,$birthday,$phone,$address,$session_id]);
        echo_json(["msg" => "ok","success" => $success]);
        $_SESSION["img_name"] = $path;
      } else if($status == "change_pass") {
        $old_pass = isset($_REQUEST["old_pass"]) ? $_REQUEST["old_pass"] : null;
        $new_pass = isset($_REQUEST["new_pass"]) ? $_REQUEST["new_pass"] : null;
        if($old_pass && $new_pass) {
            $sql_get_pwd = "select password from user where id = '$session_id' and (type = 'admin' or type = 'officer') limit 1";
            $row = fetch(sql_query($sql_get_pwd));
            if(password_verify($old_pass,$row['password'])) {
                $new_pass = password_hash($new_pass,PASSWORD_DEFAULT);
                $sql_set_pwd = "Update user set password = '$new_pass' where id = '$session_id'";
                sql_query($sql_set_pwd);
                echo_json(["msg" => "ok","success" => "Bạn đã đổi mật khẩu thành công"]);
            } else {
                echo_json(["msg" => "not_ok","error" => "Mật khẩu xác thực không chính xác"]);
            }
        }
        echo_json(["msg" => "ok"]);
        
      }
    }
?>