<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $changed = false;

        // Admin Features: Insert after Notice Board or at the end of Admin Features
        if (preg_match('/(<\?php if\(\$user\[\'role\'\] === \'admin\'\): \?>.*?)(<\?php endif; \?>)/s', $content, $matches)) {
            $adminBlock = $matches[1];
            if (strpos($adminBlock, '/cit_ums/course') === false) {
                // append before the end of the block
                $newAdminBlock = $adminBlock . '                <a href="/cit_ums/course" class="nav-link">Course Management</a>' . "\n            ";
                $content = str_replace($adminBlock, $newAdminBlock, $content);
                $changed = true;
            }
        }

        // Faculty Features:
        if (preg_match('/(<\?php if\(\$user\[\'role\'\] === \'faculty\'\): \?>.*?)(<\?php endif; \?>)/s', $content, $matches)) {
            $facultyBlock = $matches[1];
            if (strpos($facultyBlock, '/cit_ums/course') === false) {
                $newFacultyBlock = $facultyBlock . '                <a href="/cit_ums/course" class="nav-link">My Courses</a>' . "\n            ";
                $content = str_replace($facultyBlock, $newFacultyBlock, $content);
                $changed = true;
            }
        }

        // Student Features:
        if (preg_match('/(<\?php if\(\$user\[\'role\'\] === \'student\'\): \?>.*?)(<\?php endif; \?>)/s', $content, $matches)) {
            $studentBlock = $matches[1];
            if (strpos($studentBlock, '/cit_ums/course') === false) {
                $newStudentBlock = $studentBlock . '                <a href="/cit_ums/course" class="nav-link">My Courses & Timetable</a>' . "\n            ";
                $content = str_replace($studentBlock, $newStudentBlock, $content);
                $changed = true;
            }
        }

        if ($changed) {
            file_put_contents($file->getRealPath(), $content);
            echo "Added Course link to " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.";
