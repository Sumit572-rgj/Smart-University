<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/gate/index.php';
$c = file_get_contents($f);

// Inject the scanned student UI right after the success alert in the scanner section
$oldHtml = <<<HTML
                        <?php if (!empty(\$success)): ?>
                            <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars(\$success) ?></div>
                        <?php endif; ?>
HTML;

$newHtml = <<<HTML
                        <?php if (!empty(\$success)): ?>
                            <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars(\$success) ?></div>
                            
                            <?php if (isset(\$scanned_student) && isset(\$scanned_student['current_action'])): ?>
                                <div style="background: white; border: 2px solid <?= \$scanned_student['action_color'] ?>; border-radius: 1rem; padding: 1.5rem; text-align: center; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);">
                                    <div style="background: <?= \$scanned_student['action_color'] ?>; color: white; display: inline-block; padding: 0.25rem 1rem; border-radius: 999px; font-weight: 800; font-size: 0.85rem; margin-bottom: 1rem; letter-spacing: 1px;">
                                        <?= \$scanned_student['current_action'] ?> SUCCESS
                                    </div>
                                    
                                    <div style="width: 100px; height: 100px; margin: 0 auto 1rem; border-radius: 50%; border: 4px solid #f1f5f9; overflow: hidden; background: #e2e8f0;">
                                        <!-- Using UI Avatars for placeholder profile pic if actual profile_pic isn't available -->
                                        <?php \$picUrl = !empty(\$scanned_student['profile_pic']) ? htmlspecialchars(\$scanned_student['profile_pic']) : "https://ui-avatars.com/api/?name=" . urlencode(\$scanned_student['first_name'] . ' ' . \$scanned_student['last_name']) . "&background=0f172a&color=fff&size=200"; ?>
                                        <img src="<?= \$picUrl ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    
                                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;"><?= htmlspecialchars(\$scanned_student['first_name'] . ' ' . \$scanned_student['last_name']) ?></h2>
                                    <p style="color: #64748b; font-size: 1.1rem; font-weight: 600; margin: 0.25rem 0 1rem;">ID: <?= htmlspecialchars(\$scanned_student['enrollment_no']) ?></p>
                                    
                                    <div style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left;">
                                        <div>
                                            <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Department</div>
                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars(\$scanned_student['department']) ?></div>
                                        </div>
                                        <div>
                                            <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Destination</div>
                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars(\$scanned_student['destination']) ?></div>
                                        </div>
                                        <div style="grid-column: span 2;">
                                            <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Reason</div>
                                            <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars(\$scanned_student['reason']) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
HTML;

$c = str_replace($oldHtml, $newHtml, $c);
file_put_contents($f, $c);
echo "Gate scanner view updated to display the digital ID card.";
