<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<style>
    .kh-board {
        width:100%;
        border:1px solid #d9585c;
    }
    .kh-padding .col-3{
        padding-left:0;
        padding-right:5px;
    }
    .bg-info-2 {
        border:1px solid #17a2b8;
    }
    .bg-info-2 .small-box-footer {
        background-color: #17a2b8;
    }
    .bg-info-2 h3,
    .bg-info-2 p,
    .bg-info-2 a {
        color: #17a2b8;
        font-weight:bold;
    }
    .bg-info-2 .small-box-footer:hover {
        background-color:#17a2b8;
    }
    /** */
    .bg-info-3 {
        border:1px solid #28a745;
    }
    .bg-info-3 .small-box-footer {
        background-color: #28a745;
    }
    .bg-info-3 h3,
    .bg-info-3 p,
    .bg-info-3 a {
        color: #28a745;
        font-weight:bold;
    }
    .bg-info-3 .small-box-footer:hover {
        background-color:#28a745;
    }
    /** */
    .bg-info-4 {
        border:1px solid #f012be;
    }
    .bg-info-4 .small-box-footer {
        background-color: #f012be;
    }
    .bg-info-4 h3,
    .bg-info-4 p,
    .bg-info-4 a {
        color: #f012be;
        font-weight:bold;
    }
    .bg-info-4 .small-box-footer:hover {
        background-color:#f012be;
    }
    /** */
    .bg-info-5 {
        border:1px solid #dc3545;
    }
    .bg-info-5 .small-box-footer {
        background-color: #dc3545;
    }
    .bg-info-5 h3,
    .bg-info-5 p,
    .bg-info-5 a {
        color: #dc3545;
        font-weight:bold;
    }
    .bg-info-5 .small-box-footer:hover {
        background-color:#dc3545;
    }
    a.small-box-footer {
        color:#fff !important;
    }
</style>
<div class="content-wrapper" style="margin-left:290px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 kh-padding">
                <div class="col-sm-6">
                    <h1 style="font-weight:bold;color:#d9585c;" class="m-0">Trang tổng quan</h1>
                </div>
            </div>
            <hr style="">
        </div>
    </div>
    <section class="content" >
        <div class="container-fluid" >
            <div class="row kh-padding">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info-2">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Đơn chưa giao</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info-3">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Đơn đã giao</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info-4">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Đơn hoãn giao</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info-5">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Đơn huỷ giao</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row kh-padding">
                <div class="col-6">
                    <h3 style="font-weight:bold;color:#17a2b8;">Đơn chưa giao</h3>
                    <table class="table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-6">
                    <h3 style="font-weight:bold;color:#28a745">Đơn đã giao</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row kh-padding mt-20">
                <div class="col-6 kh-order-delay">
                    <h3 style="font-weight:bold;color:#f012be;">Đơn hoãn giao</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-6">
                    <h3 style="font-weight:bold;color:#dc3545;">Đơn huỷ giao</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Số thứ tự</th>
                                <th>Mã hoá đơn</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<!--html & css section end-->


<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->

<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        
        // code to be executed post method
    }
?>