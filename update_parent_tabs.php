<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/parent/index.php';
$c = file_get_contents($f);

// 1. Update JS
$oldJs = <<<JS
            document.querySelectorAll('.tab-btn-' + childId).forEach(el => el.classList.remove('bg-gray-200', 'font-bold'));
            
            document.getElementById(tabId + '-' + childId).style.display = 'block';
            document.getElementById('btn-' + tabId + '-' + childId).classList.add('bg-gray-200', 'font-bold');
JS;

$newJs = <<<JS
            document.querySelectorAll('.tab-btn-' + childId).forEach(el => el.classList.remove('modern-tab-active'));
            
            document.getElementById(tabId + '-' + childId).style.display = 'block';
            document.getElementById('btn-' + tabId + '-' + childId).classList.add('modern-tab-active');
JS;
$c = str_replace($oldJs, $newJs, $c);


// 2. Update HTML
$oldHtml = <<<HTML
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 overflow-x-auto bg-white">
                    <button id="btn-progress-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> bg-gray-200 font-bold px-6 py-3 border-r border-gray-200" onclick="toggleTab(<?= \$cid ?>, 'progress')">Academic Progress</button>
                    <button id="btn-attendance-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> px-6 py-3 border-r border-gray-200" onclick="toggleTab(<?= \$cid ?>, 'attendance')">Attendance</button>
                    <button id="btn-fees-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> px-6 py-3 border-r border-gray-200" onclick="toggleTab(<?= \$cid ?>, 'fees')">Fee Status</button>
                    <button id="btn-notices-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> px-6 py-3" onclick="toggleTab(<?= \$cid ?>, 'notices')">School Notices</button>
                </div>
HTML;

$newHtml = <<<HTML
                <!-- Tabs -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0;">
                    <div class="pill-tabs-container" style="display: inline-flex; background: #e2e8f0; padding: 0.35rem; border-radius: 9999px; gap: 0.5rem; flex-wrap: wrap; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                        <button id="btn-progress-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> pill-tab-btn" onclick="toggleTab(<?= \$cid ?>, 'progress')">
                            <span style="margin-right:5px; font-size:1.1rem;">📊</span> Academic Progress
                        </button>
                        <button id="btn-attendance-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> pill-tab-btn" onclick="toggleTab(<?= \$cid ?>, 'attendance')">
                            <span style="margin-right:5px; font-size:1.1rem;">✅</span> Attendance
                        </button>
                        <button id="btn-fees-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> pill-tab-btn" onclick="toggleTab(<?= \$cid ?>, 'fees')">
                            <span style="margin-right:5px; font-size:1.1rem;">💳</span> Fee Status
                        </button>
                        <button id="btn-notices-<?= \$cid ?>" class="tab-btn-<?= \$cid ?> pill-tab-btn" onclick="toggleTab(<?= \$cid ?>, 'notices')">
                            <span style="margin-right:5px; font-size:1.1rem;">📢</span> School Notices
                        </button>
                    </div>
                </div>
HTML;
$c = str_replace($oldHtml, $newHtml, $c);

file_put_contents($f, $c);
echo "Parent view updated with pill tabs.";
