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
            <div class="welcome font-medium text-lg">Task & To-Do Manager</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper max-w-2xl mx-auto mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <form action="<?= BASE_URL ?>task" method="POST" class="flex gap-2">
                        <input type="hidden" name="action" value="add">
                        <input type="text" name="title" class="form-input flex-grow" placeholder="What needs to be done?" required>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </form>
                </div>
                <div class="p-4">
                    <?php if(empty($tasks)): ?>
                        <p class="text-gray-500 text-center">No tasks on your list. Enjoy your day!</p>
                    <?php endif; ?>
                    <?php foreach($tasks as $t): ?>
                        <div class="flex justify-between items-center p-3 mb-2 border rounded <?= $t['status'] == 'completed' ? 'bg-gray-50 text-gray-400 line-through' : 'bg-white' ?>">
                            <span><?= htmlspecialchars($t['title']) ?></span>
                            <?php if($t['status'] == 'pending'): ?>
                                <form action="<?= BASE_URL ?>task" method="POST">
                                    <input type="hidden" name="action" value="complete">
                                    <input type="hidden" name="task_id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn bg-green-500 text-white text-xs px-2 py-1">Complete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
