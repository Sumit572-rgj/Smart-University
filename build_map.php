<?php
// 1. Create MapController.php
$controllerPath = 'C:/xampp/htdocs/cit_ums/app/Controllers/MapController.php';
$controllerCode = <<<'PHP'
<?php
class MapController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }
        $data = [
            'user' => $user,
            'title' => 'Campus Map'
        ];
        $this->view('map/index', $data);
    }
}
PHP;
file_put_contents($controllerPath, $controllerCode);

// 2. Create Map View
if (!is_dir('C:/xampp/htdocs/cit_ums/app/Views/map')) {
    mkdir('C:/xampp/htdocs/cit_ums/app/Views/map');
}

$viewPath = 'C:/xampp/htdocs/cit_ums/app/Views/map/index.php';
$viewCode = <<<'PHP'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Campus Map - CIT UMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/cit_ums/public/css/style.css">
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
    <aside class="sidebar" id="chaos-sidebar">
        <div class="sidebar-header">CIT UMS</div>
        <nav class="sidebar-nav">
            <a href="/cit_ums/dashboard" class="nav-link"><span style="margin-right:8px; font-size:1.1rem;">🏠</span>Dashboard</a>
            <a href="/cit_ums/map" class="nav-link active"><span style="margin-right:8px; font-size:1.1rem;">🗺️</span>Campus Map</a>
        </nav>
    </aside>

    <main class="main-content" id="chaos-main">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <div class="welcome font-medium text-lg">🗺️ Interactive Campus Map</div>
            </div>
            <div class="user-menu flex items-center">
                <a href="/cit_ums/dashboard" class="text-sm font-medium text-gray-600 hover:text-orange-500">Back to Dashboard</a>
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
                    
                    <!-- Buildings -->
                    <div class="building" id="b-admin" onclick="openMapModal('🏛️ Admin Block', 'The central administrative hub handling admissions, finance, and the registrar office. Visit here for official documentation.', '09:00 AM - 05:00 PM', 'Dr. Sharma')">
                        <div class="b-icon">🏛️</div>Admin Block
                    </div>
                    
                    <div class="building" id="b-library" onclick="openMapModal('📚 Central Library', 'A massive three-story complex housing over 100,000 academic journals, books, and quiet study pods with high-speed Wi-Fi.', '24/7 Access', 'Mrs. Gupta')">
                        <div class="b-icon">📚</div>Central Library
                    </div>
                    
                    <div class="building" id="b-cs" onclick="openMapModal('💻 CS Department', 'State-of-the-art tech labs equipped with supercomputers, AI research centers, and coding workstations.', '08:00 AM - 08:00 PM', 'Prof. Verma')">
                        <div class="b-icon">💻</div>CS Department
                    </div>
                    
                    <div class="building" id="b-hostel" onclick="openMapModal('🛏️ Hostel Blocks', 'Residential blocks offering comfortable living quarters, common rooms, and high-speed campus network access.', 'Always Open', 'Chief Warden')">
                        <div class="b-icon">🛏️</div>Hostel Blocks
                    </div>
                    
                    <div class="building" id="b-cafe" onclick="openMapModal('☕ Cafeteria', 'The perfect hangout spot! Offers a variety of multi-cuisine meals, fresh coffee, and snacks.', '07:30 AM - 10:00 PM', 'Chef Rajesh')">
                        <div class="b-icon">☕</div>Cafeteria
                    </div>
                    
                    <div class="building" id="b-sports" onclick="openMapModal('⚽ Sports Complex', 'Features an indoor basketball court, a fully-equipped gym, and an Olympic-sized swimming pool.', '06:00 AM - 09:00 PM', 'Coach Singh')">
                        <div class="b-icon">⚽</div>Sports Complex
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
PHP;
file_put_contents($viewPath, $viewCode);

// 3. Inject Map Link into all other views that have the dashboard link
$viewsDir = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views');
$iterator = new RecursiveIteratorIterator($viewsDir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'index.php') { // skip the map/index.php we just made
        $content = file_get_contents($file->getPathname());
        
        // Find the dashboard link and append the Map link right after it
        $dashLinkPattern = '/<a href="\/cit_ums\/dashboard" class="nav-link[^>]*>.*?<\/a>/i';
        
        if (preg_match($dashLinkPattern, $content, $matches)) {
            $mapLink = "\n              <a href=\"/cit_ums/map\" class=\"nav-link\"><span style=\"margin-right:8px; font-size:1.1rem;\">🗺️</span>Campus Map</a>";
            
            // Only inject if it doesn't already exist
            if (strpos($content, '/cit_ums/map') === false) {
                $newContent = str_replace($matches[0], $matches[0] . $mapLink, $content);
                file_put_contents($file->getPathname(), $newContent);
            }
        }
    }
}

echo "Interactive Campus Map Created & Linked!\n";
