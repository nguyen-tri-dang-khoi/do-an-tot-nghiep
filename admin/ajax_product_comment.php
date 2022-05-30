<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $reply_id = isset($_REQUEST["reply_id"]) ? $_REQUEST["reply_id"] : null;
    if($status == "show_list_comment") {
        // set get
        $get = $_GET;
        unset($get['page']);
        $str_get = http_build_query($get);
        // query

        $cnt = 0;
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; 
        $limit = 2;
        $start_page = $limit * ($page - 1);
        $sql_get_count = "select count(*) as 'cnt' from product_comment pcm inner join customer cus on pcm.customer_id = cus.id where product_info_id = '$id' ";
        $total = fetch(sql_query($sql_get_count))['cnt'];
        //$sql = "select pcm.id as 'pcm_id', cus.id as 'cus_id',pcm.rate as 'pcm_rate',pcm.comment as 'pcm_comment',pcm.created_at as 'pcm_created_at',pcm.is_active as 'pcm_is_active',cus.email as 'cus_email' from product_comment pcm inner join customer cus on pcm.customer_id = cus.id where product_info_id = '$id' and pcm.reply_id is null limit $start_page,$limit";
        $sql = "select pcm.id as 'pcm_id',pcm.comment as 'pcm_comment',pcm.created_at as 'pcm_created_at',pcm.is_active as 'pcm_is_active',pcm.user_id as 'pcm_user_id',pcm.customer_id as 'pcm_customer_id' from product_comment pcm where product_info_id = '$id' and pcm.reply_id is null";
        $comments = fetch_all(sql_query($sql));
        
?>
<!--<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Số thứ tự</th>
            <th>Khách hàng</th>
            <th>Bình luận</th>
            <th>Đánh giá</th>
            <th>Tình trạng</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody class="">
        <?php
            
            foreach($comments as $row) {
        ?>
            <tr id="<?=$row['pcm_id']?>">
                <td><?=$total - ($start_page + $cnt);?></td>
                <td><?=$row['cus_email']?></td>
                <td><?=$row['pcm_comment']?></td>
                <td>
                    <?php
                        for($ik = 0 ; $ik < $row['pcm_rate']; $ik++) {
                    ?>
                    <i style="color:#fde16d;" class="fas fa-star"></i>
                    <?php }?>
                </td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" onchange="toggleActiveComment('<?=$row['pcm_id']?>','<?= $row['pcm_is_active'] == 1 ? 'Deactive' : 'Active';?>')" class="custom-control-input" id="customSwitches<?=$row['pcm_id'];?>" <?= $row['pcm_is_active'] == 1 ? "checked" : "";?>>
                        <label class="custom-control-label" for="customSwitches<?=$row['pcm_id'];?>"></label>
                    </div>  
                </td>
                <td>
                    <button onclick="replyComment('<?=$row['pcm_id']?>')" class="dt-button button-blue">Trả lời</button>
                    <button onclick="delComment('<?=$row['pcm_id']?>')" class="dt-button button-red">Xoá</button>
                </td>
            </tr>
        <?php $cnt++; } ?>
    </tbody>
</table>-->
<style>
    .kh-name {
        font-weight:bold;
        font-size:17px;
    }
    .reply:hover {
        color:green;
    }
    .d-none {
        display: none !important;
    }
    .kh-border-line {
        border-top:1px solid #c1bcbc;width: 100%;position: absolute;right: 35px;z-index: 0;
    }
    .kh-img > img {
        border-radius:50%;border:1px solid #c1bcbc;padding:3px;width:50px;height:50px;background-color:#fff;position: relative;z-index: 1;
    }
    .d-flex:last-child  .kh-border-vertical {
        
    }
</style>
<div class="kh-list-cmt ">
    <div class="kh-main ml-20">
        <?php
            foreach($comments as $comment) {
                $row = "";
                if($comment['pcm_user_id']) {
                    $sql_person_name = "select * from user where id = ". $comment['pcm_user_id'] . " limit 1";
                    $row = fetch(sql_query($sql_person_name));
                } else if($comment['pcm_customer_id']) {
                    $sql_person_name = "select * from customer where id = ". $comment['pcm_customer_id'] . " limit 1";
                    $row = fetch(sql_query($sql_person_name));
                }
        ?>
        <div class="d-flex mt-10">
            <div class="kh-grp d-flex f-column a-center">
                <div class="kh-img d-flex a-center" style="position:relative">
                    <div style="" class="kh-border-line">
                        
                    </div>
                    <?php
                        $img_default = "";
                        if($comment['pcm_user_id']) {
                            $img_default = "img/client.png";
                        } else if($comment['pcm_customer_id']) {
                            $img_default = "img/client.png";
                        }
                    ?>
                    <img style="" src="<?=$row['img_name'] ? $row['img_name'] : $img_default;?>" alt=""> 
                </div>
                <div style="<?=$cnt == $limit + 1 ? "border-left:0px" :"border-left:1px solid #c1bcbc;min-height:100%;"; ?>" class="kh-border-vertical<?=$comment['pcm_id']?>">
                    
                </div>
            </div>
            <div class="ml-10">
                <div class="info">
                    <span class="kh-name"><?=$row['email'];?>
                    </span>
                    <span class="kh-time-cmt ml-15"><?=Date("d-m-Y h:i:s",strtotime($comment['pcm_created_at']));?></span>
                    <div class="kh-content">
                        <span><?=$comment['pcm_comment'];?></span>
                    </div>
                    <div class="kh-reply">
                        <span onclick="showReplyOk('<?=$comment['pcm_id']?>','<?=$id;?>','input')" style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                        <span onclick="delComment('<?=$comment['pcm_id']?>','<?=$id;?>')" class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>   
                    </div>
                    <?php
                        $sql22 = "select count(*) as 'cnt' from product_comment where reply_id = " . $comment['pcm_id'];
                        $cnt_comment = fetch(sql_query($sql22))['cnt'];
                        if($cnt_comment > 0) {
                    ?>
                    <div style="cursor:pointer;" onclick="showReplyOk('<?=$comment['pcm_id']?>','<?=$id;?>','input')" class="reply">
                        <img src="img/reply.svg" alt=""> 
                        <span><?=$cnt_comment . " phản hồi";?></span>
                    </div>
                    <?php 
                        } 
                    ?>
                </div>
                <div class="info info<?=$comment['pcm_id']?> <?=$cnt_comment > 0 ? "mt-10" : "";?>"></div>
                <div class="input<?=$comment['pcm_id']?> mt-20 input-reply d-none a-start">
                    <div class="kh-img d-flex a-center" style="position:relative;">
                        <div style="" class="kh-border-line">

                        </div>
                        <?php
                            $sql_user = "select * from user where id = " . $_SESSION['id'] . " limit 1";
                            $user_1 = fetch(sql_query($sql_user));
                        ?>
                        <img style="" width="50" height="50" src="<?=$user_1['img_name'] ? $user_1['img_name'] : 'user.png';?>" alt="">
                    </div>
                    <textarea style="height:45px;border-radius:15px;width:400px;max-width:400px;" type="text" name="reply<?=$comment['pcm_id']?>" class="form-control ml-10"></textarea>
                    <button onclick="sendComment('<?=$comment['pcm_id']?>','<?=$id;?>')" style="height:45px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;" class="btn a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
                </div>
                <!--<div class="info mt-10">
                    <div class="d-flex">
                        <div class="kh-img">
                            <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;width:50px;height:50px;" src="img/client.png" alt=""> 
                        </div>
                        <div class="ml-10">
                            <div class="info">
                                <span class="kh-name">Nguyễn Trí Đăng Khôi</span>
                                <span class="kh-time-cmt ml-15">(26-05-2022 18:36:25)</span>
                                <div class="kh-content">
                                    <span>Sản phẩm tuyệt cú mèo</span>
                                </div>
                                <div class="kh-reply">
                                    <span style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                                    <span class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>    
                                </div>
                                <div class="reply">
                                    <img src="img/reply.svg" alt=""> 
                                    <span>1 phản hồi</span>
                                </div>
                                <div class="input-reply d-flex a-center">
                                    <div class="kh-img">
                                        <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;" width="50" height="50" src="upload/user/125/ea09d145ca7feb1c1f7c926f1a376d6b125.jpg" alt="">
                                    </div>
                                    <textarea style="height:50px;border-radius:15px;width:400px;max-width:400px;"type="text" name="reply" class="form-control ml-10"></textarea>
                                    <button style="height:45px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;" class="btn a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
                                </div>
                            </div>
                            <div class="info mt-10">
                                <div class="d-flex">
                                    <div class="kh-img">
                                        <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;width:50px;height:50px;" src="img/client.png" alt=""> 
                                    </div>
                                    <div class="ml-10">
                                        <div class="info">
                                            <span class="kh-name">Nguyễn Trí Đăng Khôi</span>
                                            <span class="kh-time-cmt ml-15">(26-05-2022 18:36:25)</span>
                                            <div class="kh-content">
                                                <span>Sản phẩm tuyệt cú mèo</span>
                                            </div>
                                            <div class="kh-reply">
                                                <span style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                                                <span class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>    
                                            </div>
                                            <div class="reply">
                                                <img src="img/reply.svg" alt=""> 
                                                <span>1 phản hồi</span>
                                            </div>
                                            <div class="input-reply d-flex a-center">
                                                <div class="kh-img">
                                                    <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;" width="50" height="50" src="upload/user/125/ea09d145ca7feb1c1f7c926f1a376d6b125.jpg" alt="">
                                                </div>
                                                <textarea style="height:50px;border-radius:15px;width:400px;max-width:400px;"type="text" name="reply" class="form-control ml-10"></textarea>
                                                <button style="height:32px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;" class="btn a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
        <?php $cnt++;} ?>
    </div>
</div>
<div data-item="<?=$limit?>" data-total="<?=$total?>" data-page="<?=$page;?>" id="pagination-comment" style="width:100%;" class="mt-20 d-flex a-center j-center"></div>
<?php } else if ($status=="show_reply_ok") {
    $sql = "select pcm.id as 'pcm_id',pcm.comment as 'pcm_comment',pcm.created_at as 'pcm_created_at',pcm.is_active as 'pcm_is_active',pcm.user_id as 'pcm_user_id',pcm.customer_id as 'pcm_customer_id' from product_comment pcm where product_info_id = '$id' and pcm.reply_id = " . $reply_id;
    $replies = fetch_all(sql_query($sql));
    foreach($replies as $reply){
        $row = "";
        if($reply['pcm_user_id']) {
            $sql_person_name = "select * from user where id = ". $reply['pcm_user_id'] . " limit 1";
            $row = fetch(sql_query($sql_person_name));
        } else if($reply['pcm_customer_id']) {
            $sql_person_name = "select * from customer where id = ". $reply['pcm_customer_id'] . " limit 1";
            $row = fetch(sql_query($sql_person_name));
        }
?>
    <div class="d-flex mt-10">
        <div class="kh-grp d-flex f-column a-center" style="position:relative">
            <div class="kh-img d-flex a-center">
                <div style="" class="kh-border-line">

                </div>
                <?php
                    $img_default = "";
                    if($reply['pcm_user_id']) {
                        $img_default = "img/client.png";
                    } else if($reply['pcm_customer_id']) {
                        $img_default = "img/client.png";
                    }
                ?>
                <img style="" src="<?=$row['img_name'] ? $row['img_name'] : $img_default;?>" alt=""> 
            </div>
            <div style="border-left:1px solid #c1bcbc;min-height:100%;" class="kh-border-vertical<?=$reply['pcm_id']?>">

            </div>
        </div>
        <div class="ml-10">
            <div class="info">
                <span class="kh-name"><?=$row['email'];?></span>
                <span class="kh-time-cmt ml-15"><?=Date("d-m-Y h:i:s",strtotime($reply['pcm_created_at']));?></span>
                <div class="kh-content">
                    <span><?=$reply['pcm_comment'];?></span>
                </div>
                <div class="kh-reply">
                    <span onclick="showReplyOk('<?=$reply['pcm_id']?>','<?=$id;?>','input')" style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                    <span onclick="delComment('<?=$reply['pcm_id']?>','<?=$id;?>')" class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>    
                </div>
                <?php
                    $sql22 = "select count(*) as 'cnt' from product_comment where reply_id = " . $reply['pcm_id'];
                    $cnt_reply = fetch(sql_query($sql22))['cnt'];
                    if($cnt_reply > 0) {
                ?>
                    <div style="cursor:pointer;" onclick="showReplyOk('<?=$reply['pcm_id']?>','<?=$id;?>','input')" class="reply">
                        <img src="img/reply.svg" alt=""> 
                        <span><?=$cnt_reply . " phản hồi";?></span>
                    </div>
                <?php 
                    }
                ?>
            </div>
            <div class="info info<?=$reply['pcm_id']?> "></div>
            <div class="input<?=$reply['pcm_id']?> mt-20 input-reply d-none a-start">
                <div class="kh-img d-flex a-center" style="position:relative;">
                    <div style="" class="kh-border-line">

                    </div>
                    <?php
                        $sql_user = "select * from user where id = " . $_SESSION['id'] . " limit 1";
                        $user_1 = fetch(sql_query($sql_user));
                    ?>
                    <img style="" width="50" height="50" src="<?=$user_1['img_name'] ? $user_1['img_name'] : 'user.png';?>" alt="">
                </div>
                <textarea style="height:45px;border-radius:15px;width:400px;max-width:400px;"type="text" name="reply<?=$reply['pcm_id']?>" class="form-control ml-10"></textarea>
                <button onclick="sendComment('<?=$reply['pcm_id']?>','<?=$id;?>')" style="height:45px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;" class="btn a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
            </div>
            <!--<div class="info mt-10">
                <div class="d-flex">
                    <div class="kh-img">
                        <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;width:50px;height:50px;" src="img/client.png" alt=""> 
                    </div>
                    <div class="ml-10">
                        <div class="info">
                            <span class="kh-name">Nguyễn Trí Đăng Khôi</span>
                            <span class="kh-time-cmt ml-15">(26-05-2022 18:36:25)</span>
                            <div class="kh-content">
                                <span>Sản phẩm tuyệt cú mèo</span>
                            </div>
                            <div class="kh-reply">
                                <span style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                                <span class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>    
                            </div>
                            <div class="reply">
                                <img src="img/reply.svg" alt=""> 
                                <span>1 phản hồi</span>
                            </div>
                            <div class="input-reply d-flex a-center">
                                <div class="kh-img">
                                    <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;" width="50" height="50" src="upload/user/125/ea09d145ca7feb1c1f7c926f1a376d6b125.jpg" alt="">
                                </div>
                                <textarea style="height:50px;border-radius:15px;width:400px;max-width:400px;"type="text" name="reply" class="form-control ml-10"></textarea>
                                <button style="height:32px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;" class="btn a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
                            </div>
                        </div>
                        <div class="info mt-10">
                            <div class="d-flex">
                                <div class="kh-img">
                                    <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;width:50px;height:50px;" src="img/client.png" alt=""> 
                                </div>
                                <div class="ml-10">
                                    <div class="info">
                                        <span class="kh-name">Nguyễn Trí Đăng Khôi</span>
                                        <span class="kh-time-cmt ml-15">(26-05-2022 18:36:25)</span>
                                        <div class="kh-content">
                                            <span>Sản phẩm tuyệt cú mèo</span>
                                        </div>
                                        <div class="kh-reply">
                                            <span style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                                            <span class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>    
                                        </div>
                                        <div class="reply">
                                            <img src="img/reply.svg" alt=""> 
                                            <span>1 phản hồi</span>
                                        </div>
                                        <div class="input-reply d-flex a-center">
                                            <div class="kh-img">
                                                <img style="border-radius:50%;border:1px solid #c1bcbc;padding:3px;" width="50" height="50" src="upload/user/125/ea09d145ca7feb1c1f7c926f1a376d6b125.jpg" alt="">
                                            </div>
                                            <textarea style="height:50px;border-radius:15px;width:400px;max-width:400px;"type="text" name="reply" class="form-control ml-10"></textarea>
                                            <button style="height:32px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;" class="btn a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
<?php 
    } 
}
?>

