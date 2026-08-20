<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

$bellSnippet = '
                <!-- BELL INJECT START -->
                <?php 
                    $notifDb = new PDO("mysql:host=localhost;dbname=cit_ums", "root", "");
                    $notifUserId = $user["id"] ?? 0;
                    $unreadCount = 0;
                    if ($notifUserId) {
                        $stmt = $notifDb->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
                        $stmt->execute([$notifUserId]);
                        $unreadCount = $stmt->fetchColumn();
                    }
                ?>
                <div class="notification-bell" style="position: relative; cursor: pointer; margin-right: 1rem;" onclick="document.getElementById(\'notif-dropdown-<?= uniqid() ?>\').classList.toggle(\'hidden\')">
                    <span style="font-size: 1.25rem;">🔔</span>
                    <?php if ($unreadCount > 0): ?>
                        <span style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; border-radius: 50%; padding: 0.1rem 0.4rem; font-size: 0.7rem; font-weight: bold;"><?= $unreadCount ?></span>
                    <?php endif; ?>
                    
                    <div id="notif-dropdown-<?= uniqid() ?>" class="hidden shadow-lg border border-gray-200" style="position: absolute; right: 0; top: 30px; background: white; width: 300px; border-radius: 0.5rem; z-index: 50;">
                        <div style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: bold;">Notifications</div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <?php
                                if ($notifUserId) {
                                    $stmt = $notifDb->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                                    $stmt->execute([$notifUserId]);
                                    $notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if (count($notifs) > 0) {
                                        foreach($notifs as $notif) {
                                            $bg = $notif["is_read"] ? "white" : "#f3f4f6";
                                            echo "<div style=\'padding: 0.75rem; border-bottom: 1px solid #e5e7eb; background: {$bg};\'>";
                                            echo "<div style=\'font-size: 0.85rem; font-weight: bold;\'>" . htmlspecialchars($notif["title"]) . "</div>";
                                            echo "<div style=\'font-size: 0.75rem; color: #4b5563;\'>" . htmlspecialchars($notif["message"]) . "</div>";
                                            echo "<div style=\'font-size: 0.65rem; color: #9ca3af; margin-top: 0.25rem;\'>" . $notif["created_at"] . "</div>";
                                            echo "</div>";
                                        }
                                    } else {
                                        echo "<div style=\'padding: 1rem; text-align: center; color: #6b7280; font-size: 0.875rem;\'>No new notifications.</div>";
                                    }
                                }
                            ?>
                        </div>
                        <div style="padding: 0.5rem; text-align: center; border-top: 1px solid #e5e7eb;">
                            <form action="/cit_ums/notifications/mark_read" method="POST" style="margin:0;">
                                <button type="submit" style="background: none; border: none; color: #3b82f6; font-size: 0.8rem; cursor: pointer;">Mark all as read</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- BELL INJECT END -->
';

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        // Skip if already injected
        if (strpos($content, 'BELL INJECT START') !== false || strpos($content, '<div class="notification-bell"') !== false) {
            echo "Skipped (Already has bell): " . $file->getFilename() . "\n";
            continue;
        }

        // Try replacing '<a href="/cit_ums/auth/logout"'
        $pattern = '/<a href="\/cit_ums\/auth\/logout"/s';
        
        if (preg_match($pattern, $content)) {
            $newContent = preg_replace($pattern, $bellSnippet . "\n                <a href=\"/cit_ums/auth/logout\"", $content);
            // Fix layout
            if (strpos($newContent, 'class="user-menu"') !== false && strpos($newContent, 'flex items-center') === false) {
                $newContent = str_replace('class="user-menu"', 'class="user-menu" style="display:flex; align-items:center;"', $newContent);
            }
            file_put_contents($file->getRealPath(), $newContent);
            echo "Updated: " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.";
