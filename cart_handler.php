<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $u_id = $_SESSION['user_id'];
    $p_id = intval($_POST['product_id'] ?? ($_POST['id'] ?? 0));

    if ($p_id > 0) {
        $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$u_id' AND product_id = $p_id");
        
        if (mysqli_num_rows($check_cart) > 0) {
            $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$u_id' AND product_id = $p_id";
        } else {
            $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$u_id', $p_id, 1)";
        }

        if (mysqli_query($conn, $sql)) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Invalid Product ID";
    }
}
?>