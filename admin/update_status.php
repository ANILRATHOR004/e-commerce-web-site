<?php
include('../db.php');
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // Database mein status update karna
    $query = "UPDATE orders SET status = '$new_status' WHERE id = '$order_id'";
    
    if(mysqli_query($conn, $query)) {
        header("Location: admin_orders.php?msg=Updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>