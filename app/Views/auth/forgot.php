<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #f3f4f6; margin: 0; }
        .auth-container { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; text-align: center; }
        .auth-container h2 { margin-top: 0; color: #1e293b; margin-bottom: 0.5rem; font-size: 1.5rem; font-weight: 700; }
        .auth-container p { color: #64748b; margin-bottom: 1.5rem; font-size: 0.875rem; }
    </style>
</head>
<body>

<div class="auth-container">
    <h2>Reset Password</h2>
    <p>Enter your email to receive a reset link.</p>
    
    <?php if (!empty($success)): ?>
        <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;text-align:left;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error" style="text-align:left;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>auth/forgot" method="POST" style="text-align: left;">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email Address</label>
            <input type="email" name="email" class="form-input w-full" required>
        </div>
        <button type="submit" class="btn btn-primary w-full">Send Reset Link</button>
    </form>
    
    <div style="margin-top: 1.5rem;">
        <a href="<?= BASE_URL ?>auth/login" class="text-cit-blue text-sm">Back to Login</a>
    </div>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
