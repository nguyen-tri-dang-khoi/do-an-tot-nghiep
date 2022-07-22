<?php
    include_once("../lib/database.php");
    // logout_session_timeout();
    // check_access_token();
    // redirect_if_login_status_false();
    $paging = isset($_REQUEST['paging']) ? $_REQUEST['paging'] : 5;
    $id = $_SESSION['id'];
    $sql = "Update user set paging = ? where id = ?";
    sql_query($sql,[$paging,$id]);
    $_SESSION['paging'] = $paging;
    echo "<script>window.history.back();</script>";
    exit();
?>