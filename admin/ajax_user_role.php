<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $sql_get_count = "select count(m.id) as 'cnt' from user_role u inner join menus m on u.menu_id = m.id where u.user_id='$id'";
    $sql = "select m.name as 'm_name',u.permission as 'u_permission',u.user_id as 'u_user_id',u.menu_id as 'u_menu_id' from menus m inner join user_role u on u.menu_id = m.id where u.user_id='$id'";
    //print_r($sql);
    $result = fetch_all(sql_query($sql));
    $count3 = sql_query($sql_get_count);
    $count3 = fetch($count3)['cnt'];
    $cnt2 = 0;
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Số thứ tự</th>
            <th>Chức năng</th>
            <th>Quyền</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($result as $res) {
    ?>
        <tr id="role-<?=$res['u_menu_id'];?>">
            <td><?=$count3 - $cnt2;?></td>
            <td><?=$res['m_name']?></td>
            <td>
                <div class="k-checkbox d-flex j-between">
                    <div class="d-flex a-center">
                        <span>Đọc</span>
                        <input type="checkbox" onchange="setRoleUpt('read',`#role-<?=$res['u_menu_id'];?> input[name='rolesUpt']`)" <?=strpos($res['u_permission'],'read') ? "checked" : "";?>>
                    </div>
                    <div class="ml-5 d-flex a-center">
                        <span>Thêm</span>
                        <input type="checkbox" onchange="setRoleUpt('insert',`#role-<?=$res['u_menu_id'];?> input[name='rolesUpt']`)" <?=strpos($res['u_permission'],'insert') ? "checked" : "";?>>
                    </div>
                    <div class="ml-5 d-flex a-center">
                        <span>Xoá</span>
                        <input type="checkbox" onchange="setRoleUpt('delete',`#role-<?=$res['u_menu_id'];?> input[name='rolesUpt']`)" <?=strpos($res['u_permission'],'delete') ? "checked" : "";?>>
                    </div>
                    <div class="ml-5 d-flex a-center" >
                        <span>Sửa</span>
                        <input type="checkbox" onchange="setRoleUpt('update',`#role-<?=$res['u_menu_id'];?> input[name='rolesUpt']`)" <?=strpos($res['u_permission'],'update') ? "checked" : "";?>>
                    </div>
                </div>
                <input type="hidden" name="rolesUpt" value="<?=$res['u_permission'];?>">
            </td>
            <td>
                <button onclick="uptRole('<?=$res['u_user_id']?>','<?=$res['u_menu_id']?>')" class="dt-button button-green">Sửa</button>
                <button onclick="delRole('<?=$res['u_user_id']?>','<?=$res['u_menu_id']?>')" class="dt-button button-red">Xoá</button>
            </td>
        </tr>
    <?php
            $cnt2++; 
        } 
    ?>
    </tbody>
</table>