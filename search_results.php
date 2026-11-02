<?php 
include('header.php'); 

$raw_q = $_GET['query'] ?? ($_GET['q'] ?? '');
$q = trim(mysqli_real_escape_string($conn, $raw_q));

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

$matched_school = "";
if (strlen($q) >= 3) {
    foreach ($schools_list as $school) {
        if (stripos($school, $q) !== false) {
            $matched_school = $school;
            break;
        }
    }
}
?>

<div style="padding: 12px 15px; background: white; border-bottom: 1px solid #eee; font-size: 14px; color: #666;">
    Showing results for <b>"<?php echo htmlspecialchars($raw_q); ?>"</b>
</div>

<?php if ($matched_school !== ""): ?>
    <div style="background: #eff6ff; margin: 15px; padding: 15px; border-radius: 10px; border: 1px solid #bfdbfe; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0; font-size: 15px; color: #1e40af;"><i class="fa fa-school"></i> <?php echo htmlspecialchars($matched_school); ?></h3>
            <small style="color: #666;">Official School Store</small>
        </div>
        <a href="school_items.php?school=<?php echo urlencode($matched_school); ?>" style="background: #2874f0; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 700;">VISIT STORE</a>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; padding: 15px;">
    <?php
    if ($q !== '') {
        $sql = "SELECT * FROM products 
                WHERE product_name LIKE '%$q%' 
                   OR school_name LIKE '%$q%' 
                   OR category LIKE '%$q%' 
                ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $p_id = $row['id'];
                $p_img = !empty($row['product_image']) ? $row['product_image'] : 'default.png';
                $p_name = $row['product_name'] ?? ($row['name'] ?? 'Product');
                $p_school = $row['school_name'] ?? '';
                $p_price = $row['product_price'] ?? ($row['price'] ?? 0);
                ?>
                <div style="background: white; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <img src="uploads/<?php echo htmlspecialchars($p_img); ?>" onerror="this.src='https://via.placeholder.com/150'" style="width: 100%; height: 120px; object-fit: contain; margin-bottom: 8px;">
                        <?php if(!empty($p_school)): ?>
                            <div style="font-size: 9px; color: #2874f0; font-weight: 700; text-transform: uppercase;"><?php echo htmlspecialchars($p_school); ?></div>
                        <?php endif; ?>
                        <h3 style="font-size: 13px; margin: 6px 0; color: #333; height: 32px; overflow: hidden;"><?php echo htmlspecialchars($p_name); ?></h3>
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: #1b5e20; margin-bottom: 10px;">₹<?php echo number_format($p_price); ?></div>
                        <a href="product_details.php?id=<?php echo $p_id; ?>" style="display: block; background: #fb641b; color: white; text-align: center; padding: 8px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">View Details</a>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin: 20px 0; border: 1px dashed #cbd5e1;">
                <i class="fa fa-search" style="font-size: 45px; color: #94a3b8; margin-bottom: 15px;"></i>
                <h3 style="font-size: 18px; color: #1e293b; margin: 0 0 8px;">No Results Found</h3>
                <p style="font-size: 13px; color: #64748b; margin: 0 0 20px;">We couldn't find anything matching "<b><?php echo htmlspecialchars($raw_q); ?></b>". Check for spelling or try searching for a school, books, or uniform.</p>
                <a href="index.php" style="background: #2874f0; color: white; padding: 10px 22px; border-radius: 25px; text-decoration: none; font-size: 13px; font-weight: 700; display: inline-block;">Browse All Products</a>
            </div>
            <?php
        }
    }
    ?>
</div>

<?php include('footer.php'); ?>