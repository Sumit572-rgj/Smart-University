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
            <div class="welcome font-medium text-lg">Alumni Network & Directory</div>
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
                                <th>Name</th>
                                <th>Class of</th>
                                <th>Company</th>
                                <th>Designation</th>
                                <th>Connect</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($alumni as $al): ?>
                            <tr>
                                <td><span class="font-bold"><?= htmlspecialchars($al['name']) ?></span></td>
                                <td><?= htmlspecialchars($al['graduation_year']) ?></td>
                                <td><?= htmlspecialchars($al['company']) ?></td>
                                <td><?= htmlspecialchars($al['designation']) ?></td>
                                <td>
                                    <?php if($al['linkedin_url']): ?>
                                        <a href="https://<?= htmlspecialchars($al['linkedin_url']) ?>" target="_blank" class="text-blue-600 hover:underline">LinkedIn</a>
                                    <?php else: ?>
                                        <span class="text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($alumni)): ?>
                                <tr><td colspan="5" class="text-center text-gray-500">No alumni records found.</td></tr>
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
