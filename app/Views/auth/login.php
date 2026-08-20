<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chennai Institute of Technology UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
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
            background-color: #f3f4f6;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            position: relative;
        }

        
            50% { transform: translate(30px, 40px) scale(1.1); }
        }

        /* Upgraded Login Card */
                .glass-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
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
            background: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
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
    
        /* Mobile Responsiveness for Login */
        @media (max-width: 480px) {
            .glass-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                width: calc(100% - 2rem);
                border-radius: 1rem;
            }
            .login-header h1 {
                font-size: 2rem;
            }
            .back-home {
                top: 1rem;
                left: 1rem;
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }
            /* --- Login Load Animation --- */
        @keyframes slideUpFadeIn {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .glass-card {
            animation: slideUpFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body>

    <!-- Background Orbs -->
    
    
    

    <a href="<?= BASE_URL ?>" class="back-home">
        <span style="margin-right: 5px;">&larr;</span> Back to Home
    </a>
    
    <div class="glass-card">
        <div class="login-header">
            <h1>CIT<span>UMS</span></h1>
            <p>Welcome back! Please sign in.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>auth/login" method="POST">
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
                                <a href="<?= BASE_URL ?>auth/forgot" style="font-size: 0.875rem; color: #f97316; text-decoration: none; font-weight: 600;">Forgot password?</a>
            </div>
            
            <!-- Quick Links -->
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #e2e8f0; text-align: center;">
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 600;">STUDENT QUICK LINKS</p>
                <a href="<?= BASE_URL ?>exam/results" style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 0.5rem; color: #334155; text-decoration: none; font-size: 0.9rem; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    🎓 Check Exam Results
                </a>
            </div>

            <button type="submit" class="login-btn">Secure Login</button>
        </form>
    </div>

</body>
</html>
