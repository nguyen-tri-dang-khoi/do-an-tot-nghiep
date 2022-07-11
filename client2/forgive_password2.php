<?php
    include_once 'db.php';
    include_once 'include/head.php';
    $conn = connect();
    $forgive_password_token = isset($_REQUEST['forgive_password_token']) ? $_REQUEST['forgive_password_token'] : null;
    print_r($_SESSION['forgive_password']['forgive_password_token']);
    if($_SESSION['forgive_password']['forgive_password_token'] == $forgive_password_token) {
        echo "
        <script>
            $.alert({
                title: 'Thông báo',
                content: 'Bạn đã xác thực email thành công, Vui lòng thiết lập lại mật khẩu',
                buttons: {
                    'Ok': function() {
                        location.href='reset_password.php';
                    }
                }
            })
        </script>";
    }
?>