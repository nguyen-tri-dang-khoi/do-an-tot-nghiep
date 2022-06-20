<?php
    include_once("../lib/database_v2.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Update") {
        $sql_get_all = "select * from notification where id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));
?>

<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" placeholder="Nhập Tiêu đề..." value="<?=$result['title'];?>">
            <div id="name_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="exampleInputFile">Upload ảnh đại diện</label>
            <div class="input-group">
            <div class="custom-file">
                <input id="fileInput" name="img_bangtin_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile">
                <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
            </div>
        </div>
        <div class="img-fluid" id="where-replace">
            <img src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
        </div>
        <div id="image_err" class="text-danger"></div>
    </div>
    <div class="form-group" style="width:100%;">
        <label for="content">Nội dung bảng tin</label>
        <textarea name="content" id="summernote"><?=$result['content'] ? $result['content'] : ""?></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<div class="card-footer">
    <button onclick="processModalInsertUpdate()" id="btn-luu-bang-tin" type="button" data-status="Update" class="dt-button button-purple">Sửa bảng tin</button>
    <input type="hidden" name="id" value="<?=$result['id'];?>">      
</div>
<?php
    } if($status == "Insert") {
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" placeholder="Nhập Tiêu đề...">
            <div id="name_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="exampleInputFile">Upload ảnh đại diện</label>
            <div class="input-group">
                <div class="custom-file">
                    <input id="fileInput" name="img_bangtin_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile">
                    <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
                </div>
            </div>
            <div class="img-fluid" id="where-replace">
                <span></span>
            </div>
            <div id="image_err" class="text-danger"></div>
        </div>
    </div>
    <div class="form-group" style="width:100%;">
        <label for="content">Nội dung bảng tin</label>
        <textarea name="content" id="summernote"></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<div class="card-footer">
    <button onclick="processModalInsertUpdate()" id="btn-luu-bang-tin" data-status="Insert" type="button" class="dt-button button-purple">Đăng bảng tin lên</button>
    <input type="hidden" name="id" >      
</div>
<?php } if($id && $status == "Read") {?>
    <?php
        $sql_get_all = "select * from notification where id = '$id' limit 1";
        $result = fetch(sql_query($sql_get_all));    
    ?>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Tiêu đề</th>
                <td><?=$result['title'];?></td>
            </tr>
            <tr>
                <th>Nội dung</th>
                <td><?=$result['content'];?></td>
            </tr>
            <tr>
                <th>Lượt xem</th>
                <td><?=$result['views'];?></td>
            </tr>
            <tr>
                <th>Ảnh đại diện</th>
                <td>
                    <div class="kh-file-list">
                        <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url(<?=$result['img_name']?>);">
                            
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td><?=Date("d-m-Y H:i:s",strtotime($result['created_at']));?></td>
            </tr>
        </table>
    </div>
<?php } if($status == "read_more") {
    $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
    $sql = "select * from notification where id in ($str_arr_upt)";
    $result = fetch_all(sql_query($sql));
    $html = "";
    $html_file = "";
    $i = 1;
    foreach($result as $res2) {
        $html .= "<tbody style='display:none;' class='t-bd-read t-bd-read-" . $i . "'><tr>
                    <th>Tiêu đề</th>
                    <td>" . $res2['title'] . "</td>
                </tr>
                <tr>
                    <th>Nội dung</th>
                    <td>" . $res2['content'] . "</td>
                </tr>
                <tr>
                    <th>Lượt xem</th>
                    <td>" . $res2['views'] . "</td>
                </tr>
                <tr>
                    <th>Ảnh đại diện</th>
                    <td>
                        <div class='kh-file-list'>
                            <div data-id='1' class='kh-custom-file' style='background-position:50%;background-size:cover;background-image:url(" . $res2['img_name'] . ")'>
                                
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            ";
        $i++;
    }
    $html = "
        <div class='card-body'>
            <table class='table table-bordered'>
                $html
            </table>
        </div>
    ";
    print_r($html);
}?>
