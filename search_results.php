<?php 
include('db.php'); 
session_start(); 

// Search query pakadna
$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

// Gwalior ke schools ki list
$schools_list = [
    "The Scindia School", 
    "Scindia Kanya Vidyalaya", 
    "Gwalior Glory High School", 
    "DPS Gwalior",
    "Little Angels High School",
    "St. Pauls School",
    "billa bong",
    "gurukul school"
];

// Check karna ki kya search query kisi school se match ho rahi hai
$matched_school = "";
foreach($schools_list as $school) {
    if(stripos($school, $q) !== false) {
        $matched_school = $school;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | GwaliorStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; padding-bottom: 80px; }
        .header { background: #2874f0; padding: 15px; color: white; display: flex; align-items: center; gap: 15px; position: sticky; top: 0; z-index: 100; }
        .header a { color: white; text-decoration: none; font-size: 20px; }
        
        .results-info { padding: 15px 20px; background: white; border-bottom: 1px solid #eee; font-size: 14px; color: #666; }
        .results-info b { color: #2874f0; }

        /* School Card Style */
        .school-card {
            background: white;
            margin: 30px 20px;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-top: 5px solid #2874f0;
        }
        .school-card i { font-size: 50px; color: #2874f0; margin-bottom: 15px; }
        .school-card h2 { font-size: 22px; margin: 10px 0; color: #333; text-transform: uppercase; }
        .btn-enter { 
            display: inline-block; 
            background: #2874f0; 
            color: white; 
            padding: 14px 30px; 
            border-radius: 50px; 
            text-decoration: none; 
            font-weight: 700; 
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(40, 116, 240, 0.3);
        }

        /* Product Grid Style */
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; padding: 15px; }
        .product-card { background: white; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; }
        .product-card h3 { font-size: 13px; margin: 8px 0; color: #333; height: 32px; overflow: hidden; }
        .price { font-size: 16px; font-weight: 700; color: #1b5e20; margin-bottom: 10px; }
        .btn-buy { display: block; background: #fb641b; color: white; text-align: center; padding: 8px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; }

        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; display: flex; justify-content: space-around; padding: 12px 0; border-top: 1px solid #eee; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); }
        .nav-link { text-decoration: none; color: #9ca3af; text-align: center; font-size: 10px; flex: 1; }
        .nav-link i { display: block; font-size: 22px; margin-bottom: 4px; }
    </style>
</head>
<body>

    <header class="header">
        <a href="index.php"><i class="fa fa-arrow-left"></i></a>
        <div style="font-weight: 700;">Search Results</div>
    </header>

    <div class="results-info">
        Showing results for <b>"<?php echo $q; ?>"</b>
    </div>

    <?php if($matched_school != ""): ?>
        
        <div class="school-card">
            <i class="fa fa-school"></i>
            <h2><?php echo $matched_school; ?></h2>
            <p style="font-size: 14px; color: #777;">GwaliorStore's official collection for this school.</p>
            <a href="school_items.php?school=<?php echo urlencode($matched_school); ?>" class="btn-enter">
                GO TO SCHOOL STORE <i class="fa fa-chevron-right"></i>
            </a>
        </div>

    <?php else: ?>
        
        <div class="products-grid">
            <?php
            $sql = "SELECT * FROM products WHERE item_name LIKE '%$q%' OR category LIKE '%$q%'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="product-card">
                        <div style="font-size: 9px; color: #2874f0; font-weight: 700; text-transform: uppercase;"><?php echo $row['school_name']; ?></div>
                        <h3><?php echo $row['item_name']; ?></h3>
                        <div class="price">₹<?php echo $row['price']; ?></div>
                        <a href="order.php?item=<?php echo urlencode($row['item_name']); ?>" class="btn-buy">Buy Now</a>
                    </div>
                    <?php
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px; color: #888;'>
                        Oops! Koi product nahi mila.
                      </div>";
            }
            ?>
        </div>

    <?php endif; ?>

    <nav class="bottom-nav">
        <a href="index.php" class="nav-link"><i class="fa fa-home"></i><span>Home</span></a>
        <a href="my_orders.php" class="nav-link"><i class="fa fa-box"></i><span>Orders</span></a>
        <a href="cart.php" class="nav-link"><i class="fa fa-shopping-cart"></i><span>Cart</span></a>
        <a href="account.php" class="nav-link"><i class="fa fa-user-circle"></i><span>Account</span></a>
    </nav>

</body>
</html>