<?php
// public/cron.php
// This script simulates a server cron job. It should be executed via CLI: `php public/cron.php`
// or hit via a secret URL `public/cron.php?secret=mysecret`

if (php_sapi_name() !== 'cli' && (!isset($_GET['secret']) || $_GET['secret'] !== 'run_cron_task')) {
    die("Unauthorized.");
}

require_once __DIR__ . '/../app/Core/Model.php';

echo "Running Cron Tasks...\n";

try {
    $db = (new Model())->db;
    
    // 1. Find all pending fees that are past their due date
    $stmt = $db->query("SELECT id, student_id, fee_type, amount, due_date FROM fees WHERE status = 'pending' AND due_date < CURDATE()");
    $overdueFees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($overdueFees) > 0) {
        $updateStmt = $db->prepare("UPDATE fees SET status = 'overdue' WHERE id = ?");
        $insertFineStmt = $db->prepare("INSERT INTO fees (student_id, invoice_no, fee_type, amount, due_date, status) VALUES (?, ?, 'other', ?, CURDATE() + INTERVAL 7 DAY, 'pending')");
        
        $count = 0;
        foreach ($overdueFees as $fee) {
            // Mark as overdue
            $updateStmt->execute([$fee['id']]);
            
            // Generate Late Fine (5% of original amount or fixed 500)
            $fineAmount = max(500, round($fee['amount'] * 0.05));
            $invoiceNo = 'FINE-' . strtoupper(substr(md5(uniqid()), 0, 8));
            
            $insertFineStmt->execute([
                $fee['student_id'],
                $invoiceNo,
                $fineAmount
            ]);
            $count++;
        }
        
        echo "Marked $count fees as overdue and generated late fines.\n";
    } else {
        echo "No overdue fees found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Cron Tasks Completed.\n";
