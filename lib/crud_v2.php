<?php
    function sql_query($sql = "",$__arr = []) {
        //print_r($sql);
        $pdo = $GLOBALS['link']->prepare($sql);
        if($pdo->execute($__arr)) return $pdo;
        return false;   
    }
    function countt($pdo) {
        return $pdo->rowCount();
    }
    function fetch($pdo) {
        return $pdo->fetch(PDO::FETCH_ASSOC);
    }
    function fetch_all($pdo) {
        return $pdo->fetchAll(PDO::FETCH_ASSOC);
    }
    function ins_id() {
        return $GLOBALS['link']->lastInsertId();
    }
    function generate_sql_ins_more_row($table_name, $__arr = [] , $values = []) {
        $binding = array_fill(0,count($__arr),"?");
        $str_binding = array_fill(0,count($values),"(" . implode(",",$binding) . ")");
        $str_binding = implode(",",$str_binding);
        $str = "INSERT INTO {$table_name}" . "(" . implode(",",$__arr) . ")" . " VALUES {$str_binding}";
        return $str;
    }
    function ins($table_name,$__arr = []) {
        $key = implode(",",array_keys($__arr));
        $question = implode(",",array_fill(0,count($__arr),'?'));
        $sql = "INSERT INTO {$table_name}({$key}) VALUES ({$question})";
        return sql_query($sql,array_values($__arr));
    }
    function ins_more($table_name,$__arr = []){
        $values = $__arr['values'];
        unset($__arr['values']);
        
        $sql = generate_sql_ins_more_row($table_name, $__arr,$values);
        $valuess = call_user_func_array("array_merge",$values);
        return sql_query($sql,$valuess);
    }
    function upt_set($a) {
        return "{$a} = ?";
    }
    function upt_id($table_name,$__arr = [],$id) {
        $new_arr = array_map("upt_set",array_keys($__arr));
        $sql = "UPDATE {$table_name} SET " . implode(",",$new_arr) . " WHERE id = ?";
        return sql_query($sql,array_merge(array_values($__arr),[$id]));
    }
    function upt_more($table_name,$__arr = [],$where_clause = "") {
        $new_where = $__arr['where'];
        print_r($new_where);
        unset($__arr['where']);
        $new_arr = array_map("upt_set",array_keys($__arr));
        $sql = "UPDATE {$table_name} SET " . implode(",",$new_arr) . " WHERE {$where_clause}";
        return sql_query($sql,array_merge(array_values($__arr),array_values($new_where)));
    }
    function del_id($table_name,$id) {
        $sql = "DELETE FROM {$table_name} WHERE id = ?";
        return sql_query($sql,[$id]);
    }
    function del_more($table_name,$__arr_value = [],$where_clause) {
        $sql = "DELETE FROM {$table_name} WHERE {$where_clause}";
        return sql_query($sql,$__arr_value);
    }
    function api_get_data(){
        return (array)json_decode(file_get_contents("php://input"));
    }
    function api_no_select($pdo,$cases = [1,2,3],$message = "",$data = ""){
        $__arr = [];
        foreach($cases as $case) {
            switch($case) {
                case 1 : $__arr['count'] = $pdo->rowCount();break;
                case 2 : $__arr['msg'] = $message;break;
                case 3 : $__arr['input'] = $input;break;
                default: break;
            }
        }
        return json_encode($__arr);
    }
    function api_select($pdo,$cases = [1,2,3,4,5],$message = "",$data = ""){
        $__arr = [];
        foreach($cases as $case) {
            switch($case) {
                case 1 : $__arr['count'] = $pdo->rowCount();break;
                case 2 : $__arr['rows'] = $pdo->fetchAll(PDO::FETCH_ASSOC);break;
                case 3 : $__arr['row'] = $pdo->fetch(PDO::FETCH_ASSOC);break;
                case 4 : $__arr['msg'] = $message;break;
                case 5 : $__arr['input'] = $input;break;
                default: break;
            }
        }
        return json_encode($__arr);
    }
?>