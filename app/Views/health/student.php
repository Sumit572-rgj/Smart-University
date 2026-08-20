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
            <div class="welcome font-medium text-lg">Health & Wellness Center</div>
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

            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
                    <h3 class="text-lg font-bold mb-4">My Medical Profile</h3>
                    <form action="<?= BASE_URL ?>health" method="POST">
                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="block text-sm mb-1">Blood Group</label>
                                <select name="blood_group" class="form-input w-full" required>
                                    <option value="">Select...</option>
                                    <?php
                                    $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                    foreach($groups as $g) {
                                        $sel = ($record && $record['blood_group'] == $g) ? 'selected' : '';
                                        echo "<option value='$g' $sel>$g</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div style="grid-column: span 2;">
                                <label class="block text-sm mb-1">Medical Conditions</label>
                                <textarea name="medical_conditions" class="form-input w-full" rows="2" placeholder="e.g. Asthma, Diabetes..."><?= $record ? htmlspecialchars($record['medical_conditions']) : '' ?></textarea>
                            </div>
                            <div style="grid-column: span 2;">
                                <label class="block text-sm mb-1">Allergies</label>
                                <textarea name="allergies" class="form-input w-full" rows="2" placeholder="e.g. Peanuts, Penicillin..."><?= $record ? htmlspecialchars($record['allergies']) : '' ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact" class="form-input w-full" value="<?= $record ? htmlspecialchars($record['emergency_contact']) : '' ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Emergency Phone</label>
                                <input type="text" name="emergency_phone" class="form-input w-full" value="<?= $record ? htmlspecialchars($record['emergency_phone']) : '' ?>" required>
                            </div>
                            <div style="grid-column: span 2;">
                                <button type="submit" class="btn btn-primary w-full">Update Medical Profile</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-red-200 bg-red-50">
                    <h3 class="text-lg font-bold text-red-700 mb-2">Emergency Hub</h3>
                    <p class="text-sm text-red-600 mb-4">In case of a medical emergency on campus, immediately alert the campus clinic.</p>
                    
                    <button class="btn w-full mb-3" style="background: var(--error-red); color: white; border: none; font-weight: bold; font-size: 1.1rem; padding: 1rem;">SOS / AMBULANCE</button>
                    
                    <div class="bg-white p-3 rounded text-sm text-center border border-red-100">
                        Campus Clinic: <br><strong>+91 44 2233 4455</strong>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
