<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Outpass Application</div>
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

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 2rem;">
                <!-- Apply Form -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
                    <h3 class="text-lg font-bold mb-4">Apply for Outpass</h3>
                    <form action="<?= BASE_URL ?>outpass" method="POST">
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium mb-1">Leave Date & Time</label>
                            <input type="datetime-local" name="leave_date" class="form-input w-full" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium mb-1">Return Date & Time</label>
                            <input type="datetime-local" name="return_date" class="form-input w-full" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium mb-1">Destination</label>
                            <input type="text" name="destination" class="form-input w-full" placeholder="e.g., Hometown, Hospital" required>
                        </div>
                        <div class="form-group mb-6">
                            <label class="block text-sm font-medium mb-1">Reason</label>
                            <textarea name="reason" class="form-input w-full" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Submit Request</button>
                    </form>
                </div>

                <!-- History -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">My Requests History</h3>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Leave Date</th>
                                    <th>Return Date</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($requests)): ?>
                                    <tr><td colspan="4" class="text-center text-gray-500">No requests found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($requests as $req): ?>
                                    <tr>
                                        <td><?= date('d M Y, h:i A', strtotime($req['leave_date'])) ?></td>
                                        <td><?= date('d M Y, h:i A', strtotime($req['return_date'])) ?></td>
                                        <td><?= htmlspecialchars($req['destination']) ?></td>
                                        <td>
                                            <?php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'warden_approved' => 'bg-blue-100 text-blue-800',
                                                    'rejected' => 'bg-red-100 text-red-800'
                                                ];
                                                $class = $statusColors[$req['status']] ?? 'bg-gray-100';
                                            ?>
                                            <span class="px-2 py-1 rounded text-xs font-medium <?= $class ?>" style="border-radius: 999px; padding: 2px 8px;">
                                                <?= ucfirst(str_replace('_', ' ', $req['status'])) ?>
                                            </span>
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
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
