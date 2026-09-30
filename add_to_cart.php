<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');

// Check karna ki kya user logged in hai (Aapka original logic)
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Jab user store page par button dabaye tab POST se data pakadna
if (isset($_POST['add_to_cart'])) {
    $u_id = $_SESSION['user_id'];
    
    // Form variables jo school_items.php se aa rahe hain
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $p_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $p_image = mysqli_real_escape_string($conn, $_POST['product_image']);

    // 1. Check karna ki kya ye item is user ke cart mein pehle se hai
    $check_q = "SELECT * FROM cart WHERE user_id = '$u_id' AND product_name = '$p_name'";
    $check_res = mysqli_query($conn, $check_q);

    if (mysqli_num_rows($check_res) > 0) {
        // Agar item pehle se hai, toh quantity +1 badha do (Default column 'quantity' table mein hona chahiye)
        $update_q = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$u_id' AND product_name = '$p_name'";
        mysqli_query($conn, $update_q);
    } else {
        // Agar naya item hai, toh insert kar do (Aapki table ke hisaab se)
        // Note: Agar aapki cart table mein product_image ya quantity column nahi hai, toh wo query auto skip kar degi ya aap image aur quantity hata sakte hain.
        $q = "INSERT INTO cart (user_id, product_name, price, quantity) VALUES ('$u_id', '$p_name', '$price', 1)";
        mysqli_query($conn, $q);
    }

    // 2. BACKUP SESSION SYSTEM: Taaki upar header ka count aur total bill bhi sahi chale
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $item_exists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] == $p_name) {
            $_SESSION['cart'][$key]['quantity'] += 1;
            $item_exists = true;
            break;
        }
    }
    if (!$item_exists) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $p_name,
            'price' => $price,
            'image' => $p_image,
            'quantity' => 1
        ];
    }

    echo "<script>alert('Bhai, Product Cart mein successfully add ho gaya!'); window.location.href=document.referrer;</script>";
    exit();

} else {
    // Agar koi direct access kare toh back bhej do
    header("Location: index.php");
    exit();
}
?>