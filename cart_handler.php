<?php
include('db.php');


if(isset($_POST['add_to_cart'])) {
    $p_id = mysqli_real_escape_string($conn, $_POST['id']);
    $u_id = $_SESSION['user_id'];

    // Ab aap direct product_id use kar sakte hain
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$u_id', '$p_id', 1)";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Added to cart!'); window.location='cart.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
}
?>