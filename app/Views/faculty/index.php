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
            <div class="welcome font-medium text-lg">Faculty Management</div>
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

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <h3 class="text-lg font-bold mb-4">Add New Faculty</h3>
                <div class="alert mb-4 text-sm text-cit-blue" style="background:#e0f2fe;border:1px solid #bae6fd;">
                    ℹ️ <strong>Note:</strong> Adding a faculty member automatically creates a user account. The default Username and Password will be set to their Employee ID.
                </div>
                <form action="<?= BASE_URL ?>faculty" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="block text-sm mb-1">Employee ID</label>
                        <input type="text" name="employee_id" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" name="email" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">First Name</label>
                        <input type="text" name="first_name" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Last Name</label>
                        <input type="text" name="last_name" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Department</label>
                        <input type="text" name="department" class="form-input w-full" placeholder="e.g., CSE" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Designation</label>
                        <input type="text" name="designation" class="form-input w-full" placeholder="e.g., Professor" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" class="btn btn-primary">Add Faculty</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Faculty Directory</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Emp ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faculty as $fac): ?>
                            <tr>
                                <td><?= htmlspecialchars($fac['employee_id']) ?></td>
                                <td><?= htmlspecialchars($fac['first_name'] . ' ' . $fac['last_name']) ?></td>
                                <td><?= htmlspecialchars($fac['department']) ?></td>
                                <td><?= htmlspecialchars($fac['designation']) ?></td>
                                <td><?= htmlspecialchars($fac['email']) ?></td>
                                <td>
                                    <form action="<?= BASE_URL ?>faculty" method="POST" onsubmit="return confirm('Are you sure you want to delete this faculty member?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $fac['user_id'] ?>">
                                        <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
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
