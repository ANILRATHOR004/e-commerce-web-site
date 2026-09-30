<?php 
// Session error fix
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('db.php'); 

$wallet_balance = 0;
if(isset($_SESSION['user_id'])){
    $u_id = $_SESSION['user_id'];
    $user_res = mysqli_query($conn, "SELECT wallet_balance FROM users WHERE id = '$u_id'");
    if($user_res && mysqli_num_rows($user_res) > 0) {
        $user_data = mysqli_fetch_assoc($user_res);
        $wallet_balance = $user_data['wallet_balance'] ?? 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GwaliorStore | Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #2874f0; --bg: #f1f3f6; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; padding-bottom: 80px; }
        .top-header { background: var(--primary); padding: 15px; text-align: center; }
        .logo { font-size: 20px; font-weight: 800; color: white; text-decoration: none; }
        
        /* Search Box CSS */
        .search-container { max-width: 600px; margin: 15px auto; padding: 0 15px; }
        .search-form { display: flex; background: #fff; border-radius: 25px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .search-input { width: 100%; border: none; padding: 12px 20px; font-size: 14px; outline: none; }
        .search-btn { background: var(--primary); border: none; color: white; padding: 0 20px; cursor: pointer; }
        
        .user-section { background: #fff; padding: 15px; text-align: center; border-radius: 0 0 20px 20px; margin-bottom: 15px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; padding: 10px 15px; max-width: 1200px; margin: auto; }
        .product-card { background: #fff; border-radius: 8px; padding: 12px; text-align: center; text-decoration: none; color: #000; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        
        /* Navigation Bar CSS */
        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; display: flex; justify-content: space-around; padding: 10px 0; border-top: 1px solid #eee; z-index: 1000; }
        .nav-link { text-decoration: none; color: #9ca3af; text-align: center; font-size: 11px; font-weight: 700; }
        .nav-link i { display: block; font-size: 20px; margin-bottom: 3px; }
        .nav-link.active { color: var(--primary); }
    </style>
</head>
<body>
    <header class="top-header">
        <a href="index.php" class="logo">welcome to schools hub.</a>
    </header>
    
    <!-- School Search Bar -->
    <div class="search-container">
        <form action="search_results.php" method="GET" class="search-form">
            <input type="text" name="query" class="search-input" placeholder="Search by School Name or Product..." required>
            <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <div class="user-section">
        <?php if(isset($_SESSION['user_name'])): ?>
            <div style="background:#eff6ff; color:#2874f0; padding:5px 15px; border-radius:20px; display:inline-block; font-size:12px; font-weight:700; margin-bottom:5px;">Balance: ₹<?php echo number_format($wallet_balance); ?></div>
            <h2 style="font-size:18px; margin:0;"><?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h2>
        <?php else: ?>
            <a href="login.php" style="color:var(--primary); font-weight:700; text-decoration:none;">Login to Start Shopping</a>
        <?php endif; ?>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">
        <?php
        $products_query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 20");
        
        if (!$products_query) {
            echo "<p style='grid-column: 1/-1; text-align:center; color:red;'>Table Error: " . mysqli_error($conn) . "</p>";
        } elseif (mysqli_num_rows($products_query) === 0) {
            echo "<p style='grid-column: 1/-1; text-align:center; color:#666;'>Abhi koi product available nahi hai. Admin panel se add karein.</p>";
        } else {
            while($row = mysqli_fetch_assoc($products_query)) {
                $p_image = !empty($row['product_image']) ? $row['product_image'] : 'default.png';
                $p_name = $row['product_name'] ?? ($row['name'] ?? 'Product');
                $p_price = $row['product_price'] ?? ($row['price'] ?? 0);
        ?>
            <a href="product_details.php?id=<?php echo $row['id']; ?>" class="product-card">
                <img src="uploads/<?php echo htmlspecialchars($p_image); ?>" onerror="this.src='https://via.placeholder.com/150'" style="width:100%; height:120px; object-fit:contain;">
                <div style="font-size:13px; font-weight:600; margin-top:10px;"><?php echo htmlspecialchars($p_name); ?></div>
                <div style="font-weight:800; margin-top:5px; color:#2874f0;">₹<?php echo number_format($p_price); ?></div>
            </a>
        <?php 
            }
        } 
        ?>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="index.php" class="nav-link active"><i class="fa fa-home"></i><span>HOME</span></a>
        <a href="my_orders.php" class="nav-link"><i class="fa fa-box"></i><span>ORDERS</span></a>
        <a href="cart.php" class="nav-link"><i class="fa fa-shopping-cart"></i><span>CART</span></a>
        <a href="account.php" class="nav-link"><i class="fa fa-user-circle"></i><span>ACCOUNT</span></a>
    </nav>
</body>
</html>