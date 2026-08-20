<?php
$f = 'app/Views/fee/student.php';
$c = file_get_contents($f);
$script = <<<HTML
<script>
function printReceipt(invoiceNo, amount, refNo) {
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Fee Receipt - \${invoiceNo}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
                .receipt-container { border: 2px solid #e5e7eb; border-radius: 8px; padding: 30px; max-width: 600px; margin: 0 auto; }
                .header { text-align: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
                .logo { font-size: 24px; font-weight: bold; color: #f97316; }
                .title { font-size: 20px; margin-top: 10px; font-weight: bold; }
                .details { margin-bottom: 30px; }
                .details table { width: 100%; border-collapse: collapse; }
                .details td { padding: 10px; border-bottom: 1px solid #f3f4f6; }
                .details .label { font-weight: bold; width: 40%; color: #6b7280; }
                .footer { text-align: center; font-size: 14px; color: #6b7280; margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
                @media print {
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="header">
                    <div class="logo">CIT UNIVERSITY</div>
                    <div class="title">OFFICIAL FEE RECEIPT</div>
                </div>
                <div class="details">
                    <table>
                        <tr><td class="label">Invoice Number</td><td>\${invoiceNo}</td></tr>
                        <tr><td class="label">Reference No</td><td>\${refNo || 'N/A'}</td></tr>
                        <tr><td class="label">Amount Paid</td><td style="font-size:18px; font-weight:bold;">₹\${parseFloat(amount).toFixed(2)}</td></tr>
                        <tr><td class="label">Payment Date</td><td>\${new Date().toLocaleDateString()}</td></tr>
                        <tr><td class="label">Status</td><td style="color:#10b981; font-weight:bold;">VERIFIED</td></tr>
                    </table>
                </div>
                <div class="footer">
                    This is a computer generated receipt and does not require a physical signature.<br>
                    Thank you for your payment.
                </div>
            </div>
            <script>
                window.onload = function() { window.print(); }
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
HTML;

$c = str_ireplace('</body>', $script . "\n</body>", $c);
file_put_contents($f, $c);
echo "Receipt script injected.";
