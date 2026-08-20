<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/outpass/student.php';
$c = file_get_contents($f);

// 1. Replace the small QR code with a "View QR" button
$oldQR = <<<PHP
                                    <?php if (\$req['status'] === 'approved' && !empty(\$req['qr_code'])): ?>
                                        <img src="<?= htmlspecialchars(\$req['qr_code']) ?>" alt="QR Code" style="width: 64px; height: 64px; border: 1px solid #e2e8f0; border-radius: 4px;">
                                    <?php endif; ?>
PHP;

$newQR = <<<PHP
                                    <?php if (\$req['status'] === 'approved' && !empty(\$req['qr_code'])): ?>
                                        <button type="button" onclick="showFullQR('<?= htmlspecialchars(\$req['qr_code']) ?>')" class="btn" style="background: #10b981; color: white; padding: 0.25rem 0.75rem; font-size: 0.8rem; margin-top: 0.5rem; border-radius: 999px;">
                                            &#128247; View QR
                                        </button>
                                    <?php endif; ?>
PHP;

$c = str_replace($oldQR, $newQR, $c);

// 2. Inject the Modal and JavaScript at the bottom of the body
$modalCode = <<<HTML

    <!-- Full Screen QR Modal -->
    <div id="full-qr-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 9999; flex-direction: column; align-items: center; justify-content: center;">
        <button onclick="closeFullQR()" style="position: absolute; top: 2rem; right: 2rem; background: none; border: none; color: white; font-size: 2.5rem; cursor: pointer;">&times;</button>
        <div style="background: white; padding: 2rem; border-radius: 1rem; text-align: center; max-width: 90vw;">
            <h2 style="font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; font-size: 1.5rem;">Security Scan QR</h2>
            <img id="full-qr-img" src="" alt="Full QR Code" style="width: 300px; height: 300px; max-width: 100%; object-fit: contain;">
            <p style="color: #64748b; margin-top: 1.5rem; font-size: 0.9rem;">Present this code to the security guard at the main gate.</p>
        </div>
    </div>

    <script>
        function showFullQR(qrUrl) {
            document.getElementById('full-qr-img').src = qrUrl;
            document.getElementById('full-qr-modal').style.display = 'flex';
        }
        function closeFullQR() {
            document.getElementById('full-qr-modal').style.display = 'none';
            document.getElementById('full-qr-img').src = '';
        }
    </script>
</body>
HTML;

$c = str_replace('</body>', $modalCode, $c);
file_put_contents($f, $c);
echo "Full screen QR modal added.";
