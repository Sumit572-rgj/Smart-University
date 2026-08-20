<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/message/index.php';
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
                    <div class="flex border-b border-gray-200" style="overflow-x: auto;">
                        <button id="btn-inbox" class="tab-btn bg-gray-200 font-bold px-6 py-4" onclick="toggleTab('inbox')">Inbox</button>
                        <button id="btn-sent" class="tab-btn px-6 py-4" onclick="toggleTab('sent')">Sent</button>
                        <button id="btn-forums" class="tab-btn px-6 py-4" onclick="toggleTab('forums')">Discussion Forums</button>
                    </div>
HTML;

$newHtml = <<<HTML
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0; border-radius: 0.5rem 0.5rem 0 0;">
                        <div class="pill-tabs-container" style="display: inline-flex; background: #e2e8f0; padding: 0.35rem; border-radius: 9999px; gap: 0.5rem; flex-wrap: wrap; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                            <button id="btn-inbox" class="tab-btn pill-tab-btn" onclick="toggleTab('inbox')">
                                <span style="margin-right:5px; font-size:1.1rem;">📥</span> Inbox
                            </button>
                            <button id="btn-sent" class="tab-btn pill-tab-btn" onclick="toggleTab('sent')">
                                <span style="margin-right:5px; font-size:1.1rem;">📤</span> Sent
                            </button>
                            <button id="btn-forums" class="tab-btn pill-tab-btn" onclick="toggleTab('forums')">
                                <span style="margin-right:5px; font-size:1.1rem;">💬</span> Discussion Forums
                            </button>
                        </div>
                    </div>
HTML;

$c = str_replace($oldHtml, $newHtml, $c);

file_put_contents($f, $c);
echo "Message view updated with pill tabs.";
