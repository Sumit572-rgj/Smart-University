<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <script>
        function toggleTab(childId, tabId) {
            document.querySelectorAll('.tab-content-' + childId).forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn-' + childId).forEach(el => el.classList.remove('modern-tab-active'));
            
            document.getElementById(tabId + '-' + childId).style.display = 'block';
            document.getElementById('btn-' + tabId + '-' + childId).classList.add('modern-tab-active');
        }

        window.onload = function() {
            <?php foreach ($children ?? [] as $childData): ?>
            toggleTab(<?= $childData['info']['student_id'] ?>, 'progress');
            <?php endforeach; ?>
        };
    </script>
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Parent & Guardian Portal</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (empty($children)): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Welcome to the Parent Portal</h3>
                    <p class="text-gray-600">You currently do not have any students linked to your account. Please contact administration.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($children as $index => $childData): 
                $child = $childData['info'];
                $cid = $child['student_id'];
            ?>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <!-- Child Header -->
                <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-cit-blue"><?= htmlspecialchars($child['first_name'] . ' ' . $child['last_name']) ?></h2>
                        <p class="text-sm text-gray-600 mt-1">
                            <strong>Enrollment No:</strong> <?= htmlspecialchars($child['enrollment_no']) ?> | 
                            <strong>Department:</strong> <?= htmlspecialchars($child['department']) ?> | 
                            <strong>Relation:</strong> <?= htmlspecialchars($child['relation']) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 mb-1">Overall Attendance</div>
                        <div class="text-2xl font-bold <?= $childData['attendance_percent'] < 75 ? 'text-red-600' : 'text-green-600' ?>">
                            <?= $childData['attendance_percent'] ?>%
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0;">
                    <div class="pill-tabs-container" style="display: inline-flex; background: #e2e8f0; padding: 0.35rem; border-radius: 9999px; gap: 0.5rem; flex-wrap: wrap; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                        <button id="btn-progress-<?= $cid ?>" class="tab-btn-<?= $cid ?> pill-tab-btn" onclick="toggleTab(<?= $cid ?>, 'progress')">
                            <span style="margin-right:5px; font-size:1.1rem;">📊</span> Academic Progress
                        </button>
                        <button id="btn-attendance-<?= $cid ?>" class="tab-btn-<?= $cid ?> pill-tab-btn" onclick="toggleTab(<?= $cid ?>, 'attendance')">
                            <span style="margin-right:5px; font-size:1.1rem;">✅</span> Attendance
                        </button>
                        <button id="btn-fees-<?= $cid ?>" class="tab-btn-<?= $cid ?> pill-tab-btn" onclick="toggleTab(<?= $cid ?>, 'fees')">
                            <span style="margin-right:5px; font-size:1.1rem;">💳</span> Fee Status
                        </button>
                        <button id="btn-notices-<?= $cid ?>" class="tab-btn-<?= $cid ?> pill-tab-btn" onclick="toggleTab(<?= $cid ?>, 'notices')">
                            <span style="margin-right:5px; font-size:1.1rem;">📢</span> School Notices
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Progress Tab -->
                    <div id="progress-<?= $cid ?>" class="tab-content-<?= $cid ?>">
                        <h3 class="font-bold text-lg mb-4">Exam Results & Grades</h3>
                        <?php if (empty($childData['exams'])): ?>
                            <p class="text-gray-500">No exam results published yet.</p>
                        <?php else: ?>
                            <table class="table w-full text-left">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 border">Exam Name</th>
                                        <th class="p-2 border">Marks Obtained</th>
                                        <th class="p-2 border">Total Marks</th>
                                        <th class="p-2 border">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($childData['exams'] as $exam): ?>
                                    <tr>
                                        <td class="p-2 border font-medium"><?= htmlspecialchars($exam['exam_name']) ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($exam['marks_obtained']) ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($exam['total_marks']) ?></td>
                                        <td class="p-2 border font-bold text-cit-blue"><?= htmlspecialchars($exam['grade']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>

                    <!-- Attendance Tab -->
                    <div id="attendance-<?= $cid ?>" class="tab-content-<?= $cid ?>" style="display:none;">
                        <h3 class="font-bold text-lg mb-4">Attendance Overview</h3>
                        <?php if ($childData['attendance_percent'] < 75 && $childData['total_classes'] > 0): ?>
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                                <p class="text-red-700 font-bold">Low Attendance Alert</p>
                                <p class="text-red-600 text-sm">Your child's attendance is below the required 75%. Please ensure regular attendance to avoid academic penalties.</p>
                            </div>
                        <?php endif; ?>
                        <div class="bg-gray-50 p-4 rounded-lg inline-block border border-gray-200">
                            <p class="text-lg">Total Classes Recorded: <strong class="text-cit-blue"><?= $childData['total_classes'] ?></strong></p>
                            <p class="text-lg">Current Percentage: <strong class="<?= $childData['attendance_percent'] < 75 ? 'text-red-600' : 'text-green-600' ?>"><?= $childData['attendance_percent'] ?>%</strong></p>
                        </div>
                    </div>

                    <!-- Fees Tab -->
                    <div id="fees-<?= $cid ?>" class="tab-content-<?= $cid ?>" style="display:none;">
                        <h3 class="font-bold text-lg mb-4">Financial Dashboard</h3>
                        <?php if (empty($childData['fees'])): ?>
                            <p class="text-gray-500">No fee records found.</p>
                        <?php else: ?>
                            <div class="grid gap-4">
                                <?php foreach ($childData['fees'] as $fee): ?>
                                <div class="p-5 border rounded-lg flex justify-between items-center <?= $fee['status'] === 'paid' ? 'bg-green-50 border-green-200' : 'bg-white shadow-sm border-gray-200' ?>">
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($fee['fee_type']) ?></h4>
                                        <p class="text-sm text-gray-500 mt-1">Due Date: <?= date('M d, Y', strtotime($fee['due_date'])) ?></p>
                                        <?php if ($fee['status'] === 'paid'): ?>
                                            <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Paid Successfully</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right" style="display:flex; flex-direction:column; align-items:flex-end;">
                                        <p class="text-2xl font-bold text-cit-blue mb-2">₹<?= number_format($fee['total_amount'], 2) ?></p>
                                        <?php if ($fee['status'] === 'pending'): ?>
                                            <form action="<?= BASE_URL ?>parent/pay" method="POST" class="mt-2" id="form-pay-<?= $fee['id'] ?>">
    <input type="hidden" name="fee_id" value="<?= $fee['id'] ?>">
    <input type="hidden" name="razorpay_payment_id" id="rzp-id-<?= $fee['id'] ?>" value="">
    <button type="button" class="razorpay-payment-button" onclick="openDemoRazorpay(<?= $fee['id'] ?>, <?= $fee['total_amount'] ?>, '<?= addslashes($fee['fee_type']) ?>')">Pay with Razorpay (Demo)</button>
</form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Notices Tab -->
                    <div id="notices-<?= $cid ?>" class="tab-content-<?= $cid ?>" style="display:none;">
                        <h3 class="font-bold text-lg mb-4">Recent School Notices & Alerts</h3>
                        <p class="text-sm text-gray-500 mb-4">Real-time SMS/Email alerts are automatically dispatched for these notices.</p>
                        <?php if (empty($childData['notices'])): ?>
                            <p class="text-gray-500">No recent notices.</p>
                        <?php else: ?>
                            <div class="grid gap-4">
                                <?php foreach ($childData['notices'] as $notice): ?>
                                <div class="bg-blue-50 border-l-4 border-cit-blue p-4">
                                    <h4 class="font-bold text-cit-blue"><?= htmlspecialchars($notice['title']) ?></h4>
                                    <p class="text-xs text-gray-500 mb-2">Posted on <?= date('M d, Y', strtotime($notice['event_date'])) ?></p>
                                    <p class="text-sm text-gray-800"><?= htmlspecialchars($notice['description']) ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mt-6">
                <h3 class="font-bold text-cit-blue mb-2">Need to contact Faculty or Admin?</h3>
                <p class="text-gray-700 text-sm mb-4">You can use our secure internal messaging system to send direct queries to teachers, wardens, or the finance department.</p>
                
            </div>

        </div>
    </main>
</div>
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
                coin.style.animation = `fall ${duration}s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards`;
                
                document.body.appendChild(coin);
                
                setTimeout(() => { coin.remove(); }, duration * 1000);
            }, i * 50); // Stagger the coins
        }
    }
</script>

</body>
</html>
