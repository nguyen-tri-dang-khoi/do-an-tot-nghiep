<?php 
// include_once 'db.php';
//     if(isset($_POST['submit'])){ 
  
//     foreach($_POST['count'] as $key => $val) { 
//         if($val==0) { 
//             unset($_SESSION['cart'][$key]); 
//         }else{ 
//             $_SESSION['cart'][$key]['count']=$val;

//         } 
//     } 
        
//     }
  ?>
<!-- <form method="post" action="index.php?page=cart"> 
      
    <table>  -->
        <!-- <tr> 
            <th>Name</th> 
            <th>count</th> 
            <th>Price</th> 
            <th>Items Price</th> 
        </tr>  -->
          
        <?php 
            
            // $sql="SELECT * FROM product_info WHERE id IN ("; 
            //         foreach ($_SESSION['cart'] as $id => $value) { 
            //             $sql.=$id.","; 
            //         } 
            //         $conn = connect();
            //         $sql=substr($sql, 0, -1).") ORDER BY name ASC"; 
            //         $query=mysqli_query($conn, $sql); 
            //         $totalprice=0; 
            //         while($row=mysqli_fetch_array($query)){ 
            //             $subtotal=$_SESSION['cart'][$row['id']]['count']*$row['price']; 
            //             $totalprice+=$subtotal; 
                    ?> 
                        <!-- <tr> 
                            <td><?php //echo $row['name'] ?></td> 
                            <td><input type="text" name="count[<?php //echo $row['id'] ?>]" size="5" value="<?php //echo $_SESSION['cart'][$row['id']]['count'] ?>" /></td> 
                            <td><?php //echo $row['price'] ?>$</td> 
                            <td><?php //echo $_SESSION['cart'][$row['id']]['count']*$row['price'] ?>$</td> 
                        </tr>  -->
                    <?php 
                          
                    // } 
        ?> 
                    <!-- <tr> 
                        <td colspan="4">Total Price: <?php //echo $totalprice ?></td> 
                    </tr> 
          
    </table> 
    <br /> 
    <button type="submit" name="submit">Update Cart</button> 
</form> 
<br /> 
<p>To remove an item, set it's quantity to 0. </p> -->

<?php 
                        include_once 'db.php';
                            if(isset($_POST['submit'])){ 
                        
                            foreach($_POST['count'] as $key => $val) { 
                                if($val==0) { 
                                    unset($_SESSION['cart'][$key]); 
                                }else{ 
                                    $_SESSION['cart'][$key]['count']=$val;

                                } 
                            } 
                                
                            }
                        ?>
                        <form method="post" action="index.php?page=cart"> 
                            
                            <table> 
                                <!-- <tr> 
                                    <th>Name</th> 
                                    <th>count</th> 
                                    <th>Price</th> 
                                    <th>Items Price</th> 
                                </tr>  -->
                                
                                <?php 
                                    
                                    $sql="SELECT * FROM product_info WHERE id IN ("; 
                                            foreach ($_SESSION['cart'] as $id => $value) { 
                                                $sql.=$id.","; 
                                            } 
                                            $conn = connect();
                                            $sql=substr($sql, 0, -1).") ORDER BY name ASC"; 
                                            $query=mysqli_query($conn, $sql); 
                                            $totalprice=0; 
                                            while($row=mysqli_fetch_array($query)){ 
                                                $subtotal=$_SESSION['cart'][$row['id']]['count']*$row['price']; 
                                                $totalprice+=$subtotal; 
                                            ?> 
                                                <tr> 
                                                    <td><?php echo $row['name'] ?></td> 
                                                    <td><input type="text" name="count[<?php echo $row['id'] ?>]" size="5" value="<?php echo $_SESSION['cart'][$row['id']]['count'] ?>" /></td> 
                                                    <td><?php echo $row['price'] ?>$</td> 
                                                    <td><?php echo $_SESSION['cart'][$row['id']]['count']*$row['price'] ?>$</td> 
                                                </tr> 
                                            <?php 
                                                
                                            } 
                                ?> 
                                            <tr> 
                                                <td colspan="4">Total Price: <?php echo $totalprice ?></td> 
                                            </tr> 
                                
                            </table> 
                            <br /> 
                            <button type="submit" name="submit">Update Cart</button> 
                        </form> 
                        <br /> 
                        <p>To remove an item, set it's quantity to 0. </p>