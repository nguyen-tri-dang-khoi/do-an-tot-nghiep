<?php
    include_once 'db.php';
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
    if(!$id) {
        header("Location:TNC_news.php");
        exit();
    }
    $conn = connect();
    $sql_notification = "select * from notification where id = $id limit 1";
    $result_notification = mysqli_query($conn,$sql_notification);
    $row_notification = mysqli_fetch_array($result_notification);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Css/StyleCss.css">
    <style>
        .container-ok {
            padding-top:35px;
            width: 814px;
            margin: auto;
        }
        .title-info {
            color: #979797;
            
        }
        .title-ct {
            margin-bottom:20px;
            font-weight: 700;
            font-size: 36px;
            color: #000;
        }
        .d-flex {
            display:flex;
        }
        .a-center {
            align-items:center;
        }
        .f-columns {
            flex-direction:column;
        }
        .j-between {
            justify-content:space-between;
        }
        .i-bg {
            color:#fff;
            padding:5px;
            margin-left:10px;
            width:30px;
            text-align:center;
        }
        .i-yt-bg {
            background-color:#f9495f;
            
        }
        .i-fb-bg {
            background-color:#005ec4;
            
        }
        .i-tw-bg {
            background-color:#68aefa;
            
        }
        .i-pin-bg {
            background-color:#ff8f02;
            
        }
    </style>
    <?php include_once ('include/head.php'); ?>
</head>
<body>
<?php include_once ('include/menu.php');?>
    <div class="container-ok">
        <h2 class="title-ct"><?php echo $row_notification['title'];?></h2>
        <div class="title-info d-flex a-start j-between">
            <p><?php echo Date("d-m-Y",strtotime($row_notification['created_at']));?></p>
            <div class="title-icn">
                <i class="fa-brands fa-youtube i-bg i-yt-bg"></i>
                <i class="fa-brands fa-facebook-f i-bg i-fb-bg"></i>
                <i class="fa-brands fa-twitter i-bg i-tw-bg"></i>
                <i class="fa-brands fa-pinterest-p i-bg i-pin-bg"></i>
            </div>
        </div>
        <div class="desc">
            <?php echo $row_notification['content'];?>
        </div>
    </div>
    <?php include_once ('js/js_customIndex.php'); ?>                       
    <?php include_once ('include/footer.php'); ?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>