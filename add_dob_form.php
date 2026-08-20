<?php
// 1. Inject DOB into the Add Student form
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/index.php';
$c = file_get_contents($f);

$pattern = '/<div>\s*<label class="block text-sm mb-1">First Name<\/label>/';
$newInput = <<<'HTML'
                        <div>
                            <label class="block text-sm mb-1">Date of Birth</label>
                            <input type="date" name="dob" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">First Name</label>
HTML;

if (!strpos($c, 'name="dob"')) {
    $c = preg_replace($pattern, $newInput, $c);
    file_put_contents($f, $c);
}

// 2. Update Student Model to save DOB
$f2 = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c2 = file_get_contents($f2);

$insertPattern = '/INSERT INTO students \(user_id, enrollment_no, first_name, last_name, department, batch, phone, admission_status, current_semester, cgpa, section\) VALUES \(:user_id, :enrollment_no, :first_name, :last_name, :department, :batch, :phone, :admission_status, :current_semester, :cgpa, :section\)/';
$newInsert = "INSERT INTO students (user_id, enrollment_no, first_name, last_name, dob, department, batch, phone, admission_status, current_semester, cgpa, section) VALUES (:user_id, :enrollment_no, :first_name, :last_name, :dob, :department, :batch, :phone, :admission_status, :current_semester, :cgpa, :section)";

if (!strpos($c2, ':dob')) {
    $c2 = preg_replace($insertPattern, $newInsert, $c2);
    
    // Also inject the array mapping
    $bindPattern = "/'last_name' => \\\$data\['last_name'\],/";
    $newBind = "'last_name' => \$data['last_name'],\n                  'dob' => \$data['dob'] ?? '2000-01-01',";
    $c2 = preg_replace($bindPattern, $newBind, $c2);
    
    file_put_contents($f2, $c2);
}

echo "Added DOB to registration logic.\n";
