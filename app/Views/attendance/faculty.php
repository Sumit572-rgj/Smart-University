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
            <div class="welcome font-medium text-lg">Faculty Attendance Portal</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form method="GET" action="<?= BASE_URL ?>attendance">
                    <div class="flex items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Course</label>
                            <select name="course" class="form-input">
                                <?php foreach ($courses as $c): ?>
                                    <option value="<?= htmlspecialchars($c['id']) ?>" <?= $c['id'] == $selected_course ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['course_code'] . ' - ' . $c['course_title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" value="<?= htmlspecialchars($selected_date) ?>" class="form-input" max="<?= date('Y-m-d') ?>">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Load Sheet</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Attendance Sheet -->
            <?php if ($selected_course && !empty($students)): ?>
                <form action="<?= BASE_URL ?>attendance" method="POST">
                    <input type="hidden" name="course_id" value="<?= htmlspecialchars($selected_course) ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($selected_date) ?>">
                    
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Enrollment No</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($students as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars($s['enrollment_no']) ?></td>
                                    <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                                    <td>
                                        <select name="attendance[<?= $s['id'] ?>]" class="form-input" style="padding: 0.25rem; height: auto;">
                                            <option value="present" <?= $s['status'] === 'present' ? 'selected' : '' ?>>Present</option>
                                            <option value="absent" <?= $s['status'] === 'absent' ? 'selected' : '' ?>>Absent</option>
                                            <option value="late" <?= $s['status'] === 'late' ? 'selected' : '' ?>>Late</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 bg-gray-50 text-right rounded-b-lg">
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
