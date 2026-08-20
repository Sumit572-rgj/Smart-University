<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/map/index.php';
$c = file_get_contents($f);

// Update building layouts and IDs
$oldBuildingsPattern = '/<!-- Buildings -->[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/m';

$newBuildings = <<<'HTML'
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
HTML;

$c = preg_replace($oldBuildingsPattern, $newBuildings, $c);
file_put_contents($f, $c);
echo "Campus map updated with real CIT data.\n";
