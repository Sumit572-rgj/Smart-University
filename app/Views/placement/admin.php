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
            <div class="welcome font-medium text-lg">Admin Placement Center</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Post New Job</h3>
                    <form action="<?= BASE_URL ?>placement" method="POST">
                        <input type="hidden" name="add_job" value="1">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Company Name</label>
                            <input type="text" name="company_name" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Job Title</label>
                            <input type="text" name="job_title" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" class="form-input w-full" rows="3" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Salary Package</label>
                            <input type="text" name="salary_package" class="form-input w-full" placeholder="e.g. 10 LPA" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Deadline</label>
                            <input type="date" name="deadline" class="form-input w-full" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Post Job</button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold">Student Applications</h3>
                    </div>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th>Resume</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($applications)): ?>
                                    <tr><td colspan="5" class="text-center py-4">No applications yet.</td></tr>
                                <?php endif; ?>
                                <?php foreach($applications as $app): ?>
                                <tr>
                                    <td><?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?> <br><small class="text-gray-500"><?= htmlspecialchars($app['enrollment_no']) ?></small></td>
                                    <td><?= htmlspecialchars($app['company_name']) ?></td>
                                    <td><?= htmlspecialchars($app['job_title']) ?></td>
                                    <td><a href="<?= htmlspecialchars($app['resume_link']) ?>" target="_blank" class="text-blue-500 underline">View Link</a></td>
                                    <td><span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800"><?= htmlspecialchars($app['status']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
