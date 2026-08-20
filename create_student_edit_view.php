<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/edit.php';
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(\$title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="/cit_ums/css/style.css?v=1787059998">
</head>
<body>
<div class="dashboard-layout">
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('open');"></div>
    <aside class="sidebar">
        <div class="sidebar-header">CIT UMS</div>
        <nav class="sidebar-nav">
            <a href="/cit_ums/dashboard" class="nav-link">Dashboard</a>
            <a href="/cit_ums/student" class="nav-link active">Student Management</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Edit Student</div>
            </div>
        </header>

        <div class="content-wrapper">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="block text-sm mb-1">Enrollment No</label>
                        <input type="text" name="enrollment_no" class="form-input w-full" value="<?= htmlspecialchars(\$student['enrollment_no']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">First Name</label>
                        <input type="text" name="first_name" class="form-input w-full" value="<?= htmlspecialchars(\$student['first_name']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Last Name</label>
                        <input type="text" name="last_name" class="form-input w-full" value="<?= htmlspecialchars(\$student['last_name']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Department</label>
                        <input type="text" name="department" class="form-input w-full" value="<?= htmlspecialchars(\$student['department']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Section</label>
                        <input type="text" name="section" class="form-input w-full" value="<?= htmlspecialchars(\$student['section'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Batch</label>
                        <input type="text" name="batch" class="form-input w-full" value="<?= htmlspecialchars(\$student['batch']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Phone</label>
                        <input type="text" name="phone" class="form-input w-full" value="<?= htmlspecialchars(\$student['phone']) ?>">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Admission Status</label>
                        <select name="admission_status" class="form-input w-full">
                            <option value="applied" <?= \$student['admission_status'] == 'applied' ? 'selected' : '' ?>>Applied</option>
                            <option value="admitted" <?= \$student['admission_status'] == 'admitted' ? 'selected' : '' ?>>Admitted</option>
                            <option value="enrolled" <?= \$student['admission_status'] == 'enrolled' ? 'selected' : '' ?>>Enrolled</option>
                            <option value="graduated" <?= \$student['admission_status'] == 'graduated' ? 'selected' : '' ?>>Graduated</option>
                            <option value="withdrawn" <?= \$student['admission_status'] == 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Current Semester</label>
                        <input type="number" name="current_semester" value="<?= htmlspecialchars(\$student['current_semester']) ?>" min="1" max="10" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">CGPA</label>
                        <input type="number" step="0.01" name="cgpa" value="<?= htmlspecialchars(\$student['cgpa']) ?>" class="form-input w-full">
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="/cit_ums/student" class="btn btn-secondary" style="margin-left:1rem; border: 1px solid #ccc; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: #333;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
HTML;
file_put_contents($f, $html);
echo "Edit view created.";
