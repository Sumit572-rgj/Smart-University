<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?> - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>
    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">System Activity Logs</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($logs as $log): ?>
                            <tr>
                                <td class="text-xs text-gray-500"><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                                <td class="font-bold"><?= htmlspecialchars($log['username'] ?? 'System/Guest') ?></td>
                                <td><span class="px-2 py-1 bg-gray-100 rounded-full text-xs"><?= htmlspecialchars($log['role'] ?? 'N/A') ?></span></td>
                                <td><?= htmlspecialchars($log['action']) ?></td>
                                <td class="text-xs font-mono"><?= htmlspecialchars($log['ip_address']) ?></td>
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
