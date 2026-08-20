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
            <div class="welcome font-medium text-lg">Edit Student: <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></div>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:1rem;margin-bottom:1rem;border-radius:4px;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;padding:1rem;margin-bottom:1rem;border-radius:4px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                
                <!-- Left Column: Student Details -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Student Profile</h3>
                    <form action="<?= BASE_URL ?>student/edit/<?= $student['user_id'] ?>" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <input type="hidden" name="action" value="update_profile">
                        <div>
                            <label class="block text-sm mb-1">Enrollment No</label>
                            <input type="text" name="enrollment_no" class="form-input w-full" value="<?= htmlspecialchars($student['enrollment_no']) ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">First Name</label>
                            <input type="text" name="first_name" class="form-input w-full" value="<?= htmlspecialchars($student['first_name']) ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Last Name</label>
                            <input type="text" name="last_name" class="form-input w-full" value="<?= htmlspecialchars($student['last_name']) ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Department</label>
                            <input type="text" name="department" class="form-input w-full" value="<?= htmlspecialchars($student['department']) ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Section</label>
                            <input type="text" name="section" class="form-input w-full" value="<?= htmlspecialchars($student['section'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Batch</label>
                            <input type="text" name="batch" class="form-input w-full" value="<?= htmlspecialchars($student['batch']) ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Phone</label>
                            <input type="text" name="phone" class="form-input w-full" value="<?= htmlspecialchars($student['phone']) ?>">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Admission Status</label>
                            <select name="admission_status" class="form-input w-full">
                                <option value="applied" <?= $student['admission_status'] == 'applied' ? 'selected' : '' ?>>Applied</option>
                                <option value="admitted" <?= $student['admission_status'] == 'admitted' ? 'selected' : '' ?>>Admitted</option>
                                <option value="enrolled" <?= $student['admission_status'] == 'enrolled' ? 'selected' : '' ?>>Enrolled</option>
                                <option value="graduated" <?= $student['admission_status'] == 'graduated' ? 'selected' : '' ?>>Graduated</option>
                                <option value="withdrawn" <?= $student['admission_status'] == 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Current Semester</label>
                            <input type="number" name="current_semester" value="<?= htmlspecialchars($student['current_semester']) ?>" min="1" max="10" class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">CGPA</label>
                            <input type="number" step="0.01" name="cgpa" value="<?= htmlspecialchars($student['cgpa']) ?>" class="form-input w-full">
                        </div>
                        <div style="grid-column: span 2; margin-top: 1rem;">
                            <button type="submit" class="btn btn-primary">Save Profile Changes</button>
                            <a href="<?= BASE_URL ?>student" class="btn btn-secondary" style="margin-left:1rem; border: 1px solid #ccc; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: #333;">Cancel</a>
                        </div>
                    </form>
                </div>
                
                <!-- Right Column: Document Management -->
                <div>
                    <!-- Upload Document -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <h3 class="text-lg font-bold mb-4">Upload Document</h3>
                        <form action="<?= BASE_URL ?>student/edit/<?= $student['user_id'] ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload_doc">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Document Type</label>
                                <select name="document_type" class="form-input w-full">
                                    <option value="ID Card">ID Card</option>
                                    <option value="Previous Transcript">Previous Transcript</option>
                                    <option value="Medical Certificate">Medical Certificate</option>
                                    <option value="Character Certificate">Character Certificate</option>
                                    <option value="Fee Receipt">Fee Receipt</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">File</label>
                                <input type="file" name="document" class="form-input w-full" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">Upload Document</button>
                        </form>
                    </div>

                    <!-- Manage Documents -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold mb-4">Student Documents</h3>
                        <div class="table-container">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($documents)): ?>
                                        <tr><td colspan="2" class="text-center text-gray-500 py-4">No documents found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($documents as $doc): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="text-blue-500 hover:underline block font-medium">
                                                        <?= htmlspecialchars($doc['document_name']) ?>
                                                    </a>
                                                    <span class="text-xs text-gray-500"><?= htmlspecialchars($doc['document_type']) ?></span>
                                                </td>
                                                <td class="text-right">
                                                    <form action="<?= BASE_URL ?>student/edit/<?= $student['user_id'] ?>" method="POST" style="display:inline;" onsubmit="let n = prompt('Enter new document name:', '<?= htmlspecialchars(addslashes($doc['document_name'])) ?>'); if(!n) return false; this.querySelector('input[name=new_name]').value = n; return true;">
                                                        <input type="hidden" name="action" value="update_doc">
                                                        <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                                        <input type="hidden" name="new_name" value="">
                                                        <button type="submit" class="text-blue-500 hover:underline text-xs bg-blue-50 px-2 py-1 rounded mr-1">Rename</button>
                                                    </form>
                                                    <form action="<?= BASE_URL ?>student/edit/<?= $student['user_id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this document forever?');">
                                                        <input type="hidden" name="action" value="delete_doc">
                                                        <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                                        <button type="submit" class="text-red-500 hover:underline text-xs bg-red-50 px-2 py-1 rounded">Delete</button>
                                                    </form>
                                                    <form action="<?= BASE_URL ?>student/edit/<?= $student['user_id'] ?>" method="POST" enctype="multipart/form-data" class="mt-2" style="display:flex; gap:0.5rem; align-items:center;">
                                                        <input type="hidden" name="action" value="replace_doc">
                                                        <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                                        <input type="file" name="document" class="text-xs" style="width: 150px;" required>
                                                        <button type="submit" class="text-green-600 hover:underline text-xs bg-green-50 px-2 py-1 rounded">Replace</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
</body>
</html>
