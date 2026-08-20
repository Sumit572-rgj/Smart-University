<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="welcome font-medium text-lg">My Exams & Results</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Available Exams -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold">Upcoming / Active Exams</h3>
                    </div>
                    <div class="p-4">
                        <?php if(empty($exams)): ?>
                            <p class="text-gray-500">No upcoming exams.</p>
                        <?php endif; ?>
                        
                        <?php foreach($exams as $exam): ?>
                            <div class="border rounded p-4 mb-4 border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-bold text-cit-blue"><?= htmlspecialchars($exam['course_code'] . ' - ' . $exam['title']) ?></h4>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">Upcoming</span>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">Starts: <?= date('d M Y, h:i A', strtotime($exam['start_time'])) ?> | Duration: <?= $exam['duration_minutes'] ?> mins</p>
                                <a href="<?= BASE_URL ?>exam/take/<?= $exam['id'] ?>" class="btn btn-primary w-full text-center">Enter Exam Hall</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Results -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-fit">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold">My Results</h3>
                    </div>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($results)): ?>
                                    <tr><td colspan="3" class="text-center text-gray-500">No results published yet.</td></tr>
                                <?php endif; ?>
                                <?php foreach($results as $res): ?>
                                <tr>
                                    <td>
                                        <span class="font-medium block"><?= htmlspecialchars($res['course_code']) ?></span>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($res['title']) ?></span>
                                    </td>
                                    <td>
                                        <span class="font-bold text-cit-blue"><?= htmlspecialchars($res['score']) ?></span> / <?= htmlspecialchars($res['total_marks']) ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($res['submitted_at'])) ?></td>
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
