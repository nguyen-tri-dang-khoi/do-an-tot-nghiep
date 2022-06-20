<?php
    include_once("../lib/database.php");
    
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Update") {
        $sql_get_user_info = "select id,type,full_name,email,phone,address,birthday,img_name,cmnd,count(*) as 'countt' from user where id = '$id' and is_delete = 0 limit 1";
        $result = fetch(sql_query($sql_get_user_info));
?>
<?php
    if($result['countt'] == 1) { 
?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="full_name">Tên đầy đủ</label>
                <input type="text" class="form-control" id="full_name" placeholder="Nhập họ tên đầy đủ" value="<?=$result['full_name']?>">
            </div>
            <div class="col-md-4 form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Nhập email" value="<?=$result['email']?>">
            </div>
            <div class="col-md-4 form-group">
                <label for="phone">Số điện thoại</label>
                <input type="number" min="1" class="form-control" id="phone" placeholder="Nhập số điện thoại" value="<?=$result['phone']?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email">Số chứng minh nhân dân</label>
                <input type="number" min="1" class="form-control" id="cmnd" placeholder="Nhập số chứng minh nhân dân" value="<?=$result['cmnd']?>">
            </div>
            <div class="col-md-6 form-group">
                <label for="image">Ảnh đại diện</label>
                <div class="custom-file">
                    <input name="img_name" type="file" class="custom-file-input" id="fileInput">
                    <label class="custom-file-label" for="fileInput">Chọn ảnh đại diện</label>
                </div>
                <div class="img-fluid" id="where-replace">
                    <img src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="birthday">Ngày sinh</label>
                <input type="text" date-date="<?=$result['birthday']?>" class="form-control" id="birthday" placeholder="Nhập ngày tháng năm sinh" value="<?=Date('d-m-Y',strtotime($result['birthday']));?>">
            </div>
            <div class="col-md-6 form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ thường trú" value="<?=$result['address']?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="type">Chức vụ</label>
                <select name="type" class="form-control">
                    <option value="">Chọn chức vụ</option>
                    <option value="officer" <?=$result['type'] == 'officer' ? "selected" : "";?>>Nhân viên văn phòng</option>
                    <option value="shipper" <?=$result['type'] == 'shipper' ? "selected" : "";?>>Nhân viên giao hàng</option>
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="card-footer">
        <button onclick="processModalUpdate()" id="btn-update" type="submit" class="dt-button button-purple">Sửa dữ liệu</button>
    </div>
<?php 
    } 
}
?>
<?php
    if($status == "Insert") {
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="full_name">Tên đầy đủ</label>
            <input type="text" class="form-control" id="full_name" placeholder="Nhập họ tên đầy đủ" >
        </div>
        <div class="col-md-4 form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Nhập email">
        </div>
        <div class="col-md-4 form-group">
            <label for="phone">Số điện thoại</label>
            <input type="number" min="1" class="form-control" id="phone" placeholder="Nhập số điện thoại">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="email">Số chứng minh nhân dân</label>
            <input type="number" min="1" class="form-control" id="cmnd" placeholder="Nhập số chứng minh nhân dân">
        </div>
        <div class="col-md-6 form-group">
            <label for="image">Ảnh đại diện</label>
            <div class="custom-file">
                <input name="img_name" type="file" class="custom-file-input" id="fileInput">
                <label class="custom-file-label" for="fileInput">Chọn ảnh đại diện</label>
            </div>
            <div class="img-fluid" id="where-replace">
                <span></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="birthday">Ngày sinh</label>
            <input date-date="" type="text" class="form-control" id="birthday" placeholder="Nhập ngày tháng năm sinh">
        </div>
        <div class="col-md-6 form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ thường trú">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="type">Chức vụ</label>
            <select name="type" class="form-control">
                <option value="">Chọn chức vụ</option>
                <option value="officer">Nhân viên văn phòng</option>
                <option value="shipper">Nhân viên giao hàng</option>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="password">Mật khẩu (mặc định là 1234)</label>
            <input type="password" class="form-control" value="1234" id="password" placeholder="Nhập mật khẩu đăng nhập" readonly>
        </div>
    </div>
</div>
<div class="card-footer">
    <button onclick="processModalInsert()" id="btn-insert" type="submit" class="dt-button button-purple">Thêm dữ liệu</button>
</div>
<?php } ?>
<?php
    if($id && $status == "Read") {
        $sql_get_user_info = "select id,type,created_at,full_name,email,phone,address,birthday,img_name,cmnd,count(*) as 'countt' from user where id = '$id' and is_delete = 0 limit 1";
        $result = fetch(sql_query($sql_get_user_info));
?>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th class='w-200'>Tên đầy đủ</th>
                <td><?=$result['full_name']?></td>
            </tr>
            <tr>
                <th class='w-200'>Email</th>
                <td><?=$result['email']?></td>
            </tr>
            <tr>
                <th class='w-200'>Số điện thoại</th>
                <td><?=$result['phone']?></td>
            </tr>
            <tr>
                <th class='w-200'>Số chứng minh nhân dân</th>
                <td><?=$result['cmnd']?></td>
            </tr>
            <tr>
                <th class='w-200'>Ảnh đại diện</th>
                <td>
                    <img style="width:100px;height:100px;" src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg"?>" alt="">
                </td>
            </tr>
            <tr>
                <th class='w-200'>Ngày sinh</th>
                <td><?=Date("d-m-Y",strtotime($result['birthday']))?></td>
            </tr>
            <tr>
                <th class='w-200'>Địa chỉ</th>
                <td><?=$result['address']?></td>
            </tr>
            <tr>
                <th class='w-200'>Chức vụ</th>
                <td><?=($result['type'] == 'officer') ? 'Nhân viên văn phòng' : 'Nhân viên giao hàng'?></td>
            </tr>
            <tr>
                <th class='w-200'>Ngày tạo</th>
                <td><?=Date("d-m-Y H:i:s",strtotime($result['created_at']))?></td>
            </tr>
        </table>
    </div>
<?php
    } if($status == "read_more") {
        $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
        $html = "";
        if($str_arr_upt) {
            $sql = "select * from user where id in ($str_arr_upt)";
            $result2 = fetch_all(sql_query($sql));
            $i = 1;
            foreach($result2 as $res) {
                $file_src = $res['img_name'] ? $res['img_name'] : "upload/noimage.jpg";
                $html .= "<tbody style='display:none;' class='t-bd-read t-bd-read-$i'>
                <tr>
                    <th class='w-200'>Tên đầy đủ</th>
                    <td>" . $res['full_name'] . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Email</th>
                    <td>" . $res['email'] . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Số điện thoại</th>
                    <td>" . $res['phone'] . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Số chứng minh nhân dân</th>
                    <td>" . $res['cmnd'] . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Ảnh đại diện</th>
                    <td>
                        <img style='width:100px;height:100px;' src='" . $file_src . "'>
                    </td>
                </tr>
                <tr>
                    <th class='w-200'>Ngày sinh</th>
                    <td>" . Date('d-m-Y',strtotime($res['birthday'])) . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Địa chỉ</th>
                    <td>" . $res['address'] . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Chức vụ</th>
                    <td>" . (($res['type'] == 'officer') ? 'Nhân viên văn phòng' : 'Nhân viên giao hàng') . "</td>
                </tr>
                <tr>
                    <th class='w-200'>Ngày tạo</th>
                    <td>" . Date("d-m-Y H:i:s",strtotime($res['created_at'])) . "</td>
                </tr>
                </tbody>";
                $i++;
            }
            $html = "<div class='card-body'>
            <table class='table table-bordered'>
                $html
            </table></div>";
            print_r($html);
        }
    }
?>