<?php
$content = file_get_contents('app/Views/dashboard/index.php');

// Extract from <body> up to <main class="main-content" id="chaos-main">
preg_match('/(.*?<main class="main-content"[^>]*>)/is', $content, $header_match);
$header = $header_match[1] ?? '';

// Extract topbar
preg_match('/(<header class="topbar shadow-sm">.*?<\/header>)/is', $content, $topbar_match);
$topbar = $topbar_match[1] ?? '';

$footer = <<<HTML
        </div>
    </main>
</div>
</body>
</html>
HTML;

$faculty_main = <<<HTML
        <div class="content-wrapper">
            <?php if (!empty(\$success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;"><?= htmlspecialchars(\$success) ?></div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <h3 class="text-lg font-bold mb-4">Select Course</h3>
                <form action="/cit_ums/attendance" method="GET" class="flex gap-4">
                    <select name="course_code" class="form-input" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach(\$courses as \$c): ?>
                            <option value="<?= htmlspecialchars(\$c['course_code']) ?>" <?= \$selected_course === \$c['course_code'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars(\$c['course_code']) ?> (<?= htmlspecialchars(\$c['department']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Load Students</button>
                </form>
            </div>

            <?php if(\$selected_course && !empty(\$students)): ?>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold mb-4">Mark Attendance - <?= htmlspecialchars(\$selected_course) ?></h3>
                <form action="/cit_ums/attendance" method="POST">
                    <input type="hidden" name="course_code" value="<?= htmlspecialchars(\$selected_course) ?>">
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Date</label>
                        <input type="date" name="date" class="form-input" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Enrollment No</th>
                                    <th>Name</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Late</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach(\$students as \$s): ?>
                                <tr>
                                    <td><?= htmlspecialchars(\$s['enrollment_no']) ?></td>
                                    <td><?= htmlspecialchars(\$s['first_name'] . ' ' . \$s['last_name']) ?></td>
                                    <td><input type="radio" name="attendance[<?= \$s['id'] ?>]" value="present" required checked></td>
                                    <td><input type="radio" name="attendance[<?= \$s['id'] ?>]" value="absent"></td>
                                    <td><input type="radio" name="attendance[<?= \$s['id'] ?>]" value="late"></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Submit Attendance</button>
                    </div>
                </form>
            </div>
            <?php elseif(\$selected_course): ?>
                <div class="alert">No students found for this course department.</div>
            <?php endif; ?>
HTML;

$student_main = <<<HTML
        <div class="content-wrapper">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">My Attendance</h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Total Classes</th>
                                <th>Classes Attended</th>
                                <th>Attendance %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty(\$attendance)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-gray-500">No attendance records found.</td></tr>
                            <?php else: ?>
                                <?php foreach(\$attendance as \$a): 
                                    \$percentage = \$a['total_classes'] > 0 ? round((\$a['present_classes'] / \$a['total_classes']) * 100, 2) : 0;
                                ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars(\$a['course_code']) ?></strong></td>
                                    <td><?= htmlspecialchars(\$a['total_classes']) ?></td>
                                    <td><?= htmlspecialchars(\$a['present_classes']) ?></td>
                                    <td>
                                        <span style="color: <?= \$percentage >= 75 ? 'green' : 'red' ?>; font-weight: bold;">
                                            <?= \$percentage ?>%
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
HTML;

file_put_contents('app/Views/attendance/faculty.php', $header . "\n" . $topbar . "\n" . $faculty_main . "\n" . $footer);
file_put_contents('app/Views/attendance/student.php', $header . "\n" . $topbar . "\n" . $student_main . "\n" . $footer);
echo "Done";
