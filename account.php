<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
include('db.php'); 

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$u_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id = '$u_id'");
$user = mysqli_fetch_assoc($res) ?? [];

$name = $user['name'] ?? $user['user_name'] ?? $user['username'] ?? $user['full_name'] ?? ($_SESSION['user_name'] ?? 'User');
$email = $user['email'] ?? 'Not available';
$wallet = $user['wallet_balance'] ?? $user['balance'] ?? $user['wallet'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - GwaliorStore</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f1f3f6; padding: 40px 15px; text-align: center; margin: 0; }
        .profile-card { background: white; padding: 30px; display: inline-block; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; box-sizing: border-box; }
        .wallet-box { background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2>👤 My Profile</h2>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
        <p><b>Naam:</b> <?php echo htmlspecialchars($name); ?></p>
        <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
        
        <div class="wallet-box">
            💰 Wallet Balance: ₹<?php echo number_format($wallet); ?>
        </div>
        
        <br>
        <a href="index.php" style="text-decoration:none; color:#2874f0; font-weight: bold;">← Back to Store</a> | 
        <a href="logout.php" style="color:red; text-decoration:none; font-weight: bold;">Logout</a>
    </div>
</body>
</html>