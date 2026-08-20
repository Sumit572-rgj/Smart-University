<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/exam/admin.php';
$c = file_get_contents($f);

$target = '<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">';

$publishCard = <<<'HTML'
            <!-- Publish Result Form -->
            <div class="bg-white p-6 rounded-lg shadow-sm border mb-6" style="border-color: #fbd38d; background: #fffaf0;">
                <h3 class="text-lg font-bold mb-4" style="color: #9c4221;">🎓 Publish Student Result</h3>
                <form action="/cit_ums/exam/publish" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="block text-sm mb-1 font-medium">Student Reg No</label>
                        <input type="text" name="register_number" required class="form-input w-full" placeholder="e.g. STU12345">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Exam Name</label>
                        <input type="text" name="exam_name" required class="form-input w-full" placeholder="e.g. Semester 4 Finals">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Subject</label>
                        <input type="text" name="subject" required class="form-input w-full" placeholder="e.g. Data Structures">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Marks Obtained</label>
                        <input type="number" name="marks" required class="form-input w-full" placeholder="e.g. 85">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Max Marks</label>
                        <input type="number" name="max_marks" value="100" required class="form-input w-full">
                    </div>
                    <div style="display:flex; align-items:flex-end;">
                        <button type="submit" class="w-full text-white font-bold py-2 px-4 rounded" style="background: #f97316; transition: 0.2s;">Publish Result</button>
                    </div>
                </form>
                <p class="text-xs mt-3" style="color: #c05621;">Note: The system will automatically calculate the final Grade (A+, A, B, etc.) and GPA based on standard university grading metrics.</p>
            </div>
HTML;

// Find the first instance of the target div (which is the schedule exam div)
$pos = strpos($c, $target);
if ($pos !== false) {
    $c = substr_replace($c, $publishCard . "\n" . $target, $pos, strlen($target));
    file_put_contents($f, $c);
    echo "Publish result UI injected successfully.\n";
} else {
    echo "Could not find target to inject.\n";
}
