<?php 
include('db.php'); 
session_start();

//
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$item_name = $_GET['item'];
// 
$res = mysqli_query($conn, "SELECT price FROM products WHERE item_name = '$item_name'");
$product = mysqli_fetch_assoc($res);
$price = $product['price'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Order - Gwalior Store</title>
    <style>
        body { font-family: Arial; background: #f1f3f6; text-align: center; padding: 50px; }
        .confirm-card { background: white; padding: 30px; display: inline-block; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        .btn-confirm { background: #fb641b; color: white; padding: 15px; width: 100%; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="confirm-card">
        <h2>Order Confirmation</h2>
        <p>Item: <b><?php echo $item_name; ?></b></p>
        <p>Amount to Pay: <b style="color:green;">₹<?php echo $price; ?></b></p>
        <hr>
        <form method="POST">
            <button type="submit" name="place_order" class="btn-confirm">Confirm Order (Cash on Delivery)</button>
        </form>

        <?php
        if(isset($_POST['place_order'])){
            $u_id = $_SESSION['user_id'];
            $u_name = $_SESSION['user_name'];
            
           
            $q = "INSERT INTO orders (user_id, customer_name, product_name, status) VALUES ('$u_id', '$u_name', '$item_name', 'Pending')";
            
            if(mysqli_query($conn, $q)){
                echo "<h3 style='color:green;'>✅ Order Placed!</h3>";
                echo "<a href='my_orders.php'>Go to My Orders</a>";
            }
        }
        ?>
    </div>
</body>
</html>