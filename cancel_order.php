<?php
// 1. Error Reporting (Debugging ke liye)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');


// Check user login status
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    // 1. Pehle check karo ki ye order isi user ka hai ya nahi
    $check_order = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'");
    
    if(mysqli_num_rows($check_order) > 0) {
        $order_data = mysqli_fetch_assoc($check_assoc = $check_order);
        
        // Dynamic column identifier product name ke liye
        $p_name = '';
        if(isset($order_data['product_name'])) {
            $p_name = mysqli_real_escape_string($conn, $order_data['product_name']);
        } elseif(isset($order_data['name'])) {
            $p_name = mysqli_real_escape_string($conn, $order_data['name']);
        } elseif(isset($order_data['item_name'])) {
            $p_name = mysqli_real_escape_string($conn, $order_data['item_name']);
        }

        // 2. Orders table mein status ko 'Cancelled' update karna
        $update_query = "UPDATE orders SET status = 'Cancelled' WHERE id = '$order_id' AND user_id = '$user_id'";
        
        if(mysqli_query($conn, $update_query)) {
            
            // 3. FIXED STOCK SYSTEM: Agar products table mein stock column ho, toh use safe update karein
            $column_check = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'stock'");
            if(mysqli_num_rows($column_check) > 0 && !empty($p_name)) {
                
                // Check whether products has item_name or product_name
                $prod_title_col = 'product_name';
                $title_check = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'product_name'");
                if(mysqli_num_rows($title_check) == 0) {
                    $title_check_alt = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'item_name'");
                    if(mysqli_num_rows($title_check_alt) > 0) {
                        $prod_title_col = 'item_name';
                    }
                }
                
                // Restoring stock safely
                mysqli_query($conn, "UPDATE products SET stock = stock + 1 WHERE `$prod_title_col` = '$p_name'");
            }

            echo "<script>
                    alert('Order successfully cancel kar diya gaya hai!');
                    window.location.href = 'my_orders.php';
                  </script>";
        } else {
            echo "Error: Order cancel nahi ho paya. " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Invalid Order ID!'); window.location.href='my_orders.php';</script>";
    }
} else {
    header("Location: my_orders.php");
    exit();
}
?>