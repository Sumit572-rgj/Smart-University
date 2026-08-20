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
            <div class="welcome font-medium text-lg">My Hostel Room</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($allocation)): ?>
                                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center max-w-xl mx-auto">
                    <svg style="width:80px;height:80px;margin:0 auto 1rem;color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h2 class="text-3xl font-bold mb-2"><?= htmlspecialchars($allocation['room_number']) ?></h2>
                    <p class="text-xl text-gray-500 mb-6"><?= htmlspecialchars($allocation['block_name']) ?></p>
                    <div class="text-sm text-gray-400">Allocated on: <?= date('d M Y', strtotime($allocation['allocated_date'])) ?></div>
                </div>

                <!-- NEW: Maintenance Generation Form for Student -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-xl mx-auto mt-6">
                    <h3 class="text-lg font-bold mb-4">Report Maintenance Issue</h3>
                    <form action="<?= BASE_URL ?>hostel" method="POST">
                        <input type="hidden" name="action" value="report_maintenance">
                        <input type="hidden" name="room_id" value="<?= htmlspecialchars($allocation['room_id'] ?? '') ?>">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Issue Description</label>
                            <textarea name="description" class="form-input w-full" rows="3" required placeholder="Describe the maintenance issue..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Generate Maintenance Record</button>
                    </form>
                </div>
                
                <!-- NEW: Maintenance History for Student -->
                <?php if(!empty($maintenance)): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-xl mx-auto mt-6">
                    <h3 class="text-lg font-bold mb-4">My Maintenance Requests</h3>
                    <div class="space-y-4">
                        <?php foreach($maintenance as $m): ?>
                        <div class="p-4 border rounded <?= $m['status'] === 'resolved' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' ?>">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold uppercase <?= $m['status'] === 'resolved' ? 'text-green-700' : 'text-yellow-700' ?>"><?= htmlspecialchars($m['status']) ?></span>
                                <span class="text-xs text-gray-500"><?= date('d M Y', strtotime($m['created_at'])) ?></span>
                            </div>
                            <p class="text-sm text-gray-700"><?= htmlspecialchars($m['issue_description']) ?></p>
                            <?php if($m['updated_at'] ?? null): ?>
                            <div class="text-xs text-gray-500 mt-2">Resolved on: <?= date('d M Y', strtotime($m['updated_at'] ?? null)) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center max-w-xl mx-auto">
                    <svg style="width:80px;height:80px;margin:0 auto 1rem;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="text-xl font-bold mb-2">No Room Allocated</h3>
                    <p class="text-gray-500">You have not been assigned a hostel room yet. Please contact the warden.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
