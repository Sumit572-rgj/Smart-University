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
            <div class="welcome font-medium text-lg">System Backup & Recovery</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper text-center py-10">
            <?php if (!empty($success)): ?><div class="alert" style="background:#d1fae5;color:#065f46; max-width: 600px; margin: 0 auto 2rem auto;"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            
            <div class="bg-white p-10 rounded-lg shadow-sm border border-gray-200 max-w-lg mx-auto">
                <svg class="w-16 h-16 mx-auto text-cit-blue mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                <h2 class="text-2xl font-bold mb-2">Cloud Database Backup</h2>
                <p class="text-gray-500 mb-8">Generate a full MySQL dump of the CIT UMS database and secure it in cloud storage.</p>
                <form action="<?= BASE_URL ?>backup" method="POST">
                    <button type="submit" class="btn btn-primary px-8 py-3 text-lg w-full">Generate New Backup</button>
                </form>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
