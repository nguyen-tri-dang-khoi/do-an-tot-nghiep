<?php
    // read data more row
    function db_query($sql = "",$__arr = [],$conn = NULL){  
        if(!$conn) {
            $conn = $GLOBALS['link'];
        }
        $stmt = $conn->prepare($sql); 
        if($stmt->execute($__arr)){
            return $stmt->fetchAll();
        }
    }
    // read data one row
    function fetch_row($sql = "",$__arr = [],$conn = NULL) {
        if(!$conn) {
            $conn = $GLOBALS['link'];
        }
        $stmt = $conn->prepare($sql);
        if($stmt->execute($__arr)){
            $bool = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$bool) {
                return [];
            }
            return $bool;
        }
    }
    function db_insert($table_name,$__arr,&$conn = NULL){
        if(!$conn) {
            $conn = $GLOBALS['link'];
        }
        $_str_insert = "INSERT INTO $table_name SET ";
        foreach($__arr as $key => $value) {
            $_str_insert .= "$key = ?,";
        }
        $_str_insert = substr($_str_insert ,0 , -1);
        $stmt = $conn->prepare($_str_insert);
        return $stmt->execute(array_values($__arr));
    }
    function db_insert_id($table_name,$__arr,$conn = NULL){
        return (db_insert($table_name,$__arr,$conn)) ? $conn->lastInsertId() : 0;
    }
    function db_insert_more_row($table_name, $__arr = [], $__arr_values = [],$conn = NULL) {
        if(!$conn) {
            $conn = $GLOBALS['link'];
        }
        $str_insert_query = ins_more_row($table_name, $__arr, count($__arr_values));
        $stmt = $conn->prepare($str_insert_query);
        return ($stmt->execute(call_user_func_array("array_merge",$__arr_values))) ? $conn->lastInsertId() : 0;  
    }
    // update data
    function db_update($table_name,$__arr,$where_clause,$__arr_value_where_clause = NULL,$conn = NULL){
        if(!$conn) {
            $conn = $GLOBALS['link'];
        }
        $_str_update = "UPDATE " . $table_name . " SET ";
        foreach($__arr as $key => $value) {
            $_str_update .= "$key = ?,";
        }
        $_str_update = substr($_str_update,0,-1);
        $_str_update .= " WHERE " . $where_clause;
        $stmt = $conn->prepare($_str_update);
        return $stmt->execute(array_merge(array_values($__arr),$__arr_value_where_clause));
    } 
    // update data by id
    function db_update_by_id($table_name,$__arr,$__arr_id = NULL,$conn = NULL){
        return db_update($table_name,$__arr,"id = ?",$__arr_id,$conn);
    }
    // delete data
    function db_delete($table_name,$where_clause,$__arr_value_where_clause = NULL,$conn = NULL){
        if(!$conn) {
            $conn = $GLOBALS['link'];
        } 
        $_str_delete = "DELETE FROM $table_name" . " WHERE " . $where_clause;
        $stmt = $conn->prepare($_str_delete);
        return $stmt->execute($__arr_value_where_clause);
    }
    // delete data by id
    function db_delete_by_id($table_name,$__arr_id,$conn = NULL){
        return db_delete($table_name,"id = ?",$__arr_id,$conn);
    }
?>