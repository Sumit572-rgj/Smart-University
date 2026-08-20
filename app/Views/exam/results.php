<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - CIT UMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body style="background: #f8fafc;">

<div style="padding: 2rem;">
    <!-- Minimal Header for Public Access -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #0f172a;">Chennai Institute of Technology</h1>
        <p style="color: #64748b;">University Management System</p>
    </div>

    <div class="results-container" style="background: white; border-radius: 1rem; padding: 2rem; max-width: 800px; margin: 0 auto; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 2rem; color: #334155;">🎓 Academic Results Portal</h2>
        
        <?php if (empty($results) && empty($student)): ?>
            <!-- Input Form -->
            <form method="POST" action="<?= BASE_URL ?>exam/results" style="max-width: 400px; margin: 0 auto;">
                <?php if ($error): ?>
                    <div style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem; font-weight: 500;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Registration Number</label>
                    <input type="text" name="register_number" placeholder="e.g. STU12345" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 1rem;">
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Date of Birth</label>
                    <input type="date" name="dob" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 1rem;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 1rem; background: var(--cit-orange, #f97316); color: white; border: none; border-radius: 0.5rem; font-weight: 700; font-size: 1rem; cursor: pointer;">
                    Fetch Results
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 2rem;">
                <a href="<?= BASE_URL ?>auth/login" style="color: #64748b; text-decoration: none; font-size: 0.9rem;">&larr; Back to Login</a>
            </div>
        <?php else: ?>
            <!-- Results Display -->
            <div class="student-header" style="text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px dashed #e2e8f0;">
                <h3 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;"><?= htmlspecialchars(strtoupper($student['first_name'] . ' ' . $student['last_name'])) ?></h3>
                <div style="display: inline-block; background: #f8fafc; padding: 0.5rem 1rem; border-radius: 999px; font-weight: 600; color: #475569; border: 1px solid #e2e8f0;">
                    Reg No: <span style="color: var(--cit-orange);"><?= htmlspecialchars($student['enrollment_no']) ?></span> <span class="separator" style="display:inline;">&nbsp;|&nbsp;</span> 
                    Dept: <span style="color: var(--cit-orange);"><?= htmlspecialchars($student['department']) ?></span>
                </div>
            </div>
            
            <h4 style="font-weight: 700; color: #334155; margin-bottom: 1rem;"><?= htmlspecialchars($results[0]['exam_name']) ?> Statement of Marks</h4>
            
            <div style="overflow-x: auto; width: 100%; margin-bottom: 2rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;"><table style="width: 100%; min-width: 600px; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 1rem; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Subject</th>
                        <th style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; color: #475569;">Max Marks</th>
                        <th style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; color: #475569;">Marks Obtained</th>
                        <th style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; color: #475569;">Grade</th>
                        <th style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; color: #475569;">GPA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalMarks = 0; $totalMax = 0; $totalGPA = 0;
                    foreach($results as $res): 
                        $totalMarks += $res['marks'];
                        $totalMax += $res['max_marks'];
                        $totalGPA += $res['gpa'];
                    ?>
                    <tr>
                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 500;"><?= htmlspecialchars($res['subject']) ?></td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0;"><?= $res['max_marks'] ?></td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 700;"><?= $res['marks'] ?></td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 800; color: <?= $res['grade'] == 'F' ? '#ef4444' : '#10b981' ?>;"><?= $res['grade'] ?></td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 600;"><?= $res['gpa'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <!-- Total Row -->
                    <tr style="background: #fff7ed; border-top: 2px solid #fdba74;">
                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 800; text-align: right; color: #9a3412;">GRAND TOTAL</td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 800;"><?= $totalMax ?></td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 800; color: var(--cit-orange); font-size: 1.1rem;"><?= $totalMarks ?></td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 800; color: #9a3412;">CGPA</td>
                        <td style="padding: 1rem; text-align: center; border: 1px solid #e2e8f0; font-weight: 800; color: var(--cit-orange); font-size: 1.1rem;"><?= number_format($totalGPA / count($results), 2) ?></td>
                    </tr>
                </tbody>
            </table></div>
            
            <div style="text-align: center; margin-top: 2rem;" class="no-print">
                <button onclick="window.print()" style="padding: 0.75rem 1.5rem; background: #0f172a; color: white; border: none; border-radius: 0.5rem; font-weight: 700; cursor: pointer; margin-right: 1rem;">
                    🖨️ Print Marksheet
                </button>
                <a href="<?= BASE_URL ?>exam/results" style="color: var(--cit-orange); text-decoration: none; font-weight: 600;">Check Another Result</a>
            </div>
            
                <style> 
        @media print { 
            body { background: white !important; }
            .no-print, .chatbot-btn, .sidebar, .topbar { display: none !important; } 
        } 
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .results-container {
                padding: 1rem !important;
            }
            .student-header h3 {
                font-size: 1.4rem !important;
            }
            .student-header div {
                font-size: 0.85rem !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 0.5rem !important;
                border-radius: 0.5rem !important;
            }
            .student-header span {
                display: block !important;
            }
            /* Hide the pipe separator on mobile */
            .student-header .separator {
                display: none !important;
            }
        }
    </style>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
