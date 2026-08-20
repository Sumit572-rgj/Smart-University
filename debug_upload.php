<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);

$pattern = '/if \(\$_SERVER\[\'REQUEST_METHOD\'\] === \'POST\' && isset\(\$_FILES\[\'profile_pic\'\]\)\) \{.*?\}/s';

$replacement = <<<PHP
        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_FILES['profile_pic'])) {
            \$file = \$_FILES['profile_pic'];
            if (\$file['error'] === UPLOAD_ERR_OK) {
                \$ext = pathinfo(\$file['name'], PATHINFO_EXTENSION);
                \$filename = 'profile_' . time() . '.' . \$ext;
                \$uploadDir = 'C:/xampp/htdocs/cit_ums/public/uploads/profiles/';
                if (!is_dir(\$uploadDir)) {
                    mkdir(\$uploadDir, 0777, true);
                }
                \$target = \$uploadDir . \$filename;
                if (move_uploaded_file(\$file['tmp_name'], \$target)) {
                    \$picUrl = '/cit_ums/uploads/profiles/' . \$filename;
                    \$db->query("UPDATE students SET profile_pic = '\$picUrl' WHERE id = " . \$student['id']);
                    \$this->redirect('/student/profile?success=Profile picture updated successfully.');
                } else {
                    \$data['error'] = 'Failed to move uploaded file.';
                }
            } else {
                \$data['error'] = 'Upload error code: ' . \$file['error'];
            }
        }
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "Debug added to upload logic.";
