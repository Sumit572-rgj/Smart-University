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
            <div class="welcome font-medium text-lg">Student Leaderboard</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper max-w-4xl mx-auto mt-8">
            <div class="bg-gradient-to-r from-cit-blue to-blue-800 rounded-t-lg p-6 text-white text-center">
                <h2 class="text-3xl font-bold">🏆 Hall of Fame</h2>
                <p class="text-blue-200 mt-2">Top performing students of the semester based on academics and engagement.</p>
            </div>
            <div class="bg-white rounded-b-lg shadow-md border border-gray-200">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Score (Points)</th>
                                <th>Achievement Badge</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1; foreach($leaders as $leader): ?>
                            <tr>
                                <td class="font-bold text-xl text-gray-400">#<?= $rank++ ?></td>
                                <td class="font-bold text-cit-blue text-lg"><?= htmlspecialchars($leader['name']) ?></td>
                                <td><?= htmlspecialchars($leader['dept']) ?></td>
                                <td class="font-bold text-green-600 text-lg"><?= $leader['score'] ?> XP</td>
                                <td><span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-medium text-sm"><?= htmlspecialchars($leader['badge']) ?></span></td>
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
