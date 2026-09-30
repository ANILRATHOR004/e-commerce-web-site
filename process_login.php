<?php
include('db.php');

// Agar session start nahi hai tabhi start karein
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identity = isset($_POST['user_identity']) ? mysqli_real_escape_string($conn, trim($_POST['user_identity'])) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, trim($_POST['password'])) : '';

    if (empty($identity) || empty($password)) {
        echo "<script>alert('Kripya Email/Mobile aur Password dono bharein.'); window.history.back();</script>";
        exit;
    }

    // Database query execution
    $query = "SELECT * FROM users WHERE (email = '$identity' OR phone = '$identity') AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Agar table ya column name me koi galti ho toh exact reason batayega
    if (!$result) {
        die("Database Query Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        
        // Verification Check (agar column nahi hai toh direct login allow karega)
        $is_verified = isset($user_data['is_verified']) ? $user_data['is_verified'] : 1;

        if ($is_verified == 1) {
            $_SESSION['user_id'] = $user_data['id'] ?? $user_data['user_id'];
            $_SESSION['user_name'] = $user_data['name'] ?? $user_data['username'];
            
            echo "<script>alert('Welcome back, " . addslashes($_SESSION['user_name']) . "!'); window.location.href='index.php';</script>";
            exit;
        } else {
            $_SESSION['temp_signup_data'] = [
                'name' => $user_data['name'] ?? '', 
                'email' => $user_data['email'] ?? '', 
                'password' => $user_data['password'] ?? ''
            ]; 
            
            echo "<script>
                alert('Bhai, aapka account abhi tak verify nahi hua hai. Kripya signup page par jaakar apna code verify karein.');
                window.location.href='signup.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>alert('Error: Galat Details! Sahi Email/Mobile ya Password daalein.'); window.history.back();</script>";
        exit;
    }
} else {
    header("Location: login.php");
    exit();
}
?>