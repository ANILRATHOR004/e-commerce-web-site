<?php
session_start(); // Session start karna zaroori hai
include('db.php');

// PHPMailer include karein (aapke paas folder hai isliye)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $identity = mysqli_real_escape_string($conn, $_POST['user_identity']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $email_val = "NULL";
    $phone_val = "NULL";

    if (is_numeric($identity)) {
        $phone_val = "'$identity'";
        $user_email = ""; // Phone case mein email khali
    } else {
        $email_val = "'$identity'";
        $user_email = $identity; // Email store karein session ke liye
    }

    // CHECK: Kya ye pehle se hai?
    $check_query = "SELECT * FROM users WHERE (email IS NOT NULL AND email = $email_val) OR (phone IS NOT NULL AND phone = $phone_val)";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Error: Ye Email ya Phone pehle se register hai!'); window.history.back();</script>";
    } else {
        // --- NAYA OTP LOGIC YAHAN SE ---
        $otp = rand(1000, 9999); // 4 digit OTP generate karein
        
        // INSERT: reset_otp aur is_verified (default 0) ke saath
        $query = "INSERT INTO users (name, email, phone, password, reset_otp, is_verified, wallet_balance) 
                  VALUES ('$name', $email_val, $phone_val, '$password', '$otp', 0, 0)";

        if (mysqli_query($conn, $query)) {
            
            // Session mein email save karein taaki verify page par use ho sake
            $_SESSION['signup_email'] = $user_email;

            // --- MAIL BHEJNE KA CODE ---
            $mail = new PHPMailer(true);
            try {
                // SMTP Settings (Apni Gmail details yahan dalein)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'Anilrathore913148@gmail.com'; 
                $mail->Password = 'wfagzglgxkznkmzh'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'Schools Hub');
                $mail->addAddress($user_email); 

                $mail->isHTML(true);
                $mail->Subject = 'Verify Your Account - Schools Hub';
                $mail->Body    = "Bhai, tera OTP hai: <b>$otp</b>";

                $mail->send();
                
                // Success: Redirect to verification page
                echo "<script>alert('Signup Successful! OTP aapki mail par bhej diya gaya hai.'); window.location.href='verify_signup.php';</script>";
            } catch (Exception $e) {
                echo "Mail nahi gayi. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>