<?php
    include_once("checkout_ok.php");
    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //var_export(curl_getinfo($ch));
        //execute post
        //var_export(curl_getinfo($ch));
        $result = curl_exec($ch);
        //var_dump(curl_errno($ch));
        //var_dump(curl_getinfo($ch,CURLINFO_HTTP_CODE));
        //close connection
        //print_r(curl_error($ch));
        //print_r($result);
        //curl_close($ch);
        return $result;
    }
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = 'MOMOSVIJ20220504';
    $accessKey = 'hg9eCBV4EgbTExUe';
    $serectKey = 'AAn1kT7790aIiw9EtHDyrnKjUqynXIWl';
    $redirectUrl = "http://localhost/project/client/payment_history_ok.php?status_type=momo&order_code=" . $_SESSION['client_order_code'];
    $ipnUrl = "http://localhost/project/client/payment_history_ok.php?status_type=momo&order_code=" . $_SESSION['client_order_code'];
    $extraData = "";
    $order_desc = $_POST["order_desc"];
    $amount = $total;
    $requestId = time() . "";
    $requestType = "captureWallet";
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $_SESSION['client_order_code'] . "&orderInfo=" . $order_desc . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    $signature = hash_hmac("sha256", $rawHash, $serectKey);
    $data = array(
        'partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $_SESSION['client_order_code'],
        'orderInfo' => $order_desc,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    );
    $result = execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);  // decode json
    if($jsonResult['resultCode'] == 0) {
        header('Location: ' . $jsonResult['payUrl']);
    } 
    
?>