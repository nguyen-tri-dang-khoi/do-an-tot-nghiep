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
        <label for="product_type">Tên loại sản phẩm</label>
        <input type="text" name="ten_loai_san_pham" class="form-control" placeholder="Nhập tên loại sản phẩm" value="<?php echo $result["name"];?>">
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
    <button id="btn-luu-loai-san-pham" type="submit" class="btn btn-primary"><?=$status;?></button>
</div>

<?php
        }
    }
    if($status == "Insert") {
?>
<div class="card-body">
    <div class="form-group">
        <label for="product_type">Tên loại sản phẩm</label>
        <input type="text" name="ten_loai_san_pham" class="form-control" placeholder="Nhập tên loại sản phẩm">
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
    <button id="btn-luu-loai-san-pham" type="submit" class="btn btn-primary"><?=$status;?></button>
</div>
<?php
    }
?>