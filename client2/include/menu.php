<?php
    $conn = connect();
    $id_loai_san_pham = isset($_REQUEST['id_loai_san_pham']) ? $_REQUEST['id_loai_san_pham'] : null;
    $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
?>
<header class="container-fluid">
    <div class="headerTop row">
        <div class="col-10 m-auto">
            <div class="headerTop__infoCompany d-flex">
                <p class="# ">
                    Hotline : (024) 3628.8790 - (086) 830.2123 • Email: cskh@tncstore.vn
                </p>
            </div>
        </div>
    </div>
    <div class="headerMain row">
        <div class="col-10 m-auto d-flex justify-content-between">
            <a href="index.php" class="headerMain__logo">
                <img src="IMG/tnc-logo.svg" alt="#" >
            </a>
            <form id="headerMain--form-search" action="Products.php" method="get" class="headerMain__formSearch">
                <div class="formSearch--keyWord">
                    <input type="text" name="keyword" autocomplete="off" placeholder="Nhập sản phẩm cần tìm ..." value="<?php echo $keyword;?>">
                </div>                    
                <div class="formSearch--select">
                    <?php
                    if($id_loai_san_pham) {
                        $sql_get_product_type_name = "select distinct pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id 
                        where pt.is_delete like 0 and pi.is_delete like 0 and pt.is_active and pi.is_active like 1 and pt.id = '$id_loai_san_pham'";
                        //print_r($sql_get_product_type_name);
                        $result33 = mysqli_query($conn,$sql_get_product_type_name);
                        $row33 = mysqli_fetch_array($result33);
                       
                    } 
                    ?>
                    <div class="btn-group">
                        <div class="select_title" class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <p id="menu--name"><?=isset($row33['pt_name']) ? $row33['pt_name'] : "Tất cả danh mục";?></p> <i class="fa-solid fa-sort-down"></i>
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                            
                            <li><a onclick="setText('Tất cả danh mục','')" class="dropdown-item" href="javascript:void(0);">Tất cả danh mục</a><hr></li>
                            <?php
                                
                                $sql_no_child = "select distinct pi.product_type_id as 'pr_type_id',pt.name as 'pt_name' from product_info pi inner join product_type pt on pi.product_type_id = pt.id 
                                where pt.is_delete like 0 and pi.is_delete like 0 and pt.is_active and pi.is_active like 1";
                                $result22 = mysqli_query($conn,$sql_no_child);
                                while($row22 = mysqli_fetch_array($result22)) {
                            ?>
                                    <li onclick="setText('<?php echo $row22['pt_name'];?>','<?php echo $row22['pr_type_id'];?>')"><a class="dropdown-item" href="javascript:void(0);"><?php echo $row22['pt_name'];?></a><hr></li>
                            
                            <?php 
                                } 
                            
                            ?>
                          
                            
                            <!-- <li><a class="dropdown-item" href="#">Tai Nghe True wwireless</a><hr></li>
                            <li><a class="dropdown-item" href="#">Xây dụng cấu hình PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">PRO AUDIO</a><hr></li>
                            <li><a class="dropdown-item" href="#">PRO CREATOR</a><hr></li>
                            <li><a class="dropdown-item" href="#">PRO DUCTIVITY</a><hr></li>
                            <li><a class="dropdown-item" href="#">PRO VR</a><hr></li>
                            <li><a class="dropdown-item" href="#">Máy Tính PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">PRO GAMING PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">Workstation PC - Đồ Họa</a><hr></li>
                            <li><a class="dropdown-item" href="#">Laptops</a><hr></li>
                            <li><a class="dropdown-item" href="#">Laptop Acer</a><hr></li>
                            <li><a class="dropdown-item" href="#">Laptop Asus</a><hr></li>
                            <li><a class="dropdown-item" href="#">Laptop Gaming</a><hr></li>
                            <li><a class="dropdown-item" href="#">Laptop Văn Phòng </a><hr></li>
                            <li><a class="dropdown-item" href="#">Phụ kiện, Balo laptop</a><hr></li>
                            <li><a class="dropdown-item" href="#">Đế Tản Nhiệt Laptop</a><hr></li>
                            <li><a class="dropdown-item" href="#">MACBOOK</a><hr></li>
                            <li><a class="dropdown-item" href="#">Linh Kiện Máy Tính</a><hr></li>
                            <li><a class="dropdown-item" href="#">Mainboard - Bo Mạch Chủ</a><hr></li>
                            <li><a class="dropdown-item" href="#">CPU - Bộ Vi Xử Lý</a><hr></li>
                            <li><a class="dropdown-item" href="#">AMD</a><hr></li>
                            <li><a class="dropdown-item" href="#">Ram - Bộ Nhớ Trong</a><hr></li>
                            <li><a class="dropdown-item" href="#">Ổ Cứng HDD</a><hr></li>
                            <li><a class="dropdown-item" href="#">Ổ Cứng HDD - Laptop</a><hr></li>
                            <li><a class="dropdown-item" href="#">ổ Cứng HDD - PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">VGA - Card Màn Hình</a><hr></li>
                            <li><a class="dropdown-item" href="#">Case - Vỏ Máy Tính</a><hr></li>
                            <li><a class="dropdown-item" href="#">PSU - Nguồn Máy Tính</a><hr></li>
                            <li><a class="dropdown-item" href="#">Ổ Cứng SSD</a><hr></li>
                            <li><a class="dropdown-item" href="#">Màn Hình Máy Tính</a><hr></li>
                            <li><a class="dropdown-item" href="#">Màn Hình Gaming</a><hr></li>
                            <li><a class="dropdown-item" href="#">Bàn Di Chuột</a><hr></li>
                            <li><a class="dropdown-item" href="#">Bàn, Ghế Gaming</a><hr></li>
                            <li><a class="dropdown-item" href="#">Chuột Gaming</a><hr></li>
                            <li><a class="dropdown-item" href="#">Keycaps</a><hr></li>
                            <li><a class="dropdown-item" href="#">MIC - Microphone</a><hr></li>
                            <li><a class="dropdown-item" href="#">Tai Nghe Gaming</a><hr></li>
                            <li><a class="dropdown-item" href="#">Tay Cầm Chơi Game</a><hr></li>
                            <li><a class="dropdown-item" href="#">Thiết Bị Stream</a><hr></li>
                            <li><a class="dropdown-item" href="#">Bàn Phím Cơ</a><hr></li>
                            <li><a class="dropdown-item" href="#">Thiết Bị Mạng</a><hr></li>
                            <li><a class="dropdown-item" href="#">ROUTER WIFI, ACCESSPOINT</a><hr></li>
                            <li><a class="dropdown-item" href="#">MODEM ADSL INTERNET</a><hr></li>
                            <li><a class="dropdown-item" href="#">Card Mạng</a><hr></li>
                            <li><a class="dropdown-item" href="#">PHỤ KIỆN, TẢN NHIỆT PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">Dây kết Nối</a><hr></li>
                            <li><a class="dropdown-item" href="#">Dây Nối Dài - Dây Riser</a><hr></li>
                            <li><a class="dropdown-item" href="#">Giá Đỡ Màn Hình</a><hr></li>
                            <li><a class="dropdown-item" href="#">Giá ĐỠ VGA</a><hr></li>
                            <li><a class="dropdown-item" href="#">Tản Nhiệt Khí PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">Tản Nước ALL in One </a><hr></li>
                            <li><a class="dropdown-item" href="#">Quạt Tản Nhiệt PC</a><hr></li>
                            <li><a class="dropdown-item" href="#">Thiết Bị văn Phòng </a><hr></li>
                            <li><a class="dropdown-item" href="#">Bộ Bàn Phím Chuột Không Dây </a><hr></li>
                            <li><a class="dropdown-item" href="#">Bộ Chia/ Hub USB</a><hr></li>
                            <li><a class="dropdown-item" href="#">Bộ Lưu Điện</a><hr></li>
                            <li><a class="dropdown-item" href="#">Loa Máy Tính</a><hr></li>
                            <li><a class="dropdown-item" href="#">Ổ Đĩa Gắn Ngoài</a><hr></li>
                            <li><a class="dropdown-item" href="#">Phần Mềm Bản Quyền</a><hr></li>
                            <li><a class="dropdown-item" href="#">USB Flash</a><hr></li>
                            <li><a class="dropdown-item" href="#">Webcam</a><hr></li>
                            <li><a class="dropdown-item" href="#">máy In Laser</a><hr></li> -->
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="id_loai_san_pham" value="<?php echo $id_loai_san_pham;?>">
                <div class="formSearch--button">
                    <img onclick="submitFormSearch()" src="IMG/search-icon.svg" alt="#" aria-valuetext="test">
                </div>
            </form>
            <div class="headerMain__cart">     
                <?php include_once("modal_cart.php"); ?>
                <span class="cart--amount">
                    <?php
                        $sum = 0;
                        $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] :[];
                            foreach($_SESSION['cart'] as $cart){
                                $sum += $cart["count"];
                            }
                            echo $sum;
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="headerBottom row">
        <div class=" d-flex col-10 m-auto">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <div class="container-fluid p-0">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse menus" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 dropdown-main">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-computer"></i>DANH MỤC SẢN PHẨM <i class="fa-solid fa-chevron-down"></i>
                                </a>
                                <div class="ul-list dropdown-menu"aria-labelledby="navbarDropdown" > 
                                    <ul class="ul-list-element" >
                                        <?= 
                                            show_menu()
                                        ?>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fa-solid fa-gears"></i>XÂY DỰNG CẤU HÌNH </a>
                            </li>
                            <li class="nav-item category--sale">
                                <a class="nav-link " href="#"><img src="Img/coupon.png" alt="icon-sale">KHUYẾN MẠI HOT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ">TIN TỨC</a>
                            </li>
                        </ul>
                        <div class="icon--socialNetwork">
                            <i class="fa-brands fa-facebook-f"></i><i class="fa-brands fa-youtube"></i><i class="fa-brands fa-instagram"></i>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<script>
    function submitFormSearch(){
        $('#headerMain--form-search').submit();
    }

    function setText(txt,id){
        $('#menu--name').text(txt);
        $('input[name="id_loai_san_pham"]').val(id);
    }
</script>