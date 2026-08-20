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
            <div class="welcome font-medium text-lg">Placement & Career Services</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <?php if (!empty($success)): ?><div class="alert" style="background:#d1fae5;color:#065f46;"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <?php foreach($jobs as $job): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-xl font-bold text-cit-blue mb-1"><?= htmlspecialchars($job['job_title']) ?></h3>
                    <p class="text-lg font-medium text-gray-700 mb-2"><?= htmlspecialchars($job['company_name']) ?></p>
                    <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($job['description']) ?></p>
                    <p class="font-bold mb-4">Package: <span class="text-green-600"><?= htmlspecialchars($job['salary_package']) ?></span></p>
                    <p class="text-sm text-red-500 font-bold mb-4">Deadline: <?= date('d M Y', strtotime($job['deadline'])) ?></p>
                    <form action="<?= BASE_URL ?>placement" method="POST" class="flex gap-2">
                        <input type="hidden" name="action" value="apply">
                        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                        <input type="url" name="resume_link" class="form-input flex-grow text-sm" placeholder="Link to Resume (Drive/PDF)" required>
                        <button type="submit" class="btn btn-primary text-sm">Apply</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
