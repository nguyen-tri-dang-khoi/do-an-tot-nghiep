<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($id && $status == "Read") {
        $sql_get_user_info = "select id,type,created_at,full_name,email,phone,address,birthday,img_name,count(*) as 'countt' from user where id = '$id' and type='customer' and is_delete = 0 limit 1";
        $result = fetch(sql_query($sql_get_user_info));
?>
<div class="card-body">
    <table class="table table-bordered">
        <tr>
            <th>Tên đầy đủ</th>
            <td><?=$result['full_name']?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?=$result['email']?></td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td><?=$result['phone']?></td>
        </tr>
        <tr>
            <th>Ảnh đại diện</th>
            <td>
                <img style="width:100px;height:100px;" src="<?=!empty($result['img_name']) ? $result['img_name'] : "upload/noimage.jpg"?>" alt="">
            </td>
        </tr>
        <tr>
            <th>Ngày sinh</th>
            <td><?=Date("d-m-Y",strtotime($result['birthday']))?></td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td><?=$result['address']?></td>
        </tr>
        <tr>
            <th>Ngày tạo</th>
            <td><?=Date("d-m-Y H:i:s",strtotime($result['created_at']))?></td>
        </tr>
    </table>
</div>
<?php } if($status == "read_more") {
        $str_arr_upt = isset($_REQUEST['str_arr_upt']) ? $_REQUEST['str_arr_upt'] : null;
        $html = "";
        if($str_arr_upt) {
            $sql = "select * from user where id in ($str_arr_upt) and is_delete = 0 and type='customer'";
            $result2 = fetch_all(sql_query($sql));
            $i = 1;
            foreach($result2 as $res) {
                $file_src = $res['img_name'] ? $res['img_name'] : "upload/noimage.jpg";
                $html .= "<tbody style='display:none;' class='t-bd-read t-bd-read-$i'>
                <tr>
                    <th>Tên đầy đủ</th>
                    <td>" . $res['full_name'] . "</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>" . $res['email'] . "</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td>" . $res['phone'] . "</td>
                </tr>
                <tr>
                    <th>Ảnh đại diện</th>
                    <td>
                        <img style='width:100px;height:100px;' src='" . $file_src . "'>
                    </td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td>" . Date('d-m-Y',strtotime($res['birthday'])) . "</td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>" . $res['address'] . "</td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td>" . Date("d-m-Y H:i:s",strtotime($res['created_at'])) . "</td>
                </tr>
                </tbody>";
                $i++;
            }
            $html = "<div class='card-body'>
            <table class='table table-bordered'>
                $html
            </table></div>";
            print_r($html);
        }
    }
?>