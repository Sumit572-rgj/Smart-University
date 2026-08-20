<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/OutpassController.php';
$c = file_get_contents($f);

$oldSimulate = <<<'PHP'
    private function simulateNotification($outpass_id, $message) {
        // In a real app, integrate Twilio / SendGrid here.
        // For now, we just simulate the event.
        error_log("NOTIFICATION [Outpass $outpass_id]: $message");
    }
PHP;

$newSimulate = <<<'PHP'
    private function simulateNotification($outpass_id, $message) {
        // Log it as before
        error_log("NOTIFICATION [Outpass $outpass_id]: $message");
        
        // Actually send real email
        require_once '../app/Core/Mail.php';
        $db = (new Model())->db;
        $stmt = $db->prepare("SELECT u.email, o.status, o.destination FROM outpass o JOIN students s ON o.student_id = s.id JOIN users u ON s.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$outpass_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data && $data['email']) {
            $statusLabel = strtoupper($data['status']);
            $msg = "There is an update regarding your Outpass application to <strong>{$data['destination']}</strong>.<br><br><strong>Current Status:</strong> {$statusLabel}<br><strong>System Message:</strong> {$message}";
            Mail::sendTemplate($data['email'], "Outpass Update: {$statusLabel}", 'Outpass Request Update', $msg, 'View Details', 'http://localhost/cit_ums/outpass');
        }
    }
PHP;

$c = str_replace($oldSimulate, $newSimulate, $c);
file_put_contents($f, $c);
echo "Outpass emails upgraded.\n";
