<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title><?= $title ?> - Warden Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <style>
        .att-radio { display: none; }
        .att-label { padding: 0.5rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; cursor: pointer; font-weight: 600; transition: all 0.2s; font-size: 0.85rem; }
        .att-radio:checked + .att-label.present { background: #10b981; color: white; border-color: #10b981; }
        .att-radio:checked + .att-label.absent { background: #ef4444; color: white; border-color: #ef4444; }
        .att-radio:checked + .att-label.outpass { background: #f59e0b; color: white; border-color: #f59e0b; }
        .att-label.disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body id="chaos-body">

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>
    <main class="main-content">
        <header class="topbar shadow-sm">
            <div class="welcome font-medium text-lg">🌙 Nightly Hostel Attendance</div>
        </header>

        <div class="p-6">
            <?php if($success): ?>
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg font-medium"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="card p-6 mb-6">
                <form method="GET" action="<?= BASE_URL ?>hostelattendance" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Select Date</label>
                        <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-input" style="width: 200px;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="background: #334155; color: white; padding: 0.5rem 1.5rem; border-radius: 0.25rem;">Fetch Roll Call</button>
                </form>
            </div>

            <div class="card p-6">
                <form method="POST" action="<?= BASE_URL ?>hostelattendance/save">
                    <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($selectedDate) ?>">
                    
                    <div style="overflow-x: auto;">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Department</th>
                                    <th class="text-center">Mark Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($students as $s): ?>
                                    <?php 
                                        $isOnOutpass = ($s['outpass_status'] === 'approved'); 
                                        $currentStatus = $s['marked_status'];
                                        if ($isOnOutpass) $currentStatus = 'Outpass';
                                        if (!$currentStatus) $currentStatus = 'Present'; // Default
                                    ?>
                                    <tr>
                                        <td class="font-bold text-gray-700"><?= htmlspecialchars($s['enrollment_no']) ?></td>
                                        <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                                        <td><?= htmlspecialchars($s['department']) ?></td>
                                        <td>
                                            <div class="flex gap-2 justify-center">
                                                <!-- Present -->
                                                <input type="radio" name="attendance[<?= $s['student_id'] ?>]" id="p_<?= $s['student_id'] ?>" value="Present" class="att-radio" <?= $currentStatus === 'Present' ? 'checked' : '' ?> <?= $isOnOutpass ? 'disabled' : '' ?>>
                                                <label for="p_<?= $s['student_id'] ?>" class="att-label present <?= $isOnOutpass ? 'disabled' : '' ?>">Present</label>
                                                
                                                <!-- Absent -->
                                                <input type="radio" name="attendance[<?= $s['student_id'] ?>]" id="a_<?= $s['student_id'] ?>" value="Absent" class="att-radio" <?= $currentStatus === 'Absent' ? 'checked' : '' ?> <?= $isOnOutpass ? 'disabled' : '' ?>>
                                                <label for="a_<?= $s['student_id'] ?>" class="att-label absent <?= $isOnOutpass ? 'disabled' : '' ?>">Absent</label>
                                                
                                                <!-- Outpass -->
                                                <?php if($isOnOutpass): ?>
                                                    <input type="hidden" name="attendance[<?= $s['student_id'] ?>]" value="Outpass">
                                                    <input type="radio" checked class="att-radio">
                                                    <label class="att-label outpass">On Outpass</label>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn btn-primary" style="background: var(--cit-orange); color: white; padding: 0.75rem 2rem; border-radius: 0.25rem; font-weight: bold; font-size: 1.1rem;">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
