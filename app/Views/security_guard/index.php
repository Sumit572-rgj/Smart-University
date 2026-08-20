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
            <div class="welcome font-medium text-lg">Security Guard Management</div>
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
                <h3 class="text-lg font-bold mb-4">Add New Security Guard</h3>
                <form action="<?= BASE_URL ?>securityguard" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <input type="hidden" name="action" value="create">
                    
                    <div>
                        <label class="block text-sm mb-1">Username</label>
                        <input type="text" name="username" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" name="email" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Password</label>
                        <input type="password" name="password" class="form-input w-full" required>
                    </div>
                    
                    <div style="grid-column: span 2;">
                        <button type="submit" class="btn btn-primary">Add Security Guard</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Security Guard List</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guards as $guard): ?>
                            <tr>
                                <td><?= htmlspecialchars($guard['id']) ?></td>
                                <td><span class="font-medium"><?= htmlspecialchars($guard['username']) ?></span></td>
                                <td><?= htmlspecialchars($guard['email']) ?></td>
                                <td><?= date('d M Y', strtotime($guard['created_at'])) ?></td>
                                <td>
                                    <form action="<?= BASE_URL ?>securityguard" method="POST" onsubmit="return confirm('Delete this security guard?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="guard_id" value="<?= $guard['id'] ?>">
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
