<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title><?= $title ?> - Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body id="chaos-body">

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>
    <main class="main-content">
        <header class="topbar shadow-sm">
            <div class="welcome font-medium text-lg">🌙 My Hostel Attendance</div>
        </header>

        <div class="p-6 max-w-4xl mx-auto">
            <div class="card p-6">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Last 30 Days Record</h3>
                
                <?php if(empty($attendance)): ?>
                    <p class="text-gray-500 text-center py-8">No attendance records found yet.</p>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table class="table w-full">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Logged At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($attendance as $a): ?>
                                    <tr>
                                        <td class="font-bold text-gray-700"><?= date('D, d M Y', strtotime($a['attendance_date'])) ?></td>
                                        <td>
                                            <?php if($a['status'] === 'Present'): ?>
                                                <span style="color: #047857; background: #d1fae5; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: bold; font-size: 0.85rem;">Present</span>
                                            <?php elseif($a['status'] === 'Absent'): ?>
                                                <span style="color: #b91c1c; background: #fee2e2; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: bold; font-size: 0.85rem;">Absent</span>
                                            <?php else: ?>
                                                <span style="color: #b45309; background: #fef3c7; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: bold; font-size: 0.85rem;">On Outpass</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-gray-500 text-sm"><?= date('h:i A', strtotime($a['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
