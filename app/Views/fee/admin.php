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
            <div class="welcome font-medium text-lg">Fee Management</div>
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

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold">Generate Invoice</h3>
                <form action="<?= BASE_URL ?>fee/admin" method="POST">
                    <input type="hidden" name="action" value="remind">
                    <button type="submit" class="btn btn-primary" style="background:#f59e0b; border:none;">&#128276; Send Pending Reminders</button>
                </form>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form action="<?= BASE_URL ?>fee/admin" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <input type="hidden" name="action" value="create">
                    <div>
                        <label class="block text-sm mb-1">Select Student</label>
                        <select name="student_id" class="form-input w-full" required>
                            <option value="" disabled selected>-- Choose Student --</option>
                            <?php foreach($students_list as $st): ?>
                                <option value="<?= $st['id'] ?>"><?= htmlspecialchars($st['enrollment_no'] . ' - ' . $st['first_name'] . ' ' . $st['last_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Fee Type</label>
                        <select name="fee_type" class="form-input w-full" required>
                            <option value="tuition">Tuition Fee</option>
                            <option value="hostel">Hostel Fee</option>
                            <option value="library">Library Fine</option>
                            <option value="exam">Exam Fee</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Base Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Scholarship / Discount (₹)</label>
                        <input type="number" step="0.01" name="discount" value="0.00" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Late Fine (₹)</label>
                        <input type="number" step="0.01" name="fine" value="0.00" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Due Date</label>
                        <input type="date" name="due_date" class="form-input w-full" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" class="btn btn-primary w-full">Generate Invoice</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">All Invoices</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Student</th>
                                <th>Type</th>
                                <th>Amount / Due Date</th>
                                <th>Ref No.</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $inv): ?>
                            <tr>
                                <td><span class="font-medium"><?= htmlspecialchars($inv['invoice_no']) ?></span></td>
                                <td><?= htmlspecialchars($inv['enrollment_no'] . ' - ' . $inv['first_name']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($inv['fee_type'])) ?></td>
                                <td>
                                    <div class="font-bold">₹<?= number_format($inv['amount'], 2) ?></div>
                                    <div class="text-xs text-gray-500">Due: <?= date('d M Y', strtotime($inv['due_date'])) ?></div>
                                </td>
                                <td>
                                    <?php if($inv['reference_no']): ?>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded border"><?= htmlspecialchars($inv['reference_no']) ?></span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-blue-100 text-blue-800',
                                            'verified' => 'bg-green-100 text-green-800',
                                            'overdue' => 'bg-red-100 text-red-800'
                                        ];
                                        $class = $statusColors[$inv['status']] ?? 'bg-gray-100';
                                        
                                        $displayStatus = ucfirst($inv['status']);
                                        if ($inv['status'] === 'paid') $displayStatus = 'Pending Verification';
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium <?= $class ?>" style="border-radius: 999px; padding: 2px 8px;">
                                        <?= $displayStatus ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.5rem;">
                                        <?php if($inv['status'] === 'paid'): ?>
                                            <form action="<?= BASE_URL ?>fee/admin" method="POST">
                                                <input type="hidden" name="action" value="verify">
                                                <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
                                                <button type="submit" class="btn btn-primary" style="background:#10b981; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Verify</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form action="<?= BASE_URL ?>fee/admin" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
                                            <button type="submit" class="btn btn-primary" style="background:#ef4444; border:none; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
