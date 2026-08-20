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
            <div class="welcome font-medium text-lg">Student Attendance Portal</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6 text-center">
                <h3 class="text-gray-500 font-bold mb-2 uppercase">Overall Attendance</h3>
                <div style="font-size: 3rem; font-weight: 800; color: <?= $percentage >= 75 ? '#10b981' : '#ef4444' ?>;">
                    <?= $percentage ?>%
                </div>
                <?php if($percentage < 75): ?>
                    <p class="text-red-500 mt-2 font-medium">Warning: Your attendance is below the 75% required threshold.</p>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200"><h3 class="text-lg font-bold">Recent Records</h3></div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Course</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($records)): ?>
                                <tr><td colspan="3" class="text-center py-4 text-gray-500">No attendance records found.</td></tr>
                            <?php endif; ?>
                            <?php foreach($records as $r): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars(date('M d, Y', strtotime($r['date']))) ?></strong></td>
                                <td><?= htmlspecialchars($r['course_code']) ?></td>
                                <td>
                                    <?php if($r['status'] === 'present'): ?>
                                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Present</span>
                                    <?php elseif($r['status'] === 'absent'): ?>
                                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Absent</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Late</span>
                                    <?php endif; ?>
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
