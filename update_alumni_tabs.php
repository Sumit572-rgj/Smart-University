<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/alumninetwork/index.php';
$c = file_get_contents($f);

// 1. Update JS
$oldJs = <<<JS
        function toggleTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('bg-gray-200', 'font-bold'));
            
            document.getElementById(tabId).style.display = 'block';
            document.getElementById('btn-' + tabId).classList.add('bg-gray-200', 'font-bold');
        }
JS;

$newJs = <<<JS
        function toggleTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('modern-tab-active'));
            
            document.getElementById(tabId).style.display = 'block';
            document.getElementById('btn-' + tabId).classList.add('modern-tab-active');
        }
JS;

$c = str_replace($oldJs, $newJs, $c);

// 2. Update HTML
$oldHtml = <<<HTML
            <!-- Navigation Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 flex overflow-x-auto">
                <button id="btn-directory" class="tab-btn bg-gray-200 font-bold px-6 py-4 border-r border-gray-200" onclick="toggleTab('directory')">Alumni Directory</button>
                <button id="btn-jobs" class="tab-btn px-6 py-4 border-r border-gray-200" onclick="toggleTab('jobs')">Job Board & Referrals</button>
                <button id="btn-events" class="tab-btn px-6 py-4 border-r border-gray-200" onclick="toggleTab('events')">Events & Reunions</button>
                <?php if (\$user['role'] === 'alumni'): ?>
                    <button id="btn-myprofile" class="tab-btn px-6 py-4 border-r border-gray-200" onclick="toggleTab('myprofile')">My Profile</button>
                    <button id="btn-giveback" class="tab-btn px-6 py-4" onclick="toggleTab('giveback')">Give Back</button>
                <?php endif; ?>
            </div>
HTML;

$newHtml = <<<HTML
            <!-- Navigation Tabs -->
            <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; text-align: center; border: 1px solid #e2e8f0; border-radius: 1rem; margin-bottom: 1.5rem;">
                <div class="pill-tabs-container" style="display: inline-flex; background: #e2e8f0; padding: 0.35rem; border-radius: 9999px; gap: 0.5rem; flex-wrap: wrap; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <button id="btn-directory" class="tab-btn pill-tab-btn" onclick="toggleTab('directory')">
                        <span style="margin-right:5px; font-size:1.1rem;">🎓</span> Alumni Directory
                    </button>
                    <button id="btn-jobs" class="tab-btn pill-tab-btn" onclick="toggleTab('jobs')">
                        <span style="margin-right:5px; font-size:1.1rem;">💼</span> Job Board & Referrals
                    </button>
                    <button id="btn-events" class="tab-btn pill-tab-btn" onclick="toggleTab('events')">
                        <span style="margin-right:5px; font-size:1.1rem;">🎉</span> Events & Reunions
                    </button>
                    <?php if (\$user['role'] === 'alumni'): ?>
                        <button id="btn-myprofile" class="tab-btn pill-tab-btn" onclick="toggleTab('myprofile')">
                            <span style="margin-right:5px; font-size:1.1rem;">👤</span> My Profile
                        </button>
                        <button id="btn-giveback" class="tab-btn pill-tab-btn" onclick="toggleTab('giveback')">
                            <span style="margin-right:5px; font-size:1.1rem;">🎁</span> Give Back
                        </button>
                    <?php endif; ?>
                </div>
            </div>
HTML;

$c = str_replace($oldHtml, $newHtml, $c);
file_put_contents($f, $c);
echo "Alumni view updated with pill tabs.";
