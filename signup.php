<?php
// Session ko bina notice error ke start karna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('db.php');

$show_otp_field = false;

if (isset($_POST['signup_request'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    $otp = rand(100000, 999999);

    $check_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($check_query) > 0) {
        $user_data = mysqli_fetch_assoc($check_query);
        if ($user_data['is_verified'] == 0) {
            $_SESSION['temp_signup_data'] = ['action' => 'update', 'name' => $name, 'email' => $email, 'password' => $password, 'otp' => $otp];
            if (sendVerificationMail($email, $name, $otp)) {
                $show_otp_field = true;
                echo "<script>alert('Verification OTP bhej diya gaya hai!');</script>";
            }
        } else {
            echo "<script>alert('Ye Email pehle se verified hai! Direct login karein.'); window.location='login.php';</script>";
            exit();
        }
    } else {
        $_SESSION['temp_signup_data'] = ['action' => 'insert', 'name' => $name, 'email' => $email, 'password' => $password, 'otp' => $otp];
        if (sendVerificationMail($email, $name, $otp)) {
            $show_otp_field = true;
            echo "<script>alert('Verification OTP aapki Email par bhej diya gaya hai!');</script>";
        } else {
            echo "<script>alert('Mail nahi ja payi!');</script>";
        }
    }
}

if (isset($_POST['verify_signup_btn'])) {
    $user_otp = mysqli_real_escape_string($conn, $_POST['otp']);
    if (isset($_SESSION['temp_signup_data']) && $user_otp == $_SESSION['temp_signup_data']['otp']) {
        $data = $_SESSION['temp_signup_data'];
        $sql = ($data['action'] == 'update') 
            ? "UPDATE users SET name='".$data['name']."', password='".$data['password']."', reset_otp='".$data['otp']."', is_verified=1 WHERE email='".$data['email']."'"
            : "INSERT INTO users (name, email, password, reset_otp, is_verified) VALUES ('".$data['name']."', '".$data['email']."', '".$data['password']."', '".$data['otp']."', 1)";

        if (mysqli_query($conn, $sql)) {
            unset($_SESSION['temp_signup_data']);
            echo "<script>alert('Registration Successful! Ab login karein.'); window.location='login.php';</script>";
            exit();
        }
    } else {
        $show_otp_field = true;
        echo "<script>alert('Galat OTP dale ho bhai!');</script>";
    }
}

function sendVerificationMail($email, $name, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'Anilrathore913148@gmail.com'; 
        $mail->Password   = 'wfagzglgxkznkmzh'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->setFrom('Anilrathore913148@gmail.com', 'Schools Hub Gwalior');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Signup';
        $mail->Body    = "<h3>Hi $name,</h3> Aapka Registration OTP hai: <b>$otp</b>";
        $mail->send();
        return true;
    } catch (Exception $e) { return false; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Schools Hub</title>
    <style>
        /* Yahan se CSS fix hai */
        body { 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; /* height fix */
            background-color: #2874f0; 
            font-family: sans-serif; 
        }
        .card { 
            background: #fff; 
            width: 350px; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); 
            text-align: center; 
        }
        .brand { color: #2874f0; font-size: 30px; font-weight: bold; margin-bottom: 25px; text-transform: lowercase; }
        input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 15px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #2874f0; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .links-container { margin-top: 20px; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">schools hub</div>
        <form action="" method="POST">
            <?php if (!$show_otp_field): ?>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Create Password" required>
                <button type="submit" name="signup_request" class="btn">Register Now</button>
            <?php else: ?>
                <p style="color: #2874f0; font-weight: bold;">OTP bhej diya gaya hai.</p>
                <input type="text" name="otp" placeholder="Enter OTP" maxlength="6" required>
                <button type="submit" name="verify_signup_btn" class="btn">Verify</button>
            <?php endif; ?>
        </form>
        <div class="links-container">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>