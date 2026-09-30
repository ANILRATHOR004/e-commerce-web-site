<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Password | Schools Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Same CSS */
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2874f0; font-family: 'Inter', sans-serif; }
        .login-card { background: #ffffff; width: 400px; padding: 40px; border-radius: 12px; text-align: center; box-sizing: border-box; }
        input { width: 100%; padding: 14px; border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 20px; }
        .btn-login { width: 100%; padding: 15px; background-color: #4caf50; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="color: #4caf50; font-size: 28px; font-weight: 800; margin-bottom: 10px;">New Password</div>
        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">Apna naya mazboot password dalein.</p>
        
        <form action="" method="POST">
            <input type="password" name="new_pass" placeholder="Enter New Password" required>
            <button type="submit" name="update" class="btn-login">Update Password</button>
        </form>

        <?php
        include('db.php');
        session_start();
        if(isset($_POST['update'])) {
            $new_pass = $_POST['new_pass'];
            $email = $_SESSION['reset_email'];
            if(mysqli_query($conn, "UPDATE users SET password = '$new_pass', reset_otp = NULL WHERE email = '$email'")) {
                session_destroy();
                echo "<script>alert('Password Update Ho Gaya!'); window.location='login.php';</script>";
            }
        }
        ?>
    </div>
</body>
</html>