<?php
include('db.php');
session_start();

// Database se saare Pending orders nikalna
$orders_query = mysqli_query($conn, "SELECT * FROM orders WHERE status = 'Pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard | GwaliorStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f3f6; margin: 0; padding: 20px; }
        .header { background: #fb641b; color: white; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .order-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #2874f0; }
        .customer-name { font-size: 18px; font-weight: 800; color: #212121; margin: 0 0 5px 0; }
        .address-box { background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 14px; color: #555; margin-bottom: 15px; border: 1px dashed #ccc; }
        
        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { flex: 1; padding: 12px; border-radius: 6px; text-align: center; text-decoration: none; font-weight: 600; color: white; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        
        /* Smart Buttons CSS */
        .btn-call { background: #008CBA; } /* Blue for Call */
        .btn-whatsapp { background: #25D366; } /* Green for WhatsApp */
        .btn-deliver { background: #4CAF50; flex: 100%; margin-top: 10px; } /* Big Green for Mark Delivered */
        
        .btn:hover { filter: brightness(1.1); transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="header">
        <h2><i class="fa fa-motorcycle"></i> Delivery Partner Dashboard</h2>
    </div>

    <div class="orders-container">
        <?php
        if(mysqli_num_rows($orders_query) > 0) {
            while($order = mysqli_fetch_assoc($orders_query)) {
                $order_id = $order['id'];
                $c_name = htmlspecialchars($order['customer_name']);
                $c_phone = htmlspecialchars($order['phone']);
                $c_address = htmlspecialchars($order['address']);
                
                // Adaptive column handler (Kyunki tumhari table me product_name/name/item_name ho sakta hai)
                $p_name = isset($order['product_name']) ? $order['product_name'] : (isset($order['name']) ? $order['name'] : (isset($order['item_name']) ? $order['item_name'] : 'Item'));
                $amount = $order['total_amount'];
        ?>
        
        <div class="order-card">
            <h3 class="customer-name"><?php echo $c_name; ?> <span style="float:right; color:#fb641b;">₹<?php echo $amount; ?> (COD)</span></h3>
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #888;"><b>Order ID:</b> #<?php echo $order_id; ?> | <b>Item:</b> <?php echo htmlspecialchars($p_name); ?></p>
            
            <div class="address-box">
                <i class="fa fa-map-marker-alt" style="color: #ea4335;"></i> <b>Drop Address:</b><br>
                <?php echo $c_address; ?>
            </div>

            <div class="action-buttons">
                <a href="tel:<?php echo $c_phone; ?>" class="btn btn-call">
                    <i class="fa fa-phone-alt"></i> Call: <?php echo $c_phone; ?>
                </a>

                <a href="https://wa.me/91<?php echo $c_phone; ?>?text=Hello%20<?php echo urlencode($c_name); ?>,%20Main%20GwaliorStore%20se%20aapka%20order%20lekar%20aa%20raha%20hu.%20Kripya%20location%20bata%20dein." target="_blank" class="btn btn-whatsapp">
                    <i class="fab fa-whatsapp" style="font-size: 18px;"></i> Message
                </a>
                
                <a href="#" class="btn btn-deliver" onclick="alert('Order #<?php echo $order_id; ?> Delivered!')">
                    <i class="fa fa-check-circle"></i> Mark as Delivered
                </a>
            </div>
        </div>

        <?php 
            } 
        } else {
            echo "<h3 style='text-align:center; color:#888;'>Aaj ke liye koi naya order nahi hai! Chill maaro 😎</h3>";
        }
        ?>
    </div>

</body>
</html>