<?php
    include_once("../lib/database.php");
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
        $search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $where = "where 1=1 ";
        if($keyword || $keyword == 0 ) 
        {
            if($search_option == "fullname") {
                $where .= "and lower(full_name) like lower('%$keyword%')";
            } else if($search_option == "email") {
                $where .= "and lower(email) like lower('%$keyword%')";
            } else if($search_option == "phone") {
                $where .= "and lower(phone) like lower('%$keyword%')";
            } else if($search_option == "cmnd") {
                $where .= "and lower(cmnd) like lower('%$keyword%')";
            } else if($search_option == "all") {
                $where .= "and lower(full_name) like lower('%$keyword%') or ";
                $where .= "lower(email) like lower('%$keyword%') or ";
                $where .= "lower(phone) like lower('%$keyword%') or ";
                $where .= "lower(cmnd) like lower('%$keyword%') ";
            }
        }
?>
<!--html & css section start-->
<style>
    table.dataTable span.highlight {
        background-color: #17a2b8;
        border-radius: 5px;
        text-align: center;
        color: white;
    }
</style>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<div class="container-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quản lý nhân viên</h3>
                        <div class="card-tools">
                            <button id="btn-add-user" class="btn btn-success">Thêm nhân viên</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form style="margin-bottom: 17px;display:flex;" action="user_manage.php" method="get">
                            <div class="">
                                <select class="form-control" name="search_option">
                                    <option value="">Chọn cột tìm kiếm</option>
                                    <option value="fullname" <?=$search_option == 'fullname' ? 'selected="selected"' : '' ?>>Tên đầy đủ</option>
                                    <option value="address" <?=$search_option == 'address' ? 'selected="selected"' : '' ?>>Địa chỉ</option>
                                    <option value="email" <?=$search_option == 'email' ? 'selected="selected"' : '' ?>>Email</option>
                                    <option value="phone" <?=$search_option == 'phone' ? 'selected="selected"' : '' ?>>Số điện thoại</option>
                                    <option value="cmnd" <?=$search_option == 'cmnd' ? 'selected="selected"' : '' ?>>Số chứng minh nhân dân</option>
                                    <option value="all" <?=$search_option == 'all' ? 'selected="selected"' : '' ?>>Tất cả</option>
                                </select>
                            </div>
                            <!--<div class="ml-10">
                                <input type="text" name="birth" placeholder="Ngày sinh..." class="form-control">
                            </div>-->
                            <div class="ml-10" style="display:flex;">
                                <input type="text" name="keyword" placeholder="Nhập từ khoá..." class="form-control" value="<?=$keyword;?>">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                        <?php
							// set get
							$get = $_GET;
							unset($get['page']);
							$str_get = http_build_query($get);
							// query
                            $arr_paras = [];
                            $where .= " and is_delete = 0 and is_lock = 0";
                            /*$keyword = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : null;
                            if($keyword) {
                                $where .= "";
                            }*/
                            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
                            $limit = $_SESSION['paging'];
                            $start_page = $limit * ($page - 1);
                            $sql_get_total = "select count(*) as 'countt' from user $where";
                            $total = fetch_row($sql_get_total,$arr_paras)['countt'];
                            array_push($arr_paras,$start_page);
                            array_push($arr_paras,$limit);
                            $sql_get_user = "select * from user $where order by id desc limit ?,? ";
                            //print_r($sql_get_user);
                            /*print_r($arr_paras);*/
							$cnt=0;
                            $rows = db_query($sql_get_user,$arr_paras);
                        ?>
                        <!--Table user-->
                        <table id="m-user-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
									<th>Số thứ tự</th>
                                    <th>Tên đầy đủ</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Số chứng minh nhân dân</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày sinh</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="m-user-body">
                                <?php foreach($rows as $row) { ?>
                                    <tr id="user-<?=$row["id"];?>">
										<td><?=$total - ($start_page + $cnt);?></td>
                                        <td><?=$row["full_name"]?></td>
                                        <td><?=$row["email"]?></td>
                                        <td><?=$row["phone"]?></td>
                                        <td><?=$row["cmnd"]?></td>
                                        <td><?=$row["address"]?></td>
                                        <td><?=$row["birthday"] ? Date("d-m-Y",strtotime($row["birthday"])) : "";?></td>
                                        <td><?=$row["username"]?></td>
                                        <td><?=$row["created_at"] ? Date("d-m-Y H:i:s",strtotime($row["created_at"])) : "";?></td>
                                        <td>
                                            <button class="btn-update-user btn btn-primary"
                                            data-id="<?=$row["id"];?>" data-number="<?=$total - ($start_page + $cnt);?>">Sửa</button>
                                            <button class="btn-delete-row btn btn-danger" data-number="<?=$total - ($start_page + $cnt);?>" data-id="<?=$row["id"];?>">Xoá
                                            </button>
                                        </td>
                                    </tr>
                                <?php 
										$cnt++;
									} 
								?>
                            </tbody>
                            <tfoot>
                                <tr>
								    <th>Số thứ tự</th>
                                    <th>Tên đầy đủ</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Số chứng minh nhân dân</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày sinh</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="justify-content:center;" class="row">
                            <ul id="pagination" class="pagination">
                                
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin nhân viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage_user" method="post">
                    
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
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/dataTables.responsive.min.js"></script>
<script src="js/responsive.bootstrap4.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>
<script src="js/buttons.colVis.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.25/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
<script src="//bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
<script>
    var dt_user;
    $(document).ready(function (e) {
        dt_user = $("#m-user-table").DataTable({
            "language": {
                "emptyTable": "Không có dữ liệu",
                "sZeroRecords": 'Không tìm thấy kết quả',
                "infoEmpty": "",
                "infoFiltered":"Lọc dữ liệu từ _MAX_ dòng",
                "search":"Tìm kiếm trong bảng này:",   
                "info":"Hiển thị từ dòng _START_ đến dòng _END_ trên tổng số _TOTAL_ dòng",
            },
            "paging":true,
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "paging":false,
            "order": [[ 0, "desc" ]],
            "searchHighlight": true,
            "buttons": [
          {
            "extend": "copy",
            "text": "Sao chép bảng",
          },{
            "extend": "excel",
          },{
            "extend": "pdf",
          },{
            "extend": "csv",
          },{
            "extend": "print",
            "text": "In bảng",
          },{
            "extend": "colvis",
            "text": "Ẩn / Hiện cột",
          }
        ]
        });
        dt_user.buttons().container().appendTo('#m-user-table_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    $(document).ready(function(){
        // validate
        const validate = () => {
            let test = true;
            let full_name = $('#full_name').val();
            let email = $('#email').val();
            let cmnd = $('#email').val();
            let phone = $('#phone').val();
            let address = $('#address').val();
            let birthday = $('#birthday').val();
            let username = $('#username').val();
            let password = $('#password').val();
            if(full_name == "") {
                $.alert({
                    title: "",
                    content: "Họ tên nhân viên không được để trống."
                });
                test = false;
            } else if(email == "") {
                $.alert({
                    title: "",
                    content: "Email nhân viên không được để trống."
                });
                test = false;
            } else if(phone == "") {
                $.alert({
                    title: "",
                    content: "Số điện thoại nhân viên không được để trống."
                });
                test = false;
            } else if(cmnd == "") {
                $.alert({
                    title: "",
                    content: "Số chứng minh nhân dân của nhân viên không được để trống."
                });
                test = false;
            } else if(address == "") {
                $.alert({
                    title: "",
                    content: "Địa chỉ của nhân viên không được để trống."
                });
                test = false;
            } else if(birthday == "") {
                $.alert({
                    title: "",
                    content: "Ngày sinh của nhân viên không được để trống."
                });
                test = false;
            } else if(username == "") {
                $.alert({
                    title: "",
                    content: "Nickname của nhân viên không được để trống."
                });
                test = false;
            } else if(password == "") {
                $.alert({
                    title: "",
                    content: "Mật khẩu của nhân viên không được để trống."
                });
                test = false;
            }
            return test;
        };
        // show image
        const readURL = (input) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                $('#display-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        };
        // mở modal thêm dữ liệu
        var click_number;
        $(document).on('click','#btn-add-user',(e) => {
            let number;
            $.ajax({
                url: "ajax_get_number.php",
                type: "POST",
                data: {
                    status: "count_user",
                },
                success:function(data){
                    data = JSON.parse(data);
                    if(data.msg == "ok") {
                        number = parseInt(data.count) + 1;
                        console.log(number);
                        $('#manage_user').load("ajax_user.php?number=" + number,() => {
                            $('#modal-xl').modal('show');
                            $("#birthday").datepicker({
                                changeMonth: true,
                                changeYear: true,
                                dateFormat: 'dd-mm-yy',
                                onSelect: function(dateText,inst) {
                                    console.log(dateText.split("-"));
                                    dateText = dateText.split("-");
                                    $('#birthday').attr('data-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                                }
                            });
                            $("#fileInput").on("change",function(){
                                $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
                                readURL(this); 
                            });
                        })
                    }
                },
                error: function(data) {
                    console.log("Error:" + data);
                }
            });
        });
        // mở modal sửa dữ liệu
        $(document).on('click','.btn-update-user',function(e) {  
            click_number = $(this).closest('tr');
            console.log(click_number);
            let number = $(e.currentTarget).attr('data-number');
            let id = $(e.currentTarget).attr('data-id');
            $('#manage_user').load("ajax_user.php?id=" + id + "&number=" + number,() => {
                $('#modal-xl').modal('show');
                $("#birthday").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText, inst) {
                        console.log(dateText.split("-"));
                        dateText = dateText.split("-");
                        $('#birthday').attr('data-date',`${dateText[2]}-${dateText[1]}-${dateText[0]}`);
                    }
                });
                $("#fileInput").on("change",function(){
                    $("#where-replace > span").replaceWith("<img style='width:200px;height:200px;' data-img='' class='img-fluid' id='display-image'/>");
                    readURL(this); 
                });
            })
        });
        // thêm xoá sửa
        // thêm 
        $(document).on('click','#btn-insert',function(e){
            event.preventDefault();
            if(validate) {
                let file = $('input[name="img_name"]')[0].files;
                let formData = new FormData($('#manage_user')[0]);
                formData.append("token","<?php echo_token();?>");
                formData.append("status","Insert");
                formData.append("full_name",$('#full_name').val());
                formData.append("email",$('#email').val());
                formData.append("phone",$('#phone').val());
                formData.append("cmnd",$('#cmnd').val());
                formData.append("address",$('#address').val());
                formData.append("birthday",$('#birthday').val());
                formData.append("username",$('#username').val());
                formData.append("password",$('#password').val());  
                if(file.length > 0) {
                    formData.append('img_name',file[0]);
                }
                $.ajax({
                    url:window.location.href,
                    type: "POST",
                    cache:false,
                    dataType:"json",
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(data){
                        if(data.msg == "ok") {
                            let html = "";
                            html += `<tr style='background-color:#ef7a1752;' id='user-${data.id}'>`;
                            html += `<td>${data.number}</td>`;
                            html += `<td>${data.full_name}</td>`;
                            html += `<td>${data.email}</td>`;
                            html += `<td>${data.phone}</td>`;
                            html += `<td>${data.cmnd}</td>`;
                            html += `<td>${data.address}</td>`;
                            html += `<td>${data.birthday}</td>`;
                            html += `<td>${data.username}</td>`;
                            html += `<td>${data.created_at}</td>`;
                            html += `<td>`;
                            html += `<button class="btn-update-user btn btn-primary" data-id="${data.id}" data-number="${data.number}">Sửa</button>`;
                            html += `<button style="margin-left:3px;" class="btn-delete-row btn btn-danger" data-id="${data.id}" data-number="${data.number}">Xoá</button>`;
                            html += `</td>`;
                            html += `</tr>`;
                            $.alert({
                                title: "Thông báo",
                                content: data.success
                            });
                            html = $(html);
                            //$('#m-user-body').append(html);
                            dt_user.row.add(html[0]).draw();
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
        });
        // sửa 
        $(document).on('click','#btn-update',function(e){
            event.preventDefault();
            console.log($('input[name=number]').val());
            if(validate) {
                let file = $('input[name="img_name"]')[0].files;
                let formData = new FormData($('#manage_user')[0]);
                formData.append("token","<?php echo_token();?>");
                formData.append("number",$('input[name=number]').val());
                formData.append("status","Update");
                formData.append("id",$('input[name=id]').val());
                formData.append("full_name",$('#full_name').val());
                formData.append("email",$('#email').val());
                formData.append("phone",$('#phone').val());
                formData.append("cmnd",$('#cmnd').val());
                formData.append("address",$('#address').val());
                formData.append("birthday",$('#birthday').val());
                formData.append("username",$('#username').val());
                formData.append("password",$('#password').val());
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
                            let id = $('input[name=id]').val();
                            let html = "";
                            html += `<tr style="background-color:#91c08552;" id='user-${id}'>`;
                            html += `<td>${data.number}</td>`;
                            html += `<td>${data.full_name}</td>`;
                            html += `<td>${data.email}</td>`;
                            html += `<td>${data.phone}</td>`;
                            html += `<td>${data.cmnd}</td>`;
                            html += `<td>${data.address}</td>`;
                            html += `<td>${data.birthday}</td>`;
                            html += `<td>${data.username}</td>`;
                            html += `<td>${data.created_at}</td>`;
                            html += `<td>`;
                            html += `<button class="btn-update-user btn btn-primary" data-id="${id}" data-number="${data.number}">Sửa</button>`;
                            html += `<button style="margin-left:3px;" class="btn-delete-row btn btn-danger" data-id="${id}" data-number="${data.number}">Xoá</button>`;
                            html += `</td>`;
                            html += "</tr>";
                            $.alert({
                                title: "Thông báo",
                                content: data.success,
                            });
                            //$("#user-" + id).replaceWith(html);
                            let one_row = dt_user.row(click_number).data();
                            console.log(one_row);
                            one_row[0] = `${data.number}`;
                            one_row[1] = `${data.full_name}`;
                            one_row[2] = `${data.email}`;
                            one_row[3] = `${data.phone}`;
                            one_row[4] = `${data.cmnd}`;
                            one_row[5] = `${data.address}`;
                            if(data.birthday != undefined) {
                                one_row[6] = `${data.birthday}`;
                            }
                            one_row[7] = `${data.username}`;
                            dt_user.row(click_number).data(one_row).draw();
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
        // xoá 
        $(document).on('click','.btn-delete-row',function(e){
            click_number = $(this).closest('tr');
            console.log(click_number);
            let id = $(e.currentTarget).attr('data-id');
            $.confirm({
                title: 'Thông báo',
			    content: 'Bạn có chắc chắn muốn xoá loại sản phẩm này ?',
                buttons: {
                    Có: function(){
                        $.ajax({
                            url:window.location.href,
                            type:"POST",
                            cache:false,
                            data: {
                                token: "<?php echo_token();?>",
                                id: id,
                                status: "Delete",
                            },
                            success:function(data){
                                data = JSON.parse(data);
                                if(data.msg == "ok") {
                                    $.alert({
                                        title: "Thông báo",
                                        content: data.success,
                                    });
                                    //$('#user-' + id).remove();
                                    console.log(click_number);
                                    dt_user.row(click_number).remove().draw();
                                } else {
                                    $.alert({
                                        title: "Thông báo",
                                        content: data.error,
                                    });
                                }
                            },
                            error:function(data) {
                                console.log("Error:",data);
                            }
                        });
                    },Không: function(){

                    }
                }
            });
        });
    });
</script>
<script>
   $(function() {
    $('#pagination').pagination({
        items: <?=$total;?>,
        itemsOnPage: <?=$limit;?>,
		currentPage: <?=$page;?>,
		hrefTextPrefix: "<?php echo '?page='; ?>",
		hrefTextSuffix: "<?php echo '&' . $str_get;?>",
        prevText: "<",
        nextText: ">",
		onPageClick: function(){
			//window.location.href=""
		},
        cssStyle: 'light-theme'
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
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
        $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
        $full_name = isset($_REQUEST["full_name"]) ? $_REQUEST["full_name"] : null;
        $cmnd = isset($_REQUEST["cmnd"]) ? $_REQUEST["cmnd"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : null;
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
        $birthday = isset($_REQUEST["birthday"]) ? $_REQUEST["birthday"] : null;
        $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null ;
        if($status == "Delete") {
            $success = "Bạn đã xoá dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng reload lại trang";
            ajax_db_update_by_id('user',["is_delete" => 1],[$id],["success" => $success],["error" => $error]);
        } else if($status == "Update") {
            $sql_check_exist = "Select cmnd,email,phone,count(*) as 'countt' from user where (email = ? or cmnd = ? or phone = ?) and id <> ? limit 1";
            $success = "Bạn đã sửa dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng reload lại trang";
            $row = fetch_row($sql_check_exist,[$email,$cmnd,$phone,$id]);
            if(2 == 1) {
                if($row['cmnd'] != "") {
                    $error = "Số chứng minh nhân dân bị trùng ";
                } else if($row['email'] != "") {
                    $error = "Email bị trùng";
                } else if($row['phone'] != "") {
                    $error = "Phone bị trùng";
                }
                echo_json(["msg" => "not_ok","error" => $error]);
            }
            $image = null;
            //file_upload(['file' => 'img_cmnd_file'],'user','img_name',"upload/user/identify/",$id,$image,'cmnd_');
            $dir = "upload/user/";
            if(!file_exists($dir)) {
               mkdir($dir, 0777); 
               chmod($dir, 0777);
            }
            $dir = "upload/user/" . $id;
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
            $password = password_hash($password,PASSWORD_DEFAULT);
            if($birthday) {
                $__arr = [
                    "full_name" => $full_name,
                    "email" => $email,
                    "phone" => $phone,
                    "cmnd" => $cmnd,
                    "address" => $address,
                    "birthday" => Date('Y-m-d',strtotime($birthday)),
                    "username" => $username,
                    "password" => $password,
                ];
            } else {
                $__arr = [
                    "full_name" => $full_name,
                    "email" => $email,
                    "phone" => $phone,
                    "cmnd" => $cmnd,
                    "address" => $address,
                    "username" => $username,
                    "password" => $password,
                ];
            }
            db_update_by_id('user',$__arr,[$id]);
            if($birthday) {
                $__arr['birthday'] = Date('d-m-Y',strtotime($birthday));
            }
            //print_r($__arr);
            echo_json(array_merge(['msg' => 'ok',"number" => $number,'success' => $success],$__arr));
        } else if($status == "Insert") {
            //validate zone
            //print_r('Insert');
            $sql_check_exist = "Select cmnd,email,phone,count(*) as 'countt' from user where (email = ? or cmnd = ? or phone = ?) limit 1";
            $success = "Bạn đã thêm dữ liệu thành công";
            $error = "Đã có lỗi xảy ra. Vui lòng tải lại trang";
            $row = fetch_row($sql_check_exist,[$email,$cmnd,$phone]);
            if(2 == 1) {
                if($row['cmnd'] != "") {
                    $error = "Số chứng minh nhân dân bị trùng";
                } else if($row['email'] != "") {
                    $error = "Email bị trùng";
                } else if($row['phone'] != "") {
                    $error = "Phone bị trùng";
                }
                echo_json(["msg" => "not_ok","error" => $error]);
            }
            else if(1 == 1) {
                //print_r('Insert');
                $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
                $__arr = [
                    "full_name" => $full_name,
                    "email" => $email,
                    "phone" => $phone,
                    "cmnd" => $cmnd,
                    "address" => $address,
                    "birthday" => Date('Y-m-d',strtotime($birthday)),
                    "username" => $username,
                    "password" => $password,
                    "created_at"=>date('Y-m-d H-i-s',time())
                ];
                $insert = db_insert_id('user',$__arr);
                
                if($insert > 0) {
                    // insert
                    $success = "Cập nhật dữ liệu thành công";
                    $error = "Đã có lỗi xảy ra. Vui lòng tải lại trang";
                    $image = null;
                    //file_upload(['file' => 'img_cmnd_file'],'user','img_name',"upload/user/identify/",$insert,$image,'cmnd_');
                    $dir = "upload/user/";
                    if(!file_exists($dir)) {
                        mkdir($dir, 0777); 
                        chmod($dir, 0777);
                    }
                    $dir = "upload/user/" . $insert;
                    if(!file_exists($dir)) {
                        mkdir($dir, 0777); 
                        chmod($dir, 0777);
                    }
                    if($_FILES['img_name']['name'] != "") {
                        $ext = strtolower(pathinfo($_FILES['img_name']['name'],PATHINFO_EXTENSION));
                        $file_name = md5(rand(1,999999999)). $insert . "." . $ext;
                        $file_name = str_replace("_","",$file_name);
                        $path = $dir . "/" . $file_name ;
                        move_uploaded_file($_FILES['img_name']['tmp_name'],$path);
                        $sql_update = "update user set img_name='$path' where id = '$insert'";
                        db_query($sql_update);
                    }
                    $__arr['id'] = $insert;
                    echo_json(array_merge(['msg' => 'ok',"number" => $number,'success' => $success],$__arr));
                    /*if(db_update_by_id('user',['img_cmnd' => $image],[$insert])) {
                        $__arr['img_cmnd'] = $image;
                        $__arr['id'] = $insert;
                        echo_json(array_merge(['msg' => 'ok','success' => $success],$__arr));
                    } else {
                        echo_json(['msg' => 'not_ok','error' => $error]);
                    }*/
                }
            }
        }
    }
?>