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
            <div class="welcome font-medium text-lg">Student Management</div>
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

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Add Student Form -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Add New Student</h3>
                    <div class="alert mb-4 text-sm text-cit-blue" style="background:#e0f2fe;border:1px solid #bae6fd;">
                        ℹ️ <strong>Note:</strong> Default Username and Password will be set to Enrollment No.
                    </div>
                    <form action="<?= BASE_URL ?>student" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="block text-sm mb-1">Enrollment No</label>
                            <input type="text" name="enrollment_no" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Email</label>
                            <input type="email" name="email" class="form-input w-full" required>
                        </div>
                                                <div>
                            <label class="block text-sm mb-1">Date of Birth</label>
                            <input type="date" name="dob" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">First Name</label>
                            <input type="text" name="first_name" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Last Name</label>
                            <input type="text" name="last_name" class="form-input w-full" required>
                        </div>
                                            <?php if($user['role'] === 'faculty'): ?>
                        <input type="hidden" name="department" value="<?= htmlspecialchars($facultyDept) ?>">
                    <?php else: ?>
                    <div>
                        <label class="block text-sm mb-1">Department</label>
                        <input type="text" name="department" class="form-input w-full" placeholder="e.g., CSE" required>
                    </div>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm mb-1">Section</label>
                        <input type="text" name="section" class="form-input w-full" placeholder="e.g. A, B, CSE-1">
                    </div>
                        <div>
                            <label class="block text-sm mb-1">Batch</label>
                            <input type="text" name="batch" class="form-input w-full" placeholder="e.g., 2026" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Phone</label>
                            <input type="text" name="phone" class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Admission Status</label>
                            <select name="admission_status" class="form-input w-full">
                                <option value="applied">Applied</option>
                                <option value="admitted" selected>Admitted</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="graduated">Graduated</option>
                                <option value="withdrawn">Withdrawn</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Current Semester</label>
                            <input type="number" name="current_semester" value="1" min="1" max="10" class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">CGPA</label>
                            <input type="number" step="0.01" name="cgpa" value="0.00" class="form-input w-full">
                        </div>
                        <div style="grid-column: span 2;">
                            <button type="submit" class="btn btn-primary w-full">Add Student</button>
                        </div>
                    </form>
                </div>

                <!-- Add Parent Form -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-green-700">Link Parent/Guardian Account</h3>
                    <div class="alert mb-4 text-sm text-green-800" style="background:#d1fae5;border:1px solid #a7f3d0;">
                        ℹ️ Provide parents access to monitor attendance, fees, and academic performance.
                    </div>
                    <form action="<?= BASE_URL ?>student" method="POST" class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                        <input type="hidden" name="action" value="create_parent">
                        
                        <div>
                            <label class="block text-sm mb-1 font-bold">Select Student</label>
                            <select name="student_id" class="form-input w-full" required>
                                <option value="" disabled selected>-- Choose Student --</option>
                                <?php foreach($students as $st): ?>
                                    <option value="<?= $st['id'] ?>"><?= htmlspecialchars($st['enrollment_no'] . ' - ' . $st['first_name'] . ' ' . $st['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="block text-sm mb-1">Parent Username</label>
                                <input type="text" name="parent_username" class="form-input w-full" placeholder="e.g. PAR101" required>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Password</label>
                                <input type="password" name="parent_password" class="form-input w-full" required>
                            </div>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div style="grid-column: span 2;">
                                <label class="block text-sm mb-1">Email Address</label>
                                <input type="email" name="parent_email" class="form-input w-full" required>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Relation</label>
                                <select name="relation" class="form-input w-full" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Guardian">Guardian</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Contact Number</label>
                                <input type="text" name="contact_number" class="form-input w-full" required>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary w-full" style="background:#059669; border-color:#059669;">Create & Link Parent</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Student Directory</h3>
                </div>
                <div class="table-container" style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Enrollment No</th>
                                <th>Name</th>
                                <th>Department</th><th>Section</th>
                                <th>Semester</th>
                                <th>CGPA</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($student['enrollment_no']) ?></strong></td>
                                <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                <td><?= htmlspecialchars($student['department']) ?></td><td><?= htmlspecialchars($student['section'] ?? '-') ?></td>
                                <td>Sem <?= htmlspecialchars($student['current_semester']) ?></td>
                                <td><span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold"><?= htmlspecialchars($student['cgpa']) ?></span></td>
                                <td>
                                    <?php 
                                        $colors = [
                                            'applied' => 'bg-yellow-100 text-yellow-800',
                                            'admitted' => 'bg-blue-100 text-blue-800',
                                            'enrolled' => 'bg-green-100 text-green-800',
                                            'graduated' => 'bg-purple-100 text-purple-800',
                                            'withdrawn' => 'bg-red-100 text-red-800'
                                        ];
                                        $c = $colors[$student['admission_status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs <?= $c ?>"><?= ucfirst(htmlspecialchars($student['admission_status'])) ?></span>
                                </td>
                                <td>
                                                                          <a href="<?= BASE_URL ?>student/edit/<?= $student['user_id'] ?>" class="text-blue-500 hover:underline text-xs mr-2">Edit</a>
                                      <form action="<?= BASE_URL ?>student" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');" style="display:inline-block;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $student['user_id'] ?>">
                                        <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                    </form>
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
