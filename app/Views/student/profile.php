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
            <div class="welcome font-medium text-lg">My Profile</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start;">
                <!-- Profile Details -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                                        <div class="flex items-center mb-6 border-b pb-4" style="flex-wrap: wrap; gap: 1.5rem;">
                        <div style="position: relative;">
                            <?php if (!empty($student['profile_pic'])): ?>
                                <img src="<?= htmlspecialchars($student['profile_pic']) ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #f1f5f9;">
                            <?php else: ?>
                                <div style="width: 100px; height: 100px; background: var(--cit-blue); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; border: 4px solid #f1f5f9;">
                                    <?= substr($student['first_name'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Hidden File Input & Label -->
                            <form action="<?= BASE_URL ?>student/profile" method="POST" enctype="multipart/form-data" id="profile-pic-form" style="position: absolute; bottom: -5px; right: -5px; margin: 0;">
                                <label for="profile_pic" style="background: white; border: 1px solid #cbd5e1; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 0;">
                                    <svg style="width: 16px; height: 16px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                                <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display: none;" onchange="document.getElementById('profile-pic-form').submit();">
                            </form>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h2>
                            <p class="text-gray-500"><?= htmlspecialchars($student['enrollment_no']) ?> | <?= htmlspecialchars($student['department']) ?></p>
                        </div>
                    </div>

                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Batch</p>
                            <p class="text-lg"><?= htmlspecialchars($student['batch']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Email</p>
                            <p class="text-lg"><?= htmlspecialchars($student['email']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Phone</p>
                            <p class="text-lg"><?= htmlspecialchars($student['phone']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Current Semester</p>
                            <p class="text-lg font-bold">Sem <?= htmlspecialchars($student['current_semester']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">CGPA</p>
                            <p class="text-lg font-bold text-cit-blue"><?= htmlspecialchars($student['cgpa']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Admission Status</p>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded font-bold uppercase text-xs"><?= htmlspecialchars($student['admission_status']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Document Uploads -->
                <div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <h3 class="text-lg font-bold mb-4">Upload Document</h3>
                        <form action="<?= BASE_URL ?>student/profile" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Document Type</label>
                                <select name="document_type" class="form-input w-full">
                                    <option value="id_proof">ID Proof</option>
                                    <option value="certificate">Certificate</option>
                                    <option value="transcript">Transcript</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Select File</label>
                                <input type="file" name="document" class="form-input w-full" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">Upload File</button>
                        </form>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold">My Documents</h3>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Type</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($documents)): ?>
                                        <tr><td colspan="3" class="text-center text-gray-500 py-4">No documents uploaded.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach($documents as $doc): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($doc['document_name']) ?></td>
                                        <td><span class="text-xs uppercase bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($doc['document_type']) ?></span></td>
                                        <td><a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="text-blue-500 hover:underline">Download</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
