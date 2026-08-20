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
            <div class="welcome font-medium text-lg">My Disciplinary Records</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper max-w-3xl mx-auto mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Incident Date</th>
                                <th>Description</th>
                                <th>Action Taken</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($records as $rec): ?>
                            <tr>
                                <td class="text-sm"><?= date('d M Y', strtotime($rec['incident_date'])) ?></td>
                                <td><?= htmlspecialchars($rec['description']) ?></td>
                                <td><span class="text-red-600 font-medium"><?= htmlspecialchars($rec['action_taken']) ?></span></td>
                                <td>
                                    <span class="px-2 py-1 rounded text-xs <?= $rec['status'] == 'active' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                        <?= ucfirst($rec['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($records)): ?>
                                <tr><td colspan="4" class="text-center text-green-600 font-bold py-6">Clean record! Keep up the good behavior.</td></tr>
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
