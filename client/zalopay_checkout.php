<?php
include_once("checkout_ok.php");
$config = [
  "appid" => 2554,
  "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
  "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
  "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
];
$embeddata = [
  "merchantinfo" => "Nguyễn Trí Đăng Khôi",
  "redirecturl" => "http://localhost/project/client/payment_history_ok.php?status_type=zalopay&order_code=" . $_SESSION['client_order_code'],
  
];
// set product_detail
$items = isset($_REQUEST['items']) ? $_REQUEST['items'] : null;
$items = json_decode($items);
$order_desc = isset($_REQUEST['order_desc']) ? $_REQUEST['order_desc'] : null;
$amount = $total;
//print_r($items);
$item_result = [];
$i = 0;
for($i = 0 ; $i < count($items) ; $i++) {
    $item_result[$i]['itemid'] = $items[$i]->pi_id;
    $item_result[$i]['itemname'] = $items[$i]->pi_name;
    $item_result[$i]['itemprice'] = $items[$i]->pi_price;
    $item_result[$i]['itemquantity'] = $items[$i]->pi_count;
}
$items = json_encode($item_result);
//print_r($items);
/*$items = [
  [ "itemid" => "knb", "itemname" => "kim nguyen bao", "itemprice" => 198400, "itemquantity" => 1 ]
];*/
$order = [
  "app_id" => $config["appid"],
  "app_time" => round(microtime(true) * 1000), // miliseconds
  "app_trans_id" => date("ymd")."_".$_SESSION['client_order_code'], // mã giao dich có định dạng yyMMdd_xxxx
  "app_user" => "demo",
  "item" => $items,
  "embed_data" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
  "amount" => $amount,
  "description" => $order_desc,
  "bank_code" => ""
];

// appid|apptransid|appuser|amount|apptime|embeddata|item
$data = $order["app_id"]."|".$order["app_trans_id"]."|".$order["app_user"]."|".$order["amount"]
  ."|".$order["app_time"]."|".$order["embed_data"]."|".$order["item"];
$order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

$context = stream_context_create([
  "http" => [
    "header" => "Content-type: application/x-www-form-urlencoded\r\n",
    "method" => "POST",
    "content" => http_build_query($order)
  ]
]);

$resp = file_get_contents($config["endpoint"], false, $context);
$result = json_decode($resp, true);
log_a($result);
if($result['return_code'] == 1) {
  
  header('Location: ' . $result['order_url']);
  die();
}
?>