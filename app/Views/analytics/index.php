<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <!-- Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>
    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">University Analytics Dashboard</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <div class="grid-cards mb-8">
                <div class="card stat-card border-t-4 border-t-cit-blue">
                    <div class="stat-value"><?= $stats['total_students'] ?></div>
                    <div class="stat-label">Total Students</div>
                </div>
                <div class="card stat-card border-t-4 border-t-cit-orange">
                    <div class="stat-value"><?= $stats['total_faculty'] ?></div>
                    <div class="stat-label">Total Faculty</div>
                </div>
                <div class="card stat-card border-t-4 border-t-green-500">
                    <div class="stat-value">₹<?= number_format($stats['total_revenue']) ?></div>
                    <div class="stat-label">Fee Revenue</div>
                </div>
                <div class="card stat-card border-t-4 border-t-purple-500">
                    <div class="stat-value"><?= $stats['active_outpasses'] ?></div>
                    <div class="stat-label">Active Outpasses</div>
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="font-bold mb-4 text-center">Student Distribution by Department</h3>
                    <canvas id="deptChart"></canvas>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="font-bold mb-4 text-center">Fee Collection Trend</h3>
                    <canvas id="feeChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    const deptCtx = document.getElementById('deptChart');
    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: ['CSE', 'ECE', 'MECH', 'IT', 'CIVIL'],
            datasets: [{
                data: [120, 90, 60, 80, 40],
                backgroundColor: ['#1e3a8a', '#ea580c', '#10b981', '#8b5cf6', '#f59e0b']
            }]
        }
    });

    const feeCtx = document.getElementById('feeChart');
    new Chart(feeCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [500000, 750000, 300000, 900000, 1200000],
                backgroundColor: '#1e3a8a'
            }]
        }
    });
</script>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
