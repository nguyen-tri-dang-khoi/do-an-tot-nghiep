<?php
    include_once("../lib/database.php");
	$number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
    if($status == "Update") {
        if($id) {
            $sql = "select id,name,img_name from product_type where is_delete = 0 and id = '$id'";
            $result = fetch(sql_query($sql));       
?>
<div class="card-body">
    <div class="form-group">
        <label for="product_type">Tên danh mục</label>
        <input type="text" name="ten_loai_san_pham" class="form-control" placeholder="Nhập tên danh mục" value="<?php echo $result["name"];?>">
        <input type="hidden" name="id" value="<?=$result["id"];?>">
        <?php if($parent_id) { ?>
            <input type="hidden" name="parent_id" value="<?=$parent_id;?>">
        <?php } ?>
    </div>
    <div class="form-group col-6" style="padding: 0;">
        <label for="exampleInputFile">Upload ảnh danh mục sản phẩm</label>
        <div class="input-group">
            <div class="custom-file">
                <input id="fileInput" name="img_category_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile" onchange="readURLok(this)">
                <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
            </div>
        </div>
        <div class="img-fluid" id="where-replace" style="background-image:url('<?=$result['img_name'] ? $result['img_name'] : '';?>');background-size:cover;height:300px;"></div>
        <div id="image_err" class="text-danger"></div>
    </div>
    <div id="product_type_err" class="text-danger"></div>
</div>
<div class="card-footer">
    <button onclick="processModalInsertUpdate()" data-status="<?=$status;?>" id="btn-luu-loai-san-pham" type="submit" class="dt-button button-purple">Sửa</button>
</div>

<?php
        }
    }
    if($status == "Insert") {
?>
<div class="card-body">
    <div class="form-group">
        <label for="product_type">Tên danh mục</label>
        <input type="text" name="ten_loai_san_pham" class="form-control" placeholder="Nhập tên danh mục">
        <input type="hidden" name="id">
        <?php if($parent_id) { ?>
            <input type="hidden" name="parent_id" value="<?=$parent_id ? $parent_id : '';?>">
        <?php } ?>
    </div>
    <div class="form-group col-6" style="padding: 0;">
        <label for="exampleInputFile">Upload ảnh danh mục sản phẩm</label>
        <div class="input-group">
            <div class="custom-file">
                <input id="fileInput" name="img_category_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile" onchange="readURLok(this)">
                <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
            </div>
        </div>
        <div class="img-fluid" id="where-replace">
            <span></span>
        </div>
        <div id="image_err" class="text-danger"></div>
    </div>
    <div id="product_type_err" class="text-danger"></div>
</div>
<div class="card-footer">
    <button onclick="processModalInsertUpdate()" data-status="<?=$status;?>" id="btn-luu-loai-san-pham" type="submit" class="dt-button button-purple">Thêm</button>
</div>
<?php
    } if($status == "Read") {
        if($id) {
            $sql = "select id, name, created_at from product_type where is_delete = 0 and id = '$id'";
            $result = fetch(sql_query($sql));
?>
<div class="card-body">
    <table class="table table-bordered">
        <tr>
            <th>Tên danh mục: </th>
            <td><?=$result['name'];?></td>
        </tr>
        <tr>
            <th>Ngày thêm: </th>
            <td><?=Date("d-m-Y H:i:s",strtotime($result['created_at']));?></td>
        </tr>
    </table>
</div> 
<?php
        }
    }
    if($status == "read_more") {
        $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
        $sql = "select * from product_type where id in ($str_arr_upt)";
        $result = fetch_all(sql_query($sql));
        $tbody = "";
        $i = 1;
        foreach($result as $res) {
            $_name = $res['name'];
            $_created_at = $res['created_at'] ? Date("d-m-Y H:i:s",strtotime($res['created_at'])) : "";
            $tbody .= "
                <tbody style='display:none;' class='t-bd-read t-bd-read-$i'>
                    <tr>
                        <th>Tên danh mục: </th>
                        <td>$_name</td>
                    </tr>
                    <tr>
                        <th>Ngày thêm: </th>
                        <td>$_created_at</td>
                    </tr>
                </tbody>
            ";
            $i++;
        }
        $html = "
            <table class='table table-bordered'>
                $tbody
            </table>
        ";
        print_r($html);
        exit();
    }
?>
<?php
    if($status=="get_count_pi") {
        echo_json(["msg" => "ok","result" => show_confirm_when_del_pt(NULL,$id)]);
    }
    if($status=="get_count_pi_is_active") {
        
        echo_json(["msg" => "ok","result" => show_confirm_when_deactive(NULL,$id)]);
    }
?>