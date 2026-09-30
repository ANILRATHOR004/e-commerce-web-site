<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');
session_start();

// URL se order id pakadna (Aapka original logic)
$order_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '0';
$total_bill = 0;

// Database se is order ka total amount fetch karna taaki invoice screen par bill dikh sake
if($order_id != '0') {
    $order_q = "SELECT total_amount FROM `orders` WHERE id = '$order_id'";
    $order_res = mysqli_query($conn, $order_q);
    if($order_res && mysqli_num_rows($order_res) > 0) {
        $order_row = mysqli_fetch_assoc($order_res);
        $total_bill = $order_row['total_amount'];
    }
}

// Order successfully hone ke baad ab user ka cart session khali kar dete hain
if(isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed! | GwaliorStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f3f6; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .success-box { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 90%; }
        .icon-circle { width: 80px; height: 80px; background: #4caf50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3); }
        .order-tag { background: #e3f2fd; color: #2874f0; padding: 8px 15px; border-radius: 50px; font-weight: 800; font-size: 14px; display: inline-block; margin: 10px 0; }
        .bill-tag { font-size: 16px; font-weight: 800; color: #1b5e20; margin: 10px 0 20px 0; background: #e8f5e9; padding: 8px 15px; border-radius: 8px; display: inline-block; }
        .btn-main { display: block; background: #2874f0; color: white; text-decoration: none; padding: 15px; border-radius: 12px; font-weight: 700; margin-top: 20px; transition: 0.3s; }
        .btn-main:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(40,116,240,0.3); }
    </style>
</head>
<body>
    <div class="success-box">
        <div class="icon-circle"><i class="fa fa-check"></i></div>
        <h2 style="margin:0;">Order Placed!</h2>
        <p style="color:#666; font-size:14px;">Shukriya! Aapka order mil gaya hai aur process ho raha hai.</p>
        
        <div class="order-tag">ID: #GWR<?php echo htmlspecialchars($order_id); ?></div>
        
        <?php if($total_bill > 0): ?>
            <br><div class="bill-tag">Total Amount: ₹<?php echo $total_bill; ?> (COD)</div>
        <?php endif; ?>
        
        <a href="my_orders.php" class="btn-main">Track My Order 📦</a>
        <a href="index.php" style="display:block; margin-top:15px; color:#999; font-size:13px; text-decoration:none;">Continue Shopping</a>
    </div>
</body>
</html>