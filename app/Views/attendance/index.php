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
            <div class="welcome font-medium text-lg">My Attendance</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <?php foreach($attendance as $record): 
                    $percentage = $record['total_classes'] > 0 ? round(($record['present_classes'] / $record['total_classes']) * 100) : 0;
                    $colorClass = $percentage >= 75 ? 'text-green-600' : 'text-red-600';
                ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col items-center justify-center">
                    <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($record['course_code']) ?></h3>
                    <div class="text-4xl font-bold <?= $colorClass ?> mb-2"><?= $percentage ?>%</div>
                    <p class="text-sm text-gray-500">Present: <?= $record['present_classes'] ?> / <?= $record['total_classes'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php if(empty($attendance)): ?>
                    <p class="text-gray-500">No attendance records found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
