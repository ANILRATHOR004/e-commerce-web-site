<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php'); 


if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$u_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart | GwaliorStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; padding-bottom: 140px; }
        .header { background: #2874f0; color: white; padding: 20px; text-align: center; font-weight: 700; font-size: 18px; }
        .cart-item { background: white; margin: 10px; padding: 15px; border-radius: 12px; display: flex; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .item-info { flex: 1; margin-left: 15px; }
        .item-price { font-weight: 700; color: #1b5e20; font-size: 16px; }
        .checkout-box { position: fixed; bottom: 0; width: 100%; background: white; padding: 20px; box-sizing: border-box; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .btn-checkout { background: #fb641b; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">My Shopping Cart 🛒</div>

<div id="cart-list" style="margin-top: 10px;">
    <?php
    $total = 0;
    // CORRECTION: Yahan c.* ke jagah humne p.product_name aur p.price liya hai
    $sql = "SELECT c.quantity, p.product_name, p.product_price, p.product_image 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = '$u_id'";
    $res = mysqli_query($conn, $sql);
    
    if($res && mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            // Price Calculation (products table se utha rahe hain)
            $subtotal = $row['product_price'] * $row['quantity'];
            $total += $subtotal;
            
            echo "<div class='cart-item'>";
            echo "<img src='uploads/".$row['product_image']."' style='width:60px; height:60px; object-fit:cover; border-radius:8px;'>";
            echo "<div class='item-info'><b>".htmlspecialchars($row['product_name'])."</b><br><small style='color:#888;'>Qty: ".$row['quantity']."</small></div>";
            echo "<div class='item-price'>₹".$subtotal."</div>";
            echo "</div>";
        }
    } else {
        echo "<div style='text-align:center; margin-top:50px;'>Aapka cart khali hai!</div>";
    }
    ?>
</div>

<?php if($total > 0): ?>
<div class="checkout-box">
    <div><small>Grand Total</small><br><b style="font-size:22px;">₹<?php echo $total; ?></b></div>
    <a href="checkout.php" class="btn-checkout">Checkout ➔</a>
</div>
<?php endif; ?>

</body>
</html>