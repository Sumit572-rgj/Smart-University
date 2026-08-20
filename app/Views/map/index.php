<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Campus Map - CIT UMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <style>
        .map-container {
            position: relative; width: 100%; height: 70vh; background: #e2e8f0; 
            border-radius: 1rem; overflow: hidden; box-shadow: inset 0 0 20px rgba(0,0,0,0.05);
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;
        }
        
        .grass { position: absolute; background: #dcfce7; border-radius: 20px; }
        .road-h { position: absolute; background: #94a3b8; height: 50px; width: 100%; top: 50%; transform: translateY(-50%); z-index: 1; }
        .road-v { position: absolute; background: #94a3b8; width: 50px; height: 100%; left: 30%; transform: translateX(-50%); z-index: 1; }
        .road-line-h { position: absolute; border-top: 4px dashed white; width: 100%; top: 50%; transform: translateY(-50%); z-index: 2; opacity: 0.5;}
        .road-line-v { position: absolute; border-left: 4px dashed white; height: 100%; left: 30%; transform: translateX(-50%); z-index: 2; opacity: 0.5;}

        .building {
            position: absolute; background: white; border: 2px solid #cbd5e1; border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            font-weight: 700; color: #334155; text-align: center; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); user-select: none; z-index: 5;
        }
        
        .building:hover {
            transform: translateY(-8px) scale(1.05); border-color: var(--cit-orange);
            box-shadow: 0 20px 25px -5px rgba(249,115,22,0.3), 0 10px 10px -5px rgba(249,115,22,0.2);
            color: var(--cit-orange); z-index: 10;
        }
        .b-icon { font-size: 2rem; margin-bottom: 0.5rem; }

        #b-admin { top: 10%; left: 5%; width: 20%; height: 25%; background: #f8fafc; }
        #b-library { top: 10%; left: 35%; width: 25%; height: 35%; background: #f0f9ff; }
        #b-cs { top: 60%; left: 5%; width: 20%; height: 30%; background: #fdf4ff; }
        #b-hostel { top: 60%; left: 40%; width: 25%; height: 30%; background: #fffbeb; }
        #b-cafe { top: 20%; right: 10%; width: 15%; height: 20%; background: #fef2f2; border-radius: 50%; }
        #b-sports { top: 55%; right: 5%; width: 25%; height: 40%; background: #ecfdf5; border: 3px dashed #10b981; }

        .map-modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.7);
            display: none; justify-content: center; align-items: center; z-index: 10000; backdrop-filter: blur(5px);
        }
        .map-modal {
            background: white; padding: 2rem; border-radius: 1rem; width: 450px; max-width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); transform: scale(0.9); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .map-modal.open { transform: scale(1); opacity: 1; }
        .modal-close { float: right; cursor: pointer; font-size: 1.5rem; line-height: 1; color: #94a3b8; }
        .modal-close:hover { color: #ef4444; }
        .modal-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; border-bottom: 3px solid var(--cit-orange); padding-bottom: 0.5rem; display: inline-block; margin-bottom: 1rem;}
        .modal-body { color: #475569; font-size: 1rem; line-height: 1.6; }
        .modal-meta { display: flex; gap: 1rem; margin-top: 1.5rem; background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; }
        .meta-item { flex: 1; }
        .meta-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700; margin-bottom: 0.25rem;}
        .meta-value { font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    </style>
</head>
<body id="chaos-body">

<div class="dashboard-layout" id="chaos-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content" id="chaos-main">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <div class="welcome font-medium text-lg">🗺️ Interactive Campus Map</div>
            </div>
            <div class="user-menu flex items-center">
                <a href="<?= BASE_URL ?>dashboard" class="text-sm font-medium text-gray-600 hover:text-orange-500">Back to Dashboard</a>
            </div>
        </header>

        <div class="p-6 max-w-7xl mx-auto">
            <div class="card p-6" style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Explore the Campus</h2>
                        <p class="text-gray-500">Click on any building to view its details, operating hours, and administrative contacts.</p>
                    </div>
                </div>
                
                <div class="map-container">
                    <!-- Decor -->
                    <div class="grass" style="top:4%; left:3%; width:94%; height:92%;"></div>
                    <div class="road-h"></div>
                    <div class="road-line-h"></div>
                    <div class="road-v"></div>
                    <div class="road-line-v"></div>
                    
                                        <!-- Buildings (CIT Real Data) -->
                    <div class="building" id="b-academic" onclick="openMapModal('🏛️ Main Academic Block', 'The core of CIT Chennai. Features smart classrooms, research labs, and an ATM/Bank branch on the first floor.', '08:00 AM - 06:00 PM', 'Dean of Academics')" style="top: 10%; left: 10%; width: 30%; height: 25%; background: #f8fafc;">
                        <div class="b-icon">🏛️</div>Academic Block
                    </div>
                    
                    <div class="building" id="b-library" onclick="openMapModal('📚 Central Library', 'A massive knowledge hub housing over 50,000 volumes, digital libraries, and multimedia learning centers.', '08:00 AM - 08:00 PM', 'Chief Librarian')" style="top: 10%; right: 10%; width: 35%; height: 25%; background: #f0f9ff;">
                        <div class="b-icon">📚</div>Central Library
                    </div>
                    
                    <div class="building" id="b-boys-hostel" onclick="openMapModal('🏢 Podhigai Boys Hostel', 'Residential block for boys featuring 24-hour electricity, TV lounges, reading rooms, and indoor games.', '24/7 Access', 'Chief Warden (Boys)')" style="top: 45%; left: 5%; width: 25%; height: 35%; background: #fffbeb;">
                        <div class="b-icon">🏢</div>Podhigai Boys Hostel
                    </div>
                    
                    <div class="building" id="b-girls-hostel" onclick="openMapModal('🏨 Vaigai Girls Hostel', 'Dedicated female residence offering high-speed Wi-Fi, RO water, power backup, and an exclusive gym.', '24/7 Access', 'Chief Warden (Girls)')" style="top: 45%; left: 35%; width: 25%; height: 35%; background: #fdf4ff;">
                        <div class="b-icon">🏨</div>Vaigai Girls Hostel
                    </div>
                    
                    <div class="building" id="b-health" onclick="openMapModal('🏥 Health Center', 'On-campus medical facility staffed by a dedicated medical officer, healthcare assistants, and inpatient beds.', '24/7 Emergency', 'Resident Medical Officer')" style="top: 60%; right: 10%; width: 25%; height: 20%; background: #fef2f2; border: 2px solid #fca5a5;">
                        <div class="b-icon">🏥</div>Health Center
                    </div>
                    
                    <div class="building" id="b-sports" onclick="openMapModal('⚽ Sports & Indoor Stadium', 'Features a massive playground, an indoor stadium, fitness center, and yoga halls for holistic development.', '06:00 AM - 09:00 PM', 'Physical Director')" style="bottom: 5%; left: 10%; width: 80%; height: 10%; background: #ecfdf5; border: 3px dashed #10b981;">
                        <div class="b-icon" style="display:inline-block; margin-right: 10px; margin-bottom: 0;">⚽</div>Sports Complex
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div class="map-modal-overlay" id="mapModalOverlay">
    <div class="map-modal" id="mapModal">
        <span class="modal-close" onclick="closeMapModal()">&times;</span>
        <h3 class="modal-title" id="modalTitle">Building Name</h3>
        <div class="modal-body" id="modalDesc">Building Description</div>
        <div class="modal-meta">
            <div class="meta-item">
                <div class="meta-label">Hours</div>
                <div class="meta-value" id="modalHours">09:00 - 17:00</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">In Charge</div>
                <div class="meta-value" id="modalStaff">Staff Name</div>
            </div>
        </div>
    </div>
</div>

<script>
    function openMapModal(title, desc, hours, staff) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDesc').innerText = desc;
        document.getElementById('modalHours').innerText = hours;
        document.getElementById('modalStaff').innerText = staff;
        
        const overlay = document.getElementById('mapModalOverlay');
        const modal = document.getElementById('mapModal');
        
        overlay.style.display = 'flex';
        setTimeout(() => { modal.classList.add('open'); }, 10);
    }
    
    function closeMapModal() {
        const modal = document.getElementById('mapModal');
        modal.classList.remove('open');
        setTimeout(() => { document.getElementById('mapModalOverlay').style.display = 'none'; }, 300);
    }
    
    document.getElementById('mapModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeMapModal();
    });
</script>

</body>
</html>
