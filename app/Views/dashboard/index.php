<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <style>
        .stat-link { text-decoration: none; display: block; color: inherit; }
        
        
            33% { background-color: blue; color: green; }
            66% { background-color: hotpink; color: cyan; }
            100% { background-color: lime; color: purple; }
        }
    </style>

</head>
<body id="chaos-body">

<div class="dashboard-layout" id="chaos-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content" id="chaos-main">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Welcome, <?= htmlspecialchars($user['username']) ?>!</div>
            </div>
            <div class="user-menu flex items-center">
                                <!-- 2FA Toggle -->
                <?php
                    $db = (new Model())->db;
                    $stmt = $db->prepare("SELECT two_factor_enabled FROM users WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $is2fa = $stmt->fetchColumn();
                ?>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-right: 1rem; background: #f8fafc; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid #e2e8f0;">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #475569;">2FA</span>
                    <a href="<?= BASE_URL ?>auth/toggle2fa" style="background: <?= $is2fa ? '#10b981' : '#cbd5e1' ?>; width: 36px; height: 20px; border-radius: 999px; position: relative; display: inline-block; transition: background 0.3s;" title="Toggle 2FA">
                        <span style="position: absolute; top: 2px; <?= $is2fa ? 'right: 2px;' : 'left: 2px;' ?> width: 16px; height: 16px; background: white; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,0.2); transition: all 0.3s;"></span>
                    </a>
                </div>
                <span class="mr-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">Role: <?= ucfirst($user['role']) ?></span>
                
                

                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>
        </header>

        <div class="content-wrapper">
            <div class="grid-cards">
                <?php foreach($stats as $stat): ?>
                    <a href="<?= $stat['link'] ?>" class="stat-link">
                        <div class="stat-card">
                            <h3><?= htmlspecialchars($stat['label']) ?></h3>
                            <div class="value"><?= htmlspecialchars($stat['value']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold mb-4">Recent Notifications</h3>
                <ul class="list-disc pl-5 mb-6">
                    <?php foreach($recent_activities as $activity): ?>
                        <li class="mb-2 text-gray-700"><?= htmlspecialchars($activity) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if(isset($timetable)): ?>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-6">
                <h3 class="text-lg font-bold mb-4">My Class Timetable</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Course Code</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($timetable)): ?>
                                <tr><td colspan="6" class="text-center py-4 text-gray-500">No classes scheduled.</td></tr>
                            <?php endif; ?>
                            <?php foreach($timetable as $tt): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($tt['day_of_week']) ?></strong></td>
                                <td><?= date('h:i A', strtotime($tt['start_time'])) ?> - <?= date('h:i A', strtotime($tt['end_time'])) ?></td>
                                <td><?= htmlspecialchars($tt['course_code']) ?></td>
                                <td><?= htmlspecialchars($tt['department']) ?></td>
                                <td><?= htmlspecialchars($tt['semester']) ?></td>
                                <td><?= htmlspecialchars($tt['room']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-6" style="max-width: 500px;">
                <h3 class="text-lg font-bold mb-4">Change Password</h3>
                
                <?php if (isset($_GET['pwd_success'])): ?>
                    <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0; margin-bottom:1rem;">Password updated successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['pwd_error'])): ?>
                    <div class="alert alert-error mb-4">Failed to update password. Please check your inputs.</div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>auth/changePassword" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Current Password</label>
                        <input type="password" name="current_password" class="form-input w-full" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">New Password</label>
                        <input type="password" name="new_password" class="form-input w-full" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-input w-full" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>

            <div class="text-center mt-8">
            </div>
        </div>
    </main>
</div>



<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
