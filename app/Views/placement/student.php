<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Student Placement Center</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <h3 class="text-xl font-bold mb-4">Active Job Postings</h3>
            <div class="grid-cards">
                <?php if(empty($jobs)): ?>
                    <p class="text-gray-500">No active job postings at the moment.</p>
                <?php endif; ?>
                <?php foreach($jobs as $job): ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-lg font-bold text-blue-600"><?= htmlspecialchars($job['company_name']) ?></h4>
                        <p class="font-medium text-gray-800 mb-2"><?= htmlspecialchars($job['job_title']) ?></p>
                        <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars($job['description']) ?></p>
                        
                        <div class="text-sm mb-4">
                            <span class="block"><strong>Package:</strong> <?= htmlspecialchars($job['salary_package']) ?></span>
                            <span class="block"><strong>Deadline:</strong> <?= htmlspecialchars($job['deadline']) ?></span>
                        </div>

                        <?php if(in_array($job['id'], $applied_job_ids)): ?>
                            <button class="btn w-full" style="background:#e2e8f0; color:#64748b; cursor:not-allowed;" disabled>Applied</button>
                        <?php else: ?>
                            <form action="<?= BASE_URL ?>placement" method="POST">
                                <input type="hidden" name="apply_job" value="1">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                <input type="url" name="resume_link" class="form-input w-full mb-2" placeholder="Paste Resume Drive Link" required>
                                <button type="submit" class="btn btn-primary w-full">Submit Application</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
