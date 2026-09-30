<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Schools Hub Gwalior</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Global Styles (Aapka Original CSS) */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #2874f0; 
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            box-sizing: border-box;
        }

        .brand-name {
            color: #2874f0;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .login-title {
            color: #333;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        input {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        input:focus {
            border-color: #2874f0;
            background: #fff;
            box-shadow: 0 0 8px rgba(40, 116, 240, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #2874f0; 
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
            text-transform: uppercase;
        }

        .btn-login:hover {
            background-color: #1259cc;
            transform: translateY(-1px);
        }

        .footer-text {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .footer-text a {
            color: #2874f0;
            text-decoration: none;
            font-weight: 700;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .divider {
            margin: 20px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-name">schools hub</div>
        <div class="login-title">Welcome Back!</div>

        <form action="process_login.php" method="POST">
            <div class="input-group">
                <input type="text" name="user_identity" placeholder="Email or Mobile Number" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>

            <button type="submit" class="btn-login">Login Now</button>
        </form>

        <p class="footer-text" style="margin-top: 15px;">
            Password bhool gaye? <a href="forgot_password.php">Forgot Password</a>
        </p>

        <div class="divider"></div>

        <p class="footer-text">
            Naya account banana hai? <a href="signup.php">Create Account</a>
        </p>
    </div>

</body>
</html>