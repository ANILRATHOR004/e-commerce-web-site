<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include('db.php');

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$u_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $c_name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $c_phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $c_address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $total_bill = mysqli_real_escape_string($conn, $_POST['total_amount'] ?? '0');
    
    $direct_p_id = intval($_POST['product_id'] ?? ($_GET['id'] ?? ($_SESSION['checkout_product_id'] ?? 0)));
    unset($_SESSION['checkout_product_id']);
} else {
    header("Location: index.php");
    exit();
}

if ($direct_p_id > 0) {
    $p_res = mysqli_query($conn, "SELECT * FROM products WHERE id = $direct_p_id");
    if (!$p_res) {
        die(mysqli_error($conn));
    }
    
    $p_row = mysqli_fetch_assoc($p_res);
    $p_name = mysqli_real_escape_string($conn, $p_row['item_name'] ?? ($p_row['product_name'] ?? ($p_row['name'] ?? 'Product')));

    $order_query = "INSERT INTO orders (user_id, customer_name, phone, address, product_name, total_amount, status) 
                    VALUES ('$u_id', '$c_name', '$c_phone', '$c_address', '$p_name', '$total_bill', 'Pending')";
    
    if (!mysqli_query($conn, $order_query)) {
        die(mysqli_error($conn));
    }
} else {
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$u_id'");
    if (!$cart_query) {
        die(mysqli_error($conn));
    }

    if (mysqli_num_rows($cart_query) > 0) {
        while ($item = mysqli_fetch_assoc($cart_query)) {
            $prod_id = intval($item['product_id']);
            $prod_res = mysqli_query($conn, "SELECT * FROM products WHERE id = $prod_id");
            $prod_row = $prod_res ? mysqli_fetch_assoc($prod_res) : [];
            
            $p_name = mysqli_real_escape_string($conn, $prod_row['item_name'] ?? ($prod_row['product_name'] ?? ($prod_row['name'] ?? 'Cart Product')));

            $order_query = "INSERT INTO orders (user_id, customer_name, phone, address, product_name, total_amount, status) 
                            VALUES ('$u_id', '$c_name', '$c_phone', '$c_address', '$p_name', '$total_bill', 'Pending')";
            
            if (!mysqli_query($conn, $order_query)) {
                die(mysqli_error($conn));
            }
        }
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$u_id'");
    } else {
        die("Cart is empty");
    }
}

$wa_msg_owner = "New Order Recieved! Name: $c_name, Address: $c_address, Total: ₹$total_bill.";
$wa_link_owner = "https://wa.me/919713901851?text=" . urlencode($wa_msg_owner);

$wa_msg_user = "Hello $c_name, GwaliorStore par aapka order confirm ho gaya hai! Total: ₹$total_bill. Delivery jald hogi.";
$wa_link_user = "https://wa.me/91$c_phone?text=" . urlencode($wa_msg_user); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed | GwaliorStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; background: #f1f3f6; }
        .success-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 90%; }
        .check-container { width: 100px; height: 100px; background: #4caf50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 25px; }
        .btn { padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 700; display: block; margin-top: 10px; color: white; }
        .btn-track { background: #2874f0; }
        .btn-owner { background: #25D366; }
        .btn-user { background: #0084ff; }
        .btn-shop { background: #fb641b; }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="check-container"><i class="fa fa-check"></i></div>
        <h2>Order Successful!</h2>
        
        <a href="my_orders.php" class="btn btn-track">TRACK MY ORDER 📦</a>
        <a href="<?php echo $wa_link_owner; ?>" target="_blank" class="btn btn-owner">Notify Owner (WhatsApp)</a>
        <a href="<?php echo $wa_link_user; ?>" target="_blank" class="btn btn-user">Send Conf. to User</a>
        <a href="index.php" class="btn btn-shop">CONTINUE SHOPPING 🛒</a>
    </div>
</body>
</html>