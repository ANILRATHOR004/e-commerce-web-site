<?php
session_start();
include('db.php');
include('send_otp.php'); // PHPMailer wali file

$show_otp_field = false; // Shuruat mein OTP input hidden rahega

if (isset($_POST['send_otp_btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check karein email database mein hai ya nahi
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $otp = rand(100000, 999999); // 6 Digit secure OTP
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;

        // Database mein OTP update kar rahe hain
        mysqli_query($conn, "UPDATE users SET reset_otp='$otp' WHERE email='$email'");

        if (sendOTP($email, $otp)) {
            $show_otp_field = true; // Email sahi hai aur mail chala gaya, ab OTP box dikhao
            echo "<script>alert('OTP aapki Registered Email ID par bhej diya gaya hai!');</script>";
        } else {
            echo "<script>alert('Error: Mail nahi ja raha, App Password check karein!');</script>";
        }
    } else {
        echo "<script>alert('Ye Email registered nahi hai!');</script>";
    }
}

if (isset($_POST['verify_otp_btn'])) {
    $user_otp = mysqli_real_escape_string($conn, $_POST['otp']);
    
    // Check karein jo user ne dala aur jo session/database mein hai wo match ho raha hai
    if ($user_otp == $_SESSION['reset_otp']) {
        // OTP match ho gaya, ab naya password banane ke liye bhejein
        echo "<script>alert('OTP Verified! Ab naya password banayein.'); window.location='new_password.php';</script>";
    } else {
        $show_otp_field = true; // Galat hone par bhi OTP field ko dikhate rehna hai
        echo "<script>alert('Galat OTP dale ho bhai! Phir se try karo.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Schools Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2874f0; font-family: 'Inter', sans-serif; }
        .login-card { background: #ffffff; width: 100%; max-width: 400px; padding: 40px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); text-align: center; box-sizing: border-box; }
        .brand-name { color: #2874f0; font-size: 32px; font-weight: 800; margin-bottom: 10px; letter-spacing: -1px; }
        .login-title { color: #333; font-size: 18px; font-weight: 700; margin-bottom: 30px; }
        input { width: 100%; padding: 14px 15px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 15px; outline: none; box-sizing: border-box; margin-bottom: 20px; background: #f9f9f9; }
        .btn-login { width: 100%; padding: 15px; background-color: #2874f0; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; text-transform: uppercase; }
        .footer-text { margin-top: 25px; font-size: 14px; color: #666; }
        .footer-text a { color: #2874f0; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-name">schools hub</div>
        <div class="login-title">Reset Password</div>
        
        <form action="" method="POST">
            <input type="email" name="email" value="<?php echo @$_SESSION['reset_email']; ?>" placeholder="Enter Registered Email" required <?php if($show_otp_field) echo 'readonly'; ?>>
            
            <?php if (!$show_otp_field): ?>
                <p style="font-size: 14px; color: #666; margin-bottom: 20px;">Apni registered Email ID dalein, hum OTP bhejenge.</p>
                <button type="submit" name="send_otp_btn" class="btn-login">Send OTP</button>
            <?php else: ?>
                <p style="font-size: 14px; color: #2874f0; font-weight: 600; margin-bottom: 20px;">Email par bheja gaya 6-digit OTP dalein.</p>
                <input type="text" name="otp" placeholder="Enter 6-Digit OTP" maxlength="6" required style="text-align: center; font-size: 18px; letter-spacing: 4px;">
                <button type="submit" name="verify_otp_btn" class="btn-login">Verify & Proceed</button>
            <?php endif; ?>
        </form>

        <p class="footer-text">
            <a href="login.php">Wapas Login Karein</a>
        </p>
    </div>
</body>
</html>