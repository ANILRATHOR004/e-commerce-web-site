<?php
include('db.php');
session_start();

// Security Check: Agar direct koi is page par aaye toh use wapas bhej do
if(!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if(isset($_POST['verify'])) {
    $input_otp = $_POST['otp'];
    $email = $_SESSION['reset_email'];
    
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND reset_otp = '$input_otp'");
    
    if(mysqli_num_rows($res) > 0) {
        // OTP sahi hai, ab password badalne do
        header("Location: change_password.php");
    } else {
        echo "<script>alert('Galat OTP! Fir se try karein.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Schools Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2874f0; font-family: 'Inter', sans-serif; }
        .login-card { background: #ffffff; width: 400px; padding: 40px; border-radius: 12px; text-align: center; box-sizing: border-box; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); }
        .brand-name { color: #2874f0; font-size: 32px; font-weight: 800; margin-bottom: 10px; }
        
        /* Input Styling: Professional OTP Look */
        input { 
            width: 100%; 
            padding: 14px; 
            border: 1px solid #e0e0e0; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            font-size: 22px; 
            text-align: center; 
            letter-spacing: 8px; 
            font-weight: 700;
            outline: none;
            background: #f9f9f9;
        }
        input:focus { border-color: #2874f0; background: #fff; }

        .btn-login { width: 100%; padding: 15px; background-color: #2874f0; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-transform: uppercase; transition: 0.3s; }
        .btn-login:hover { background-color: #1259cc; }

        /* Timer and Resend Link Styles */
        .resend-section { margin-top: 25px; font-size: 14px; color: #666; }
        #resend_link { color: #2874f0; text-decoration: none; font-weight: 700; display: none; }
        #timer_text { font-weight: 600; color: #333; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-name">schools hub</div>
        <div style="margin-bottom: 20px; font-weight: 600; color: #555;">Enter 6-Digit OTP</div>
        
        <form action="" method="POST">
            <input type="text" name="otp" placeholder="0000" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
            <button type="submit" name="verify" class="btn-login">Verify OTP</button>
        </form>

        <div class="resend-section">
            OTP nahi mila? 
            <span id="timer_wrapper">Resend karein <span id="timer">30</span>s mein</span>
            <a href="resend_otp.php" id="resend_link">Resend OTP</a>
        </div>
    </div>

    <script>
        // Countdown Logic
        let timeLeft = 30;
        const timerDisplay = document.getElementById('timer');
        const timerWrapper = document.getElementById('timer_wrapper');
        const resendLink = document.getElementById('resend_link');

        const countdown = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerWrapper.style.display = 'none'; // Timer ko chhupao
                resendLink.style.display = 'inline'; // Resend link ko dikhao
            }
        }, 1000);
    </script>
</body>
</html>