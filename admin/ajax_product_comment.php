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
        $sql = "select pcm.id as 'pcm_id',pcm.comment as 'pcm_comment',pcm.created_at as 'pcm_created_at',pcm.is_active as 'pcm_is_active',pcm.user_id as 'pcm_user_id',pcm.customer_id as 'pcm_customer_id' from product_comment pcm where product_info_id = '$id' and pcm.reply_id is null";
        $comments = fetch_all(sql_query($sql));
        
?>
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
                        $txt_type = "";
                        if($comment['pcm_user_id']) {
                            $img_default = "img/user.png";
                            $txt_type = "Admin";
                        } else if($comment['pcm_customer_id']) {
                            $txt_type = "Khách hàng";
                            $img_default = "img/client.png";
                        }
                    ?>
                    <img style="" src="<?=$row['img_name'] ? $row['img_name'] : $img_default;?>" alt=""> 
                </div>
                <div style="<?=$cnt == $limit + 1 ? "border-left:0px" :"border-left:1px solid #c1bcbc;min-height:100%;"; ?>" class="kh-border-vertical<?=$comment['pcm_id']?>">
                    
                </div>
            </div>
            <div class="ml-10 all-reply">
                <div class="info">
                    <span class="kh-name"><?=$row['email'];?> (<?=$txt_type;?>)</span>
                    <span class="kh-time-cmt ml-15"><?=Date("d-m-Y h:i:s",strtotime($comment['pcm_created_at']));?></span>
                    <div class="kh-content">
                        <span><?=$comment['pcm_comment'];?></span>
                    </div>
                    <div class="kh-reply d-flex">
                        <span onclick="showReplyOk('<?=$comment['pcm_id']?>','<?=$id;?>','input')" style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                        <div style="font-size:14px;color:green;text-decoration:underline;cursor:pointer" class="ml-20 form-check d-flex">
                            <input <?=$comment['pcm_is_active'] == 1 ? "checked" : "";?> onchange="toggleComment('<?=$comment['pcm_id']?>','<?=$id;?>','<?=$comment['pcm_is_active'] == 1 ? 'Deactive' : 'Active';?>')" style="accent-color: green;font-size:17px;" class="form-check-input" name="check_cmt<?=$comment['pcm_id'];?>" type="checkbox" value="" id="check_cmt<?=$comment['pcm_id'];?>">
                            <label style="cursor:pointer;" class="form-check-label" for="check_cmt<?=$comment['pcm_id'];?>">
                                Duyệt
                            </label>
                        </div>
                        <span onclick="delComment('<?=$comment['pcm_id']?>','<?=$id;?>')" class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>   
                    </div>
                    <?php
                        $sql22 = "select count(*) as 'cnt' from product_comment where reply_id = " . $comment['pcm_id'];
                        $cnt_comment = fetch(sql_query($sql22))['cnt'];
                        if($cnt_comment > 0) {
                    ?>
                    <div style="cursor:pointer;" onclick="showReplyOk('<?=$comment['pcm_id']?>','<?=$id;?>','input')" class="reply">
                        <img style="transform:rotateY(180deg);" src="img/reply.png" alt=""> 
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
            </div>
        </div>
        <?php $cnt++;} ?>
    </div>
</div>
<div></div>
<!--<div data-item="<?=$limit?>" data-total="<?=$total?>" data-page="<?=$page;?>" id="pagination-comment" style="width:100%;" class="mt-20 d-flex a-center j-center"></div>-->
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
                    $txt_type = "";
                    if($reply['pcm_user_id']) {
                        $img_default = "img/user.png";
                        $txt_type = "Admin";
                    } else if($reply['pcm_customer_id']) {
                        $img_default = "img/client.png";
                        $txt_type = "Khách hàng";
                    }
                ?>
                <img style="" src="<?=$row['img_name'] ? $row['img_name'] : $img_default;?>" alt=""> 
            </div>
            <div style="border-left:1px solid #c1bcbc;min-height:100%;" class="kh-border-vertical<?=$reply['pcm_id']?>">

            </div>
        </div>
        <div class="ml-10 all-reply">
            <div class="info">
                <span class="kh-name"><?=$row['email'];?> (<?=$txt_type;?>)</span>
                <span class="kh-time-cmt ml-15"><?=Date("d-m-Y h:i:s",strtotime($reply['pcm_created_at']));?></span>
                <div class="kh-content">
                    <span><?=$reply['pcm_comment'];?></span>
                </div>
                <div class="kh-reply d-flex">
                    <span onclick="showReplyOk('<?=$reply['pcm_id']?>','<?=$id;?>','input')" style="font-size:14px;color:blue;text-decoration:underline;cursor:pointer;">Phản hồi</span>
                    
                    <div style="font-size:14px;color:green;text-decoration:underline;cursor:pointer" class="ml-20 form-check d-flex">
                        <input <?=$reply['pcm_is_active'] == 1 ? "checked" : "";?> onchange="toggleComment('<?=$reply['pcm_id']?>','<?=$id;?>','<?=$reply['pcm_is_active'] == 1 ? 'Deactive' : 'Active';?>')" style="accent-color: green;font-size:17px;" class="form-check-input" name="check_cmt<?=$reply['pcm_id'];?>" type="checkbox" value="" id="check_cmt<?=$reply['pcm_id'];?>">
                        <label style="cursor:pointer;" class="form-check-label" for="check_cmt<?=$reply['pcm_id'];?>">
                            Duyệt
                        </label>
                    </div>
                    <span onclick="delComment('<?=$reply['pcm_id']?>','<?=$id;?>')" class="ml-20" style="font-size:14px;color:red;text-decoration:underline;cursor:pointer;">Xoá</span>    
                </div>
                <?php
                    $sql22 = "select count(*) as 'cnt' from product_comment where reply_id = " . $reply['pcm_id'];
                    $cnt_reply = fetch(sql_query($sql22))['cnt'];
                    if($cnt_reply > 0) {
                ?>
                    <div style="cursor:pointer;" onclick="showReplyOk('<?=$reply['pcm_id']?>','<?=$id;?>','input')" class="reply">
                        <img style="transform:rotateY(180deg);" src="img/reply.png" alt=""> 
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
        </div>
    </div>
<?php 
    } 
}
?>

