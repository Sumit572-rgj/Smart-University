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
            <div class="welcome font-medium text-lg">Exam Management</div>
            </div>
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

                        <!-- Publish Result Form -->
            <div class="bg-white p-6 rounded-lg shadow-sm border mb-6" style="border-color: #fbd38d; background: #fffaf0;">
                <h3 class="text-lg font-bold mb-4" style="color: #9c4221;">🎓 Publish Student Result</h3>
                <form action="<?= BASE_URL ?>exam/publish" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="block text-sm mb-1 font-medium">Student Reg No</label>
                        <input type="text" name="register_number" required class="form-input w-full" placeholder="e.g. STU12345">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Exam Name</label>
                        <input type="text" name="exam_name" required class="form-input w-full" placeholder="e.g. Semester 4 Finals">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Subject</label>
                        <input type="text" name="subject" required class="form-input w-full" placeholder="e.g. Data Structures">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Marks Obtained</label>
                        <input type="number" name="marks" required class="form-input w-full" placeholder="e.g. 85">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Max Marks</label>
                        <input type="number" name="max_marks" value="100" required class="form-input w-full">
                    </div>
                    <div style="display:flex; align-items:flex-end;">
                        <button type="submit" class="w-full text-white font-bold py-2 px-4 rounded" style="background: #f97316; transition: 0.2s;">Publish Result</button>
                    </div>
                </form>
                <p class="text-xs mt-3" style="color: #c05621;">Note: The system will automatically calculate the final Grade (A+, A, B, etc.) and GPA based on standard university grading metrics.</p>
            </div>
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <h3 class="text-lg font-bold mb-4">Schedule New Exam</h3>
                <form action="<?= BASE_URL ?>exam" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <input type="hidden" name="action" value="create">
                    <div>
                        <label class="block text-sm mb-1">Exam Title</label>
                        <input type="text" name="title" class="form-input w-full" placeholder="e.g. Midterm - Data Structures" required>
                    </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Course</label>
                            <select name="course_id" class="form-input w-full" required>
                                <?php foreach($my_courses as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code'] . ' - ' . $c['course_title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" class="form-input w-full" value="60" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" class="btn btn-primary">Schedule Exam</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Scheduled Exams</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Title</th>
                                <th>Start Time</th>
                                <th>Duration</th>
                                <th>Faculty</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exams as $exam): ?>
                            <tr>
                                <td><span class="font-medium"><?= htmlspecialchars($exam['new_course_code']) ?></span></td>
                                <td><?= htmlspecialchars($exam['title']) ?></td>
                                <td><?= date('d M Y, h:i A', strtotime($exam['start_time'])) ?></td>
                                <td><?= htmlspecialchars($exam['duration_minutes']) ?> mins</td>
                                <td><?= htmlspecialchars($exam['first_name'] . ' ' . $exam['last_name']) ?></td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'upcoming' => 'bg-yellow-100 text-yellow-800',
                                            'active' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $class = $statusColors[$exam['status']] ?? 'bg-gray-100';
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium <?= $class ?>" style="border-radius: 999px;">
                                        <?= ucfirst($exam['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="<?= BASE_URL ?>exam?manage=<?= $exam['id'] ?>" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Manage Questions</a>
                                        <form action="<?= BASE_URL ?>exam" method="POST" onsubmit="return confirm('Are you sure you want to delete this exam? All results for it will be lost.');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                                            <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                        </form>
                                    </div>
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
