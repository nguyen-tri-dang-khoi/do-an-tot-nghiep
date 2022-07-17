<?php
    include_once("../lib/database.php");
    include_once("include/login_fail_redirect.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $shipper_id = isset($_SESSION['shipper_id']) ? $_SESSION['shipper_id'] : null;
        $sql_shipper_info = "select * from user where type = 'shipper' and id = '$shipper_id' limit 1";
        $result = fetch(sql_query($sql_shipper_info));
?>
<!--html & css section start-->
<style>
    .kh-bg-th th{
        background-color: #d9585c;
        color:#fff;
    }
    .kh-bg-th td {
        border-right: 3px solid #d9585c;
        color: #d9585c;
        font-weight:bold;
    }
    
</style>
<div class="content-wrapper" style="margin-left:290px;">
    <div class="content-header">
        <div class="container-fluid">   
            <div class="row mb-2 kh-padding d-flex j-between">
                <h1 class="col-md-6 col-sm-12" style="font-weight:bold;color:#d9585c;" class="m-0">Trang thông tin cá nhân</h1>
                <div class="col-md-6 col-sm-12 row mt-15" style="justify-content:end;">
                    <div style="">
                        <button onclick="showModalShipper('<?=$_SESSION['shipper_id'];?>')" class="btn" style="background-color:#d9585c;color:#fff;font-weight:bold">Sửa thông tin</button>
                        <button onclick="showModalShipperChangePass('<?=$_SESSION['shipper_id'];?>')" class="btn" style="background-color:#d9585c;color:#fff;font-weight:bold">Đổi mật khẩu</button>
                    </div>
                </div>
            </div>
            <hr style="">
        </div> 
    </div>
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
                            <td><img style="width:200px;height:200px;" src="<?=$result['img_name'] ? "../admin/".$result['img_name'] : "../admin/upload/noimage.jpg";?>" alt=""></td>
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
                <h4 class="modal-title" style="color:#d9585c;font-weight:bold;">Thông tin shipper</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage_shipper" method="post">
                    
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
    
</script>
<script>
    function showModalShipper(shipper_id){
        $('#manage_shipper').load("ajax_shipper_info.php?status=Update&shipper_id=" + shipper_id,() => {
            $('#modal-xl').modal({backdrop: 'static',"keyboard":false});
            $(".kh-datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy',
            })
        })
    }
    function showModalShipperChangePass(shipper_id) {
        $('#manage_shipper').load("ajax_shipper_info.php?status=ChangePass&shipper_id=" + shipper_id,() => {
            $('#modal-xl').modal({backdrop: 'static',"keyboard":false});
        })
    }
</script>

<script>
    function validate(){
        let test = true;
        let full_name = $('#full_name').val();
        let email = $('#email').val();
        let cmnd = $('#cmnd').val();
        let phone = $('#phone').val();
        let address = $('#address').val();
        let birthday = $('#birthday').val();
        if(full_name == "") {
            $('#full_name').focus();
            $.alert({
                title: "",
                content: "Họ tên nhân viên không được để trống.",
            });
            test = false;
        } else if(email == "") {
            $('#email').focus();
            $.alert({
                title: "Thông báo",
                content: "Email nhân viên không được để trống.",
            });
            test = false;
        } else if(phone == "") {
            $('#phone').focus();
            $.alert({
                title: "Thông báo",
                content: "Số điện thoại nhân viên không được để trống."
            });
            test = false;
        } else if(birthday == "") {
            $('#birthday').focus();
            $.alert({
                title: "Thông báo",
                content: "Ngày sinh của nhân viên không được để trống."
            });
            test = false;
        } else if(address == "") {
            $('#address').focus();
            $.alert({
                title: "Thông báo",
                content: "Địa chỉ của nhân viên không được để trống."
            });
            test = false;
        }
        return test;
    }
    // show image
    function validate_pass(){
        let test = true;
        let old_pass = $('#old_pass').val();
        let new_pass = $('#new_pass').val();
        let confirm_new_pass = $('#confirm_new_pass').val();
        if(old_pass == "") {
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu xác thực không được để trống",
            });
            test = false;
        } else if(new_pass == "") {
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu mới không được để trống.",
            });
            test = false;
        } else if(confirm_new_pass == "") {
            $.alert({
                title: "Thông báo",
                content: "Xác nhận mật khẩu mới không được để trống."
            });
            test = false;
        } else if(confirm_new_pass != new_pass) {
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu mới không khớp với xác nhận mật khẩu mơi"
            });
            test = false;
        } 
        return test;
    }
    const readURL = (input) => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#display-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    };
    $(function(){
        $(document).on('click','#btn-update',function(e){
            event.preventDefault();
            if(validate()) {
                let file = $('input[name="img_name"]')[0].files;
                console.log(file);
                let formData = new FormData($('#manage_shipper')[0]);
                formData.append("token","<?php echo_token();?>");
                formData.append("status","Update");
                formData.append("id",$('input[name=id]').val());
                formData.append("full_name",$('#full_name').val());
                formData.append("email",$('#email').val());
                formData.append("phone",$('#phone').val());
                formData.append("cmnd",$('#cmnd').val());
                formData.append("address",$('#address').val());
                let birthday = $('#birthday').val().split('-');
                birthday = birthday[2] + "-" + birthday[1] + "-" + birthday[0];
                formData.append("birthday",birthday);
                if(file.length > 0) {
                    formData.append('img_name',file[0]); 
                }
                if(validate()) {
                    $.ajax({
                    url:window.location.href,
                    type: "POST",
                    cache:false,
                    dataType:"json",
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(data){
                        //data = JSON.parse(data);
                        console.log(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons: {
                                    "Ok": function(){
                                        location.reload();
                                    },
                                }
                            });
                        } else {
                            $.alert({
                                title: "Thông báo",
                                content: data.error
                            });
                        }
                        $('#modal-xl').modal('hide');
                    },
                    error:function(data) {
                        console.log("Error:",data);
                    }
                });
                }
                
            }
        });
        $(document).on('click','#btn-change-pass',function(e){
            event.preventDefault();
            let old_pass = $('#old_pass').val();
            let new_pass = $('#new_pass').val();
            
            let id = $('input[name=id]').val();
            if(validate_pass()) {
                console.log(new_pass);
                $.ajax({
                    url:"information.php",
                    type:"POST",
                    data:{
                        id:id,
                        status:"ChangePass",
                        old_pass: old_pass,
                        new_pass: new_pass,
                    },success:function(data) {
                        console.log(data);
                        data = JSON.parse(data);
                        if(data.msg == "ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                                buttons:{
                                    "Ok":function(){
                                        $('#modal-xl').modal('hide');
                                    }
                                }
                            });
                        } else if(data.msg == "not_ok") {
                            $.alert({
                                title: "Thông báo",
                                content: data.error,
                            });
                        }
                    },error:function(data) {
                        console.log("Error:" + data);
                    }
                })
            }
            
        });
    })
</script>
<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        // code to be executed post method
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $full_name = isset($_REQUEST["full_name"]) ? $_REQUEST["full_name"] : null;
        $cmnd = isset($_REQUEST["cmnd"]) ? $_REQUEST["cmnd"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
        $birthday = isset($_REQUEST["birthday"]) ? $_REQUEST["birthday"] : null;
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null ;
        if($status == "Update") {
            $success = "Bạn đã sửa dữ liệu thành công";
            $dir = "../admin/upload/user/";
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            $dir = "../admin/upload/user/" . $id;
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            if($_FILES['img_name']['name'] != "") {
               $sql_get_old_file = "select img_name from user where id = '$id'";
               $old_file = fetch_row($sql_get_old_file)['img_name'];
               if(file_exists($old_file)) {
                   unlink($old_file);
                   chmod($dir, 0777);
               }
               $ext = strtolower(pathinfo($_FILES['img_name']['name'],PATHINFO_EXTENSION));
               $file_name = md5(rand(1,999999999)). $id . "." . $ext;
               $file_name = str_replace("_","",$file_name);
               $path = $dir . "/" . $file_name ;
               move_uploaded_file($_FILES['img_name']['tmp_name'],$path);
               $sql_update = "update user set img_name='$path' where id = '$id'";
               db_query($sql_update);
            }
            $sql = "Update user set full_name = '$full_name',email = '$email',phone = '$phone',cmnd = '$cmnd',address = '$address',birthday = '$birthday' where id = '$id'";
            sql_query($sql);
            echo_json(["msg" => "ok","success" => $success]);
        } else if($status == "ChangePass") {
            $old_pass = isset($_REQUEST["old_pass"]) ? $_REQUEST["old_pass"] : null;
            $new_pass = isset($_REQUEST["new_pass"]) ? $_REQUEST["new_pass"] : null;
            if($old_pass && $new_pass && $id) {
                $sql_get_pwd = "select password from user where id = '$id' limit 1";
                $row = fetch(sql_query($sql_get_pwd));
                if(password_verify($old_pass,$row['password'])) {
                    $new_pass = password_hash($new_pass,PASSWORD_DEFAULT);
                    $sql_set_pwd = "Update user set password = '$new_pass' where id = '$id'";
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