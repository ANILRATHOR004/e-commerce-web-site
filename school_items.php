<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');
if (!$conn) {
    die("<h2 style='color:red; text-align:center;'>Database Connection Fail: " . mysqli_connect_error() . "</h2>");
}
$school_name = isset($_GET['school']) ? mysqli_real_escape_string($conn, $_GET['school']) : '';
$cat = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : 'books'; 
$class = isset($_GET['class']) ? mysqli_real_escape_string($conn, $_GET['class']) : ''; 
$sub = isset($_GET['sub']) ? mysqli_real_escape_string($conn, $_GET['sub']) : ''; 
$gender = isset($_GET['gender']) ? mysqli_real_escape_string($conn, $_GET['gender']) : ''; 
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : ''; 

$grades = ['Nursery', 'LKG', 'UKG', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'Other'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($school_name); ?> | GwaliorStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #2874f0; --secondary: #fb641b; --bg: #f4f7fa; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; padding-bottom: 90px; }
        .header { background: var(--primary); color: white; padding: 18px 15px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header-left { display: flex; align-items: center; }
        .header-left a { color: white; text-decoration: none; margin-right: 15px; font-size: 22px; }
        .header-title { font-size: 16px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: white; }
        .cart-icon-btn { color: white; text-decoration: none; font-size: 22px; position: relative; padding-right: 5px; }
        .cart-count { position: absolute; top: -8px; right: -8px; background: var(--secondary); color: white; font-size: 11px; padding: 2px 7px; border-radius: 50%; font-weight: 700; }
        .category-nav { display: flex; background: #fff; overflow-x: auto; padding: 12px; gap: 10px; position: sticky; top: 58px; z-index: 999; border-bottom: 1px solid #e0e0e0; scrollbar-width: none; }
        .nav-btn { padding: 10px 20px; background: #f0f3f6; border-radius: 30px; text-decoration: none; color: #555; font-size: 11px; font-weight: 700; white-space: nowrap; transition: 0.3s; }
        .nav-btn.active { background: var(--primary); color: #fff; }
        .filter-card { background: #fff; margin: 12px; padding: 18px; border-radius: 18px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .label-text { font-size: 12px; font-weight: 800; color: #444; margin-bottom: 12px; display: block; border-left: 4px solid var(--primary); padding-left: 8px; }
        .class-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .class-item { background: #fff; border: 1.5px solid #eee; padding: 12px 2px; border-radius: 10px; text-align: center; text-decoration: none; color: #555; font-size: 10px; font-weight: 700; }
        .class-item.active { border-color: var(--primary); background: #eff6ff; color: var(--primary); }
        .select-box { width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #f0f0f0; margin-bottom: 10px; font-family: inherit; font-weight: 600; outline: none; background: #fafafa; font-size: 13px; }
        .items-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 12px; }
        .item-card { background: #fff; border-radius: 15px; padding: 12px; text-align: center; border: 1px solid #f0f0f0; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        .item-card img { width: 100%; height: 110px; object-fit: contain; margin-bottom: 10px; }
        .item-title { font-size: 13px; font-weight: 700; height: 34px; overflow: hidden; margin-bottom: 3px; color: #333; text-align: center; }
        .item-desc { font-size: 11px; color: #666; margin-bottom: 8px; min-height: 28px; overflow: hidden; text-align: center; background: #f9f9f9; padding: 4px; border-radius: 6px; }
        .item-price { font-size: 16px; font-weight: 800; color: #1b5e20; margin-bottom: 8px; }
        .buy-btn { background: var(--secondary); color: white; border: none; width: 100%; padding: 11px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 13px; text-transform: uppercase; }
        .buy-btn:hover { background: #e05310; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <a href="index.php"><i class="fa fa-arrow-left"></i></a>
            <div class="header-title"><?php echo htmlspecialchars($school_name); ?></div>
        </div>
        <a href="cart.php" class="cart-icon-btn">
            <i class="fa fa-shopping-cart"></i>
            <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
        </a>
    </header>

    <div class="category-nav">
        <a href="?school=<?php echo urlencode($school_name); ?>&cat=books" class="nav-btn <?php echo ($cat == 'books') ? 'active' : ''; ?>">📚 BOOKS</a>
        <a href="?school=<?php echo urlencode($school_name); ?>&cat=shoes" class="nav-btn <?php echo ($cat == 'shoes') ? 'active' : ''; ?>">👟 SHOES</a>
        <a href="?school=<?php echo urlencode($school_name); ?>&cat=dress" class="nav-btn <?php echo ($cat == 'dress') ? 'active' : ''; ?>">👕 DRESS</a>
        <a href="?school=<?php echo urlencode($school_name); ?>&cat=other" class="nav-btn <?php echo ($cat == 'other') ? 'active' : ''; ?>">🛍️ OTHER</a>
    </div>

    <?php if($cat == 'books'): ?>
    <div class="filter-card">
        <span class="label-text">Select Class / Grade:</span>
        <div class="class-grid">
            <?php foreach($grades as $g): ?>
                <a href="?school=<?php echo urlencode($school_name); ?>&cat=books&class=<?php echo urlencode($g . (($g=='11' || $g=='12') ? 'th' : '')); ?>" 
                   class="class-item <?php echo ($class == $g.'th' || $class == $g) ? 'active' : ''; ?>"><?php echo $g; ?></a>
            <?php endforeach; ?>
        </div>

        <form method="GET" style="margin-top:15px;">
            <input type="hidden" name="school" value="<?php echo htmlspecialchars($school_name); ?>">
            <input type="hidden" name="cat" value="books">
            <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
            <span class="label-text">Select Stream (For 11th / 12th):</span>
            <select name="sub" class="select-box" onchange="this.form.submit()">
                <option value="">-- Choose Stream / Subject --</option>
                <option value="Maths" <?php if($sub == 'Maths') echo 'selected'; ?>>Mathematics (PCM)</option>
                <option value="Biology" <?php if($sub == 'Biology') echo 'selected'; ?>>Biology (PCB)</option>
                <option value="Commerce" <?php if($sub == 'Commerce') echo 'selected'; ?>>Commerce</option>
                <option value="Arts" <?php if($sub == 'Arts') echo 'selected'; ?>>Arts (Humanities)</option>
                <option value="Agriculture" <?php if($sub == 'Agriculture') echo 'selected'; ?>>Agriculture</option>
            </select>
        </form>
    </div>
    <?php endif; ?>

    <?php if($cat == 'shoes'): ?>
    <div class="filter-card">
        <form method="GET">
            <input type="hidden" name="school" value="<?php echo htmlspecialchars($school_name); ?>">
            <input type="hidden" name="cat" value="shoes">
            
            <span class="label-text">Style & Gender:</span>
            <div style="display: flex; gap: 8px;">
                <select name="sub" class="select-box" onchange="this.form.submit()">
                    <option value="">-- Style --</option>
                    <option value="Sports Shoes" <?php if($sub == 'Sports Shoes') echo 'selected'; ?>>Sports Shoes</option>
                    <option value="Black Shoes" <?php if($sub == 'Black Shoes') echo 'selected'; ?>>Black Shoes</option>
                    <option value="White PT Shoes" <?php if($sub == 'White PT Shoes') echo 'selected'; ?>>White PT Shoes</option>
                </select>
                <select name="gender" class="select-box" onchange="this.form.submit()">
                    <option value="">-- Gender --</option>
                    <option value="Boys" <?php if($gender == 'Boys') echo 'selected'; ?>>Boys</option>
                    <option value="Girls" <?php if($gender == 'Girls') echo 'selected'; ?>>Girls</option>
                </select>
            </div>
            
            <span class="label-text">Size Options:</span>
            <select name="size" class="select-box" onchange="this.form.submit()">
                <option value="">-- Select Size --</option>
                <?php for($s=1; $s<=10; $s++) echo "<option value='$s' ".($size==$s?'selected':'').">Size $s</option>"; ?>
                <option value="7-8 Years" <?php if($size == '7-8 Years') echo 'selected'; ?>>7-8 Years</option>
                <option value="9-10 Years" <?php if($size == '9-10 Years') echo 'selected'; ?>>9-10 Years</option>
            </select>
        </form>
    </div>
    <?php endif; ?>

    <?php if($cat == 'dress'): ?>
    <div class="filter-card">
        <form method="GET">
            <input type="hidden" name="school" value="<?php echo htmlspecialchars($school_name); ?>">
            <input type="hidden" name="cat" value="dress">
            
            <span class="label-text">Gender & Uniform Type:</span>
            <div style="display: flex; gap: 8px;">
                <select name="gender" class="select-box" onchange="this.form.submit()">
                    <option value="">-- Gender --</option>
                    <option value="Boys" <?php if($gender == 'Boys') echo 'selected'; ?>>Boys</option>
                    <option value="Girls" <?php if($gender == 'Girls') echo 'selected'; ?>>Girls</option>
                </select>
                <select name="sub" class="select-box" onchange="this.form.submit()">
                    <option value="">-- Type --</option>
                    <option value="Summer Uniform" <?php if($sub == 'Summer Uniform') echo 'selected'; ?>>Summer Uniform</option>
                    <option value="Winter Uniform" <?php if($sub == 'Winter Uniform') echo 'selected'; ?>>Winter Uniform</option>
                    <option value="Sports Dress" <?php if($sub == 'Sports Dress') echo 'selected'; ?>>Sports Dress</option>
                </select>
            </div>
            
            <span class="label-text">Size Options:</span>
            <select name="size" class="select-box" onchange="this.form.submit()">
                <option value="">-- Select Size --</option>
                <option value="S" <?php if($size == 'S') echo 'selected'; ?>>S (Small)</option>
                <option value="M" <?php if($size == 'M') echo 'selected'; ?>>M (Medium)</option>
                <option value="L" <?php if($size == 'L') echo 'selected'; ?>>L (Large)</option>
                <option value="XL" <?php if($size == 'XL') echo 'selected'; ?>>XL (Extra Large)</option>
                <option value="XXL" <?php if($size == 'XXL') echo 'selected'; ?>>XXL (Double Extra Large)</option>
                <option value="22" <?php if($size == '22') echo 'selected'; ?>>Size 22 (5T)</option>
                <option value="24" <?php if($size == '24') echo 'selected'; ?>>Size 24 (6T)</option>
                <option value="26" <?php if($size == '26') echo 'selected'; ?>>Size 26 (7T)</option>
                <option value="28" <?php if($size == '28') echo 'selected'; ?>>Size 28 (8T)</option>
                <?php for($i=30; $i<=44; $i+=2): ?>
                    <option value="<?php echo $i; ?>" <?php if($size == $i) echo 'selected'; ?>>Size <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>
    <?php endif; ?>

    <?php if($cat == 'other'): ?>
    <div class="filter-card">
        <form method="GET">
            <input type="hidden" name="school" value="<?php echo htmlspecialchars($school_name); ?>">
            <input type="hidden" name="cat" value="other">
            <span class="label-text">Select Stationery & Essentials:</span>
            <select name="sub" class="select-box" onchange="this.form.submit()">
                <option value="">-- Choose Item --</option>
                <option value="Bag" <?php if($sub == 'Bag') echo 'selected'; ?>>School Bags</option>
                <option value="Bottle" <?php if($sub == 'Bottle') echo 'selected'; ?>>Water Bottles</option>
                <option value="Lunch" <?php if($sub == 'Lunch') echo 'selected'; ?>>Lunch Boxes</option>
                <option value="Notebook" <?php if($sub == 'Notebook') echo 'selected'; ?>>Notebooks / Copies</option>
            </select>
        </form>
    </div>
    <?php endif; ?>

    <main class="items-grid">
        <?php
        $sql = "SELECT * FROM products WHERE school_name = '$school_name'";
        
        if(!empty($gender)) { $sql .= " AND gender LIKE '%$gender%'"; }
        if(!empty($size))   { $sql .= " AND item_size LIKE '%$size%'"; }
        
        if(!empty($class))  { 
            $clean_class = str_replace('th', '', $class);
            $sql .= " AND (item_class LIKE '%$clean_class%')"; 
        }
        
        if(!empty($sub))    { 
            $clean_sub = str_replace(' Shoes', '', $sub);
            $clean_sub = str_replace(' Uniform', '', $clean_sub);
            $clean_sub = str_replace(' Dress', '', $clean_sub);
            $sql .= " AND (item_type LIKE '%$clean_sub%' OR stream_name LIKE '%$sub%' OR product_name LIKE '%$clean_sub%')"; 
        }

        $res = mysqli_query($conn, $sql);

        if($res) {
            if(mysqli_num_rows($res) > 0){
                $has_items = false;
                while($row = mysqli_fetch_assoc($res)){
                    
                    $p_name = isset($row['product_name']) ? $row['product_name'] : 'School Item';
                    $name_lower = strtolower($p_name);
                    $db_item_cat = isset($row['item_category']) ? strtolower($row['item_category']) : '';
                    $should_show = false;

                    if($cat == 'books' && ($db_item_cat == 'books' || strpos($name_lower, 'book') !== false)) {
                        $should_show = true;
                    } elseif($cat == 'shoes' && ($db_item_cat == 'shoes' || strpos($name_lower, 'shoe') !== false || strpos($name_lower, 'shose') !== false)) {
                        $should_show = true;
                    } elseif($cat == 'dress' && ($db_item_cat == 'dress' || $db_item_cat == 'uniform' || strpos($name_lower, 'dress') !== false || strpos($name_lower, 'uniform') !== false)) {
                        $should_show = true;
                    } elseif($cat == 'other' && ($db_item_cat == 'other' || $db_item_cat == 'stationery')) {
                        $should_show = true;
                    }

                    if($should_show) {
                        $has_items = true;
                        $p_id = isset($row['id']) ? $row['id'] : rand(1, 999);
                        $p_price = isset($row['product_price']) ? $row['product_price'] : (isset($row['price']) ? $row['price'] : '0');
                        
                        $p_image = 'default.jpg';
                        if(!empty($row['product_image'])) { $p_image = $row['product_image']; }
                        elseif(!empty($row['image'])) { $p_image = $row['image']; }

                        $p_desc = "";
                        if(!empty($row['item_type']) && $row['item_type'] != 'N/A') { $p_desc .= $row['item_type']; }
                        if(!empty($row['item_size']) && $row['item_size'] != 'N/A') { $p_desc .= " (Size: ".$row['item_size'].")"; }
                        if(!empty($row['item_color']) && $row['item_color'] != 'N/A') { $p_desc .= " - Color: ".$row['item_color']; }
                        if(!empty($row['stream_name']) && $row['stream_name'] != 'N/A') { $p_desc .= " [".$row['stream_name']."]"; }
                        if(empty(trim($p_desc))) { $p_desc = "High quality school essential."; }

                        // FORM ACTION LINKED: Form ko add_to_cart.php par redirect kiya gaya hai aur counter variable pass ho raha hai
                        echo '<div class="item-card">
                                <form method="POST" action="add_to_cart.php">
                                    <img src="uploads/'.$p_image.'">
                                    <div class="item-title">'.htmlspecialchars($p_name).'</div>
                                    <div class="item-desc">'.htmlspecialchars($p_desc).'</div>
                                    <div class="item-price">₹'.$p_price.'</div>
                                    
                                    <input type="hidden" name="product_id" value="'.$p_id.'">
                                    <input type="hidden" name="product_name" value="'.htmlspecialchars($p_name).'">
                                    <input type="hidden" name="product_price" value="'.$p_price.'">
                                    <input type="hidden" name="product_image" value="'.$p_image.'">
                                    <input type="hidden" name="add_to_cart" value="1">
                                    
                                    <button type="submit" name="add_to_cart" class="buy-btn">Add to Cart</button>
                                </form>
                              </div>';
                    }
                }
                if(!$has_items) {
                    echo "<div style='grid-column:span 2; text-align:center; padding:50px; color:#999;'>No items found in this category.</div>";
                }
            } else { 
                echo "<div style='grid-column:span 2; text-align:center; padding:50px; color:#999;'>No items found for this school.</div>"; 
            }
        } else { 
            echo "<div style='color:red;'>SQL Error: " . mysqli_error($conn) . "</div>"; 
        }
        ?>
    </main>
</body>
</html>