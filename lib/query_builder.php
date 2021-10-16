<?php
    define("INNER_JOIN",'INNER JOIN');
    define("LEFT_JOIN",'LEFT JOIN');
    define("ON","ON");  
    define("LIMIT","LIMIT");
    define("WHERE","WHERE");
    define("GROUP_BY","GROUP BY");
    define("HAVING","HAVING");
    define("__AND","AND");
    define("__OR","OR");
    define("SELECT","SELECT");
    define("FROM","FROM");
    define("ORDER_BY","ORDER BY");
    define("ASC","ASC");
    define("DESC","DESC");
    define("__IN","IN");
    //--------------===khoi-handsome===---------------//
    function s($__arr = []){ // s(['a','b','c']) output: select a, b, c 
        return SELECT . " " . implode(",",$__arr) . " ";
    }
    function f($__arr) { // f['product'] output: from product
        return FROM . " " . implode(",",$__arr) . " ";
    }
    function ij($__arr) { // ij(['admin','user']) output: from admin inner join user
        return FROM . " " . implode(" " . INNER_JOIN . " ",$__arr) . " ";
    }
    function lj($__arr) { // lj(['admin','user']) output: from admin left join user
        return FROM . " " . implode(" " . LEFT_JOIN . " ",$__arr) . " ";
    }
    function _ij($__arr) {
        return implode(" " . INNER_JOIN . " ",$__arr) . " ";
    }
    function _lj($__arr) {
        return implode(" " . LEFT_JOIN . " ",$__arr) . " ";
    }
    function w($__arr = []) { // w(['a','>']) output: where a > ?
        return WHERE . " " . implode(" ",$__arr) . " ? ";
    }
    function w_a($__arr2) { // w_a([['a','>'],['b','=']]) output: where a > ? and b = ?
        foreach($__arr2 as &$arr) {
            $arr = implode(" ",$arr) . " ? ";
        }
        return WHERE . " " . implode( " " . __AND . " " , $__arr2) . " ";
    }
    function w_o($__arr2) { // w_o([['a','>'],['b','=']]) output: where a > ? or b = 
        foreach($__arr2 as &$arr) {
            $arr = implode(" ",$arr) . " ? ";
        }
        return WHERE . " " . implode( " " . __OR . " " , $__arr2);
    }
    function w_i($__arr) { // w_i(['admin.id',1,2,3,4]) output: where admin.id in (1,2,3,4)
        $column = $__arr[0];
        array_shift($__arr);
        return WHERE . " " . $column . " " . __IN . " " . "(" . implode(",",$__arr) . ")" . " ";
    }
    function o($__arr) { // o(['admin.id','user.admin_id']) output: on admin.id = user.admin_id
        return ON . " " . implode(" = ",$__arr) . " ";
    }
    function o_a($__arr2) { // o_a([['admin.month','user.month'],['admin.year','user.year']]) output: on admin.month = user.month and admin.year = user.year
        foreach($__arr2 as &$arr) {
            $arr = implode(" = ",$arr) . " ";
        }
        return ON . " " . implode( " " . __AND . " " , $__arr2) . " ";
    }
    function o_o($__arr2) { // o_o([['admin.month','user.month'],['admin.year','user.year']]) output: on admin.month = user.month or admin.year = user.year
        foreach($__arr2 as &$arr) {
            $arr = implode(" = ",$arr) . " ";
        }
        return ON . " " . implode( " " . __OR . " " , $__arr2) . " ";
    }
    function gb($__arr) { // gb(['admin.id','user.id','shipper.id']) output: // group by admin.id, user.id, shipper.id
        return GROUP_BY . " " . implode(",",$__arr) . " ";
    }
    function h($__arr) { // h(['count(admin.id)','>']) output: having count(admin.id) > ?
        return HAVING . " " . implode(" ",$__arr) . " ? ";
    }
    function asc($__arr) { // asc(['a']) output: order by a asc// asc(['a']) output: order by a asc
        return ORDER_BY . " " . implode(" ",$__arr) . " " . ASC . " ";
    }
    function desc($__arr) { // desc(['a']) output: order by a desc
        return ORDER_BY . " " . implode(" ",$__arr) . " " . DESC . " ";
    }
    function lim($__arr) {
        return LIMIT . " " . implode(",",$__arr) . " ";
    }
    function ins_more_row($table_name, $__arr = [], $rows_many) {
        $str = "INSERT INTO $table_name" ;
        $str .= "(" . implode(",",$__arr) . ")" . " VALUES ";
        foreach($__arr as &$question) {
            $question = "?";
        }
        $str_question = "(" . implode(",",$__arr) . "),";
        for($i = 0 ; $i < $rows_many ; $i++) {
            $str .= $str_question;
        }
        return substr($str,0,-1);
    }
    function like_search($__arr = []){
        return (is_array($__arr)) ? implode(" LIKE CONCAT('%' , ? , '%') OR ",$__arr) : "";
    }
?>