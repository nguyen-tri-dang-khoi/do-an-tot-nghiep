<?php
    function api_generate_json($sql,$root_name = "records",$value_binding = []) {
        $results = db_query($sql,$value_binding);
        $row = [];
        $rows = [];
        $rows[$root_name] = [];
        foreach($results as $result) {
            foreach($result as $key => $value) {
                if(!is_numeric($key)) {
                    $row[$key] = $value;
                }
            }
            array_push($rows[$root_name],$row);
        }   
        if(count($rows[$root_name]) > 0 ) {
            http_response_code(200); // 200 Ok
            return json_encode($rows);
        } else {
            http_response_code(404); // 404 Not found
            return json_encode(["msg" => "not_ok","error" => "No record founds"]);
        }
    }
    function api_fetch_one_json($sql,$value_binding = [],$success = "Success",$error = "Error") {
        $one_row = fetch_row($sql,$value_binding);
        //extract($one_row);
        $row = [];
        foreach($one_row as $key => $value) {
            if(!is_numeric($key)) {
                $row[$key] = $value;
            }
        }
        if($one_row) {
            http_response_code(200); // 200 Ok
            return json_encode(array_merge(["msg" => "ok","success" => $success],$row));
        } else {
            http_response_code(404); // 404 Not found
            return json_encode(["msg" => "not_ok","error" => $error]);
        }
    }
    function api_fetch_one_json_print($sql,$value_binding,$success = "Success",$error = "Error") {
        print_r(api_fetch_one_json($sql,$value_binding));
        exit();
    }
    function api_generate_json_print($sql,$root_name = "records",$value_binding = []) {
        print_r(api_generate_json($sql,$root_name,$value_binding));
        exit();
    }
    function api_paging_json($sql,$sql_get_count,$page_url,$num_page,$page,$value_binding = [], $root_name = "records",$root_page = "pages") {
        $from = ($page - 1) * ($num_page);
        $data_arr = [];
        $data_arr["records"] = [];
        $data_arr["paging"] = [];
        $total = fetch_row($sql_get_count)["total"];
        // url page
        // paging
        $paging_arr = [];
        $paging_arr['first'] = $page > 1 ? "{$page_url}page=1" : "";
        // count all products in the database to calculate total pages
        $range = 2;
        $total_pages = ceil($total / $num_page);
    
        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
        $paging_arr['pages']=array();
        $page_count=0;
        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
                $page_count++;
            }
        }
        // button for last page
        $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";
        $data_arr["paging"] = $paging_arr;

        // read paging function method
        $data_arr["records"] = json_decode(api_generate_json($sql,"records",array_merge($value_binding,[$from,$num_page])))->records;
        $data_arr["sql"] = $sql;
        return json_encode($data_arr);
    }
    
    function api_insert_json_arg_array($table_name,$data,$success = "Success",$error = "Error") {
        if(db_insert($table_name,(array)$data)) {
            http_response_code(201); // created
            return json_encode(array_merge(["msg" => "ok","success" => $success],(array)$data));
        } else {
            if(!$data) {
                http_response_code(400); // 400 bad request
            } else {
                http_response_code(503); // 503 service unavailable
            }
            return json_encode(array_merge(["msg" => "not_ok","error" => $error],(array)$data));
        }
    }
    function api_insert_json($table_name,$success = "Success",$error = "Error"){
        $data = json_decode(file_get_contents("php://input"));
        api_insert_json_arg_array($table_name,(array)$data,$success,$error);
    }
    function api_update_json_arg_array($table_name,$data,$where_clause,$value_binding = NULL,$success = "Success",$error = "Error") {
        if(db_update($table_name,(array)$data,$where_clause,$value_binding)) {
            http_response_code(201); // created
            return json_encode(array_merge(["msg" => "ok","success" => $success],(array)$data));
        } else {
            if(!$data) {
                http_response_code(400); // 400 bad request
            } else {
                http_response_code(503); // 503 service unavailable
            }
            return json_encode(array_merge(["msg" => "not_ok","error" => $error],(array)$data));
        }
    }
    
    function api_update_json($table_name,$where_clause,$value_binding,$success="Success",$error="Error") {
        $data = json_decode(file_get_contents("php://input"));
        api_update_json_arg_array($table_name,$data,$where_clause,$value_binding,$success,$error);
    }
    function api_update_by_id_json($table_name,$success="Success",$error="Error") {
        $data = json_decode(file_get_contents("php://input"));
        $data = (array)$data;
        //print_r($data);
        $id = isset($data["id"]) ? $data["id"] : null;
        if($id) {
            return api_update_json_arg_array($table_name,$data,"id = ?",[$id],$success,$error);
        } else {
            return json_encode(["msg" => "not_ok","error" => "ID NOT FOUND"]);
        }
    }
    function api_delete_json_arg_array($table_name,$where_clause,$value_binding,$success="Success",$error="Error") {
        if(db_delete($table_name,$where_clause,$value_binding)) {
            http_response_code(201); // created
            return json_encode(array_merge(["msg" => "ok","success" => $success]));
        } else {
            if(!$data) {
                http_response_code(400); // 400 bad request
            } else {
                http_response_code(503); // 503 service unavailable
            }
            return json_encode(array_merge(["msg" => "not_ok","error" => $error],(array)$data));
        }
    }
    function api_delete_json($table_name,$where_clause,$success,$error) {
        $data = json_decode(file_get_contents("php://input"));
        api_delete_json_arg_array($table_name,$where_clause,(array)$data,$success,$error);
    }
    function api_delete_by_id_json($table_name,$success="Success",$error="Error") {
        $data = json_decode(file_get_contents("php://input"));
        $data = (array)$data;
        //print_r($data);
        $id = isset($data["id"]) ? $data["id"] : null;
        if($id) {
            return api_delete_json_arg_array($table_name,"id = ?",[$id],$success,$error);
        } else {
            return json_encode(["msg" => "not_ok","error" => "ID NOT FOUND"]);
        }
        
    }
?>