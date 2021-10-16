<?php
    include_once("../lib/database.php");
    
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    if($id) {
        $sql_get_all = "select pi.id as 'pi_id',pi.product_type_id as 'pi_type_id',pi.name as 'pi_name',pi.count,pi.price,pi.description,pi.img_name,pt.id as 'pt_id',pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id where pi.id = ? and pi.is_delete = 0 limit 1";
        $result = fetch_row($sql_get_all,[$id]);
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="ten_san_pham">Tên sản phẩm</label>
            <input type="text" name="ten_san_pham" class="form-control" placeholder="Nhập tên sản phẩm..." value="<?php echo $result['pi_name']?>">
            <div id="name_err" class="text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="so_luong">Số lượng</label>
            <input type="number" name="so_luong" min="1" class="form-control" placeholder="Nhập số lượng" value="<?php echo $result['count']?>">
            <div id="count_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row" style="margin-left:0px;flex-direction:column;">
        <label for="danh_muc">Danh mục sản phẩm</label>
        <div style="display:flex;flex-direction:row;align-items:center;">
            <ul class="col-md-6" style="padding-left:0px;" id="menu">
                <li class="parent" style="border: 1px solid #dce1e5;position:relative;">
                    <a href="#">Chọn danh mục</a>
                    <ul class="child" >
                        <?php echo show_menu();?>
                    </ul>
                </li>
            </ul>
            <nav id="breadcrumb-menu" class="col-md-6" aria-label="breadcrumb">
                <?=generate_breadcrumb_menus($result['pi_type_id']);?>
            </nav>
        </div>
        
        <input type="hidden" name="category_id" value="<?=$result['pi_type_id'];?>">
        <input type="hidden" name="category_name" value="<?=$result['pt_name'];?>">
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="don_gia">Đơn giá</label>
            <input type="number" name="don_gia" min="1" max="100000000000" class="form-control" placeholder="Nhập đơn giá" value="<?php echo $result['price']?>">
            <div id="price_err" class="text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputFile">Upload ảnh đại diện</label>
            <div class="input-group">
            <div class="custom-file">
                <input id="fileInput" name="img_sanpham_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile">
                <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
            </div>
        </div>
        <!--_DIR_["IMG"]["ADMINS"];?>product/echo $result["img_name"];-->
        <div class="img-fluid" id="where-replace">
            <img src="<?php echo "upload/product/{$result['img_name']}";?>" class="img-fluid" id="display-image"/>
        </div>
        <div id="image_err" class="text-danger"></div>
    </div>
    <div class="form-group">
        <label for="exampleInputFile">Upload ảnh mô tả (tối đa 5 hình)</label>
        <div class="input-group mb-3">
            <div class="custom-file">
                <input id="file_input_anh_mo_ta" name="anh_mo_ta[]" type="file" accept="image/*" class="custom-file-input" multiple="multiple">
                <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
            </div>
        </div>
        <?php
            $sql_result_img = "select a.id as 'a_id',b.id as 'b_id',b.img_id as 'image' from product_info a inner join product_image b on a.id = b.product_info_id where a.id = ?";
            $result_img = db_query($sql_result_img,[$id]);
        ?>
        <div id="image_preview" class="filter-container p-0 row" style="margin-left:0px;margin-right:0px;border:1px dashed;">
        <?php
            foreach($result_img as $img) {
        ?>
                <div class="img-child filtr-item col-sm-1">
                    <img src="<?php echo "upload/product/{$img['a_id']}/{$img['image']}";?>" class="img-fluid mb-2" alt="white sample"/>
                    <button data-img_id="<?php echo $img["b_id"];?>" type="button" class="icon-x btn-xoa-anh-mo-ta-san-pham btn btn-tool">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
        <?php
            }
        ?>
        </div>
    </div>
    <div class="form-group" style="width:100%;">
        <label for="mo_ta_san_pham">Mô tả sản phẩm</label>
        <textarea name="mo_ta_san_pham" id="summernote"><?php echo $result['description']?></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<div class="card-footer">
    <button id="btn-luu-san-pham" type="submit" class="btn btn-primary">Đăng sản phẩm lên</button>
    <input type="hidden" name="id" value="<?php echo $result['pi_id'];?>">      
</div>
<?php
        exit();
    }
?>
<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="ten_san_pham">Tên sản phẩm</label>
            <input type="text" name="ten_san_pham" class="form-control" placeholder="Nhập tên sản phẩm...">
            <div id="name_err" class="text-danger"></div>
        </div>
        
        <div class="col-md-6 form-group">
            <label for="so_luong">Số lượng</label>
            <input type="number" name="so_luong" min="1" class="form-control" placeholder="Nhập số lượng">
            <div id="count_err" class="text-danger"></div>
        </div>
    </div>
    <div class="row" style="margin-left:0px;flex-direction:column;">
        <label for="danh_muc">Danh mục sản phẩm</label>
        <div style="display:flex;flex-direction:row;align-items:center;">
            <ul class="col-md-6" style="padding-left:0px;" id="menu">
                <li class="parent" style="border: 1px solid #dce1e5;position:relative;">
                    <a href="#">Chọn danh mục</a>
                    <ul class="child" >
                        <?php echo show_menu();?>
                    </ul>
                </li>
            </ul>
            <nav id="breadcrumb-menu" class="col-md-6" aria-label="breadcrumb"></nav>
        </div>
        
        <input type="hidden" name="category_id">
        <input type="hidden" name="category_name">
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="don_gia">Đơn giá</label>
            <input type="number" name="don_gia" min="1" max="100000000000" class="form-control" placeholder="Nhập đơn giá">
            <div id="price_err" class="text-danger"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputFile">Upload ảnh đại diện</label>
            <div class="input-group">
                <div class="custom-file">
                    <input id="fileInput" name="img_sanpham_file" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile">
                    <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
                </div>
            </div>
            <div class="img-fluid" id="where-replace">
                <span></span>
            </div>
            <div id="image_err" class="text-danger"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="exampleInputFile">Upload ảnh mô tả (tối đa 5 hình)</label>
        <div class="input-group mb-3">
            <div class="custom-file">
                <input id="file_input_anh_mo_ta" name="anh_mo_ta[]" type="file" accept="image/*" class="custom-file-input" multiple="multiple">
                <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
            </div>
        </div>
        <div id="image_preview" class="filter-container p-0 row" style="margin-left:0px;margin-right:0px;border:1px dashed">
        </div>
    </div>
    <div class="form-group">
        <label for="mo_ta_san_pham">Mô tả sản phẩm</label>
        <textarea name="mo_ta_san_pham" id="summernote"></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<div class="card-footer">
    <button id="btn-luu-san-pham" type="submit" class="btn btn-primary">Đăng sản phẩm lên</button>
    <input type="hidden" name="id" >      
</div>
