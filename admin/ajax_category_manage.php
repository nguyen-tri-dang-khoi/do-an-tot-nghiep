<?php
    include_once("../lib/database.php");
	$number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $parent_id = isset($_REQUEST["parent_id"]) ? $_REQUEST["parent_id"] : null;
    if($status == "Update") {
        if($id) {
            $sql = "select id, name from product_type where is_delete = 0 and id = ?";
            $result = fetch_row($sql,[$id]);       
?>
<div class="card-body">
    <div class="form-group">
        <label for="product_type">Tên danh mục</label>
        <input type="text" name="ten_loai_san_pham" class="form-control" placeholder="Nhập tên danh mục" value="<?php echo $result["name"];?>">
        <input type="hidden" name="id" value="<?=$result["id"];?>">
        <?php if($parent_id) { ?>
            <input type="hidden" name="parent_id" value="<?=$parent_id;?>">
        <?php } ?>
		<input type="hidden" name="number" value="<?=$number?>">
        <input type="hidden" name="token" value="<?php echo_token();?>">
    </div>
    <div id="product_type_err" class="text-danger"></div>
</div>
<div class="card-footer">
    <button data-status="<?=$status;?>" id="btn-luu-loai-san-pham" type="submit" class="dt-button button-purple">Sửa</button>
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
            <input type="hidden" name="parent_id" value="<?=$parent_id;?>">
        <?php } ?>
        <input type="hidden" name="token" value="<?php echo_token();?>">
		<input type="hidden" name="number" value="<?=$number?>">
    </div>
    <div id="product_type_err" class="text-danger"></div>
</div>
<div class="card-footer">
    <button data-status="<?=$status;?>" id="btn-luu-loai-san-pham" type="submit" class="dt-button button-purple">Thêm</button>
</div>
<?php
    } if($status == "Read") {
        if($id) {
            $sql = "select id, name, created_at from product_type where is_delete = 0 and id = ?";
            $result = fetch_row($sql,[$id]);
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
                <tbody style='display:none;' class='t-bd-read tb-bd-read-$i'>
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