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
            <div class="welcome font-medium text-lg">Manage Notice Board & Events</div>
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
                <h3 class="text-lg font-bold mb-4">Post New Event / Notice</h3>
                <form action="<?= BASE_URL ?>event/admin" method="POST" class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                    <input type="hidden" name="action" value="add">
                    <div>
                        <label class="block text-sm mb-1">Title</label>
                        <input type="text" name="title" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Description</label>
                        <textarea name="description" class="form-input w-full" rows="4" required></textarea>
                    </div>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="block text-sm mb-1">Type</label>
                            <select name="type" class="form-input w-full" required>
                                <option value="event">Event / Program</option>
                                <option value="notice">Notice / Announcement</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Visibility (Role)</label>
                            <select name="visibility" class="form-input w-full" required>
                                <option value="all">Public (All Users)</option>
                                <option value="students">Students Only</option>
                                <option value="faculty">Faculty Only</option>
                                <option value="warden">Wardens Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="block text-sm mb-1">Event Date & Time</label>
                            <input type="datetime-local" name="event_date" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Venue (or Virtual Link)</label>
                            <input type="text" name="venue" class="form-input w-full" required>
                        </div>
                    </div>
                    <div class="mt-2 mb-2 flex items-center gap-2">
                        <input type="checkbox" name="notify" id="notify" value="1" style="width:16px;height:16px;">
                        <label for="notify" class="text-sm font-bold text-red-600">Send Push Notification (Email/SMS) to target audience instantly</label>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Post Announcement</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Manage Posted Announcements</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Type & Target</th>
                                <th>Title / Venue</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($events)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-4">No events found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($events as $event): ?>
                                <tr>
                                    <td style="white-space: nowrap;"><?= date('M d, Y h:i A', strtotime($event['event_date'])) ?></td>
                                    <td>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded uppercase"><?= htmlspecialchars($event['type']) ?></span><br>
                                        <span class="text-xs text-gray-500 block mt-1">To: <?= ucfirst(htmlspecialchars($event['visibility'])) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                                        <span class="text-xs text-gray-500">📍 <?= htmlspecialchars($event['venue']) ?></span>
                                    </td>
                                    <td style="max-width: 300px; overflow:hidden; text-overflow: ellipsis;"><?= nl2br(htmlspecialchars($event['description'])) ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>event/admin" method="POST" onsubmit="return confirm('Delete this post?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                            <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
