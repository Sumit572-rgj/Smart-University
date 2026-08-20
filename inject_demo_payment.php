<?php
$file = __DIR__ . '/app/Views/parent/index.php';
$content = file_get_contents($file);

// Replace the real Razorpay script with our custom button
$pattern = '/<form action="\/cit_ums\/parent\/pay" method="POST" class="mt-2">.*?<\/form>/is';
$replacement = <<<HTML
<form action="/cit_ums/parent/pay" method="POST" class="mt-2" id="form-pay-<?= \$fee['id'] ?>">
    <input type="hidden" name="fee_id" value="<?= \$fee['id'] ?>">
    <input type="hidden" name="razorpay_payment_id" id="rzp-id-<?= \$fee['id'] ?>" value="">
    <button type="button" class="razorpay-payment-button" onclick="openDemoRazorpay(<?= \$fee['id'] ?>, <?= \$fee['total_amount'] ?>, '<?= addslashes(\$fee['fee_type']) ?>')">Pay with Razorpay (Demo)</button>
</form>
HTML;

$content = preg_replace($pattern, $replacement, $content);

// Append Demo Modal and Coin Animation JS before closing </body>
$demoScript = <<<HTML

<!-- Demo Razorpay Modal Overlay -->
<div id="demo-rzp-overlay" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); z-index:9999; justify-content:center; align-items:center;">
    <div id="demo-rzp-modal" style="background:#fff; width:100%; max-width:400px; border-radius:12px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); transform:scale(0.9); opacity:0; transition:all 0.3s cubic-bezier(0.4,0,0.2,1);">
        <div style="background:#0f172a; color:#fff; padding:1.5rem; text-align:center; position:relative;">
            <button onclick="closeDemoRazorpay()" style="position:absolute; right:15px; top:15px; background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer;">&times;</button>
            <h3 style="margin:0; font-weight:600; font-size:1.2rem;">CIT UMS Payment</h3>
            <p style="margin:5px 0 0; opacity:0.8; font-size:0.9rem;" id="demo-rzp-desc">Fee Payment</p>
            <div style="font-size:2rem; font-weight:700; margin-top:10px;">₹<span id="demo-rzp-amt">0.00</span></div>
        </div>
        
        <div style="padding:1.5rem;" id="demo-rzp-methods">
            <p style="margin:0 0 1rem; font-size:0.9rem; color:#64748b; font-weight:600; text-transform:uppercase;">Select Payment Method</p>
            
            <button onclick="processDemoPayment('UPI')" style="width:100%; text-align:left; padding:1rem; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:10px; background:#f8fafc; cursor:pointer; display:flex; align-items:center; gap:15px; font-weight:600;">
                <div style="width:30px; height:30px; background:#f97316; border-radius:4px; display:flex; justify-content:center; align-items:center; color:#fff; font-size:0.8rem;">UPI</div>
                Pay via UPI (GPay, PhonePe)
            </button>
            
            <button onclick="processDemoPayment('CARD')" style="width:100%; text-align:left; padding:1rem; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:10px; background:#f8fafc; cursor:pointer; display:flex; align-items:center; gap:15px; font-weight:600;">
                <div style="width:30px; height:30px; background:#3b82f6; border-radius:4px; display:flex; justify-content:center; align-items:center; color:#fff; font-size:0.8rem;">💳</div>
                Pay via Credit/Debit Card
            </button>
            
            <button onclick="processDemoPayment('NETBANK')" style="width:100%; text-align:left; padding:1rem; border:1px solid #e2e8f0; border-radius:8px; background:#f8fafc; cursor:pointer; display:flex; align-items:center; gap:15px; font-weight:600;">
                <div style="width:30px; height:30px; background:#10b981; border-radius:4px; display:flex; justify-content:center; align-items:center; color:#fff; font-size:0.8rem;">🏦</div>
                Net Banking
            </button>
        </div>
        
        <!-- Loading State -->
        <div style="padding:3rem 1.5rem; text-align:center; display:none;" id="demo-rzp-loading">
            <div style="display:inline-block; width:40px; height:40px; border:4px solid #f3f4f6; border-top-color:#f97316; border-radius:50%; animation:spin 1s linear infinite;"></div>
            <p style="margin-top:1rem; font-weight:600; color:#475569;">Processing Payment...</p>
            <p style="font-size:0.85rem; color:#94a3b8; margin-top:0.25rem;">Please do not refresh the page</p>
        </div>
        
        <!-- Success State -->
        <div style="padding:2.5rem 1.5rem; text-align:center; display:none; background:#ecfdf5;" id="demo-rzp-success">
            <div style="display:inline-flex; justify-content:center; align-items:center; width:60px; height:60px; background:#10b981; color:white; border-radius:50%; font-size:2rem; margin-bottom:1rem;">✓</div>
            <h3 style="margin:0; font-size:1.5rem; color:#065f46;">Payment Successful</h3>
            <p style="color:#047857; margin-top:0.5rem; font-size:0.95rem;">Redirecting...</p>
        </div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes fall { 
        0% { transform: translateY(-100px) rotate(0deg); opacity: 1; }
        100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
    }
    .coin {
        position: fixed;
        width: 30px;
        height: 30px;
        background: radial-gradient(circle, #fcd34d 0%, #fbbf24 60%, #d97706 100%);
        border: 2px solid #b45309;
        border-radius: 50%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3), inset 0 2px 4px rgba(255,255,255,0.5);
        z-index: 10000;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #b45309;
        font-weight: bold;
        font-size: 14px;
        pointer-events: none;
    }
    .coin::after { content: '₹'; }
</style>

<script>
    let currentFeeId = null;

    function openDemoRazorpay(feeId, amount, desc) {
        currentFeeId = feeId;
        document.getElementById('demo-rzp-amt').innerText = parseFloat(amount).toFixed(2);
        document.getElementById('demo-rzp-desc').innerText = desc;
        
        document.getElementById('demo-rzp-methods').style.display = 'block';
        document.getElementById('demo-rzp-loading').style.display = 'none';
        document.getElementById('demo-rzp-success').style.display = 'none';
        
        const overlay = document.getElementById('demo-rzp-overlay');
        const modal = document.getElementById('demo-rzp-modal');
        
        overlay.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        }, 10);
    }
    
    function closeDemoRazorpay() {
        const modal = document.getElementById('demo-rzp-modal');
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.9)';
        setTimeout(() => {
            document.getElementById('demo-rzp-overlay').style.display = 'none';
        }, 300);
    }
    
    function processDemoPayment(method) {
        document.getElementById('demo-rzp-methods').style.display = 'none';
        document.getElementById('demo-rzp-loading').style.display = 'block';
        
        // Simulate network delay
        setTimeout(() => {
            document.getElementById('demo-rzp-loading').style.display = 'none';
            document.getElementById('demo-rzp-success').style.display = 'block';
            
            // Wait 1 second on success screen, then submit form
            setTimeout(() => {
                const form = document.getElementById('form-pay-' + currentFeeId);
                const rzpInput = document.getElementById('rzp-id-' + currentFeeId);
                rzpInput.value = 'pay_demo_' + Math.random().toString(36).substr(2, 9);
                form.submit();
            }, 1200);
            
        }, 2000);
    }
    
    // Coin Animation Logic - Play if URL has success=Payment+Successful
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') && urlParams.get('success').includes('Payment')) {
            playCoinAnimation();
        }
    });
    
    function playCoinAnimation() {
        // Play success sound? Browser might block it, but visual is enough.
        for(let i = 0; i < 50; i++) {
            setTimeout(() => {
                let coin = document.createElement('div');
                coin.className = 'coin';
                coin.style.left = (Math.random() * 100) + 'vw';
                
                // Randomize size slightly
                let scale = 0.6 + Math.random() * 0.8;
                coin.style.width = (30 * scale) + 'px';
                coin.style.height = (30 * scale) + 'px';
                coin.style.fontSize = (14 * scale) + 'px';
                
                let duration = 1.5 + Math.random() * 2;
                coin.style.animation = `fall \${duration}s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards`;
                
                document.body.appendChild(coin);
                
                setTimeout(() => { coin.remove(); }, duration * 1000);
            }, i * 50); // Stagger the coins
        }
    }
</script>

HTML;

if (strpos($content, 'demo-rzp-overlay') === false) {
    $content = str_ireplace('</body>', $demoScript . "\n</body>", $content);
}

file_put_contents($file, $content);
echo "Demo Razorpay Modal and Coin Animations added to Parent portal.";
