<?php
  $url = get_url_current_page();
  get_user_info();
  $currentPage= basename($_SERVER['SCRIPT_NAME']);
  //print_r($currentPage);
  check_permission_redirect($currentPage);
  // checking role permission
?>
<style>
  .kh-active {
    background-color: #494e53;
    
  }
  .kh-active:hover {
    background-color: #494e53;
  }
  .kh-active p {
    color: #fff !important;
  }
  div.dt-button-collection>:last-child {
    display: flex !important;
  }
  /*.dt-button {
    border: 1px solid #2771e1bd;
    background: #F3F4F6;
    cursor: pointer;
    align-items: center;
    padding: 4px 10px;
    outline: none;
    margin: 0px 2px;
    color: #2d159d;
    border-radius: 7px;
  }*/
  .dt-button-collection div[role='menu'] button {
    border-radius: 7px;
  }
</style>
<nav style="transition:unset;" class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a tabindex="-1" class="nav-link" onclick="hidden_menu()" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <!--<li class="nav-item d-none d-sm-inline-block">
      <a href="index3.html" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li>-->
  </ul>
  <ul class="navbar-nav ml-auto">
    <li>
      <form action="paging_ok.php" method="post" style="display: flex;align-items: center;justify-content: space-between;width: 230px;">
        <label for="">Số dòng: </label>
        <input tabindex="-1"  class="form-control" style="width:100px;" type="number" name="paging" value="<?=$_SESSION['paging'];?>">
        <button tabindex="-1" class="dt-button button-grey">Lưu</button>
      </form>
    </li>
  </ul>
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div style="min-height:1200px;" class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src=<?=$_SESSION["img_name"] ? $_SESSION["img_name"] : "upload/image.png";?> class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a tabindex="0" id="first_tab" href="#" class="d-block"><?=$_SESSION["username"];?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
        <li class="nav-item " >
          <a tabindex="-1" href="information.php" class="nav-link <?=strpos($url,"information.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Thông tin tài khoản
            </p>
          </a>
        </li>
        <?php if(check_permission_link("category_manage.php")){?>
        <li class="nav-item" >
          <a tabindex="-1" href="category_manage.php" class="nav-link <?=strpos($url,"category_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Quản lý danh mục
            </p>
          </a>
        </li>
        <?php } ?>
        <?php if(check_permission_link("product_manage.php")){?>
        <li class="nav-item " >
          <a tabindex="-1" href="product_manage.php" class="nav-link <?=strpos($url,"product_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Quản lý sản phẩm
            </p>
          </a>
        </li>
        <?php } ?>
        <?php if(check_permission_link("order_manage.php")){?>
        <li class="nav-item " >
          <a tabindex="-1" href="order_manage.php" class="nav-link <?=strpos($url,"order_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Quản lý đơn hàng
            </p>
          </a>
        </li>
        <?php } ?>
        <?php if(check_permission_link("user_manage.php")){?>
        <li class="nav-item " >
          <a tabindex="-1" href="user_manage.php" class="nav-link <?=strpos($url,"user_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Quản lý nhân viên
            </p>
          </a>
        </li>
        <?php } ?>
        <?php if(check_permission_link("customer_manage.php")){?>
        <li class="nav-item " >
          <a tabindex="-1" href="customer_manage.php" class="nav-link <?=strpos($url,"customer_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Quản lý khách hàng
            </p>
          </a>
        </li>
        <?php } ?>
        <?php if(check_permission_link("history_manage.php")){?>
        <li class="nav-item " >
          <a tabindex="-1" href="history_manage.php" class="nav-link <?=strpos($url,"history_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Quản lý lịch sử tìm kiếm
            </p>
          </a>
        </li>
        <?php } ?>
        <?php if(check_permission_link("notification_manage.php")){?>
        <li class="nav-item " >
          <a tabindex="-1" href="notification_manage.php" class="nav-link <?=strpos($url,"notification_manage.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Quản lý bảng tin
            </p>
          </a>
        </li>
        <?php } ?>
        <li class="nav-item ">
          <a tabindex="-1" href="change_password.php" class="nav-link <?=strpos($url,"change_password.php") ? "kh-active" : "";?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
            Đổi mật khẩu
            </p>
          </a>
        </li>
        <li class="nav-item " >
          <a tabindex="-1" href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
            Đăng xuất
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<script>
  function hidden_menu(){
    if($('.main-sidebar').is(":visible")) {
      $('.main-sidebar').css({"display":"none"});
      $('.main-header').css({"margin-left":"0px"});
      $('.container-wrapper').css({"margin-left":"0px"});
    } else {
      $('.main-sidebar').css({"display":"block"});
      $('.main-header').css({"margin-left":"250px"});
      $('.container-wrapper').css({"margin-left":"250px"});
    }
  }
</script>
