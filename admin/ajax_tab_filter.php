<?php
    include_once("../lib/database_v2.php");
    $tab_name = isset($_REQUEST['tab_name']) ? $_REQUEST['tab_name'] : null;
    $tab_urlencode = isset($_REQUEST['tab_urlencode']) ? $_REQUEST['tab_urlencode'] : null;
    $tab_unique = uniqid("tab_");
    $_SESSION['tab'] = isset($_SESSION['tab']) ? $_SESSION['tab'] : [];
    array_push($_SESSION['tab'],[
        "tab_unique" => $tab_unique,
        "tab_name" => $tab_name,
        "tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique",
    ]);
    echo_json(["msg" => "ok","tab_urlencode" => $tab_urlencode . "&tab_unique=$tab_unique"]);
?>