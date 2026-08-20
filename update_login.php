<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/auth/login.php';

$content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chennai Institute of Technology UMS</title>
    <link rel="stylesheet" href="/cit_ums/css/style.css?v=<?= time() ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f7f9;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            position: relative;
        }

        /* Animated Floating Background Orbs */
        .blur-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.5;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: #f97316; /* Vibrant Orange */
            top: -100px; left: -100px;
            animation: float 8s ease-in-out infinite;
        }
        .orb-2 {
            width: 600px; height: 600px;
            background: #3b82f6; /* Bright Blue */
            bottom: -200px; right: -150px;
            animation: float 12s ease-in-out infinite reverse;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: #10b981; /* Soft Green */
            top: 40%; left: 60%;
            animation: float 10s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 40px) scale(1.1); }
        }

        /* Upgraded Login Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            border-radius: 1.5rem;
            padding: 3rem;
            max-width: 420px;
            width: 100%;
            position: relative;
            z-index: 10;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .login-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
            letter-spacing: -1px;
        }
        .login-header h1 span {
            color: #f97316;
        }
        .login-header p {
            color: #64748b;
            font-size: 1.05rem;
            margin: 0;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }
        .input-group input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            font-size: 1rem;
            background: rgba(255,255,255,0.9);
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .input-group input:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
            background: #ffffff;
        }

        .login-btn {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.4);
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.5);
        }

        .back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            z-index: 20;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.5);
        }
        .back-home:hover {
            color: #0f172a;
            background: rgba(255,255,255,0.9);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #ef4444;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Background Orbs -->
    <div class="blur-orb orb-1"></div>
    <div class="blur-orb orb-2"></div>
    <div class="blur-orb orb-3"></div>

    <a href="/cit_ums/" class="back-home">
        <span style="margin-right: 5px;">&larr;</span> Back to Home
    </a>
    
    <div class="glass-card">
        <div class="login-header">
            <h1>CIT<span>UMS</span></h1>
            <p>Welcome back! Please sign in.</p>
        </div>

        <?php if (!empty(\$error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars(\$error) ?>
            </div>
        <?php endif; ?>

        <form action="/cit_ums/auth/login" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
            </div>
            
            <div class="input-group" style="margin-bottom: 2rem;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; font-size: 0.875rem; color: #475569; cursor: pointer;">
                    <input type="checkbox" style="margin-right: 0.5rem; width: 1rem; height: 1rem; cursor: pointer;"> Remember me
                </label>
                <a href="/cit_ums/auth/forgot" style="font-size: 0.875rem; color: #f97316; text-decoration: none; font-weight: 600;">Forgot password?</a>
            </div>

            <button type="submit" class="login-btn">Secure Login</button>
        </form>
    </div>

</body>
</html>
HTML;

file_put_contents($f, $content);
echo "Login page upgraded.";
