<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <style>
        .event-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border-left: 4px solid #0284c7;
        }
        .event-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .event-meta {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
        }
        .event-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .event-desc {
            color: #374151;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Notice Board & Events</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <h2 class="text-2xl font-bold mb-6">Upcoming Events & Notices</h2>
            
            <?php if (empty($events)): ?>
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center text-gray-500">
                    No upcoming events or notices at the moment.
                </div>
            <?php else: ?>
                <div class="events-list">
                    <?php foreach ($events as $event): ?>
                        <div class="event-card" style="border-left-color: <?= $event['type'] === 'notice' ? '#ef4444' : '#0284c7' ?>;">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="event-title mb-0"><?= htmlspecialchars($event['title']) ?></h3>
                                <span class="px-2 py-1 text-xs font-bold rounded uppercase text-white" style="background: <?= $event['type'] === 'notice' ? '#ef4444' : '#0284c7' ?>;">
                                    <?= htmlspecialchars($event['type']) ?>
                                </span>
                            </div>
                            <div class="event-meta">
                                <span>📅 <?= date('l, F j, Y \a\t h:i A', strtotime($event['event_date'])) ?></span>
                                <?php if($event['type'] !== 'notice' || !empty($event['venue'])): ?>
                                    <span>📍 <?= htmlspecialchars($event['venue']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="event-desc">
                                <?= nl2br(htmlspecialchars($event['description'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
