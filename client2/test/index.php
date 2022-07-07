<?php 
    // session_start(); 
    include_once 'db.php';
    if(isset($_GET['page'])){ 
          
        $pages=array("products", "cart"); 
          
        if(in_array($_GET['page'], $pages)) { 
              
            $_page=$_GET['page']; 
              
        }else{ 
              
            $_page="products"; 
              
        } 
          
    }else{ 
          
        $_page="products"; 
          
    } 
  
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
      
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <link rel="stylesheet" href="reset.css" /> 
    <link rel="stylesheet" href="style.css" /> 
      
  
    <title>Shopping Cart</title> 
  

</head> 
  
<body> 
      
    <div id="container">      
        <div id="sidebar"> 
            <h1>Cart</h1> 
            <?php 
            
                if(isset($_SESSION['cart'])){ 
                    $conn = connect();
                    $sql="SELECT * FROM product_info WHERE id IN ("; 
                    
                    foreach($_SESSION['cart'] as $id => $value) { 
                        $sql.=$id.","; 
                    } 
                    
                    $sql=substr($sql, 0, -1).") ORDER BY name ASC"; 
                    //print_r ($sql);
                    $query=mysqli_query($conn, $sql); 
                    while($row=mysqli_fetch_array($query)){ 
                        
                    ?> 
                        <p><?php echo $row['name'] ?> x <?php echo $_SESSION['cart'][$row['id']]['count'] ?></p> 
                    <?php 
                        
                    } 
                ?> 
                <?php 
                    
                }else{ 
                    
                    echo "<p>Your Cart is empty. Please add some products.</p>"; 
                    
                } 
            
            ?>
        </div><!--end of sidebar--> 
  
    </div><!--end container--> 
  
</body> 
</html>