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
            <div class="welcome font-medium text-lg">Transport Management - Admin</div>
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

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Create New Route</h3>
                    <form action="<?= BASE_URL ?>transport" method="POST" class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                        <input type="hidden" name="action" value="create_route">
                                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label class="block text-sm mb-1">Route Name</label>
                                        <input type="text" name="route_name" class="form-input w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Bus Number</label>
                                        <input type="text" name="bus_number" class="form-input w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Driver Name</label>
                                        <input type="text" name="driver_name" class="form-input w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Driver Phone</label>
                                        <input type="text" name="driver_phone" class="form-input w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Driver License No</label>
                                        <input type="text" name="license_number" class="form-input w-full">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Last Maintenance Date</label>
                                        <input type="date" name="maintenance_date" class="form-input w-full">
                                    </div>
                                    <div style="grid-column: span 2;">
                                        <label class="block text-sm mb-1">Capacity</label>
                                        <input type="number" name="capacity" class="form-input w-full" required>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Create Route</button>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-lg font-bold mb-4">Allocate Student to Route</h3>
                            <form action="<?= BASE_URL ?>transport" method="POST" class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                                <input type="hidden" name="action" value="allocate_student">
                                <div>
                                    <label class="block text-sm mb-1">Student Enrollment No</label>
                                    <input type="text" name="enrollment_no" class="form-input w-full" required>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Select Route</label>
                                    <select name="route_id" class="form-input w-full" required>
                                        <option value="">-- Select Route --</option>
                                        <?php foreach ($routes as $route): ?>
                                            <option value="<?= $route['id'] ?>"><?= htmlspecialchars($route['route_name']) ?> (<?= htmlspecialchars($route['bus_number']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Boarding Point</label>
                                    <input type="text" name="boarding_point" class="form-input w-full" required>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Allocate Student</button>
                                </div>
                            </form>
                            
                            <hr class="my-6">
                            
                            <h3 class="text-lg font-bold mb-4 text-red-600">Send Bus Alert</h3>
                            <form action="<?= BASE_URL ?>transport" method="POST" class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                                <input type="hidden" name="action" value="send_alert">
                                <div>
                                    <label class="block text-sm mb-1">Select Route</label>
                                    <select name="route_id" class="form-input w-full" required>
                                        <option value="">-- Select Route --</option>
                                        <?php foreach ($routes as $route): ?>
                                            <option value="<?= $route['id'] ?>"><?= htmlspecialchars($route['route_name']) ?> (<?= htmlspecialchars($route['bus_number']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Alert Message (Delays / Changes)</label>
                                    <textarea name="alert_message" class="form-input w-full" rows="2" required></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" style="background:#ef4444; border-color:#ef4444;">Dispatch Alert to Students</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Transport Routes Directory & Live GPS Tracker</h3>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Route / Bus</th>
                                        <th>Driver Info</th>
                                        <th>License / Maintenance</th>
                                        <th>Occupancy</th>
                                        <th>Live GPS Tracking</th>
                                        <th>Actions</th>
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
                                            <span class="text-xs font-bold text-gray-700 block">ID: <?= htmlspecialchars($route['driver_license'] ?? 'N/A') ?></span>
                                            <span class="text-xs text-gray-500">Maintained: <?= !empty($route['next_maintenance']) ? date('d M Y', strtotime($route['next_maintenance'])) : 'Unknown' ?></span>
                                        </td>
                                        <td>
                                            <span class="font-bold <?= $route['occupancy'] >= $route['capacity'] ? 'text-red-500' : 'text-green-600' ?>">
                                                <?= htmlspecialchars($route['occupancy']) ?> / <?= htmlspecialchars($route['capacity']) ?>
                                            </span>
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
                                        <td>
                                            <div class="flex gap-2">
                                                <button onclick='editRoute(<?= json_encode($route) ?>)' class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</button>
                                                <form action="<?= BASE_URL ?>transport" method="POST" onsubmit="return confirm('Are you sure you want to delete this route?');" style="margin:0;">
                                                    <input type="hidden" name="action" value="delete_route">
                                                    <input type="hidden" name="route_id" value="<?= $route['id'] ?>">
                                                    <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($routes)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No transport routes available.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
        </div>
    </main>
</div>
<script>
function editRoute(route) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    const form = document.querySelector('form[action="<?= BASE_URL ?>transport"]:first-of-type');
    form.querySelector('[name="action"]').value = 'update_route';
    if (!form.querySelector('[name="route_id"]')) {
        const routeIdInput = document.createElement('input');
        routeIdInput.type = 'hidden';
        routeIdInput.name = 'route_id';
        form.appendChild(routeIdInput);
    }
    form.querySelector('[name="route_id"]').value = route.id;
    form.querySelector('[name="route_name"]').value = route.route_name;
    form.querySelector('[name="bus_number"]').value = route.bus_number;
    form.querySelector('[name="driver_name"]').value = route.driver_name;
    form.querySelector('[name="driver_phone"]').value = route.driver_phone;
    form.querySelector('[name="capacity"]').value = route.capacity;
    form.querySelector('[name="license_number"]').value = route.driver_license || '';
    form.querySelector('[name="maintenance_date"]').value = route.next_maintenance ? route.next_maintenance.split(' ')[0] : '';
    form.querySelector('button[type="submit"]').textContent = 'Update Route';
    form.parentElement.querySelector('h3').textContent = 'Edit Route: ' + route.route_name;
}
</script>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
