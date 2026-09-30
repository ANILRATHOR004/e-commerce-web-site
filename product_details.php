<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');

if (!isset($_GET['id']) || empty($_GET['id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$p_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$p_id'");

if (!$query || mysqli_num_rows($query) === 0) {
    die("<h3 style='text-align:center; padding:50px;'>Product nahi mila! <a href='index.php'>Go Back</a></h3>");
}

$row = mysqli_fetch_assoc($query);

$current_cat = $row['category'] ?? ($row['item_category'] ?? 'General');
$main_image = !empty($row['product_image']) ? 'uploads/' . $row['product_image'] : 'https://placehold.co/400x350?text=No+Image';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['product_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f1f3f6; margin: 0; padding-bottom: 70px; }
        .container { max-width: 600px; margin: auto; background: #fff; }
        .main-img { width: 100%; height: 350px; object-fit: contain; padding: 20px; box-sizing: border-box; }
        .section { padding: 20px; border-bottom: 1px solid #eee; }
        .btn-group { display: flex; gap: 10px; margin-top: 20px; position: sticky; bottom: 0; background: #fff; padding: 10px; border-top: 1px solid #ddd; }
        .btn { flex: 1; padding: 15px; border: none; font-weight: bold; cursor: pointer; text-align: center; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
        .sim-card { border: 1px solid #eee; padding: 10px; border-radius: 8px; text-decoration: none; color: #000; text-align: center; }
        .sim-card img { width: 100%; height: 120px; object-fit: contain; }
    </style>
</head>
<body>

<div class="container">
    <img src="<?php echo htmlspecialchars($main_image); ?>" onerror="this.src='https://placehold.co/400x350?text=No+Image'" class="main-img">
    
    <div class="section">
        <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
        <div style="font-size:24px; font-weight:bold; color:#2874f0;">₹<?php echo number_format($row['product_price']); ?></div>
        <div style="color:#2874f0; font-weight:bold; margin-top:5px;"><i class="fa fa-shield-alt"></i> GwaliorStore Assured</div>
    </div>

    <div class="section">
        <h3>Product Highlights</h3>
        <table style="width:100%; font-size:14px; line-height:24px;">
            <tr><td style="color:#666;">Category</td><td><b><?php echo htmlspecialchars($current_cat); ?></b></td></tr>
            <tr><td style="color:#666;">Material</td><td><b>Premium Quality</b></td></tr>
        </table>
    </div>

    <div class="section">
        <h3>Ratings & Reviews</h3>
        <div style="font-weight:bold; font-size:24px;">4.5 ★</div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin: 15px 0;">
            <?php
            $rev_query = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = '$p_id' ORDER BY id DESC");
            if ($rev_query && mysqli_num_rows($rev_query) > 0) {
                while($r = mysqli_fetch_assoc($rev_query)) {
                    echo '<img src="uploads/reviews/' . htmlspecialchars($r['img_name']) . '" onerror="this.style.display=\'none\'" style="width:70px; height:70px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">';
                }
            } else {
                echo '<p style="font-size:13px; color:#888;">No feedback photos yet.</p>';
            }
            ?>
        </div>
        
        <form action="upload_review.php" method="POST" enctype="multipart/form-data" style="background:#f9f9f9; padding:15px; border-radius:6px;">
            <input type="hidden" name="p_id" value="<?php echo $p_id; ?>">
            <label style="font-size:12px; display:block; margin-bottom:5px; font-weight:bold;">Upload your product photo:</label>
            <input type="file" name="review_img" required>
            <button type="submit" style="background:#2874f0; color:white; border:none; padding:8px 15px; cursor:pointer; border-radius:4px;">Upload Feedback</button>
        </form>
    </div>

    <div class="section">
        <h3>Similar Products</h3>
        <div class="grid">
            <?php
            $sim = mysqli_query($conn, "SELECT * FROM products WHERE (category = '$current_cat' OR school_name = '{$row['school_name']}') AND id != '$p_id' LIMIT 4");
            if ($sim && mysqli_num_rows($sim) > 0) {
                while($s = mysqli_fetch_assoc($sim)) {
                    $s_img = !empty($s['product_image']) ? 'uploads/' . $s['product_image'] : 'https://placehold.co/150';
            ?>
                <a href="product_details.php?id=<?php echo $s['id']; ?>" class="sim-card">
                    <img src="<?php echo htmlspecialchars($s_img); ?>" onerror="this.src='https://placehold.co/150'">
                    <div style="font-size:13px; margin:5px 0;"><?php echo htmlspecialchars($s['product_name']); ?></div>
                    <div style="font-weight:bold; color:#2874f0;">₹<?php echo number_format($s['product_price']); ?></div>
                </a>
            <?php 
                }
            } else {
                echo '<p style="grid-column: 1/-1; color:#888; font-size:13px;">No similar items found.</p>';
            }
            ?>
        </div>
    </div>

    <div class="btn-group">
        <form action="cart_handler.php" method="POST" style="flex:1;">
            <input type="hidden" name="id" value="<?php echo $p_id; ?>">
            <button type="submit" name="add_to_cart" class="btn" style="background:#fff; border:1px solid #ddd; width:100%;">ADD TO CART</button>
        </form>

        <form action="checkout.php" method="GET" style="flex:1;">
            <input type="hidden" name="id" value="<?php echo $p_id; ?>">
            <button type="submit" class="btn" style="background:#fb641b; color:white; width:100%;">BUY NOW</button>
        </form>
    </div>
</div>
</body>
</html>