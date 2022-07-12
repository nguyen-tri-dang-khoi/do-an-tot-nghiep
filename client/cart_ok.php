<?php
    include_once 'db.php';
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null; 
    $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : null;
    $img = isset($_REQUEST['img']) ? $_REQUEST['img'] : null;
    $price = isset($_REQUEST['price']) ? $_REQUEST['price'] : null;
    $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $conn = connect();
    if($thao_tac == 'addCart') {
        if(isset($_SESSION['cart']))
        {
            // rang buoc so luong san pham ton kho
            $sql_get_count = "select count from product_info where id = $id and is_delete like 0 and is_active like 1 limit 1";
            $result = mysqli_query($conn,$sql_get_count);
            $row = mysqli_fetch_array($result);
            if(isset($_SESSION['cart'][$id])){
                if($row['count'] >= $count + $_SESSION['cart'][$id]['count']) {
                    $_SESSION['cart'][$id]['count'] += $count;
                } else {
                    echo json_encode(["msg" => "out_of_stock"]);
                    exit();
                }
            } else {
                if($row['count'] >= $count) {
                    $_SESSION['cart'][$id] = ['count' => $count,'price' => $price,'name' => $name,'img' => $img];
                } else {
                    echo json_encode(["msg" => "out_of_stock"]);
                    exit();
                }
            }
            //
        }
    } else if($thao_tac == 'updateInfoCart') {
        $_SESSION['cart'][$id]['count'] = $count;
    } else if($thao_tac == 'deleteCart') {
        unset($_SESSION['cart'][$id]);
    } else if($thao_tac == 'loadCart') {
        echo json_encode(['msg' => 'ok','cart' => $_SESSION['cart']]);
        exit();
    } else if($thao_tac == 'deleteAllCart') {
        unset($_SESSION['cart']);
    }
    echo json_encode(['msg' => 'ok','cart' => $_SESSION['cart']]);
    exit();
?>