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
            <div class="welcome font-medium text-lg">Research & Publications Hub</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <?php foreach($publications as $pub): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-l-4 border-l-cit-blue">
                    <h3 class="text-lg font-bold mb-2"><?= htmlspecialchars($pub['title']) ?></h3>
                    <p class="text-sm text-gray-700 font-medium mb-1"><?= htmlspecialchars($pub['journal_name']) ?></p>
                    <p class="text-xs text-gray-500 mb-4">By: <?= htmlspecialchars($pub['first_name'] . ' ' . $pub['last_name']) ?> (<?= htmlspecialchars($pub['department']) ?>)</p>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500"><?= date('M Y', strtotime($pub['publication_date'])) ?></span>
                        <?php if($pub['doi_link']): ?>
                            <a href="<?= htmlspecialchars($pub['doi_link']) ?>" target="_blank" class="text-blue-600 hover:underline">View DOI</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($publications)): ?>
                    <p class="text-gray-500">No publications found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
