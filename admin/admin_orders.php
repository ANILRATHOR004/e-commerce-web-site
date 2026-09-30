<?php 
include('../db.php');
// Yahan aap apna admin login check bhi jodh sakte hain
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - GwaliorStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .admin-card { background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; min-width: 1000px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; font-size: 13px; }
        th { background: #2874f0; color: white; position: sticky; top: 0; }
        .status-select { padding: 5px; border-radius: 4px; border: 1px solid #ccc; font-size: 12px; }
        .update-btn { background: #4caf50; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .update-btn:hover { background: #45a049; }
        .price-tag { color: #1b5e20; font-weight: 800; }
        .address-box { max-width: 200px; font-size: 12px; color: #555; }
    </style>
</head>
<body>

<div class="admin-card">
    <h2><i class="fa fa-gauge"></i> Gwalior Store - Order Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Contact No.</th>
            <th>Delivery Address</th>
            <th>Product Name</th>
            <th>Total Bill</th>
            <th>Current Status</th>
            <th>Action</th>
        </tr>
        <?php
        // Saare orders database se nikalna (Naye orders sabse upar)
        $res = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
        
        if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $status = $row['status']; 
                ?>
                <tr>
                    <td><b>#GWR<?php echo $row['id']; ?></b></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><a href="tel:<?php echo $row['phone']; ?>" style="text-decoration:none; color:#2874f0; font-weight:600;"><?php echo $row['phone']; ?></a></td>
                    <td><div class="address-box"><?php echo $row['address']; ?></div></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td class="price-tag">₹<?php echo $row['total_amount']; ?></td>
                    <td>
                        <span style="color: <?php echo ($status=='Delivered') ? '#4caf50' : '#fb641b'; ?>; font-weight:bold;">
                            <?php echo $status; ?>
                        </span>
                    </td>
                    <td>
                        <form action="update_status.php" method="POST" style="display:flex; gap:5px;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status" class="status-select">
                                <option value="Pending" <?php if($status=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Confirmed" <?php if($status=='Confirmed') echo 'selected'; ?>>Confirmed</option>
                                <option value="Shipped" <?php if($status=='Shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="Delivered" <?php if($status=='Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Cancelled" <?php if($status=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='8' style='text-align:center; padding:30px;'>Abhi tak koi order nahi aaya hai.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>