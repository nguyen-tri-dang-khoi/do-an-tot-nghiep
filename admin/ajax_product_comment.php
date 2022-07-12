<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $reply_id = isset($_REQUEST["reply_id"]) ? $_REQUEST["reply_id"] : null;
    if($status == "show_list_comment") {
        $sql = "select pcm.id as 'pcm_id',pcm.comment as 'pcm_comment',pcm.rate as 'pcm_rate',pcm.created_at as 'pcm_created_at',pcm.is_active as 'pcm_is_active',pcm.user_id as 'pcm_user_id' from product_comment pcm where product_info_id = '$id' and pcm.reply_id is null and pcm.is_delete = 0";
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
        border-top:1px solid #c1bcbc;
        width: 100%;
        position: absolute;
        right: 35px;
        z-index: 0;
    }
    .kh-img > img {
        border-radius:50%;
        border:1px solid #c1bcbc;
        padding:3px;
        width:50px;
        height:50px;
        background-color:#fff;
        position: relative;
        z-index: 1;
    }
    .kh-border-vertical{
        min-height:calc(100% - 32px) !important;
        border-left: 1px solid rgb(221, 221, 221);
    }
    .kh-border-horiziontal {
        border-left:1px solid #c1bcbc;
        position:relative;
        z-index:1;
    }
    .kh-yellow {
        color:#ffc107;
    }
    .txt-reply {
        font-size:14px;
        color:blue;
        text-decoration:underline;
        cursor:pointer;
    }
    .txt-content-reply {
        font-size:14px;
        color:green;
        text-decoration:underline;
        cursor:pointer
    }
    .del-cmt {
        font-size:14px;
        color:red;
        text-decoration:underline;
        cursor:pointer
    }
    .btn-send {
        height:45px;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }
    .kh-cmt-textarea {
        height:45px;
        border-radius:15px;
        width:400px;
        max-width:400px;
    }
    .modal-body {
        padding: 1.1rem;
    }
</style>
<div class="kh-list-cmt ">
    <div class="kh-main ml-20">
        <?php
            foreach($comments as $comment) {
                $sql_person_name = "select * from user where id = ? limit 1";
                $row = fetch(sql_query($sql_person_name,[$comment['pcm_user_id']]));
        ?>
        <div class="d-flex mt-10">
            <div class="kh-grp d-flex f-column a-center">
                <div class="kh-img d-flex a-center" style="position:relative">
                    <div style="" class="kh-border-line"></div>
                    <?php
                        $img_default = "";
                        $txt_type = "";
                        if($row['type'] == 'admin' || $row['type'] == 'officer') {
                            $img_default = "img/user.png";
                            $txt_type = "Admin";
                        } else if($row['type'] == 'customer') {
                            $txt_type = "Khách hàng";
                            $img_default = "img/client.png";
                        }
                    ?>
                    <img style="" src="<?=$row['img_name'] ? $row['img_name'] : $img_default;?>" alt=""> 
                </div>
                <div class="kh-border-horiziontal kh-border-vertical<?=$comment['pcm_id']?> kh-border-vertical"></div>
            </div>
            <div class="ml-10 all-reply">
                <div class="info">
                    <span class="kh-name">
                        <?=$row['email'];?> (<?=$txt_type;?>)
                        <?php
                            if($txt_type != "Admin") {
                        ?>
                        <?php
                            for($il = 0 ; $il < $comment['pcm_rate'] ; $il++) {
                        ?>
                            <i class="fas fa-star kh-yellow"></i> 
                        <?php
                            }
                            for($il = 0 ; $il < 5 - $comment['pcm_rate'] ; $il++) {
                        ?>
                            <i class="fas fa-star"></i> 
                        <?php
                            }
                        }
                        ?>
                    </span> 
                    <span class="kh-time-cmt ml-15"><?=Date("d-m-Y h:i:s",strtotime($comment['pcm_created_at']));?></span>
                    <div class="kh-content">
                        <span><?=$comment['pcm_comment'];?></span>
                    </div>
                    <div class="kh-reply d-flex">
                        <span class="txt-reply" onclick="showReplyOk('<?=$comment['pcm_id']?>','<?=$id;?>','input')">Phản hồi</span>
                        <div class="txt-content-reply ml-20 form-check d-flex">
                            <input <?=$comment['pcm_is_active'] == 1 ? "checked" : "";?> onchange="toggleComment('<?=$comment['pcm_id']?>','<?=$id;?>','<?=$comment['pcm_is_active'] == 1 ? 'Deactive' : 'Active';?>')" style="accent-color: green;font-size:17px;" class="form-check-input" name="check_cmt<?=$comment['pcm_id'];?>" type="checkbox" value="" id="check_cmt<?=$comment['pcm_id'];?>">
                            <label style="cursor:pointer;" class="form-check-label" for="check_cmt<?=$comment['pcm_id'];?>">Duyệt</label>
                        </div>
                        <span onclick="delComment('<?=$comment['pcm_id']?>','<?=$id;?>')" class="ml-20 del-cmt">Xoá</span>
                    </div>
                    <?php
                        $sql22 = "select count(*) as 'cnt' from product_comment where is_delete = 0 and reply_id = ?";
                        $cnt_comment = fetch(sql_query($sql22,[$comment['pcm_id']]))['cnt'];
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
                <div class="info info<?=$comment['pcm_id']?> <?=$cnt_comment > 0 ? "mt-10" : "";?>">
                </div>
                <div class="input<?=$comment['pcm_id']?> mt-20 input-reply d-none a-start">
                    <div class="kh-img d-flex a-center" style="position:relative;">
                        <div style="" class="kh-border-line"></div>
                        <?php
                            $sql_user = "select * from user where id = ? limit 1";
                            $user_1 = fetch(sql_query($sql_user,[$_SESSION['id']]));
                        ?>
                        <img width="50" height="50" src="<?=$user_1['img_name'] ? $user_1['img_name'] : 'user.png';?>" alt="">
                    </div>
                    <textarea type="text" name="reply<?=$comment['pcm_id']?>" class="kh-cmt-textarea form-control ml-10"></textarea>
                    <button onclick="sendComment('<?=$comment['pcm_id']?>','<?=$id;?>')" class="btn btn-send a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div>

<?php } else if ($status=="show_reply_ok") {
    $sql = "select pcm.id as 'pcm_id',pcm.comment as 'pcm_comment',pcm.rate as 'pcm_rate',pcm.created_at as 'pcm_created_at',pcm.is_active as 'pcm_is_active',pcm.user_id as 'pcm_user_id' from product_comment pcm where product_info_id = '$id' and pcm.is_delete = 0 and pcm.reply_id = " . $reply_id;
    $replies = fetch_all(sql_query($sql));
    foreach($replies as $reply){
        $sql_person_name = "select * from user where id = ? limit 1";
        $row = fetch(sql_query($sql_person_name,[$reply['pcm_user_id']]));
?>
    <div class="d-flex mt-10">
        <div class="kh-grp d-flex f-column a-center" style="position:relative">
            <div class="kh-img d-flex a-center">
                <div style="" class="kh-border-line">

                </div>
                <?php
                    $img_default = "";
                    $txt_type = "";
                    if($row['type'] == 'admin' || $row['type'] == 'officer') {
                        $img_default = "img/user.png";
                        $txt_type = "Admin";
                    } else if($row['type'] == 'customer') {
                        $txt_type = "Khách hàng";
                        $img_default = "img/client.png";
                    }
                ?>
                <img style="" src="<?=$row['img_name'] ? $row['img_name'] : $img_default;?>" alt=""> 
            </div>
            <div class="kh-border-horiziontal kh-border-vertical<?=$reply['pcm_id']?> kh-border-vertical"></div>
        </div>
        <div class="ml-10 all-reply">
            <div class="info">
                <span class="kh-name">
                    <?=$row['email'];?> (<?=$txt_type;?>)
                    <?php
                        if($txt_type != "Admin") {
                    ?>
                    <?php
                        for($il = 0 ; $il < $reply['pcm_rate'] ; $il++) {
                    ?>
                        <i class="fas fa-star kh-yellow"></i> 
                    <?php
                        }
                        for($il = 0 ; $il < 5 - $reply['pcm_rate'] ; $il++) {
                    ?>
                        <i class="fas fa-star"></i> 
                    <?php
                        }
                    }
                    ?>
                </span>
                <span class="kh-time-cmt ml-15"><?=Date("d-m-Y h:i:s",strtotime($reply['pcm_created_at']));?></span>
                <div class="kh-content">
                    <span><?=$reply['pcm_comment'];?></span>
                </div>
                <div class="kh-reply d-flex">
                    <span class="txt-reply" onclick="showReplyOk('<?=$reply['pcm_id']?>','<?=$id;?>','input')">Phản hồi</span>
                    
                    <div class="txt-content-reply ml-20 form-check d-flex">
                        <input <?=$reply['pcm_is_active'] == 1 ? "checked" : "";?> onchange="toggleComment('<?=$reply['pcm_id']?>','<?=$id;?>','<?=$reply['pcm_is_active'] == 1 ? 'Deactive' : 'Active';?>')" style="accent-color: green;font-size:17px;" class="form-check-input" name="check_cmt<?=$reply['pcm_id'];?>" type="checkbox" value="" id="check_cmt<?=$reply['pcm_id'];?>">
                        <label style="cursor:pointer;" class="form-check-label" for="check_cmt<?=$reply['pcm_id'];?>">
                            Duyệt
                        </label>
                    </div>
                    <span onclick="delComment('<?=$reply['pcm_id']?>','<?=$id;?>')" class="ml-20 del-cmt">Xoá</span>    
                </div>
                <?php
                    $sql22 = "select count(*) as 'cnt' from product_comment where is_delete = 0 and reply_id = ?";
                    $cnt_reply = fetch(sql_query($sql22,[$reply['pcm_id']]))['cnt'];
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
            <div class="info info<?=$reply['pcm_id']?>">
            </div>
            <div class="input<?=$reply['pcm_id']?> mt-20 input-reply d-none a-start">
                <div class="kh-img d-flex a-center" style="position:relative;">
                    <div style="" class="kh-border-line"></div>
                    <?php
                        $sql_user = "select * from user where id = ? limit 1";
                        $user_1 = fetch(sql_query($sql_user,[$_SESSION['id']]));
                    ?>
                    <img style="" width="50" height="50" src="<?=$user_1['img_name'] ? $user_1['img_name'] : 'user.png';?>" alt="">
                </div>
                <textarea type="text" name="reply<?=$reply['pcm_id']?>" class="kh-cmt-textarea form-control ml-10"></textarea>
                <button onclick="sendComment('<?=$reply['pcm_id']?>','<?=$id;?>')" class="btn btn-send a-center d-flex" type="button"><img src="img/send.png" alt=""></button>
            </div>
        </div>
    </div>
<?php 
    } 
}
?>

