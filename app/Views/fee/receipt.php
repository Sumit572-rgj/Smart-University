<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - <?= htmlspecialchars($fee['invoice_no']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 2rem; font-family: 'Inter', sans-serif; background: #e2e8f0; display: flex; justify-content: center; }
        .receipt-container { background: white; width: 100%; max-width: 800px; padding: 3rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 2rem; margin-bottom: 2rem; }
        .logo { font-size: 2rem; font-weight: 800; color: #0f172a; letter-spacing: -1px; }
        .logo span { color: #f97316; }
        .title { color: #64748b; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; margin-top: 0.5rem; text-align: right;}
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem; }
        .details-box { background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; }
        .details-box h4 { margin: 0 0 1rem 0; color: #475569; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
        .details-box p { margin: 0.5rem 0; color: #0f172a; font-weight: 600; }
        .details-box span { color: #64748b; font-weight: 400; display: inline-block; width: 120px; }
        .amount-table { width: 100%; border-collapse: collapse; margin-bottom: 3rem; }
        .amount-table th { background: #f1f5f9; padding: 1rem; text-align: left; color: #475569; font-weight: 600; }
        .amount-table td { padding: 1rem; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600; }
        .amount-table .total-row td { background: #fff3ed; font-size: 1.25rem; font-weight: 700; color: #f97316; border-bottom: none; }
        .footer { text-align: center; color: #94a3b8; font-size: 0.9rem; border-top: 1px solid #e2e8f0; padding-top: 2rem; }
        
        .print-btn {
            position: fixed; top: 2rem; right: 2rem; background: #0f172a; color: white; border: none; padding: 1rem 2rem; border-radius: 999px; font-weight: 600; cursor: pointer; font-size: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        @media print {
            body { background: white; padding: 0; display: block; }
            .receipt-container { box-shadow: none; max-width: 100%; padding: 0; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>

    <div class="receipt-container">
        <div class="header">
            <div>
                <div class="logo">CIT<span>UMS</span></div>
                <p style="color: #64748b; margin: 0.5rem 0 0 0;">University Management System</p>
            </div>
            <div>
                <div class="title">Official Receipt</div>
                <p style="color: #0f172a; font-weight: 600; margin: 0.5rem 0 0 0; text-align: right;">No: <?= htmlspecialchars($fee['invoice_no']) ?></p>
                <p style="color: #64748b; margin: 0.25rem 0 0 0; text-align: right;">Date: <?= date('F j, Y', strtotime($fee['updated_at'] ?? $fee['created_at'])) ?></p>
            </div>
        </div>

        <div class="details-grid">
            <div class="details-box">
                <h4>Student Details</h4>
                <p><span>Name:</span> <?= htmlspecialchars($fee['first_name'] . ' ' . $fee['last_name']) ?></p>
                <p><span>Enrollment No:</span> <?= htmlspecialchars($fee['enrollment_no']) ?></p>
                <p><span>Department:</span> <?= htmlspecialchars($fee['department']) ?></p>
            </div>
            <div class="details-box">
                <h4>Payment Details</h4>
                <p><span>Status:</span> <strong style="color: #10b981;">VERIFIED & PAID</strong></p>
                <p><span>Reference No:</span> <?= htmlspecialchars($fee['reference_no']) ?></p>
                <p><span>Fee Type:</span> <?= htmlspecialchars(ucfirst($fee['fee_type'])) ?></p>
            </div>
        </div>

        <table class="amount-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars(ucfirst($fee['fee_type'])) ?> Fee</td>
                    <td style="text-align: right;">Rs. <?= number_format($fee['amount'] + $fee['discount_amount'] - $fee['fine_amount']) ?></td>
                </tr>
                <?php if($fee['discount_amount'] > 0): ?>
                <tr>
                    <td>Scholarship / Discount</td>
                    <td style="text-align: right; color: #10b981;">- Rs. <?= number_format($fee['discount_amount']) ?></td>
                </tr>
                <?php endif; ?>
                <?php if($fee['fine_amount'] > 0): ?>
                <tr>
                    <td>Late Payment Fine</td>
                    <td style="text-align: right; color: #ef4444;">+ Rs. <?= number_format($fee['fine_amount']) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td>Total Amount Paid</td>
                    <td style="text-align: right;">Rs. <?= number_format($fee['amount']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>This is a computer-generated receipt and does not require a physical signature.</p>
            <p>&copy; <?= date('Y') ?> CIT University Management System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Automatically trigger print dialog after 1 second
        setTimeout(() => {
            window.print();
        }, 1000);
    </script>
</body>
</html>
