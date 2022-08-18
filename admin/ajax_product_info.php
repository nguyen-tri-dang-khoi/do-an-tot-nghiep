<?php
    include_once("../lib/database.php");
    $number = isset($_REQUEST["number"]) ? $_REQUEST["number"] : null;
    $number2 = $number;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    
    if($id && $status == "Update") {
        $sql_get_all = "select pi.id as 'pi_id',pi.product_type_id as 'pi_type_id',pi.name as 'pi_name',pi.count,pi.cost,pi.price,pi.description as 'description',pi.img_name,pt.id as 'pt_id',pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id where pi.id = '$id' and pi.is_delete = 0 limit 1";
        $result = fetch(sql_query($sql_get_all));
?>
<form id="form-san-pham" method="post" enctype='multipart/form-data'> 
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="ten_san_pham">Tên sản phẩm</label>
                <input type="text" name="ten_san_pham" class="form-control" placeholder="Nhập tên sản phẩm..." value="<?php echo $result['pi_name']?>">
                <p id="name_err" class="text-danger"></p>
            </div>
            <div class="col-md-6 form-group">
                <label for="so_luong">Số lượng</label>
                <input type="text" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="so_luong"  class="form-control" placeholder="Nhập số lượng" value="<?=number_format($result['count'],0,'','.');?>">
                <p id="count_err" class="text-danger"></p>
            </div>
        </div>
        <div class="row" style="margin-left:0px;flex-direction:column;">
            <label style="margin-bottom:-5px;" for="danh_muc">Danh mục sản phẩm</label>
            <div style="display:flex;flex-direction:row;align-items:center;">
                <ul tabindex="1" class="col-md-6" style="padding-left:0px;height: 65px;" id="menu">
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
            <input type="hidden" name="product_type_id" value="<?=$result['pi_type_id'];?>">
            <input type="hidden" name="category_name" value="<?=$result['pt_name'];?>">
            <p style="margin-top:-25px;" id="product_type_id_err" class="text-danger"></p>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="don_gia">Giá gốc</label>
                <input type="text" onblur="blur_number_format()" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="gia_goc" class="form-control" placeholder="Nhập giá gốc" value="<?=number_format($result['cost'],0,'','.')?>">
                <div id="cost_err" class="text-danger"></div>
                
            </div>
            <div class="col-md-3 form-group">
                <label for="don_gia">Đơn giá</label>
                <input type="text" onblur="blur_number_format()" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="don_gia"   class="form-control" placeholder="Nhập đơn giá" value="<?=number_format($result['price'],0,'','.')?>">
                <div id="price_err" class="text-danger"></div>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="exampleInputFile">Upload ảnh đại diện</label>
                <div class="input-group">
                <div class="custom-file">
                    <input id="fileInput" name="img_sanpham_file" onchange="readURLok(this)" type="file" accept="image/*" class="custom-file-input" id="exampleInputFile">
                    <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
                </div>
            </div>
            <div class="img-fluid" id="where-replace">
                <img src="<?=$result['img_name'] ? $result['img_name'] : "upload/noimage.jpg";?>" class="img-fluid" id="display-image"/>
            </div>
            <div id="image_err" class="text-danger"></div>
        </div>
        <div class="form-group" style="width:100%;">
            <label for="">Ảnh mô tả sản phẩm</label>
            <div class="kh-files">
                <div class="kh-file-lists">
                    <?php
                        $sql = "select * from product_image where product_info_id = '$id'";
                        $result2 = fetch_all(sql_query($sql));
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
            <div id="desc_err" class="text-danger"></div>
        </div>
    </div>
    <div class="card-footer">
        <button onclick="processModalInsertUpdate()" id="btn-luu-san-pham" data-status="Update" type="submit" class="dt-button button-purple">Đăng sản phẩm lên</button>
        <input type="hidden" name="id" value="<?=$result['pi_id'];?>">      
    </div>
</form>
<?php
    }
    if($status == "Insert") {
?>
<form id="form-san-pham" method="post" enctype='multipart/form-data'>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="ten_san_pham">Tên sản phẩm</label>
                <input type="text" name="ten_san_pham" class="form-control" placeholder="Nhập tên sản phẩm...">
                <p id="name_err" class="text-danger"></p>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="so_luong">Số lượng</label>
                <input type="text" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="so_luong"  class="form-control" placeholder="Nhập số lượng">
                <p id="count_err" class="text-danger"></p>
            </div>
        </div>
        <div class="row" style="margin-left:0px;flex-direction:column;">
            <label for="danh_muc">Danh mục sản phẩm</label>
            <div style="display:flex;flex-direction:row;">
                <ul tabindex="1" class="col-md-6" style="padding-left:0px;height: 65px;" id="menu">
                    <li class="parent" style="border: 1px solid #dce1e5;position:relative">
                        <a href="#">Chọn danh mục</a>
                        <ul class="child" style="">
                            <?php echo show_menu();?>
                        </ul>
                    </li>
                </ul>
                <nav id="breadcrumb-menu" class="col-md-6" aria-label="breadcrumb"></nav>
            </div>
            
            <input type="hidden" name="product_type_id" value="">
            <input type="hidden" name="category_name" value="">
            <p style="margin-top:-25px;" id="product_type_id_err" class="text-danger"></p>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="don_gia">Giá gốc</label>
                <input type="text" onblur="blur_number_format()" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="gia_goc" class="form-control" placeholder="Nhập giá gốc">
                <p id="cost_err" class="text-danger"></p>
            </div>
            <div class="col-md-3 form-group">
                <label for="don_gia">Đơn giá</label>
                <input type="text" onblur="blur_number_format()" onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" name="don_gia" class="form-control" placeholder="Nhập đơn giá">
                <p id="price_err" class="text-danger"></p>
            </div>
            <div class="col-md-6 form-group">
                <label for="exampleInputFile">Upload ảnh đại diện</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input id="fileInput" name="img_sanpham_file" type="file" accept="image/*" onchange="readURLok(this)" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Chọn ảnh</label>
                    </div>
                </div>
                <div class="img-fluid" id="where-replace">
                    <span></span>
                </div>
                <p id="image_err" class="text-danger"></p>
            </div>
        </div>
        <div class="form-group" style="width:100%;">
            <label for="">Ảnh mô tả sản phẩm</label>
            <div class="kh-files" ondragleave="hideDragText()" ondragover="allowDrop()" ondrop="drop()">
                <div class="kh-file-lists">
                    <div class="kh-file-list">
                        <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                            <input class="nl-form-control" name="img[]" type="file" onchange="readURLChange(this,'1')">
                            <div class="kh-custom-remove-img" style="display:none;">
                                <span class="kh-custom-btn-remove" onclick="removeImageChange(this,'1')"></span>
                            </div>
                        </div>
                        <input name="list_file_del" type='hidden' value="">
                    </div>
                </div>
                <div class="kh-div-append-file">
                    <button type="button" class="kh-btn-append-file" onclick="addFileInputChange()">+</button>
                </div>
            </div>
        </div>
        <div style="display:none;width:100%;border:none;border-bottom:4px dashed red;" class="k-border"></div>
        <div class="form-group" style="width:100%;">
            <label for="mo_ta_san_pham">Mô tả sản phẩm</label>
            <textarea name="mo_ta_san_pham" id="summernote"></textarea>
            <p id="desc_err" class="text-danger"></p>
        </div>
    </div>
    <div class="card-footer">
        <button onclick="processModalInsertUpdate()" id="btn-luu-san-pham" type="submit" data-status="Insert" class="dt-button button-purple">Đăng sản phẩm lên</button>
        <input type="hidden" name="id" >
    </div>
</form>
<?php } if($id && $status == "Read") {?>
    <?php
        $sql_get_all = "select pi.id as 'pi_id',pi.product_type_id as 'pi_type_id',pi.name as 'pi_name',pi.created_at as 'created_at',pi.count,pi.cost,pi.price,pi.description as 'description',pi.img_name,pt.id as 'pt_id',pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id where pi.id = '$id' and pi.is_delete = 0 and pt.is_delete = 0 limit 1";
        $result = fetch(sql_query($sql_get_all));    
    ?>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th class="w-250">Tên sản phẩm</th>
                <td class="width-auto"><?=$result['pi_name']?></td>
            </tr>
            <tr>
                <th class="w-250">Danh mục sản phẩm</th>
                <td class="width-auto">
                    <nav id="breadcrumb-menu" class="" aria-label="breadcrumb">
                        <?=generate_breadcrumb_menus($result['pi_type_id']);?>
                    </nav>
                </td>
            </tr>
            <tr>
                <th>Số lượng</th>
                <td class="width-auto"><?=number_format($result['count'],0,'','.');?></td>
            </tr>
            <tr>
                <th>Giá gốc</th>
                <td class="width-auto"><?=number_format($result['cost'],0,'','.') . "đ";?></td>
            </tr>
            <tr>
                <th>Đơn giá</th>
                <td class="width-auto"><?=number_format($result['price'],0,'','.') . "đ";?></td>
            </tr>
            <tr>
                <th>Ảnh đại diện</th>
                <td class="width-auto">
                    <div class="kh-file-list">
                        <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url(<?=$result['img_name']?>);">
                            
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Ảnh mô tả sản phẩm</th>
                <td class="width-auto">
                    <div class="kh-files">
                        <div class="kh-file-lists">
                            <?php
                                $sql = "select * from product_image where product_info_id = '$id' order by img_order asc";
                                $result2 = fetch_all(sql_query($sql));
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
                                       
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Nội dung mô tả sản phẩm</th>
                <td class="width-auto">
                    <div>
                    <?=$result['description']?>
                    </div> 
                </td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td><?=Date("d-m-Y H:i:s",strtotime($result['created_at']));?></td>
            </tr>
        </table>
    </div>
<?php } ?>
<?php 
    if($status == "read_more") {
        $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
        $html = "";
        $html_file = '<div class="kh-files"><div class="kh-file-lists">';
        if($str_arr_upt) {
            $arr = explode(",",$str_arr_upt);
            $i = 1;
            foreach($arr as $id) {
                $html_file = '<div class="kh-files"><div class="kh-file-lists">';

                $sql_get_all = "select pi.id as 'pi_id',pi.product_type_id as 'pi_type_id',pi.name as 'pi_name',pi.created_at as 'created_at',pi.count,pi.price,pi.description as 'description',pi.cost,pi.img_name,pt.id as 'pt_id',pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id where pi.id = '$id' and pi.is_delete = 0 limit 1";
                $result = fetch(sql_query($sql_get_all)); 
                //
                $sql2 = "select * from product_image where product_info_id = '$id' order by img_order asc";
                $result2 = fetch_all(sql_query($sql2));
                $list_file_del = [];
                $i2 = 0;
                foreach($result2 as $res){
                    array_push($list_file_del,$res['img_order']);
                    if($i2 % 6 == 0) {
                        $html_file .= '<div class="kh-file-list">';
                    }
                    $html_file .= '<div data-id="' . $res['img_order'] . '" class="kh-custom-file" style="background-position:50%;background-size:cover;background-image:url(' . $res['img_id'] . '");">
                    
                    </div>';
                    if($i2 % 6 == 5) {
                        $html_file .= '</div>';
                    }
                    $i2++;
                }
                if($i2 % 6 != 0 && $i2 != 0) {
                    $html_file.= "</div>";
                }
                if($i2 == 0) {
                    $html_file .= '<div class="kh-file-list">
                        <div data-id="1" class="kh-custom-file" style="background-position:50%;background-size:cover;background-image:url();">
                        </div>
                    </div>';
                }	
                //
                $html .= '<tbody style="display:none;" class="t-bd-read t-bd-read-' . $i .'">
                    <tr>
                        <th class="w-250">Tên sản phẩm</th>
                        <td class="width-auto">' . $result['pi_name'] . '</td>
                    </tr>
                    <tr>
                        <th class="w-250">Danh mục sản phẩm</th>
                        <td class="width-auto">
                            <nav id="breadcrumb-menu" class="" aria-label="breadcrumb">
                                ' . generate_breadcrumb_menus($result['pi_type_id']) . '
                            </nav>
                        </td>
                    </tr>
                    <tr>
                        <th>Số lượng</th>
                        <td class="width-auto">' . number_format($result['count'],0,'','.') . '</td>
                    </tr>
                    <tr>
                        <th>Giá gốc</th>
                        <td class="width-auto">' . number_format($result['cost'],0,'','.') . "đ" . '</td>
                    </tr>
                    <tr>
                        <th>Đơn giá</th>
                        <td class="width-auto">' . number_format($result['price'],0,'','.') . "đ" . '</td>
                    </tr>
                    
                    <tr>
                        <th>Ảnh đại diện</th>
                        <td class="width-auto">
                            <div class="kh-file-list">
                                <div data-id="1" class="kh-custom-file" style="background-position:50%;background-size:cover;background-image:url(' . $result['img_name'] . ')">
                                    
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Ảnh mô tả sản phẩm</th>
                        <td class="width-auto">'
                           . $html_file .
                        '</td>
                    </tr>
                    <tr>
                        <th>Nội dung mô tả sản phẩm</th>
                        <td class="width-auto">
                            <div>'
                                . $result['description'] . '
                            </div> 
                        </td>
                    </tr>
                </tbody>';
                $i++;
            }
        }
        $html = "
            <div class='card-body'>
                <table class='table table-bordered'>
                    $html
                </table>
            </div>
        ";
        print_r($html);
    }
?>
