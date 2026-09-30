<?php
include('db.php');
session_start();

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if(isset($_GET['id'])) {
    $c_id = mysqli_real_escape_string($conn, $_GET['id']);
    $u_id = $_SESSION['user_id'];

    // Sirf usi user ka item delete hoga
    $sql = "DELETE FROM cart WHERE id = '$c_id' AND user_id = '$u_id'";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: cart.php?msg=item_removed");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: cart.php");
}
?>