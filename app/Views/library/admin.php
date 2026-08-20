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
            <div class="welcome font-medium text-lg">Library Management</div>
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

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Add Book Form -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Add New Book</h3>
                    <form action="<?= BASE_URL ?>library" method="POST">
                        <input type="hidden" name="action" value="add_book">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Book Title</label>
                            <input type="text" name="title" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Author</label>
                            <input type="text" name="author" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">ISBN</label>
                            <input type="text" name="isbn" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Resource Type</label>
                            <select name="resource_type" class="form-input w-full" required>
                                <option value="physical">Physical Book</option>
                                <option value="journal">Journal</option>
                                <option value="digital">Digital Resource / PDF</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Subject</label>
                            <input type="text" name="subject" class="form-input w-full" placeholder="e.g. Computer Science">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Digital Link (If E-Resource)</label>
                            <input type="url" name="digital_link" class="form-input w-full" placeholder="https://...">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Copies (Set 1 for Digital)</label>
                            <input type="number" name="copies" class="form-input w-full" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Add Resource</button>
                    </form>
                </div>

                <!-- Book Inventory -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Catalogue</h3>
                        <form action="<?= BASE_URL ?>library" method="GET" class="flex gap-2">
                            <input type="text" name="q" class="form-input" placeholder="🔍 AI Smart Search (Title, Author, Subject)..." value="<?= htmlspecialchars($search_query) ?>" style="width: 300px;">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                        <table class="table">
                            <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                <tr>
                                    <th>Resource</th>
                                    <th>Type</th>
                                    <th>Subject / ISBN</th>
                                    <th>Available</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($books as $book): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($book['title']) ?></strong><br>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($book['author']) ?></span>
                                    </td>
                                    <td><span class="text-xs uppercase bg-gray-100 px-2 py-1 rounded border"><?= htmlspecialchars($book['resource_type']) ?></span></td>
                                    <td>
                                        <div class="text-sm"><?= htmlspecialchars($book['subject']) ?></div>
                                        <div class="text-xs text-gray-400"><?= htmlspecialchars($book['isbn']) ?></div>
                                    </td>
                                    <td>
                                        <?php if($book['resource_type'] === 'digital'): ?>
                                            <a href="<?= htmlspecialchars($book['digital_link']) ?>" target="_blank" class="text-blue-500 hover:underline text-sm">Open Link</a>
                                        <?php else: ?>
                                            <?= $book['available_copies'] ?> / <?= $book['total_copies'] ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Issued Books Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Issued Books & Fines</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Student</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Fines</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($issued as $iss): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($iss['title']) ?></strong></td>
                                <td>
                                    <?= htmlspecialchars($iss['first_name'] . ' ' . $iss['last_name']) ?><br>
                                    <span class="text-xs text-gray-500"><?= htmlspecialchars($iss['enrollment_no']) ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $dueDate = date('d M Y', strtotime($iss['due_date'])); 
                                        if($iss['status'] === 'overdue'):
                                    ?>
                                        <span class="text-red-600 font-bold"><?= $dueDate ?></span>
                                    <?php else: ?>
                                        <?= $dueDate ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($iss['status'] === 'returned'): ?>
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800" style="border-radius: 999px;">Returned</span>
                                    <?php elseif($iss['status'] === 'overdue'): ?>
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800" style="border-radius: 999px;">Overdue</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800" style="border-radius: 999px;">Issued</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($iss['fine_amount'] > 0): ?>
                                        <span class="text-red-600 font-bold">₹<?= number_format($iss['fine_amount'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($iss['status'] !== 'returned'): ?>
                                    <form action="<?= BASE_URL ?>library" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="return_book">
                                        <input type="hidden" name="issue_id" value="<?= $iss['id'] ?>">
                                        <input type="hidden" name="book_id" value="<?= $iss['book_id'] ?>">
                                        <button type="submit" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Mark Returned</button>
                                    </form>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">Resolved</span>
                                    <?php endif; ?>
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
