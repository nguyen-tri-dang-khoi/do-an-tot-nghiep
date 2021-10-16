  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div style="min-height:1200px;" class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <?php
          if(trim($_SESSION["img_name"]) == "") {
        ?>
            <img src=<?php echo 'upload/image.png';?> class="img-circle elevation-2" alt="User Image">
        <?php
          } else {
        ?>
            <img src=<?php echo "upload/user/".$_SESSION["img_name"]?> class="img-circle elevation-2" alt="User Image">
        <?php
          }
        ?>
          </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION["username"];?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="information.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Thông tin tài khoản
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="category_manage.php" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Quản lý loại sản phẩm
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="product_manage.php" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Quản lý sản phẩm
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="order_manage.php" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Quản lý đơn hàng
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="user_manage.php" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Quản lý nhân viên
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="customer_manage.php" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Quản lý khách hàng
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="change_password.php" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
              Đổi mật khẩu
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
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
