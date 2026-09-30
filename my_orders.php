<?php 
error_reporting(E_ALL & ~E_NOTICE); // Sirf zaroori errors dikhane ke liye
include('db.php'); 

if (session_status() === PHP_SESSION_NONE) { session_start(); }

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | GwaliorStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #2874f0; --bg: #f1f3f6; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; padding: 10px; padding-bottom: 80px; }
        .order-card { background: white; margin-bottom: 12px; padding: 15px; border-radius: 12px; border: 1px solid #e0e0e0; box-shadow: 0 2px 4px rgba(0,0,0,0.04); }
        .product-name { font-size: 14px; font-weight: 700; color: #333; }
        .order-price { font-size: 14px; font-weight: 800; color: #1b5e20; margin-top: 5px; }
        .status-val { font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px; background: #f0f0f0; }
        .bottom-nav { position: fixed; bottom: 0; left: 0; width: 100%; background: white; display: flex; justify-content: space-around; padding: 10px 0; border-top: 1px solid #eee; }
    </style>
</head>
<body>

    <div class="page-header"><h1>My Orders</h1></div>

    <div class="orders-list">
        <?php
        // Query check: kya 'total_amount' database mein hai?
        $res = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$u_id' ORDER BY id DESC");
        
        if($res && mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                // Agar total_amount khali hai toh ₹0 dikhaye
                $price = !empty($row['total_amount']) ? $row['total_amount'] : '0';
                ?>
                <div class="order-card">
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <p class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></p>
                            <p class="order-meta">Order #GWR<?php echo $row['id']; ?></p>
                            <div class="order-price">₹<?php echo $price; ?></div>
                        </div>
                        <div>
                            <span class="status-val"><?php echo htmlspecialchars($row['status']); ?></span>
                        </div>
                    </div>
                </div>
            <?php }
        } else {
            echo "<div style='text-align:center; padding:50px;'>Koi order nahi hai.</div>";
        }
        ?>
    </div>

    <nav class="bottom-nav">
        <a href="index.php" class="nav-link"><i class="fa fa-home"></i><span>Home</span></a>
        <a href="my_orders.php" class="nav-link active"><i class="fa fa-box"></i><span>Orders</span></a>
        <a href="cart.php" class="nav-link"><i class="fa fa-shopping-cart"></i><span>Cart</span></a>
    </nav>

</body>
</html>