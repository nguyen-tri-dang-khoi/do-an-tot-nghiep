<?php 
    include_once("../db.php"); 
    if(isset($_GET['action']) && $_GET['action']=="add"){ 
          
        $id=intval($_GET['id']); 
          
        if(isset($_SESSION['cart'][$id])){ 
              
            $_SESSION['cart'][$id]['count']++; 
              
        }else{ 
            $conn = connect();
            $sql_s="SELECT * FROM product_info
                WHERE id={$id}"; 
            $query_s=mysqli_query($conn, $sql_s); 
            if(mysqli_num_rows($query_s)!=0){ 
                $row_s=mysqli_fetch_array($query_s); 
                  
                $_SESSION['cart'][$row_s['id']]=array( 
                        "count" => 1, 
                        "price" => $row_s['price'] 
                    ); 
                  
                  
            }else{ 
                  
                $message="This product id it's invalid!"; 
                  
            } 
              
        } 
          
    } 
  
?> 
<h1>Product List</h1>
<?php 
    if(isset($message)){ 
        echo "<h2>$message</h2>"; 
    } 
?>
	<table> 
	    <tr> 
	        <th>Name</th> 
	        <th>Description</th> 
	        <th>Price</th> 
	        <th>Action</th> 
	    </tr>
        <?php 
    $conn = connect();
    $sql="SELECT * FROM product_info ORDER BY name ASC"; 
    $query=mysqli_query($conn, $sql); 
      
    while ($row=mysqli_fetch_array($query)) { 
          
?> 
        <tr> 
            <td><?php echo $row['name'] ?></td> 
            <td><?php echo $row['description'] ?></td> 
            <td><?php echo $row['price'] ?>$</td> 
            <td><a href="../include/modal_cart.php?page=cart&action=add&id=<?php echo $row['id'] ?>">Add to cart</a></td> 
            <?php //print_r ($_SESSION['cart']); ?>
        </tr> 
<?php 
          
    } 
  
?>
    </table>