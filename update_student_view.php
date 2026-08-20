<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/profile.php';
$c = file_get_contents($f);

$oldHeader = <<<HTML
                    <div class="flex items-center mb-6 border-b pb-4">
                        <div style="width: 80px; height: 80px; background: var(--cit-blue); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin-right: 1.5rem;">
                            <?= substr(\$student['first_name'], 0, 1) ?>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold"><?= htmlspecialchars(\$student['first_name'] . ' ' . \$student['last_name']) ?></h2>
                            <p class="text-gray-500"><?= htmlspecialchars(\$student['enrollment_no']) ?> | <?= htmlspecialchars(\$student['department']) ?></p>
                        </div>
                    </div>
HTML;

$newHeader = <<<HTML
                    <div class="flex items-center mb-6 border-b pb-4" style="flex-wrap: wrap; gap: 1.5rem;">
                        <div style="position: relative;">
                            <?php if (!empty(\$student['profile_pic'])): ?>
                                <img src="<?= htmlspecialchars(\$student['profile_pic']) ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #f1f5f9;">
                            <?php else: ?>
                                <div style="width: 100px; height: 100px; background: var(--cit-blue); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; border: 4px solid #f1f5f9;">
                                    <?= substr(\$student['first_name'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Hidden File Input & Label -->
                            <form action="/cit_ums/student/profile" method="POST" enctype="multipart/form-data" id="profile-pic-form" style="position: absolute; bottom: -5px; right: -5px;">
                                <label for="profile_pic" style="background: white; border: 1px solid #cbd5e1; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <svg style="width: 16px; height: 16px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                                <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display: none;" onchange="document.getElementById('profile-pic-form').submit();">
                            </form>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold"><?= htmlspecialchars(\$student['first_name'] . ' ' . \$student['last_name']) ?></h2>
                            <p class="text-gray-500"><?= htmlspecialchars(\$student['enrollment_no']) ?> | <?= htmlspecialchars(\$student['department']) ?></p>
                        </div>
                    </div>
HTML;

$c = str_replace($oldHeader, $newHeader, $c);
file_put_contents($f, $c);
echo "Student profile view updated with picture upload button.";
