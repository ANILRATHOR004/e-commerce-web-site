<?php
if (!isset($_GET['id']) || empty($_GET['id'])) { 
    header("Location: index.php"); 
    exit(); 
}

include('header.php');

$p_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$p_id'");

if (!$query || mysqli_num_rows($query) === 0) {
    die("<h3 style='text-align:center; padding:50px;'>Product nahi mila! <a href='index.php'>Go Back</a></h3>");
}

$row = mysqli_fetch_assoc($query);

$p_name = $row['product_name'] ?? ($row['item_name'] ?? 'Product');
$p_price = $row['product_price'] ?? ($row['price'] ?? 0);
$current_cat = $row['category'] ?? ($row['item_category'] ?? 'General');
$main_image = !empty($row['product_image']) ? 'uploads/' . $row['product_image'] : 'https://placehold.co/400x350?text=No+Image';
?>

<style>
    .details-container { max-width: 600px; margin: 10px auto 30px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.06); }
    .main-img { width: 100%; height: 320px; object-fit: contain; padding: 20px; box-sizing: border-box; }
    .details-section { padding: 18px; border-bottom: 1px solid #eee; }
    .btn-group { display: flex; gap: 10px; padding: 15px; background: #fff; border-top: 1px solid #eee; }
    .action-btn { flex: 1; padding: 14px; border: none; font-weight: bold; cursor: pointer; text-align: center; border-radius: 8px; font-size: 13px; text-transform: uppercase; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 15px; }
    .sim-card { border: 1px solid #eee; padding: 10px; border-radius: 8px; text-decoration: none; color: #000; text-align: center; background: #fafafa; }
    .sim-card img { width: 100%; height: 110px; object-fit: contain; }
</style>

<div class="details-container">
    <img src="<?php echo htmlspecialchars($main_image); ?>" onerror="this.src='https://placehold.co/400x350?text=No+Image'" class="main-img">
    
    <div class="details-section">
        <h2 style="font-size: 18px; margin: 0 0 8px;"><?php echo htmlspecialchars($p_name); ?></h2>
        <div style="font-size: 24px; font-weight: 800; color: #2874f0;">₹<?php echo number_format($p_price); ?></div>
        <div style="color: #2874f0; font-weight: bold; margin-top: 5px; font-size: 13px;"><i class="fa fa-shield-alt"></i> GwaliorStore Assured</div>
    </div>

    <div class="details-section">
        <h3 style="font-size: 15px; margin: 0 0 10px;">Product Highlights</h3>
        <table style="width: 100%; font-size: 13px; line-height: 24px;">
            <tr><td style="color: #666;">Category</td><td><b><?php echo htmlspecialchars($current_cat); ?></b></td></tr>
            <tr><td style="color: #666;">Material</td><td><b>Premium Quality</b></td></tr>
        </table>
    </div>

    <div class="details-section">
        <h3 style="font-size: 15px; margin: 0 0 10px;">Ratings & Reviews</h3>
        <div style="font-weight: bold; font-size: 22px; color: #2e7d32;">4.5 ★</div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin: 15px 0;">
            <?php
            $rev_query = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = '$p_id' ORDER BY id DESC");
            if ($rev_query && mysqli_num_rows($rev_query) > 0) {
                while($r = mysqli_fetch_assoc($rev_query)) {
                    echo '<img src="uploads/reviews/' . htmlspecialchars($r['img_name']) . '" onerror="this.style.display=\'none\'" style="width:65px; height:65px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">';
                }
            } else {
                echo '<p style="font-size:13px; color:#888;">No feedback photos yet.</p>';
            }
            ?>
        </div>
        
        <form action="upload_review.php" method="POST" enctype="multipart/form-data" style="background: #f9f9f9; padding: 12px; border-radius: 8px;">
            <input type="hidden" name="p_id" value="<?php echo $p_id; ?>">
            <label style="font-size: 12px; display: block; margin-bottom: 6px; font-weight: bold;">Upload your product photo:</label>
            <input type="file" name="review_img" required style="font-size: 12px; margin-bottom: 8px;">
            <button type="submit" style="background: #2874f0; color: white; border: none; padding: 7px 14px; cursor: pointer; border-radius: 4px; font-size: 12px; font-weight: bold;">Upload Feedback</button>
        </form>
    </div>

    <div class="details-section">
        <h3 style="font-size: 15px; margin: 0 0 10px;">Similar Products</h3>
        <div class="grid">
            <?php
            $sim = mysqli_query($conn, "SELECT * FROM products WHERE (category = '$current_cat' OR school_name = '{$row['school_name']}') AND id != '$p_id' LIMIT 4");
            if ($sim && mysqli_num_rows($sim) > 0) {
                while($s = mysqli_fetch_assoc($sim)) {
                    $s_img = !empty($s['product_image']) ? 'uploads/' . $s['product_image'] : 'https://placehold.co/150';
                    $s_name = $s['product_name'] ?? ($s['item_name'] ?? 'Product');
                    $s_price = $s['product_price'] ?? ($s['price'] ?? 0);
            ?>
                <a href="product_details.php?id=<?php echo $s['id']; ?>" class="sim-card">
                    <img src="<?php echo htmlspecialchars($s_img); ?>" onerror="this.src='https://placehold.co/150'">
                    <div style="font-size: 12px; margin: 6px 0; height: 30px; overflow: hidden;"><?php echo htmlspecialchars($s_name); ?></div>
                    <div style="font-weight: bold; color: #2874f0; font-size: 13px;">₹<?php echo number_format($s_price); ?></div>
                </a>
            <?php 
                }
            } else {
                echo '<p style="grid-column: 1/-1; color:#888; font-size:13px; text-align:center;">No similar items found.</p>';
            }
            ?>
        </div>
    </div>

    <div class="btn-group">
        <form action="cart_handler.php" method="POST" style="flex: 1;">
            <input type="hidden" name="id" value="<?php echo $p_id; ?>">
            <button type="submit" name="add_to_cart" class="action-btn" style="background: #fff; border: 2px solid #ccc; width: 100%;">ADD TO CART</button>
        </form>

        <form action="checkout.php" method="GET" style="flex: 1;">
            <input type="hidden" name="id" value="<?php echo $p_id; ?>">
            <button type="submit" class="action-btn" style="background: #fb641b; color: white; width: 100%;">BUY NOW</button>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>