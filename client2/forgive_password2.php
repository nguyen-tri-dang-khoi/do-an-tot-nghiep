<?php
    include_once 'db.php';
    include_once 'include/head.php';
    $conn = connect();
    $forgive_password_token = isset($_REQUEST['forgive_password_token']) ? $_REQUEST['forgive_password_token'] : null;
    if($_SESSION['forgive_password']['forgive_password_token'] == $forgive_password_token) {
        echo "
        <script>
            $.alert({
                title: 'Thông báo',
                content: 'Bạn đã xác thực email thành công, Vui lòng thiết lập lại mật khẩu',
            })
        </script>";
        include_once 'change_password.php';
    } else {
        echo "
        <script>
            $.alert({
                title: 'Thông báo',
                content: 'Token này đã hết hạn',
                buttons: {
                    'Ok':function(){
                        location.href='reset_password.php';
                    }
                }
            })
        </script>";
    }
    
?>