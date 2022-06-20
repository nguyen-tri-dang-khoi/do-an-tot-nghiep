<body>
<?php
    $url = get_url_current_page();
    $shipper_id = isset($_SESSION['shipper_id']) ? $_SESSION['shipper_id'] : null;
    $sql_shipper_info = "select * from user where type = 'shipper' and id = '$shipper_id' limit 1";
    $result = fetch(sql_query($sql_shipper_info));
?>
<style>
    .root-color {
        color: #d9585c;
    }
    .sidebar-collapse nav {
        margin-left:-25px !important;
    }
    .kh-padding {
        transition: margin-left .3s ease-in-out;
    }
    .sidebar-collapse .kh-padding {
        margin-left:300px !important;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .sidebar-collapse .content-wrapper,
    .sidebar-collapse aside {
        margin-left:-300px !important;
    }
    .content-wrapper {
        background-color:#fff;
        
    }
    .root-bg-color {
        background-color: #ffeaea;
    }
    .d-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .ul-menu {
        list-style-type:none;
        padding:0;
        margin:0;
    }
    .ul-menu .li-menu a:hover {
        text-decoration:underline;
    }
    .ul-menu .li-menu {
        background-color:#d9585c;
        /*border-radius:18px;*/
    }
    .ul-menu .li-menu a {
        display:block;
        font-size:17px;
        font-weight:bold;
        color:#fff;
        width:100%;
        padding: 12px 23px;
        margin:0 auto;
    }
    .kh-main-info h5 {
        font-weight:bold;
        font-size:18px;
        color:black;
    }
    .ul-menu .li-active {
        background-color:#fff;
        border-right: 6px solid #d9585c;
    }
    .ul-menu .li-active a {
        color:#d9585c;
        cursor:default;
        pointer-events:none;
    }
    .ul-menu .li-active a:hover{
        text-decoration:none;
    }
    .kh-nav-cst {
        height:50px;
    }
    .container-fluid {
        margin-left: -8px;
    }
    .sidebar-open .kh-padding {
        margin-left:300px !important;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .sidebar-open nav {
        margin-left:-25px !important;
    }
    .sidebar-open .main-sidebar {
        display:block;
    }
    .sidebar-open .content-wrapper {
        margin-left:-300px !important;
    }
</style>
<style>
    @media only screen and (max-width: 600px) {
        td > img {
            width:100px !important;
            height:100px !important;
        }
    }
    @media only screen and (max-width: 500px) {
        h1 {
            font-size:16px !important;
        }
        h2 {
            font-size:15px !important;
        }
        h3 {
            font-size:14px !important;
        }
        h4,h5,h6 {
            font-size:13px !important;
        }
        p,label,input.form-control,th,td,button,li > a,a {
            font-size:11px !important;
        }
        input.form-control {
            padding:4px 10px;
            height:30px;
        }
        
        button[class='close'] {
            font-size:18px !important;
        }
        .list-row form {
            width:200px !important;
        }
        .form-group {
            margin-bottom:.3rem;
        }
        .list-row form label,.form-group label {
            margin-bottom:0px;
        }
        .list-row form input {
            height: 1.5rem;
        }
        .main-sidebar {
            width:210px;
        }
        input[placeholder] {
            font-size:10px !important;
        }
        .main-sidebar {
            width:220px !important;
        }
        .main-header a[data-widget='pushmenu'] {
            height:2rem;
        }
        
    }
    @media only screen and (max-width: 400px) {
        p,label,input,th,td,button,li > a,a {
            font-size:10px !important;
        }
    }
    @media only screen and (max-width: 378px) {
        th,td {
            font-size:9px !important;
        }
        th.w-300 {
            width:100px !important;
        }
    }
</style>
<div class="wrapper">
    <aside class="main-sidebar root-bg-color" style="width:274px;">
        <div class="sidebar" style="min-height:100vh;">
            <div class="kh-main-info text-center mt-15">
                <h2 class="kh-logo"></h2>
                <hr style="background-color:#d9585c;">
                <div class=" d-flex a-center">
                    <div class="image">
                        <img style="border-radius:50%;width:40px;height:40px;" src=<?=$_SESSION["shipper_img_name"] ? "../admin/".$_SESSION["shipper_img_name"] : "upload/image.png";?> class="img-circle elevation-2" alt="">
                    </div>
                    <h5 class="kh-title ml-10" style="margin-right:0px;margin-bottom:0px;"><?=$result['email']?></h5>
                </div>
                <hr style="background-color:#d9585c;">
            </div>
            <div class="kh-main-siderbar">
                <ul class="ul-menu">
                    <li class="li-menu mt-15 <?=strpos($url,'index.php') ? "li-active" :"";?>"><a href="index.php"><span class="fas fa-tachometer-alt" style="margin-right:5px;"></span> Tổng quan</a></li>
                    <li class="li-menu mt-15 <?=strpos($url,'information.php') ? "li-active" :"";?>"><a href="information.php"><span class="fas fa-address-card" style="margin-right:5px;"></span> Thông tin cá nhân</a></li>
                    <li class="li-menu mt-15 <?=strpos($url,'shipper_order.php') ? "li-active" :"";?>"><a href="shipper_order.php"><span class="fas fa-shipping-fast" style="margin-right:5px;"></span> Quản lý đơn giao hàng</a></li>
                    <li class="li-menu mt-15"><a href="logout.php"><span class="fas fa-sign-out-alt" style="margin-right:5px;"></span> Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <nav class="kh-nav-cst main-header navbar navbar-expand navbar-white navbar-light">
        <a class="nav-link ml-15" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars root-color"></i>
        </a>
        <ul class="navbar-nav ml-auto list-row">
            <li>
                <form action="paging_ok.php" method="post" style="display: flex;align-items: center;justify-content: space-between;width: 230px;">
                    <label for="">Số dòng: </label>
                    <input tabindex="-1"  class="form-control" style="width:100px;" type="number" name="paging" value="<?=$_SESSION['shipper_paging'];?>">
                    <button tabindex="-1" class="dt-button button-grey">Lưu</button>
                </form>
            </li>
        </ul>
    </nav>

