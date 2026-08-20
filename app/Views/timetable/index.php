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
            <div class="welcome font-medium text-lg">Smart Timetable</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="grid" style="grid-template-columns: repeat(5, 1fr); gap: 1rem;">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    foreach($days as $day): 
                    ?>
                        <div>
                            <h3 class="font-bold text-center bg-gray-100 py-2 rounded mb-3 border border-gray-200"><?= $day ?></h3>
                            <?php if(isset($timetable[$day])): ?>
                                <?php foreach($timetable[$day] as $class): ?>
                                    <div class="border rounded p-3 mb-2 bg-blue-50 border-blue-100 text-sm">
                                        <p class="font-bold text-cit-blue"><?= htmlspecialchars($class['course_code']) ?></p>
                                        <p class="text-xs text-gray-500 mb-1"><?= substr($class['start_time'], 0, 5) ?> - <?= substr($class['end_time'], 0, 5) ?></p>
                                        <p class="text-xs">Room: <?= htmlspecialchars($class['room']) ?></p>
                                        <p class="text-xs text-gray-400"><?= htmlspecialchars($class['first_name'] . ' ' . $class['last_name']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-xs text-center text-gray-400 italic">No classes scheduled</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
