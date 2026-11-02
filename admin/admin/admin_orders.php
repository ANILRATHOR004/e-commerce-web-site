<?php include('../db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Orders</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #2874f0; color: white; }
        .btn-update { background: #27ae60; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .status-tag { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>

    <h2>Gwalior Store - All Customer Orders 📦</h2>
    <a href="add_product.php">← Add Products</a>
    <hr>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Status</th>
            <th>Change Status</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($res)){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['customer_name'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td><span class='status-tag'>" . $row['status'] . "</span></td>";
            echo "<td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='order_id' value='".$row['id']."'>
                        <select name='new_status'>
                            <option value='Pending'>Pending</option>
                            <option value='Confirmed'>Confirmed</option>
                            <option value='Out for Delivery'>Out for Delivery</option>
                            <option value='Delivered'>Delivered</option>
                        </select>
                        <button type='submit' name='update_status' class='btn-update'>Update</button>
                    </form>
                  </td>";
            echo "</tr>";
        }

        if(isset($_POST['update_status'])){
            $o_id = $_POST['order_id'];
            $status = $_POST['new_status'];
            mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = '$o_id'");
            echo "<script>window.location.href='view_orders.php';</script>";
        }
        ?>
    </table>

</body>
</html>