<?php
    include_once("../lib/database.php");
    $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    if($id) {
        $sql_get_all = "select pi.id as 'pi_id',pi.product_type_id as 'pi_type_id',pi.name as 'pi_name',pi.count,pi.price,pi.description as 'description',pi.img_name,pt.id as 'pt_id',pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id where pi.id = ? and pi.is_delete = 0 limit 1";
       // print_r($sql_get_all);
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
            <img src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
        </div>
        <div id="image_err" class="text-danger"></div>
    </div>
    <!--<div class="form-group">
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
    </div>-->
    <div class="form-group" style="width:100%;">
        <div class="kh-files">
            <div class="kh-file-lists">
                <?php
                    $sql = "select * from product_image where product_info_id = '$id'";
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
        <label for="mo_ta_san_pham">Mô tả sản phẩm</label>
        <textarea name="mo_ta_san_pham" id="summernote"><?=$result['description'] ? $result['description'] : ""?></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<input type="hidden" name="number" value="<?=$number;?>">
<div class="card-footer">
    <button id="btn-luu-san-pham" data-status="Update" type="submit" class="btn btn-primary">Đăng sản phẩm lên</button>
    <input type="hidden" name="id" value="<?=$result['pi_id'];?>">      
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
        <label for="mo_ta_san_pham">Mô tả sản phẩm</label>
        <textarea name="mo_ta_san_pham" id="summernote"></textarea>
        <div id="name_desc_err" class="text-danger"></div>
    </div>
</div>
<input type="hidden" name="token" value="<?php echo_token();?>">
<input type="hidden" name="number" value="<?=$number;?>">
<div class="card-footer">
    <button id="btn-luu-san-pham" type="submit" data-status="Insert" class="btn btn-primary">Đăng sản phẩm lên</button>
    <input type="hidden" name="id" >      
</div>
