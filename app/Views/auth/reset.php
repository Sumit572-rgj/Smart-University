<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP & Reset Password - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f8fafc; margin: 0; padding: 20px; box-sizing: border-box;">

<div class="card" style="width: 100%; max-width: 420px; padding: 2.5rem 2rem; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="background: #fff3ed; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: var(--cit-orange); font-size: 1.75rem;">
            🔐
        </div>
    </div>
    
    <h2 style="text-align: center; margin-bottom: 0.5rem; color: #0f172a; font-size: 1.5rem;">Secure Password Reset</h2>
    <p style="text-align: center; color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 2rem;">
        We've sent a 6-digit verification code to your email. Enter it below to create a new password.
    </p>

    <?php if(!empty($data['error'])): ?>
        <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center; border: 1px solid #f87171;">
            <?= htmlspecialchars($data['error']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>auth/reset" method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($data['email']) ?>">
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label" style="text-align: center; display: block; color: #475569; font-weight: 600;">6-Digit OTP Code</label>
            <input type="text" name="otp" class="form-control" required placeholder="• • • • • •" pattern="\d{6}" maxlength="6" style="text-align: center; letter-spacing: 0.75rem; font-size: 1.75rem; font-weight: bold; padding: 1rem; background: #f8fafc; border: 2px solid #e2e8f0; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--cit-orange)'" onblur="this.style.borderColor='#e2e8f0'">
        </div>
        
        <div class="form-group" style="margin-bottom: 1rem;">
            <label class="form-label" style="color: #475569; font-weight: 600;">New Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Create a strong password" style="padding: 0.875rem;">
        </div>
        
        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label" style="color: #475569; font-weight: 600;">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm your new password" style="padding: 0.875rem;">
        </div>
        
        <button type="submit" class="btn" style="width: 100%; padding: 0.875rem; font-size: 1.1rem; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Verify & Reset Password</button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="<?= BASE_URL ?>auth/login" style="color: #64748b; text-decoration: none; font-size: 0.9rem; transition: color 0.2s;" onmouseover="this.style.color='var(--cit-orange)'" onmouseout="this.style.color='#64748b'">Cancel and return to login</a>
    </div>
</div>

</body>
</html>
