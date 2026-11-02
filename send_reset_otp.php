<?php
include('db.php');
include('send_otp.php'); // send_otp.php ko link kiya
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check karein ki user exist karta hai ya nahi
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if(mysqli_num_rows($result) > 0) {
        $otp = rand(100000, 999999); // 6 digit secure OTP
        
        // Database mein reset_otp column update karein
        $update = mysqli_query($conn, "UPDATE users SET reset_otp = '$otp' WHERE email = '$email'");
        
        if($update) {
            $_SESSION['reset_email'] = $email;

            // Mail bhejna shuru karein
            if(sendOTP($email, $otp)) {
                echo "<script>alert('OTP aapki Registered Email ID par bhej diya gaya hai!'); window.location='verify_reset_otp.php';</script>";
            } else {
                echo "<script>alert('Error: Mail nahi ja raha. PHPMailer settings check karein.'); window.location='forgot_password.php';</script>";
            }
        } else {
            echo "<script>alert('Database Error: OTP update nahi ho paya.'); window.location='forgot_password.php';</script>";
        }

    } else {
        echo "<script>alert('Ye Email registered nahi hai!'); window.location='forgot_password.php';</script>";
    }
}
?>