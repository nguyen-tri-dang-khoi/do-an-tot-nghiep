<?php
    
    include_once("../lib/database_v2.php");
    $case = isset($_REQUEST["case"]) ? $_REQUEST["case"] : null;
    $year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : null;
    $month = isset($_REQUEST["month"]) ? $_REQUEST["month"] : null;
    $day = isset($_REQUEST["day"]) ? $_REQUEST["day"] : null;
    $k_word = "";
    $where = "where 1 = 1";
    $select = "";
    $gb = "GROUP BY";
    $_data = [];
    $_label = [];
    $max_label = -1;
    $max_data = -1;
    // Thống kê doanh thu
    if($case == "1") {
        if($year) {
            $where .= " and YEAR(orders.created_at)='$year'";
            $select = "MONTH(orders.created_at) as 'month'";
            $max_label = 12;
            $gb = "GROUP BY MONTH(orders.created_at)";
            $k_word = "month";
        }
        if($month) {
            $where .= " and MONTH(orders.created_at)='$month'";
            $select = "DAY(orders.created_at) as 'day'";
            if(in_array($month,[1,3,5,7,8,10,12])) {
                $max_label = 31;
            } else if(in_array($month,[4,6,9,11])){
                $max_label = 30;
            } else if($month == 2) {
                if(($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0){
                    $max_label = 29;
                }
                $max_label = 28;
            }
            $gb = "GROUP BY MONTH(orders.created_at),DAY(orders.created_at)";
            $k_word = "day";
        }
        if($day) {
            $where .= " and DAY(orders.created_at)='$day'";
            $select = "HOUR(orders.created_at) as 'hour'";
            $max_label = 24;
            $gb = "GROUP BY MONTH(orders.created_at),DAY(orders.created_at),HOUR(orders.created_at)";
            $k_word = "hour";
        }
        for($i = 1 ; $i <= $max_label ; $i++) {
            array_push($_data,0);
        }
        $sql_case_1 = "select $select,sum(orders.total) as 'sum' from orders $where $gb";
        $pdo_statistic = sql_query($sql_case_1);
        foreach(fetch_all($pdo_statistic) as $row) {
            $_data[$row[$k_word] - 1] = $row['sum'];
        }
        
        $_data['label'] = $max_label;
        print_r(json_encode(array_merge(["msg" => "ok"],$_data)));
        exit();
        /*log_v($sql_case_1);
        log_a($_data);
        log_a($_label);*/
    }
?>
