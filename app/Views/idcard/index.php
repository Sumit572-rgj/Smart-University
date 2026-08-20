<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <style>
        .id-card {
            width: 320px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f4f8 100%);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
        }
        .id-header {
            background-color: #1e3a8a; /* cit-blue */
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .id-photo {
            width: 120px;
            height: 120px;
            background-color: #cbd5e1;
            border-radius: 50%;
            margin: 20px auto 10px auto;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        .id-body {
            padding: 10px 20px 20px 20px;
            text-align: center;
        }
        .id-name { font-size: 1.5rem; font-weight: bold; color: #1e293b; mb-1 }
        .id-role { color: #ea580c; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 15px; }
        .id-details p { font-size: 0.85rem; color: #475569; margin-bottom: 5px; }
        .id-footer {
            background-color: #f1f5f9;
            padding: 10px;
            text-align: center;
            font-size: 0.75rem;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .barcode {
            height: 30px;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                transparent 2px,
                transparent 4px,
                #000 4px,
                #000 7px,
                transparent 7px,
                transparent 9px
            );
            margin: 15px auto 0 auto;
            width: 80%;
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
            <div class="welcome font-medium text-lg">Digital Identity</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>
        <div class="content-wrapper py-10">
            <div class="id-card">
                <div class="id-header">
                    Chennai Institute of Technology
                </div>
                <div class="id-photo">
                    👤
                </div>
                <div class="id-body">
                    <div class="id-name"><?= htmlspecialchars($details['name']) ?></div>
                    <div class="id-role"><?= htmlspecialchars($user['role']) ?></div>
                    <div class="id-details">
                        <p><strong>ID No:</strong> <?= htmlspecialchars($details['id_number']) ?></p>
                        <p><strong>Dept:</strong> <?= htmlspecialchars($details['dept']) ?></p>
                        <p><strong>Blood Group:</strong> <span class="text-red-600 font-bold"><?= htmlspecialchars($details['blood']) ?></span></p>
                    </div>
                    <div class="barcode"></div>
                </div>
                <div class="id-footer">
                    Valid Till: <?= htmlspecialchars($details['valid_till']) ?><br>
                    Issuer: CIT Administration
                </div>
            </div>
            
            <div class="text-center mt-8">
                <button onclick="window.print()" class="btn btn-primary shadow-md">🖨️ Print ID Card</button>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
