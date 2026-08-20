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

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" style="height: fit-content;">
                    <h3 class="text-lg font-bold mb-4">Add New Entry</h3>
                    <form action="<?= BASE_URL ?>departmentmeetings" method="POST">
                        <input type="hidden" name="add_data" value="1">
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Meeting Title</label><input type="text"  name="meeting_title" class="form-input w-full" required></div>
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Meeting Date</label><input type="date"  name="meeting_date" class="form-input w-full" required></div>
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Agenda</label><textarea name="agenda" class="form-input w-full" rows="3" required></textarea></div>
                        
                        <button type="submit" class="btn btn-primary w-full mt-4">Submit</button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200"><h3 class="text-lg font-bold">Recent Records</h3></div>
                    <div class="table-container" style="overflow-x: auto;">
                        <table class="table">
                            <thead><tr><th>User</th><th>Meeting Title</th><th>Meeting Date</th><th>Agenda</th><th>Date</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php if(empty($records)): ?>
                                    <tr><td colspan="10" class="text-center text-gray-500 py-4">No records found.</td></tr>
                                <?php endif; ?>
                                <?php foreach($records as $r): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($r['username']) ?></strong></td>
                                    <td><?= htmlspecialchars($r['meeting_title']) ?></td><td><?= htmlspecialchars($r['meeting_date']) ?></td><td><?= htmlspecialchars($r['agenda']) ?></td>
                                    <td class="text-xs text-gray-500"><?= htmlspecialchars(date('M d, Y', strtotime($r['created_at']))) ?></td>
                                    <td><a href="<?= BASE_URL ?>departmentmeetings?delete=<?= $r['id'] ?>" class="text-red-500 hover:underline">Delete</a></td>
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
