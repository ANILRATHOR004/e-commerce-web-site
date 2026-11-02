<?php
include('db.php');
session_start();

// Agar user direct yahan aane ki koshish kare bina signup ke, toh wapas bhej do
if(!isset($_SESSION['signup_email'])) {
    header("Location: signup.php");
    exit();
}

if(isset($_POST['verify'])) {
    $user_otp = $_POST['otp'];
    $email = $_SESSION['signup_email'];

    // Database mein check karein ki OTP sahi hai ya nahi
    $query = "SELECT * FROM users WHERE email='$email' AND reset_otp='$user_otp'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        // OTP sahi hai! Account active karein
        mysqli_query($conn, "UPDATE users SET is_verified=1, reset_otp=NULL WHERE email='$email'");
        
        echo "<script>alert('Bhai, Verification Success! Ab Login karein.'); window.location='login.php';</script>";
        unset($_SESSION['signup_email']); // Session clear karein
    } else {
        echo "<script>alert('Opps! Galat OTP dala hai.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | Schools Hub</title>
    <style>
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2874f0; font-family: sans-serif; }
        .card { background: #fff; width: 350px; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        h2 { color: #2874f0; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 18px; letter-spacing: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #fb641b; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Enter OTP</h2>
        <p>Aapki Email par ek code bheja gaya hai.</p>
        <form action="" method="POST">
            <input type="text" name="otp" placeholder="XXXX" maxlength="4" required>
            <button type="submit" name="verify" class="btn">VERIFY NOW</button>
        </form>
    </div>
</body>
</html>