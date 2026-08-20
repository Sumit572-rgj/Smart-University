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
            <div class="welcome font-medium text-lg">My Outpasses</div>
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

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <h3 class="text-lg font-bold mb-4">Apply for Outpass</h3>
                <form action="<?= BASE_URL ?>outpass" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <input type="hidden" name="action" value="create">
                    <div>
                        <label class="block text-sm mb-1">Leave Date & Time</label>
                        <input type="datetime-local" name="leave_date" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Return Date & Time</label>
                        <input type="datetime-local" name="return_date" class="form-input w-full" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="block text-sm mb-1">Destination</label>
                        <input type="text" name="destination" class="form-input w-full" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="block text-sm mb-1">Reason</label>
                        <textarea name="reason" class="form-input w-full" rows="3" required></textarea>
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">My Request History</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Applied On</th>
                                <th>Leave</th>
                                <th>Return</th>
                                <th>Destination</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($req['created_at'])) ?></td>
                                <td><?= date('d M Y H:i', strtotime($req['leave_date'])) ?></td>
                                <td><?= date('d M Y H:i', strtotime($req['return_date'])) ?></td>
                                <td><?= htmlspecialchars($req['destination']) ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        if ($req['status'] === 'approved') $statusClass = 'bg-green-100 text-green-800';
                                        if ($req['status'] === 'rejected') $statusClass = 'bg-red-100 text-red-800';
                                        if (strpos($req['status'], 'pending') !== false) $statusClass = 'bg-yellow-100 text-yellow-800';
                                        
                                        $displayStatus = str_replace('_', ' ', strtoupper($req['status']));
                                    ?>
                                    <div class="mb-2">
                                        <span class="px-2 py-1 rounded text-xs font-medium <?= $statusClass ?>" style="border-radius: 999px; padding: 2px 8px;">
                                            <?= $displayStatus ?>
                                        </span>
                                    </div>
                                    <?php if ($req['status'] === 'approved' && !empty($req['qr_code'])): ?>
                                        <button type="button" onclick="showFullQR('<?= htmlspecialchars($req['qr_code']) ?>')" class="btn" style="background: #10b981; color: white; padding: 0.25rem 0.75rem; font-size: 0.8rem; margin-top: 0.5rem; border-radius: 999px;">
                                            &#128247; View QR
                                        </button>
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

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>

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
</html>
