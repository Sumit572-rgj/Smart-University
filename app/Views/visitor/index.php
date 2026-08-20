<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
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
            <div class="welcome font-medium text-lg">Visitor Management (Gate Pass)</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg">Active Visitors (Checked In)</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Visitor Name</th>
                                <th>Phone</th>
                                <th>Purpose</th>
                                <th>Host / To Meet</th>
                                <th>Check-In Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($visitors as $v): ?>
                            <tr>
                                <td class="font-bold text-cit-blue"><?= htmlspecialchars($v['visitor_name']) ?></td>
                                <td><?= htmlspecialchars($v['phone']) ?></td>
                                <td><?= htmlspecialchars($v['purpose']) ?></td>
                                <td><?= htmlspecialchars($v['host_name']) ?></td>
                                <td class="text-sm"><?= date('d M Y, h:i A', strtotime($v['check_in'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($visitors)): ?>
                                <tr><td colspan="5" class="text-center text-gray-500">No active visitors.</td></tr>
                            <?php endif; ?>
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
