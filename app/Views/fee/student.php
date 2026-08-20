<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <style>
        /* Mock Payment Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(5px);
            display: none; align-items: center; justify-content: center; z-index: 1000;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        .payment-modal {
            background: white; padding: 2rem; border-radius: 1rem;
            width: 100%; max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95); transition: transform 0.3s ease;
        }
        .modal-overlay.active .payment-modal { transform: scale(1); }
        .stripe-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .card-input { border: 1px solid #e2e8f0; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; width: 100%; }
        .loader {
            display: none; border: 3px solid #f3f3f3; border-top: 3px solid var(--cit-orange);
            border-radius: 50%; width: 24px; height: 24px; animation: spin 1s linear infinite; margin: 0 auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">My Fees & Invoices</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($invoices)): ?>
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">No fees found.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($invoices as $inv): 
                                $total_amount = $inv['amount'] - $inv['discount_amount'] + $inv['fine_amount'];
                            ?>
                            <tr>
                                <td><span class="font-medium"><?= htmlspecialchars($inv['invoice_no']) ?></span></td>
                                <td><?= ucfirst(htmlspecialchars($inv['fee_type'])) ?></td>
                                <td>
                                    <div class="font-bold">₹<?= number_format($total_amount, 2) ?></div>
                                    <?php if($inv['discount_amount'] > 0 || $inv['fine_amount'] > 0): ?>
                                    <div class="text-xs text-gray-500">
                                        Base: ₹<?= number_format($inv['amount'], 2) ?> 
                                        <?php if($inv['discount_amount'] > 0) echo "| Disc: -₹" . number_format($inv['discount_amount'], 2); ?>
                                        <?php if($inv['fine_amount'] > 0) echo "| Fine: +₹" . number_format($inv['fine_amount'], 2); ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y', strtotime($inv['due_date'])) ?></td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-blue-100 text-blue-800',
                                            'verified' => 'bg-green-100 text-green-800',
                                            'overdue' => 'bg-red-100 text-red-800'
                                        ];
                                        $class = $statusColors[$inv['status']] ?? 'bg-gray-100';
                                        
                                        $displayStatus = ucfirst($inv['status']);
                                        if ($inv['status'] === 'paid') $displayStatus = 'Pending Verification';
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium <?= $class ?>" style="border-radius: 999px; padding: 2px 8px;">
                                        <?= $displayStatus ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($inv['status'] === 'pending' || $inv['status'] === 'overdue'): ?>
                                        <button type="button" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="openDemoRazorpay(<?= $inv['id'] ?>, <?= $total_amount ?>, 'Student Fee')">Pay Now</button>
                                        <form id="pay-form-<?= $inv['id'] ?>" action="<?= BASE_URL ?>fee/student" method="POST" style="display:none;">
                                            <input type="hidden" name="action" value="pay">
                                            <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
                                            <input type="hidden" name="reference_no" id="ref-<?= $inv['id'] ?>" value="">
                                        </form>
                                    <?php elseif ($inv['status'] === 'verified'): ?>
                                        <button type="button" class="btn btn-primary" style="background:#4b5563; padding: 0.25rem 0.5rem; font-size: 0.75rem; border:none;" onclick="printReceipt('<?= $inv['invoice_no'] ?>', <?= $total_amount ?>, '<?= $inv['reference_no'] ?>')">🧾 Receipt</button>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">Paid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>



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
                const form = document.getElementById('pay-form-' + currentFeeId);
                const rzpInput = document.getElementById('ref-' + currentFeeId);
                if(rzpInput) rzpInput.value = 'pay_demo_' + Math.random().toString(36).substr(2, 9);
                form.submit();
            }, 1200);
            
        }, 2000);
    }
    
    // Coin Animation Logic - Play if URL has success message
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        // Student FeeController uses POST action 'pay' and shows success in body, not URL.
        // But let's check for success message element presence
        const successAlert = document.querySelector('.alert-success');
        if ((urlParams.get('success') && urlParams.get('success').includes('Payment')) || 
            (successAlert && successAlert.innerText.includes('Payment successful'))) {
            playCoinAnimation();
        }
    });
    
    function playCoinAnimation() {
        for(let i = 0; i < 50; i++) {
            setTimeout(() => {
                let coin = document.createElement('div');
                coin.className = 'coin';
                coin.style.left = (Math.random() * 100) + 'vw';
                
                let scale = 0.6 + Math.random() * 0.8;
                coin.style.width = (30 * scale) + 'px';
                coin.style.height = (30 * scale) + 'px';
                coin.style.fontSize = (14 * scale) + 'px';
                
                let duration = 1.5 + Math.random() * 2;
                coin.style.animation = `fall ${duration}s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards`;
                
                document.body.appendChild(coin);
                
                setTimeout(() => { coin.remove(); }, duration * 1000);
            }, i * 50); 
        }
    }
</script>

<script>
function printReceipt(invoiceNo, amount, refNo) {
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Fee Receipt - ${invoiceNo}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
                .receipt-container { border: 2px solid #e5e7eb; border-radius: 8px; padding: 30px; max-width: 600px; margin: 0 auto; }
                .header { text-align: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
                .logo { font-size: 24px; font-weight: bold; color: #f97316; }
                .title { font-size: 20px; margin-top: 10px; font-weight: bold; }
                .details { margin-bottom: 30px; }
                .details table { width: 100%; border-collapse: collapse; }
                .details td { padding: 10px; border-bottom: 1px solid #f3f4f6; }
                .details .label { font-weight: bold; width: 40%; color: #6b7280; }
                .footer { text-align: center; font-size: 14px; color: #6b7280; margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
                @media print {
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="header">
                    <div class="logo">CIT UNIVERSITY</div>
                    <div class="title">OFFICIAL FEE RECEIPT</div>
                </div>
                <div class="details">
                    <table>
                        <tr><td class="label">Invoice Number</td><td>${invoiceNo}</td></tr>
                        <tr><td class="label">Reference No</td><td>${refNo || 'N/A'}</td></tr>
                        <tr><td class="label">Amount Paid</td><td style="font-size:18px; font-weight:bold;">₹${parseFloat(amount).toFixed(2)}</td></tr>
                        <tr><td class="label">Payment Date</td><td>${new Date().toLocaleDateString()}</td></tr>
                        <tr><td class="label">Status</td><td style="color:#10b981; font-weight:bold;">VERIFIED</td></tr>
                    </table>
                </div>
                <div class="footer">
                    This is a computer generated receipt and does not require a physical signature.<br>
                    Thank you for your payment.
                </div>
            </div>
            <script>
                window.onload = function() { window.print(); }
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
</body></html>
        `;
        const win = window.open('', '_blank');
        win.document.write(receiptHtml);
        win.document.close();
        win.focus();
        setTimeout(() => { win.print(); }, 500);
    }
</script>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>

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
                const form = document.getElementById('pay-form-' + currentFeeId);
                const rzpInput = document.getElementById('ref-' + currentFeeId);
                if(rzpInput) rzpInput.value = 'pay_demo_' + Math.random().toString(36).substr(2, 9);
                form.submit();
            }, 1200);
            
        }, 2000);
    }
    
    // Coin Animation Logic - Play if URL has success message
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        // Student FeeController uses POST action 'pay' and shows success in body, not URL.
        // But let's check for success message element presence
        const successAlert = document.querySelector('.alert-success');
        if ((urlParams.get('success') && urlParams.get('success').includes('Payment')) || 
            (successAlert && successAlert.innerText.includes('Payment successful'))) {
            playCoinAnimation();
        }
    });
    
    function playCoinAnimation() {
        for(let i = 0; i < 50; i++) {
            setTimeout(() => {
                let coin = document.createElement('div');
                coin.className = 'coin';
                coin.style.left = (Math.random() * 100) + 'vw';
                
                let scale = 0.6 + Math.random() * 0.8;
                coin.style.width = (30 * scale) + 'px';
                coin.style.height = (30 * scale) + 'px';
                coin.style.fontSize = (14 * scale) + 'px';
                
                let duration = 1.5 + Math.random() * 2;
                coin.style.animation = `fall ${duration}s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards`;
                
                document.body.appendChild(coin);
                
                setTimeout(() => { coin.remove(); }, duration * 1000);
            }, i * 50); 
        }
    }
</script>

<script>
function printReceipt(invoiceNo, amount, refNo) {
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Fee Receipt - ${invoiceNo}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
                .receipt-container { border: 2px solid #e5e7eb; border-radius: 8px; padding: 30px; max-width: 600px; margin: 0 auto; }
                .header { text-align: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
                .logo { font-size: 24px; font-weight: bold; color: #f97316; }
                .title { font-size: 20px; margin-top: 10px; font-weight: bold; }
                .details { margin-bottom: 30px; }
                .details table { width: 100%; border-collapse: collapse; }
                .details td { padding: 10px; border-bottom: 1px solid #f3f4f6; }
                .details .label { font-weight: bold; width: 40%; color: #6b7280; }
                .footer { text-align: center; font-size: 14px; color: #6b7280; margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
                @media print {
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="header">
                    <div class="logo">CIT UNIVERSITY</div>
                    <div class="title">OFFICIAL FEE RECEIPT</div>
                </div>
                <div class="details">
                    <table>
                        <tr><td class="label">Invoice Number</td><td>${invoiceNo}</td></tr>
                        <tr><td class="label">Reference No</td><td>${refNo || 'N/A'}</td></tr>
                        <tr><td class="label">Amount Paid</td><td style="font-size:18px; font-weight:bold;">₹${parseFloat(amount).toFixed(2)}</td></tr>
                        <tr><td class="label">Payment Date</td><td>${new Date().toLocaleDateString()}</td></tr>
                        <tr><td class="label">Status</td><td style="color:#10b981; font-weight:bold;">VERIFIED</td></tr>
                    </table>
                </div>
                <div class="footer">
                    This is a computer generated receipt and does not require a physical signature.<br>
                    Thank you for your payment.
                </div>
            </div>
            <script>
                window.onload = function() { window.print(); }
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
</body>
</html>
