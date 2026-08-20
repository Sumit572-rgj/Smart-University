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
            <div class="welcome font-medium text-lg">Hostel Management</div>
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

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                
                <!-- Add Room -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Add New Room</h3>
                    <form action="<?= BASE_URL ?>hostel" method="POST">
                        <input type="hidden" name="action" value="add_room">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Room Number</label>
                            <input type="text" name="room_number" class="form-input w-full" placeholder="e.g. A-101" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Block Name</label>
                            <input type="text" name="block_name" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Capacity</label>
                            <input type="number" name="capacity" class="form-input w-full" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Create Room</button>
                    </form>
                </div>

                <!-- Allocate Student -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Allocate Student</h3>
                    <form action="<?= BASE_URL ?>hostel" method="POST">
                        <input type="hidden" name="action" value="allocate">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Student Enrollment No.</label>
                            <input type="text" name="enrollment_no" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Select Room</label>
                            <select name="room_id" class="form-input w-full" required>
                                <option value="">-- Select Room --</option>
                                <?php foreach($rooms as $room): ?>
                                    <option value="<?= $room['id'] ?>">
                                        <?= htmlspecialchars($room['room_number']) ?> (<?= $room['occupancy'] ?>/<?= $room['capacity'] ?> full)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Allocate Student</button>
                    </form>
                </div>
            </div>

            <!-- Visual Capacity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Room Occupancy Tracker</h3>
                </div>
                <div class="p-4 grid" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                    <?php foreach($rooms as $room): 
                        $percent = ($room['occupancy'] / $room['capacity']) * 100;
                        $color = $percent >= 100 ? '#ef4444' : ($percent >= 75 ? '#eab308' : '#22c55e');
                    ?>
                    <div style="border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; text-align: center;">
                        <div class="font-bold text-lg"><?= htmlspecialchars($room['room_number']) ?></div>
                        <div class="text-xs text-gray-500 mb-2"><?= htmlspecialchars($room['block_name']) ?></div>
                        
                        <!-- Progress bar -->
                        <div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem;">
                            <div style="background: <?= $color ?>; height: 100%; width: <?= $percent ?>%;"></div>
                        </div>
                        <div class="text-xs font-medium" style="color: <?= $color ?>;"><?= $room['occupancy'] ?> / <?= $room['capacity'] ?> Beds</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Allocations Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Current Allocations</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Block</th>
                                <th>Student Name</th>
                                <th>Enrollment No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allocations as $alloc): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($alloc['room_number']) ?></strong></td>
                                <td><?= htmlspecialchars($alloc['block_name']) ?></td>
                                <td><?= htmlspecialchars($alloc['first_name'] . ' ' . $alloc['last_name']) ?></td>
                                <td><?= htmlspecialchars($alloc['enrollment_no']) ?></td>
                                <td>
                                    <form action="<?= BASE_URL ?>hostel" method="POST" onsubmit="return confirm('Vacate this student?');" style="display:inline;">
                                        <input type="hidden" name="action" value="vacate">
                                        <input type="hidden" name="allocation_id" value="<?= $alloc['allocation_id'] ?>">
                                        <input type="hidden" name="room_id" value="<?= $alloc['room_id'] ?>">
                                        <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Vacate</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- AI Forecast -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="text-2xl mr-2">🤖</span>
                        <h3 class="text-lg font-bold">AI Resource Forecast</h3>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Based on current occupancy data, the predicted daily consumption is:</p>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm font-bold mb-1">
                                <span>⚡ Electricity</span>
                                <span class="text-cit-blue"><?= number_format($ai_prediction['electricity'], 1) ?> kWh</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-bold mb-1">
                                <span>💧 Water</span>
                                <span class="text-blue-400"><?= number_format($ai_prediction['water']) ?> Liters</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-400 h-2.5 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-bold mb-1">
                                <span>🍲 Food Supplies</span>
                                <span class="text-orange-500"><?= number_format($ai_prediction['food'], 1) ?> kg</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-orange-500 h-2.5 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Tracking -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold">Maintenance Requests</h3>
                    </div>
                    <div class="p-4" style="max-height: 300px; overflow-y: auto;">
                        <?php if(empty($maintenance)): ?>
                            <p class="text-sm text-gray-500 text-center">No pending maintenance requests.</p>
                        <?php endif; ?>
                        <?php foreach($maintenance as $m): ?>
                                                        <div class="mb-3 p-3 border rounded <?= $m['status'] === 'resolved' ? 'bg-green-50' : 'bg-gray-50' ?>">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-sm">Room <?= htmlspecialchars($m['room_number']) ?></span>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <span class="text-xs uppercase px-2 py-1 rounded <?= $m['status'] === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= htmlspecialchars($m['status']) ?>
                                        </span>
                                        <?php if($m['status'] !== 'resolved'): ?>
                                        <form action="<?= BASE_URL ?>hostel" method="POST" style="margin:0;">
                                            <input type="hidden" name="action" value="resolve_maintenance">
                                            <input type="hidden" name="maintenance_id" value="<?= $m['id'] ?>">
                                            <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Resolve</button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="text-xs font-semibold text-cit-orange mb-1"><?= ucfirst(htmlspecialchars($m['issue_type'] ?? 'General' ?? '')) ?></p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($m['issue_description']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
