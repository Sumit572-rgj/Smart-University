<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Course Management - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Course Management - Admin</div>
            </div>
            <div class="user-menu" style="display:flex; align-items:center;" style="display: flex; align-items: center; gap: 1rem;">
                
                

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

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Create Course -->
                <div class="bg-white p-6 rounded shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Create Course</h3>
                    <form action="<?= BASE_URL ?>course" method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Course Code</label>
                            <input type="text" name="code" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Course Title</label>
                            <input type="text" name="title" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Credits</label>
                            <input type="number" name="credits" class="form-input w-full" value="3" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>

                <!-- Assign Faculty & Enroll Student -->
                <div class="bg-white p-6 rounded shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Assign Faculty</h3>
                    <form action="<?= BASE_URL ?>course" method="POST" class="mb-6">
                        <input type="hidden" name="action" value="assign_faculty">
                        <div class="flex gap-2">
                            <select name="course_id" class="form-input" required>
                                <?php foreach($courses as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="faculty_id" class="form-input" required>
                                <?php foreach($faculties as $f): ?>
                                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['first_name'] . ' ' . $f['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </div>
                    </form>

                    <h3 class="text-lg font-bold mb-4 border-t pt-4">Enroll Student</h3>
                    <form action="<?= BASE_URL ?>course" method="POST">
                        <input type="hidden" name="action" value="enroll_student">
                        <div class="flex gap-2">
                            <select name="course_id" class="form-input" required>
                                <?php foreach($courses as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="enrollment_no" placeholder="Enrollment No" class="form-input" required>
                            <button type="submit" class="btn btn-primary">Enroll</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Upload Syllabus -->
                <div class="bg-white p-6 rounded shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Upload Syllabus (PDF)</h3>
                    <form action="<?= BASE_URL ?>course" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload_syllabus">
                        <div class="mb-4">
                            <select name="course_id" class="form-input w-full" required>
                                <?php foreach($courses as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code'] . ' - ' . $c['course_title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <input type="file" name="syllabus" accept="application/pdf" class="form-input w-full" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>

                <!-- Add Timetable -->
                <div class="bg-white p-6 rounded shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Add Timetable Slot</h3>
                    <form action="<?= BASE_URL ?>course" method="POST">
                        <input type="hidden" name="action" value="add_timetable">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <select name="course_id" class="form-input w-full" required style="grid-column: span 2;">
                                <?php foreach($courses as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="day" class="form-input w-full" required>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                            <input type="text" name="room" placeholder="Room No" class="form-input w-full" required>
                            <input type="time" name="start" class="form-input w-full" required>
                            <input type="time" name="end" class="form-input w-full" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Slot</button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
