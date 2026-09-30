<?php 
include('db.php'); 
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$u_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id = '$u_id'");
$user = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Account - GwaliorStore</title>
    <style>
        body { font-family: Arial; background: #f1f3f6; padding: 50px; text-align: center; }
        .profile-card { background: white; padding: 30px; display: inline-block; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        .wallet-box { background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2>👤 My Profile</h2>
        <hr>
        <p><b>Naam:</b> <?php echo $user['full_name']; ?></p>
        <p><b>Email:</b> <?php echo $user['email']; ?></p>
        
        <div class="wallet-box">
            💰 Wallet Balance: ₹<?php echo $user['wallet_balance']; ?>
        </div>
        
        <br>
        <a href="index.php" style="text-decoration:none; color:#2874f0;">← Back to Store</a> | 
        <a href="logout.php" style="color:red;">Logout</a>
    </div>
</body>
</html>