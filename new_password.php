<?php
session_start();
include('db.php');

// Agar koi bina OTP verify kiye direct is page par aane ki koshish kare
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if (isset($_POST['update_password_btn'])) {
    $email = $_SESSION['reset_email'];
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_password']);
    
    // Database mein password update karne ki query
    // NOTE: Agar aap password hash kar rahe hain toh password_hash() use karein
    $query = "UPDATE users SET password = '$new_pass', reset_otp = NULL WHERE email = '$email'";
    
    if (mysqli_query($conn, $query)) {
        // Password badalne ke baad session clear karein
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_otp']);
        
        echo "<script>alert('Password successfully badal gaya hai! Ab login karein.'); window.location='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Database Error: Password update nahi ho paya.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Password | Schools Hub</title>
    <style>
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2874f0; font-family: sans-serif; }
        .card { background: #ffffff; width: 350px; padding: 40px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); text-align: center; }
        input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 20px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #2874f0; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color: #2874f0;">schools hub</h2>
        <p>Naya Password Banayein</p>
        <form method="POST">
            <input type="password" name="new_password" placeholder="Enter New Password" required>
            <button type="submit" name="update_password_btn" class="btn">UPDATE PASSWORD</button>
        </form>
    </div>
</body>
</html>