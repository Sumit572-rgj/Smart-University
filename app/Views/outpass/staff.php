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
            <div class="welcome font-medium text-lg">Pending Outpass Approvals (<?= ucfirst($user['role']) ?>)</div>
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

            <div class="grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <!-- AI Forecast -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-sm text-gray-500 mb-1">🤖 Frequent Travelers</div>
                    <div class="text-2xl font-bold text-cit-blue"><?= $ai_analytics['frequent_travelers'] ?> Students</div>
                    <div class="text-xs text-red-500 mt-1">Exceeding 3 passes/month</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-sm text-gray-500 mb-1">🤖 Unusual Patterns</div>
                    <div class="text-2xl font-bold text-orange-500"><?= $ai_analytics['unusual_patterns'] ?> Flags</div>
                    <div class="text-xs text-gray-500 mt-1">Late returns / Misuse detected</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-sm text-gray-500 mb-1">🤖 Avg. Duration</div>
                    <div class="text-2xl font-bold text-green-600"><?= $ai_analytics['avg_duration'] ?></div>
                    <div class="text-xs text-gray-500 mt-1">Based on last 30 days data</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Leave</th>
                                <th>Return</th>
                                <th>Destination</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($pending_requests)): ?>
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">No pending outpasses to review.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($pending_requests as $req): ?>
                            <tr>
                                <td>
                                    <div class="font-medium text-cit-blue"><?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($req['enrollment_no']) ?></div>
                                </td>
                                <td><?= date('d M Y H:i', strtotime($req['leave_date'])) ?></td>
                                <td><?= date('d M Y H:i', strtotime($req['return_date'])) ?></td>
                                <td><?= htmlspecialchars($req['destination']) ?></td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($req['reason']) ?>">
                                    <?= htmlspecialchars($req['reason']) ?>
                                </td>
                                <td>
                                    <div class="flex" style="gap: 0.5rem;">
                                        <form action="<?= BASE_URL ?>outpass" method="POST">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="outpass_id" value="<?= $req['id'] ?>">
                                            <button type="submit" class="btn btn-primary" style="background:#10b981; border:none; padding: 0.25rem 0.75rem; font-size: 0.75rem;">Approve</button>
                                        </form>
                                        <form action="<?= BASE_URL ?>outpass" method="POST">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="outpass_id" value="<?= $req['id'] ?>">
                                            <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.75rem; font-size: 0.75rem;">Reject</button>
                                        </form>
                                    </div>
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
</body>
</html>
