<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f8fafc;">

<div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem 2rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem; color: var(--cit-orange);">🔒</span>
    </div>
    <h2 style="text-align: center; margin-bottom: 1rem; color: #0f172a;">Two-Step Verification</h2>
    <p style="text-align: center; color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 2rem;">
        We've sent a 6-digit authentication code to your email. Enter it below to access your account.
    </p>

    <?php if(!empty($data['error'])): ?>
        <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center; border: 1px solid #f87171;">
            <?= htmlspecialchars($data['error']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>auth/verify2fa" method="POST">
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <input type="text" name="otp" class="form-control" required placeholder="123456" pattern="\d{6}" maxlength="6" style="text-align: center; letter-spacing: 0.75rem; font-size: 1.75rem; font-weight: bold; padding: 1rem;">
        </div>
        
        <button type="submit" class="btn" style="width: 100%; padding: 0.875rem; font-size: 1.1rem; border-radius: 8px;">Verify & Login</button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="<?= BASE_URL ?>auth/login" style="color: #64748b; text-decoration: none; font-size: 0.9rem;">Back to login</a>
    </div>
</div>

</body>
</html>
