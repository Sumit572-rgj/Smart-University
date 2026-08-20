<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg"><?= htmlspecialchars($title) ?> Portal</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="grid" style="<?= $user['role'] === 'student' ? 'grid-template-columns: 1fr 2fr;' : 'grid-template-columns: 1fr;' ?> gap: 1.5rem;">
                                <?php if ($user['role'] === 'student'): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" style="height: fit-content;">
                    <h3 class="text-lg font-bold mb-4">Report an Issue</h3>
                    <form action="<?= BASE_URL ?>roommaintenance" method="POST">
                        <input type="hidden" name="add_data" value="1">
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Room No</label><input type="text"  name="room_no" class="form-input w-full" required></div>
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Issue Description</label><textarea name="issue" class="form-input w-full" rows="3" required></textarea></div>
                        <button type="submit" class="btn btn-primary w-full mt-4">Report Issue</button>
                    </form>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200"><h3 class="text-lg font-bold">Recent Records</h3></div>
                    <div class="table-container" style="overflow-x: auto;">
                        <table class="table">
                            <thead><tr><th>User</th><th>Room No</th><th>Issue</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php if(empty($records)): ?>
                                    <tr><td colspan="10" class="text-center text-gray-500 py-4">No records found.</td></tr>
                                <?php endif; ?>
                                <?php foreach($records as $r): ?>
                                                                <tr>
                                    <td><strong><?= htmlspecialchars($r['username']) ?></strong></td>
                                    <td><?= htmlspecialchars($r['room_no']) ?></td>
                                    <td><?= htmlspecialchars($r['issue']) ?></td>
                                    <td>
                                        <?php if ($user['role'] === 'warden' || $user['role'] === 'admin'): ?>
                                            <form action="<?= BASE_URL ?>roommaintenance" method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                                                <input type="hidden" name="update_status" value="1">
                                                <input type="hidden" name="issue_id" value="<?= $r['id'] ?>">
                                                <select name="status" class="form-input" style="padding:0.25rem 0.5rem; font-size:0.875rem;" onchange="this.form.submit()">
                                                    <option value="Pending" <?= $r['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="In Progress" <?= $r['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="Resolved" <?= $r['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                                </select>
                                            </form>
                                        <?php else: ?>
                                            <?php 
                                                $color = '#f59e0b'; // Pending
                                                if ($r['status'] === 'In Progress') $color = '#3b82f6';
                                                if ($r['status'] === 'Resolved') $color = '#10b981';
                                            ?>
                                            <span style="background: <?= $color ?>; color: white; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; white-space: nowrap;">
                                                <?= htmlspecialchars($r['status']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs text-gray-500"><?= htmlspecialchars(date('M d, Y h:i A', strtotime($r['created_at']))) ?></td>
                                    <td>
                                        <?php if ($user['role'] === 'student' || $user['role'] === 'admin'): ?>
                                            <a href="<?= BASE_URL ?>roommaintenance?delete=<?= $r['id'] ?>" class="text-red-500 hover:underline text-sm font-bold" onclick="return confirm('Delete this record?');">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
