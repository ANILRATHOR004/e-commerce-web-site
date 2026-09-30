<?php
include('db.php');
session_start();

if(isset($_SESSION['reset_email'])) {
    $email = $_SESSION['reset_email'];
    $new_otp = rand(1000, 9999);
    
    // Database mein naya OTP update karo
    mysqli_query($conn, "UPDATE users SET reset_otp = '$new_otp' WHERE email = '$email'");
    
    // Testing ke liye alert dikhao (Localhost par mail nahi jati isliye)
    echo "<script>alert('Naya OTP bhej diya gaya hai: $new_otp'); window.location='verify_reset_otp.php';</script>";
} else {
    header("Location: forgot_password.php");
}
?>