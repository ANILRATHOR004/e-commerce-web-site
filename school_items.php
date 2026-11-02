<?php
include('header.php');

$school_name = isset($_GET['school']) ? mysqli_real_escape_string($conn, $_GET['school']) : '';
$cat = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : 'books'; 
$class = isset($_GET['class']) ? mysqli_real_escape_string($conn, $_GET['class']) : ''; 
$board = isset($_GET['board']) ? mysqli_real_escape_string($conn, $_GET['board']) : ''; 
$sub = isset($_GET['sub']) ? mysqli_real_escape_string($conn, $_GET['sub']) : ''; 
$gender = isset($_GET['gender']) ? mysqli_real_escape_string($conn, $_GET['gender']) : ''; 
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : ''; 

$grades = ['Nursery', 'LKG', 'UKG', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'Other'];

$clean_class = strtolower(str_replace('th', '', $class));
$is_senior = in_array($clean_class, ['11', '12']);
?>

<style>
    .school-title-bar { background: #1e40af; color: white; padding: 12px 15px; display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 15px; text-transform: uppercase; }
    .school-title-bar a { color: white; text-decoration: none; font-size: 18px; }
    .category-nav { display: flex; background: #fff; overflow-x: auto; padding: 12px; gap: 10px; border-bottom: 1px solid #e0e0e0; scrollbar-width: none; }
    .nav-btn { padding: 8px 18px; background: #f0f3f6; border-radius: 30px; text-decoration: none; color: #555; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .nav-btn.active { background: var(--primary); color: #fff; }
    .filter-card { background: #fff; margin: 12px; padding: 15px; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .label-text { font-size: 12px; font-weight: 800; color: #444; margin-bottom: 10px; display: block; border-left: 4px solid var(--primary); padding-left: 8px; }
    .class-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
    .class-item { background: #fff; border: 1.5px solid #eee; padding: 10px 2px; border-radius: 8px; text-align: center; text-decoration: none; color: #555; font-size: 11px; font-weight: 700; }
    .class-item.active { border-color: var(--primary); background: #eff6ff; color: var(--primary); }
    .select-box { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #e2e8f0; margin-bottom: 10px; font-weight: 600; outline: none; background: #fafafa; font-size: 13px; }
    .items-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; padding: 12px; }
    .item-card { background: #fff; border-radius: 12px; padding: 12px; text-align: center; border: 1px solid #f0f0f0; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
    .item-card img { width: 100%; height: 110px; object-fit: contain; margin-bottom: 8px; }
    .item-title { font-size: 13px; font-weight: 700; height: 32px; overflow: hidden; margin-bottom: 3px; color: #333; text-align: center; }
    .item-desc { font-size: 11px; color: #666; margin-bottom: 8px; height: 26px; overflow: hidden; text-align: center; background: #f9f9f9; padding: 4px; border-radius: 4px; }
    .item-price { font-size: 16px; font-weight: 800; color: #1b5e20; margin-bottom: 8px; }
    .buy-btn { background: var(--secondary); color: white; border: none; width: 100%; padding: 9px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 12px; text-transform: uppercase; }
</style>

<div class="school-title-bar">
    <a href="index.php"><i class="fa fa-arrow-left"></i></a>
    <span><?php echo htmlspecialchars($school_name); ?></span>
</div>

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
            <a href="?school=<?php echo urlencode($school_name); ?>&cat=books&class=<?php echo urlencode($g . (in_array($g, ['11', '12']) ? 'th' : '')); ?>" 
               class="class-item <?php echo ($class == $g.'th' or $class == $g) ? 'active' : ''; ?>"><?php echo $g; ?></a>
        <?php endforeach; ?>
    </div>

    <?php if(!empty($class)): ?>
    <form method="GET" style="margin-top:15px;">
        <input type="hidden" name="school" value="<?php echo htmlspecialchars($school_name); ?>">
        <input type="hidden" name="cat" value="books">
        <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">

        <span class="label-text">Select Education Board:</span>
        <select name="board" class="select-box" onchange="this.form.submit()">
            <option value="">-- All Boards (CBSE / NCERT / State Board) --</option>
            <option value="CBSE" <?php if($board == 'CBSE') echo 'selected'; ?>>CBSE Board</option>
            <option value="NCERT" <?php if($board == 'NCERT') echo 'selected'; ?>>NCERT</option>
            <option value="State Board" <?php if($board == 'State Board') echo 'selected'; ?>>State Board (MP Board)</option>
            <option value="ICSE" <?php if($board == 'ICSE') echo 'selected'; ?>>ICSE Board</option>
        </select>

        <?php if($is_senior): ?>
            <span class="label-text">Select Stream / Subject (11th & 12th):</span>
            <select name="sub" class="select-box" onchange="this.form.submit()">
                <option value="">-- Choose Stream / Subject --</option>
                <option value="Maths" <?php if($sub == 'Maths') echo 'selected'; ?>>Mathematics (PCM)</option>
                <option value="Biology" <?php if($sub == 'Biology') echo 'selected'; ?>>Biology (PCB)</option>
                <option value="Commerce" <?php if($sub == 'Commerce') echo 'selected'; ?>>Commerce</option>
                <option value="Arts" <?php if($sub == 'Arts') echo 'selected'; ?>>Arts (Humanities)</option>
                <option value="Agriculture" <?php if($sub == 'Agriculture') echo 'selected'; ?>>Agriculture</option>
            </select>
        <?php endif; ?>
    </form>
    <?php endif; ?>
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
            <?php for($i=22; $i<=44; $i+=2): ?>
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
        $sql .= " AND (item_class LIKE '%$clean_class%')"; 
    }
    
    if(!empty($board))  {
        $sql .= " AND (item_type LIKE '%$board%' OR product_name LIKE '%$board%')";
    }

    if(!empty($sub))    { 
        $clean_sub = str_replace([' Shoes', ' Uniform', ' Dress'], '', $sub);
        $sql .= " AND (item_type LIKE '%$clean_sub%' OR stream_name LIKE '%$sub%' OR product_name LIKE '%$clean_sub%')"; 
    }

    $res = mysqli_query($conn, $sql);

    if($res) {
        if(mysqli_num_rows($res) > 0){
            $has_items = false;
            while($row = mysqli_fetch_assoc($res)){
                
                $p_name = $row['product_name'] ?? ($row['name'] ?? 'School Item');
                $name_lower = strtolower($p_name);
                $db_item_cat = strtolower($row['category'] ?? ($row['item_category'] ?? ''));
                $should_show = false;

                if($cat == 'books' and ($db_item_cat == 'books' or strpos($name_lower, 'book') !== false)) {
                    $should_show = true;
                } elseif($cat == 'shoes' and ($db_item_cat == 'shoes' or strpos($name_lower, 'shoe') !== false)) {
                    $should_show = true;
                } elseif($cat == 'dress' and ($db_item_cat == 'dress' or db_item_cat == 'uniform' or strpos($name_lower, 'dress') !== false or strpos($name_lower, 'uniform') !== false)) {
                    $should_show = true;
                } elseif($cat == 'other' and ($db_item_cat == 'other' or db_item_cat == 'stationery')) {
                    $should_show = true;
                }

                if($should_show) {
                    $has_items = true;
                    $p_id = $row['id'];
                    $p_price = $row['product_price'] ?? ($row['price'] ?? 0);
                    $p_image = !empty($row['product_image']) ? $row['product_image'] : 'default.png';

                    $p_desc = "";
                    if(!empty($row['item_type']) and $row['item_type'] != 'N/A') { $p_desc .= $row['item_type']; }
                    if(!empty($row['item_size']) and $row['item_size'] != 'N/A') { $p_desc .= " (Size: ".$row['item_size'].")"; }
                    if(!empty($row['stream_name']) and $row['stream_name'] != 'N/A') { $p_desc .= " [".$row['stream_name']."]"; }
                    if(empty(trim($p_desc))) { $p_desc = "High quality school essential."; }

                    echo '<div class="item-card">
                            <form method="POST" action="cart_handler.php">
                                <img src="uploads/'.$p_image.'" onerror="this.src=\'https://via.placeholder.com/150\'">
                                <div class="item-title">'.htmlspecialchars($p_name).'</div>
                                <div class="item-desc">'.htmlspecialchars($p_desc).'</div>
                                <div class="item-price">₹'.number_format($p_price).'</div>
                                
                                <input type="hidden" name="product_id" value="'.$p_id.'">
                                <button type="submit" name="add_to_cart" class="buy-btn">Add to Cart</button>
                            </form>
                          </div>';
                }
            }
            if(!$has_items) {
                echo "<div style='grid-column: 1/-1; text-align:center; padding:50px; color:#888;'>No items found in this category.</div>";
            }
        } else { 
            echo "<div style='grid-column: 1/-1; text-align:center; padding:50px; color:#888;'>No items found for this school.</div>"; 
        }
    }
    ?>
</main>

<?php include('footer.php'); ?>