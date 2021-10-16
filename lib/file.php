<?php
    function file_upload($file_post = [],$table_name_old_img = "", $column_name_old_img = "", $dir_storage_file,$img_id, &$img_name = "",$code="image_") {
        if(file_exists($_FILES[$file_post['file']]['tmp_name']) && is_uploaded_file($_FILES[$file_post['file']]['tmp_name'])){
            $ext = strtolower(pathinfo($_FILES[$file_post['file']]['name'], PATHINFO_EXTENSION));
            $code = $code . $img_id;
            $sql_check_old_image = "select {$column_name_old_img} from {$table_name_old_img} where id = ? limit 1";
            $old_image = fetch_row($sql_check_old_image,[$img_id]);
            if(count($old_image) == 1 && $old_image["$column_name_old_img"] != "") {
                $test = file_exists($dir_storage_file.$old_image["$column_name_old_img"]) ? unlink($dir_storage_file.$old_image["$column_name_old_img"]) : false;
            }
            $result = move_uploaded_file($_FILES[$file_post['file']]['tmp_name'], $dir_storage_file . $code.'.'.$ext);
            $img_name = $code.'.'.$ext;
        }
    }
    function multiple_file_upload($__arr = []) {
        $code = "";
        $test = true;
        $__arr_file_ext = [];
        if(array_key_exists('file',$__arr) && array_key_exists('file_name',$__arr) && array_key_exists('dirname',$__arr) && array_key_exists('img_id',$__arr)){
            $i = $__arr["img_id"];
            if(!file_exists($__arr["dirname"])) {
                mkdir($__arr["dirname"],0777);
            }
            foreach ($_FILES[$__arr["file"]]["error"] as $key => $error) {
                $code = $__arr['file_name'] . $i;
                $ext = strtolower(pathinfo($_FILES[$__arr['file']]['name'][$key], PATHINFO_EXTENSION));
                if($error == UPLOAD_ERR_OK) {
                    move_uploaded_file($_FILES[$__arr["file"]]["tmp_name"][$key],$__arr["dirname"] . $code . "." . $ext);
                    array_push($__arr_file_ext,$ext);
                } 
                $i++;
            }
            return $__arr_file_ext;
        } else {
            return ['msg' => 'not_ok','error' => 'Missing parameter. Check again !'];
        }
    }
?>