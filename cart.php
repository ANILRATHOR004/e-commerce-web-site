<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
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
    $sql = "SELECT c.quantity, p.* 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = '$u_id'";
    $res = mysqli_query($conn, $sql);
    
    if($res && mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            $p_name = $row['product_name'] ?? ($row['item_name'] ?? ($row['name'] ?? 'Product'));
            $p_price = $row['product_price'] ?? ($row['price'] ?? 0);
            $p_img = !empty($row['product_image']) ? $row['product_image'] : (!empty($row['image']) ? $row['image'] : 'default.jpg');
            
            $subtotal = $p_price * $row['quantity'];
            $total += $subtotal;
            
            echo "<div class='cart-item'>";
            echo "<img src='uploads/".$p_img."' style='width:60px; height:60px; object-fit:cover; border-radius:8px;'>";
            echo "<div class='item-info'><b>".htmlspecialchars($p_name)."</b><br><small style='color:#888;'>Qty: ".$row['quantity']."</small></div>";
            echo "<div class='item-price'>₹".$subtotal."</div>";
            echo "</div>";
        }
    } else {
        echo "<div style='text-align:center; padding:60px 20px; background:white; border-radius:16px; margin:20px; border:1px solid #e2e8f0;'><h2 style='color:#1e293b; margin-bottom:8px;'>Your Cart is Empty</h2><p style='color:#64748b; margin-bottom:20px;'>Looks like you haven\'t added any items yet.</p><a href='index.php' style='background:#2874f0; color:white; padding:10px 25px; border-radius:20px; text-decoration:none; font-weight:bold;'>Explore Products</a></div>";
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