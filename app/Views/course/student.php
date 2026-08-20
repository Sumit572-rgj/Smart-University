<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>My Courses & Timetable - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">My Academic Profile</div>
            </div>
            <div class="user-menu" style="display:flex; align-items:center;" style="display: flex; align-items: center; gap: 1rem;">
                
                

                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>
        </header>

        <div class="content-wrapper">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">My Enrolled Courses</h3>
                </div>
                <div class="table-container p-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Credits</th>
                                <th>Syllabus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($my_courses as $c): ?>
                                <tr>
                                    <td><strong class="text-cit-blue"><?= htmlspecialchars($c['course_code']) ?></strong></td>
                                    <td><?= htmlspecialchars($c['course_title']) ?></td>
                                    <td><?= htmlspecialchars($c['credits']) ?></td>
                                    <td>
                                        <?php if(!empty($c['syllabus_path'])): ?>
                                            <a href="<?= htmlspecialchars($c['syllabus_path']) ?>" target="_blank" class="text-blue-600 hover:underline">Download PDF</a>
                                        <?php else: ?>
                                            <span class="text-gray-400">Not Uploaded</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($my_courses)): ?>
                                <tr><td colspan="4" class="text-center">Not enrolled in any courses.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">My Weekly Timetable</h3>
                </div>
                <div class="table-container p-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Course</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $currentDay = '';
                            foreach($timetable as $t): 
                                $isNewDay = $t['day_of_week'] !== $currentDay;
                                $currentDay = $t['day_of_week'];
                            ?>
                                <tr class="<?= $isNewDay ? 'border-t-2 border-gray-300' : '' ?>">
                                    <td><?= $isNewDay ? '<strong>' . htmlspecialchars($t['day_of_week']) . '</strong>' : '' ?></td>
                                    <td><?= date('h:i A', strtotime($t['start_time'])) ?> - <?= date('h:i A', strtotime($t['end_time'])) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($t['course_code']) ?></strong><br>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($t['course_title']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($t['room_number']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($timetable)): ?>
                                <tr><td colspan="4" class="text-center">Timetable is empty.</td></tr>
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
