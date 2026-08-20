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
            <div class="welcome font-medium text-lg">My Transport</div>
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

            <div class="grid" style="grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <?php if ($allocation): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">My Allocated Route</h3>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p class="text-sm text-gray-500">Route Name</p>
                            <p class="font-medium"><?= htmlspecialchars($allocation['route_name']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bus Number</p>
                            <p class="font-medium"><?= htmlspecialchars($allocation['bus_number']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Driver Name</p>
                            <p class="font-medium"><?= htmlspecialchars($allocation['driver_name']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Driver Phone</p>
                            <p class="font-medium"><?= htmlspecialchars($allocation['driver_phone']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Boarding Point</p>
                            <p class="font-medium"><?= htmlspecialchars($allocation['boarding_point']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="font-medium" style="text-transform: capitalize; color: #059669;"><?= htmlspecialchars($allocation['status']) ?></p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Book Transport Route</h3>
                    <form action="<?= BASE_URL ?>transport" method="POST" class="grid" style="grid-template-columns: 1fr; gap: 1rem; max-width: 500px;">
                        <input type="hidden" name="action" value="book">
                        <div>
                            <label class="block text-sm mb-1">Select Route</label>
                            <select name="route_id" class="form-input w-full" required>
                                <option value="">-- Select Route --</option>
                                <?php foreach ($routes as $route): ?>
                                    <option value="<?= $route['id'] ?>"><?= htmlspecialchars($route['route_name']) ?> (<?= htmlspecialchars($route['bus_number']) ?>) - Seats: <?= $route['capacity'] - $route['occupancy'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Boarding Point</label>
                            <input type="text" name="boarding_point" class="form-input w-full" required>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Book Route</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">All Transport Routes</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Route / Bus</th>
                                <th>Driver Info</th>
                                <th>Live GPS Tracking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($routes as $route): ?>
                            <tr>
                                <td>
                                    <strong class="text-cit-blue"><?= htmlspecialchars($route['route_name']) ?></strong><br>
                                    <span class="text-sm text-gray-500">Bus: <?= htmlspecialchars($route['bus_number']) ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($route['driver_name']) ?><br>
                                    <span class="text-sm text-gray-500">📞 <?= htmlspecialchars($route['driver_phone']) ?></span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div style="width:10px;height:10px;border-radius:50%;background:#10b981;animation: pulse 2s infinite;"></div>
                                        <span class="text-sm text-gray-600 font-mono">
                                            <?= htmlspecialchars($route['lat'] ?? '11.0168') ?>, <?= htmlspecialchars($route['lng'] ?? '76.9558') ?>
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Updated: <?= !empty($route['last_updated']) ? date('h:i A', strtotime($route['last_updated'])) : 'Just now' ?></div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($routes)): ?>
                            <tr>
                                <td colspan="3" class="text-center">No transport routes available.</td>
                            </tr>
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
