<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Gate Security Scanner</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                <!-- Scanner Section -->
                <div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 scanner-container">
                        <h3 class="text-lg font-bold mb-4">Scan Outpass</h3>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
                            
                            <?php if (isset($scanned_student) && isset($scanned_student['current_action'])): ?>
                                <div style="background: white; border: 2px solid <?= $scanned_student['action_color'] ?>; border-radius: 1rem; padding: 1.5rem; text-align: center; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);">
                                    <div style="background: <?= $scanned_student['action_color'] ?>; color: white; display: inline-block; padding: 0.25rem 1rem; border-radius: 999px; font-weight: 800; font-size: 0.85rem; margin-bottom: 1rem; letter-spacing: 1px;">
                                        <?= $scanned_student['current_action'] ?> SUCCESS
                                    </div>
                                    
                                    <div style="width: 100px; height: 100px; margin: 0 auto 1rem; border-radius: 50%; border: 4px solid #f1f5f9; overflow: hidden; background: #e2e8f0;">
                                        <!-- Using UI Avatars for placeholder profile pic if actual profile_pic isn't available -->
                                        <?php $picUrl = !empty($scanned_student['profile_pic']) ? htmlspecialchars($scanned_student['profile_pic']) : "https://ui-avatars.com/api/?name=" . urlencode($scanned_student['first_name'] . ' ' . $scanned_student['last_name']) . "&background=0f172a&color=fff&size=200"; ?>
                                        <img src="<?= $picUrl ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    
                                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;"><?= htmlspecialchars($scanned_student['first_name'] . ' ' . $scanned_student['last_name']) ?></h2>
                                    <p style="color: #64748b; font-size: 1.1rem; font-weight: 600; margin: 0.25rem 0 1rem;">ID: <?= htmlspecialchars($scanned_student['enrollment_no']) ?></p>
                                    
                                    <div class="digital-id-details" style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left;">
                                        <div>
                                            <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Department</div>
                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars($scanned_student['department']) ?></div>
                                        </div>
                                        <div>
                                            <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Destination</div>
                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars($scanned_student['destination']) ?></div>
                                        </div>
                                        <div style="grid-column: span 2;">
                                            <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Reason</div>
                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars($scanned_student['reason']) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-error" style="margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <div id="reader-container" style="display:none; margin-bottom:1rem; border-radius:0.5rem; overflow:hidden;">
                            <div id="reader" style="width: 100%;"></div>
                            <button class="btn btn-secondary w-full mt-2" onclick="stopScanner()">Stop Camera</button>
                        </div>

                        <div id="camera-prompt" style="background:#f8fafc; border:2px dashed #cbd5e1; padding:2rem; text-align:center; margin-bottom:1rem; border-radius:0.5rem; cursor:pointer;" onclick="startScanner()">
                            <p class="text-gray-500 mb-2">Click to Start Camera Scanner</p>
                            <svg style="width:64px;height:64px;margin:0 auto;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>

                        <form id="scan-form" action="<?= BASE_URL ?>gate" method="POST">
                            <label class="block text-sm font-medium mb-1">Or Enter Outpass ID Manually</label>
                            <div class="scanner-manual-input" style="display:flex;gap:0.5rem;">
                                <input type="text" id="outpass_input" name="outpass_id" class="form-input" style="flex:1;" placeholder="ID..." required autofocus>
                                <button type="submit" class="btn btn-primary">Verify</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Logs Section -->
                <div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Recent Gate Activity</h3>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Outpass ID</th>
                                        <th>Student Name</th>
                                        <th>Enrollment No</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($activities)): ?>
                                        <tr><td colspan="4" class="text-center text-gray-500 py-4">No recent activity found.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach ($activities as $log): ?>
                                    <tr>
                                        <td><strong>#<?= htmlspecialchars($log['id']) ?></strong></td>
                                        <td><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></td>
                                        <td><?= htmlspecialchars($log['enrollment_no']) ?></td>
                                        <td>
                                            <?php if($log['status'] === 'checked_out'): ?>
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800" style="border-radius: 999px;">Out of Campus</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800" style="border-radius: 999px;">Returned Inside</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Load HTML5-QRCode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let html5QrcodeScanner = null;

    function startScanner() {
        document.getElementById('camera-prompt').style.display = 'none';
        document.getElementById('reader-container').style.display = 'block';

        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function stopScanner() {
        if(html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                document.getElementById('reader-container').style.display = 'none';
                document.getElementById('camera-prompt').style.display = 'block';
            }).catch(error => {
                console.error("Failed to clear html5QrcodeScanner. ", error);
            });
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning
        html5QrcodeScanner.clear();
        
        // Populate the input field
        document.getElementById('outpass_input').value = decodedText;
        
        // Auto submit the form
        document.getElementById('scan-form').submit();
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }

    // Automatically focus the input box after page reload so barcode scanner (physical) can also keep firing
    window.onload = function() {
        document.getElementById('outpass_input').focus();
    };
</script>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
