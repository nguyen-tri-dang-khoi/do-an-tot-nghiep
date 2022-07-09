<?php
    include_once("../lib/database.php");
    logout_session_timeout();
    check_access_token();
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
?>
<!--html & css section start-->
<?php
    $sql_get_info = "select * from company_info limit 1";
    $result = fetch(sql_query($sql_get_info));
    $sql_check_admin = "select type from user where id = ?";
    $row = fetch(sql_query($sql_check_admin,[$_SESSION['id']]));
?>
<div class="container-wrapper" style="margin-left:250px;">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 d-flex j-between">
          <div class="col-sm-6">
            <h1>Thông tin công ty</h1>
          </div>
          <?php
            //log_a($row);
            if($row['type'] == 'admin') {
          ?>
          <div class="">
            <button onclick="showModalChangeCompanyInfo()" class="dt-button button-blue">
              Sửa thông tin công ty
            </button>
          </div>
          <?php } ?>
        </div>
      </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row kh-padding">
                <div class="col-12">
                    <table class="table table-bordered kh-bg-th">
                        <tr>
                            <th class="w-300">Tên cêng ty</th>
                            <td><?=$result['company_name'];?></td>
                        </tr>
                        <tr>
                            <th class="w-00">Mã số thuế: </th>
                            <td><?=$result['company_tax_code'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Số điện thoại:</th>
                            <td><?=$result['company_phone'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Email liên hệ:</th>
                            <td><?=$result['company_email'];?></td>
                        </tr>
                        <tr>
                            <th class="w-200">Địa chỉ:</th>
                            <td><?=$result['company_address'];?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--<section class="content">
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
                        <div id="name_err" class="text-danger"></div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10 d-flex">
                          <input name="email" type="email" class="form-control" value="<?=$cp_info["company_email"];?>">
                        </div>
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
    </section>-->
</div>
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin công ty</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="change_company_info" method="post">
                    
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
   function showModalChangeCompanyInfo(){
    $('#change_company_info').load("ajax_company_info.php?status=changeCompanyInfo",() => {
      $('#modal-xl').modal('show');
    });
   }
   function processChangeCompanyInfo(){
      event.preventDefault();
      let formData = new FormData();
      let test = true;
      $('p.text-danger').text('');
      let phone_reg = /^\d{10}$/;
      let email_reg = /^[A-Za-z0-9+_.-]+@(.+)/;
      let company_tax_code = $('input[name="company_tax_code"]').val();
      let company_name = $('input[name="company_name"]').val();
      let company_phone = $('input[name="company_phone"]').val();
      let company_email = $('input[name="company_email"]').val();
      let company_address = $('input[name="company_address"]').val();
      if(company_name == ""){
        $('#company_name_err').text('Vui lòng không để trống tên công ty');
        test = false;
      } else if(company_name.length > 200){
        $('#company_name_err').text('Tên công ty không được phép vượt quá 200 ký tự');
        test = false;
      }
      //
      if(company_phone == "") {
        $('#company_phone_err').text('Vui lòng không để trống số điện thoại công ty');
        test = false;
      } else if(!company_phone.match(phone_reg)) {
        $('#company_phone_err').text('Định dạng số điện thoại công ty không hợp lệ');
        test = false;
      }
      //
      if(company_email == "") {
        $('#company_email_err').text('Vui lòng không để trống email công ty');
        test = false;
      } else if(!company_email.match(email_reg)) {
        $('#company_email_err').text('Định dạng email công ty không hợp lệ');
        test = false;
      }
      //
      if(company_address == "") {
        $('#company_address_err').text('Vui lòng không để trống địa chỉ công ty');
        test = false;
      } else if(company_address.length > 1800) {
        $('#company_address_err').text('Địa chỉ công ty không được phép vượt quá 1800 ký tự');
        test = false;
      }
      if(company_tax_code == "") {
        $('#company_tax_code_err').text('Vui lòng không để trống mã số thuế công ty');
        test = false;
      }

      if(test) {
        formData.append('status','change_company_info');
        formData.append('company_name',company_name);
        formData.append('company_phone',company_phone);
        formData.append('company_email',company_email);
        formData.append('company_tax_code',company_tax_code);
        formData.append('company_address',company_address);
        $.ajax({
          url: window.location.href,
          type: "POST",
          data: formData,
          cache: false,
          processData:false,
          contentType:false,
          success:function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
              $.alert({
                title:"Thông báo",
                content:"Bạn đã cập nhật thông tin công ty thành công",
                buttons:{
                  "Ok":function(){
                    location.reload();
                  }
                }
              })
            }
          }
        })
      }
  }
</script>
<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        $sql_check_admin = "select type from user where id = ?";
        $row = fetch(sql_query($sql_check_admin,[$_SESSION['id']]));
        if($row['type'] == 'admin') {
          $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
          $company_name = isset($_REQUEST["company_name"]) ? $_REQUEST["company_name"] : null;
          $company_email = isset($_REQUEST["company_email"]) ? $_REQUEST["company_email"] : null;
          $company_phone = isset($_REQUEST["company_phone"]) ? $_REQUEST["company_phone"] : null;
          $company_address = isset($_REQUEST["company_address"]) ? $_REQUEST["company_address"] : null;
          $company_tax_code = isset($_REQUEST["company_tax_code"]) ? $_REQUEST["company_tax_code"] : null;
          $company_id = 1;
          if($status == "change_company_info") {
            $success = "Bạn đã cập nhật thông tin công ty thành công";
            $error = "Đã có lỗi xảy ra, vui lòng tải lại trang.";
            $sql = "Update company_info set company_name = ?,company_email = ?,company_phone = ?,company_address = ?,company_tax_code = ? where id = ?";
            sql_query($sql,[$company_name,$company_email,$company_phone,$company_address,$company_tax_code,$company_id]);
            echo_json(["msg" => "ok"]);
          }
        }
        
    }
?>