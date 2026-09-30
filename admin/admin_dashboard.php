<?php 
include('../db.php'); 
session_start();

// --- 1. Basic Stats (Optimized) ---
$total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$total_data = mysqli_fetch_assoc($total_res);

$pending_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'");
$pending_data = mysqli_fetch_assoc($pending_res);

// AB REVENUE DIRECT ORDERS TABLE SE AAYEGA (FASTER & ACCURATE)
$sales_res = mysqli_query($conn, "SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'Delivered'");
$sales_data = mysqli_fetch_assoc($sales_res);
$total_revenue = $sales_data['revenue'] ?? 0;

// Bestselling Item
$best_res = mysqli_query($conn, "SELECT product_name, COUNT(*) as qty FROM orders GROUP BY product_name ORDER BY qty DESC LIMIT 1");
$best_item = mysqli_fetch_assoc($best_res);

// Low Stock Alert
$stock_alert = mysqli_query($conn, "SELECT item_name, stock FROM products WHERE stock < 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pro Max | GwaliorStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --f-blue: #2874f0; --a-orange: #fb641b; --bg: #f1f3f6; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; padding: 20px; }
        
        .header-pro { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 25px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .card { background: white; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border-bottom: 4px solid #ddd; transition: 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card.blue { border-color: var(--f-blue); }
        .card.orange { border-color: var(--a-orange); }
        .card.green { border-color: #388e3c; }
        
        .card i { font-size: 30px; opacity: 0.8; }
        .card-val { font-size: 24px; font-weight: 800; display: block; }
        .card-label { font-size: 11px; color: #777; font-weight: 700; text-transform: uppercase; }

        /* Quick Actions */
        .quick-actions { display: flex; gap: 10px; margin-bottom: 25px; overflow-x: auto; padding-bottom: 10px; }
        .action-btn { background: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; color: #333; font-size: 12px; font-weight: 700; border: 1px solid #ddd; white-space: nowrap; }
        .action-btn i { color: var(--f-blue); margin-right: 5px; }

        .main-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .box { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); }
        h4 { margin-top: 0; display: flex; align-items: center; gap: 10px; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 15px; color: #333; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: #999; padding: 12px 5px; font-size: 11px; text-transform: uppercase; }
        td { padding: 15px 5px; border-bottom: 1px solid #f5f5f5; font-size: 13px; }
        
        .badge { padding: 5px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; }
        .bg-pending { background: #fff3e0; color: #fb641b; }
        .bg-delivered { background: #e8f5e9; color: #388e3c; }

        .stock-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
        .low-tag { background: #ffebee; color: #d32f2f; padding: 2px 8px; border-radius: 4px; font-weight: 700; font-size: 10px; }

        .btn-view { background: var(--f-blue); color: white; text-decoration: none; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 13px; display: block; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="header-pro">
    <div>
        <h2 style="margin:0; font-size:20px;">GwaliorStore <span style="color:var(--f-blue)">Pro Panel</span></h2>
        <small style="color:#888;">Welcome back, Master Admin</small>
    </div>
    <div style="text-align: right;">
        <div style="font-size:12px; font-weight:700; color:var(--a-orange)">
            <i class="fa fa-fire"></i> Hot Sale: <?php echo $best_item['product_name'] ?? 'None'; ?>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="quick-actions">
    <a href="admin_add_product.php" class="action-btn"><i class="fa fa-plus-circle"></i> Add Product</a>
    <a href="admin_orders.php" class="action-btn"><i class="fa fa-list"></i> View Orders</a>
    <a href="admin_users.php" class="action-btn"><i class="fa fa-users"></i> Manage Users</a>
    <a href="index.php" class="action-btn" target="_blank"><i class="fa fa-external-link"></i> Live Store</a>
</div>

<div class="stats-grid">
    <div class="card blue">
        <i class="fa fa-shopping-bag" style="color:var(--f-blue)"></i>
        <div><span class="card-val"><?php echo $total_data['total']; ?></span><span class="card-label">Orders</span></div>
    </div>
    <div class="card orange">
        <i class="fa fa-clock" style="color:var(--a-orange)"></i>
        <div><span class="card-val"><?php echo $pending_data['total']; ?></span><span class="card-label">Pending</span></div>
    </div>
    <div class="card green">
        <i class="fa fa-indian-rupee-sign" style="color:#388e3c"></i>
        <div><span class="card-val">₹<?php echo number_format($total_revenue); ?></span><span class="card-label">Total Sale</span></div>
    </div>
</div>

<div class="main-layout">
    <div class="box">
        <h4><i class="fa fa-history" style="color:var(--f-blue)"></i> Recent Sales Feed</h4>
        <table>
            <thead><tr><th>Order</th><th>Customer</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
                <?php 
                $recent = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC LIMIT 6");
                while($r = mysqli_fetch_assoc($recent)) {
                    $st_class = ($r['status'] == 'Delivered') ? 'bg-delivered' : 'bg-pending';
                    echo "<tr>
                        <td>#GWR{$r['id']}</td>
                        <td><b>{$r['customer_name']}</b></td>
                        <td>₹{$r['total_amount']}</td>
                        <td><span class='badge {$st_class}'>{$r['status']}</span></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="admin_orders.php" class="btn-view">OPEN FULL ORDER LOG</a>
    </div>

    <div class="box">
        <h4><i class="fa fa-box-open" style="color:#d32f2f"></i> Inventory Watch</h4>
        <?php 
        if(mysqli_num_rows($stock_alert) > 0) {
            while($s = mysqli_fetch_assoc($stock_alert)) {
                echo "<div class='stock-item'>
                        <span style='font-size:13px; font-weight:600;'>{$s['item_name']}</span>
                        <span class='low-tag'>Stock: {$s['stock']}</span>
                      </div>";
            }
        } else {
            echo "<div style='text-align:center; padding:20px;'><i class='fa fa-check-circle' style='color:#4caf50; font-size:30px;'></i><p style='font-size:12px; color:#888;'>Inventory looks perfect!</p></div>";
        }
        ?>
        
        <div style="margin-top:20px; padding:15px; background:linear-gradient(135deg, #2874f0, #1e56b8); border-radius:12px; color:white;">
            <p style="margin:0; font-size:11px; line-height:1.4;"><strong>Business Advice:</strong> School session starts in July. Stock up on White PT shoes now!</p>
        </div>
    </div>
</div>

</body>
</html>