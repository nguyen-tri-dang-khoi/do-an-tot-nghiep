<?php
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    if($status == 'FILTER') {
        $price_min = isset($_REQUEST['price_min']) ? $_REQUEST['price_min'] : null;
        $price_max = isset($_REQUEST['price_max']) ? $_REQUEST['price_max'] : null;
        $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
        $where = "where 1=1 ";
        if($price_min) {
            $where .= " and price >= '$price_min'";
        }
        if($price_max) {
            $where .= " and price >= '$price_max'";
        }
        if($keyword) {
            $where .= " and lower(product_info.name) like lower('%keyword%')";
        }
        if($rate) {
            $where .= " and product_info.id in (select product_rate.id from product_rate where product_rate.rate='$rate')";
        }
        $sql_price = "select * from product_info $where";
        $result = sql_query($sql_price);
    }
?>