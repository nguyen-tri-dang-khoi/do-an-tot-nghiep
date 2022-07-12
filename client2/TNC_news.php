<?php 
    include_once 'db.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<?php
    $conn = connection();
    $_SESSION['notifcation'] = isset($_SESSION['notifcation']) ? $_SESSION['notifcation'] : [];
?>
<body>
    <?php include_once ('include/menu.php');?>
    <div class="news__content row col-10 m-auto mt-5 mb-5">
        <?php row['id'] ?>
    </div>
    <?php include_once ('include/footer.php'); ?>
    <!-- <script src = '../js/toast.min.js' > </script> -->
    <script type = "text/javascript" src = "//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"> </script>
    <?php include_once ('js/js_customIndex.php'); ?>
</body>
</html>
