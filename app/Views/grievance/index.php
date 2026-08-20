<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
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
            <div class="welcome font-medium text-lg">Grievance Redressal System</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper">
            <?php if (!empty($success)): ?><div class="alert" style="background:#d1fae5;color:#065f46;"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
                    <h3 class="text-lg font-bold mb-4">Lodge a Grievance</h3>
                    <form action="<?= BASE_URL ?>grievance" method="POST">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Category</label>
                            <select name="category" class="form-input w-full" required>
                                <option value="academic">Academic</option>
                                <option value="hostel">Hostel</option>
                                <option value="facilities">Facilities</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Subject</label>
                            <input type="text" name="subject" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Description</label>
                            <textarea name="description" class="form-input w-full" rows="4" required></textarea>
                        </div>
                        <div class="mb-6 flex items-center">
                            <input type="checkbox" name="anonymous" id="anon" class="mr-2">
                            <label for="anon" class="text-sm">Submit Anonymously</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Submit</button>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">My Grievances</h3>
                    <?php if(empty($my_grievances)): ?>
                        <p class="text-gray-500">No grievances lodged.</p>
                    <?php else: ?>
                        <?php foreach($my_grievances as $g): ?>
                            <div class="border-b pb-3 mb-3">
                                <h4 class="font-bold text-sm text-cit-blue"><?= htmlspecialchars($g['subject']) ?></h4>
                                <p class="text-xs text-gray-500 mb-2">Category: <?= ucfirst($g['category']) ?></p>
                                <div class="flex justify-between items-center text-xs">
                                    <span><?= date('d M Y', strtotime($g['created_at'])) ?></span>
                                    <span class="font-bold <?= $g['status'] == 'resolved' ? 'text-green-600' : 'text-yellow-600' ?>"><?= ucfirst(str_replace('_', ' ', $g['status'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
