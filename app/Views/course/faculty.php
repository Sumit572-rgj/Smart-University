<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>My Courses - Faculty</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Faculty Academic Profile</div>
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

            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem; mb-8">
                <!-- Course List -->
                <div class="bg-white rounded shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold">My Assigned Courses</h3>
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
                                                <a href="<?= htmlspecialchars($c['syllabus_path']) ?>" target="_blank" class="text-blue-600 hover:underline">View PDF</a>
                                            <?php else: ?>
                                                <span class="text-gray-400">Not Uploaded</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if(empty($my_courses)): ?>
                                    <tr><td colspan="4" class="text-center">No assigned courses found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Upload Syllabus -->
                <div class="bg-white p-6 rounded shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Upload Syllabus (PDF)</h3>
                    <form action="<?= BASE_URL ?>course" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload_syllabus">
                        <div class="mb-4">
                            <select name="course_id" class="form-input w-full" required>
                                <option value="">-- Select Course --</option>
                                <?php foreach($my_courses as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <input type="file" name="syllabus" accept="application/pdf" class="form-input w-full" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
