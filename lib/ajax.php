<?php
    //=====================ajax===================//
    //f_ad
    function echo_json($__arr = []){
        echo json_encode($__arr);
        exit();
    }
    function ajax_db_insert($table_name, $__arr = [],$__arr_result = [],$__arr_error = [], $conn = NULL){
        if(db_insert($table_name,$__arr, $conn)) {
            echo json_encode(array_merge($__arr_result,["msg" => "ok"]));
        } else {
            echo json_encode(array_merge($__arr_error,["msg" => "not_ok"]));
        }
        exit();
    }
    //f_ah
    function ajax_db_insert_id($table_name, $__arr = [],$__arr_result = [],$__arr_error = [], &$conn = NULL){
        if(db_insert($table_name,$__arr,$conn)) {
            echo json_encode(array_merge($__arr_result,["msg" => "ok","id" => $conn->lastInsertId()]));
        } else {
            echo json_encode(array_merge($__arr_error,["msg" => "not_ok"]));
        }
        exit();
    }
    //f_ak
    function ajax_db_update($table_name,$__arr = [],$where_clause,$__arr_value_where_clause = [],$__arr_result = [],$__arr_error = [], $conn = NULL){
        if(db_update($table_name,$__arr,$where_clause,$__arr_value_where_clause)) {
            echo json_encode(array_merge($__arr_result,["msg" => "ok"]));
        } else {
            echo json_encode(array_merge($__arr_error,["msg" => "not_ok"]));
        }
        exit();
    }
    //f_al
    function ajax_db_update_by_id($table_name,$__arr = [],$__arr_id = [],$__arr_result = [],$__arr_error = [],$conn = NULL){
        if(db_update($table_name,$__arr,"id = ?",$__arr_id,$conn)) {
            echo json_encode(array_merge($__arr_result,["msg" => "ok"]));
        } else {
            echo json_encode(array_merge($__arr_error,["msg" => "not_ok"]));
        }
        exit();
    }
    //f_am
    function ajax_db_delete($table_name,$where_clause,$__arr_value_where_clause = NULL,$__arr_result = [],$__arr_error = [], $conn = NULL){
        if(db_delete($table_name,$where_clause,$__arr_value_where_clause)) {
            echo json_encode(array_merge($__arr_result,["msg" => "ok"]));
        } else {
            echo json_encode(array_merge($__arr_error,["msg" => "not_ok"]));
        }
        exit();
    }
    //f_an
    function ajax_db_delete_by_id($table_name,$__arr_id = [],$__arr_result = [],$__arr_error = [],$conn = NULL){
        if(db_delete($table_name,"id = ?",$__arr_id)) {
            echo json_encode(array_merge($__arr_result,["msg" => "ok"]));
        } else {
            echo json_encode(array_merge($__arr_error,["msg" => "not_ok"]));
        }
        exit();
    }
?>