<?php
    include_once("../lib/database.php");
    $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    if($id) {
        $sql_get_all = "select * from notification where id = ? limit 1";
       // print_r($sql_get_all);
        $result = fetch_row($sql_get_all,[$id]);
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
        <div class="kh-files">
            <div class="kh-file-lists">
                <?php
                    $sql = "select * from notification_image where notify_id = '$id'";
                    $result2 = db_query($sql);
                    $list_file_del = [];
                    $i = 0;
                    foreach($result2 as $res){
                        array_push($list_file_del,$res['img_order']);
                ?>
                <?php
                    if($i % 6 == 0) {
                        echo '<div class="kh-file-list">';
                    }
                ?>
                    <div data-id="<?=$res['img_order']?>"  class="kh-custom-file" style="background-position:50%;background-size:cover;background-image:url('<?=$res['img_id']?>');">
                        <input class="nl-form-control" name="img[]" type="file" onchange="readURLChange(this,'<?=$res['img_order'];?>')">
                        <div class="kh-custom-remove-img" style="display:block;">
                            <span class="kh-custom-btn-remove" onclick="removeImageDel(this,'<?=$res['img_order'];?>')"></span>
                        </div>
                    </div>
                <?php
                    if($i % 6 == 5) {
                        echo '</div>';
                    }	
                ?>
                <?php
                        $i++;
                    }
                ?>
                <?php
                    if($i % 6 != 0 && $i != 0) {
                        echo "</div>";
                    }	
                ?>
                <?php if($i == 0) {?>
                    <div class="kh-file-list">
                        <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                            <input class="nl-form-control" name="img[]" type="file" onchange="readURLChange(this,'1')">
                            <div class="kh-custom-remove-img" style="display:none;">
                                <span class="kh-custom-btn-remove" onclick="removeImageDel(this,'1')"></span>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
            <input name="list_file_del" type='hidden' value="<?=implode(",",$list_file_del);?>">
            <div class="kh-div-append-file">
                <button type="button" class="kh-btn-append-file" onclick="addFileInputChange('.kh-file-list:last-child')">+</button>
            </div>
        </div>

    </div>
    <div class="form-group" style="width:100%;">
        <label for="content">Nội dung bảng tin</label>
        <textarea name="content" id="summernote"><?=$result['content'] ? $result['content'] : ""?></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<input type="hidden" name="number" value="<?=$number;?>">
<div class="card-footer">
    <button id="btn-luu-bang-tin" type="button" data-status="Update" class="btn btn-primary">Sửa bảng tin</button>
    <input type="hidden" name="id" value="<?=$result['id'];?>">      
</div>
<?php
        exit();
    }
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
        <div class="kh-files">
            <div class="kh-file-lists">
                <div class="kh-file-list">
                    <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                        <input class="nl-form-control" name="img[]" type="file" onchange="readURL(this,'1')">
                        <div class="kh-custom-remove-img" style="display:none;">
                            <span class="kh-custom-btn-remove" onclick="removeImage(this,'1')"></span>
                        </div>
                    </div>
                    <input name="list_file_del" type='hidden' value="">
                </div>
            </div>
            <div class="kh-div-append-file">
                <button type="button" class="kh-btn-append-file" onclick="addFileInput('.kh-file-list:last-child')">+</button>
            </div>
        </div>
    </div>
    <div class="form-group" style="width:100%;">
        <label for="content">Nội dung bảng tin</label>
        <textarea name="content" id="summernote"></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<input type="hidden" name="number" value="<?=$number;?>">
<div class="card-footer">
    <button id="btn-luu-bang-tin" data-status="Insert" type="button" class="btn btn-primary">Đăng bảng tin lên</button>
    <input type="hidden" name="id" >      
</div>
