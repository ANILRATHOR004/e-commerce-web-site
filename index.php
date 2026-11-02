<?php
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
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; padding-bottom: 80px; }
        
        .top-header { background: var(--primary); padding: 12px 15px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .menu-btn { color: white; font-size: 20px; cursor: pointer; border: none; background: none; }
        .logo { font-size: 17px; font-weight: 800; color: white; text-decoration: none; }
        .header-cart { color: white; font-size: 18px; text-decoration: none; }

        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; display: none; }
        .sidebar { position: fixed; top: 0; left: -280px; width: 280px; height: 100%; background: white; z-index: 1000; transition: left 0.3s ease; display: flex; flex-direction: column; overflow-y: auto; }
        .sidebar.active { left: 0; }
        .sidebar-header { background: var(--primary); color: white; padding: 18px 15px; display: flex; justify-content: space-between; align-items: center; font-size: 16px; font-weight: 700; }
        .close-btn { color: white; font-size: 22px; cursor: pointer; background: none; border: none; }
        .sidebar-menu { list-style: none; padding: 10px 0; margin: 0; }
        .sidebar-title { font-size: 11px; font-weight: 800; color: #888; text-transform: uppercase; padding: 12px 15px 5px; }
        .sidebar-menu li a { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; color: #333; text-decoration: none; font-size: 14px; border-bottom: 1px solid #f3f4f6; }
        .sidebar-menu li a:hover { background: #eff6ff; color: var(--primary); }
        .sidebar-menu li a i.left-icon { width: 22px; color: var(--primary); }
        .sidebar-menu li a i.fa-chevron-right { font-size: 11px; color: #bbb; }

        .search-container { max-width: 600px; margin: 15px auto; padding: 0 15px; }
        .search-form { display: flex; background: #fff; border-radius: 25px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .search-input { width: 100%; border: none; padding: 12px 20px; font-size: 14px; outline: none; }
        .search-btn { background: var(--primary); border: none; color: white; padding: 0 20px; cursor: pointer; }
        
        .user-section { background: #fff; padding: 15px; text-align: center; border-radius: 0 0 20px 20px; margin-bottom: 15px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; padding: 10px 15px; max-width: 1200px; margin: auto; }
        .product-card { background: #fff; border-radius: 8px; padding: 12px; text-align: center; text-decoration: none; color: #000; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        
        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; display: flex; justify-content: space-around; padding: 10px 0; border-top: 1px solid #eee; z-index: 900; }
        .nav-link { text-decoration: none; color: #9ca3af; text-align: center; font-size: 11px; font-weight: 700; }
        .nav-link i { display: block; font-size: 20px; margin-bottom: 3px; }
        .nav-link.active { color: var(--primary); }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span><i class="fa fa-user-circle"></i> Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
            <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        </div>
        <ul class="sidebar-menu">
            <div class="sidebar-title">Schools</div>
            <li><a href="search_results.php?query=Scindia"><i class="fa fa-school left-icon"></i> The Scindia School <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="search_results.php?query=DPS"><i class="fa fa-school left-icon"></i> DPS Gwalior <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="search_results.php?query=Kanya"><i class="fa fa-school left-icon"></i> Scindia Kanya Vidyalaya <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="search_results.php?query=Glory"><i class="fa fa-school left-icon"></i> Gwalior Glory High School <i class="fa fa-chevron-right"></i></a></li>

            <div class="sidebar-title">Categories</div>
            <li><a href="search_results.php?query=books"><i class="fa fa-book left-icon"></i> Books & Notebooks <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="search_results.php?query=uniform"><i class="fa fa-tshirt left-icon"></i> School Uniforms <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="search_results.php?query=stationery"><i class="fa fa-pencil-alt left-icon"></i> Other Products <i class="fa fa-chevron-right"></i></a></li>

            <div class="sidebar-title">Account & Orders</div>
            <li><a href="my_orders.php"><i class="fa fa-box left-icon"></i> My Orders <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="cart.php"><i class="fa fa-shopping-cart left-icon"></i> My Cart <i class="fa fa-chevron-right"></i></a></li>
            <li><a href="account.php"><i class="fa fa-user-cog left-icon"></i> My Profile <i class="fa fa-chevron-right"></i></a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php"><i class="fa fa-sign-out-alt left-icon"></i> Logout <i class="fa fa-chevron-right"></i></a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fa fa-sign-in-alt left-icon"></i> Login <i class="fa fa-chevron-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <header class="top-header">
        <button class="menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
        <a href="index.php" class="logo">welcome to schools hub.</a>
        <a href="cart.php" class="header-cart"><i class="fa fa-shopping-cart"></i></a>
    </header>
    
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

    <nav class="bottom-nav">
        <a href="index.php" class="nav-link active"><i class="fa fa-home"></i><span>HOME</span></a>
        <a href="my_orders.php" class="nav-link"><i class="fa fa-box"></i><span>ORDERS</span></a>
        <a href="cart.php" class="nav-link"><i class="fa fa-shopping-cart"></i><span>CART</span></a>
        <a href="account.php" class="nav-link"><i class="fa fa-user-circle"></i><span>ACCOUNT</span></a>
    </nav>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const isActive = sidebar.classList.contains('active');

        if (isActive) {
            sidebar.classList.remove('active');
            overlay.style.display = 'none';
        } else {
            sidebar.classList.add('active');
            overlay.style.display = 'block';
        }
    }
    </script>
</body>
</html>