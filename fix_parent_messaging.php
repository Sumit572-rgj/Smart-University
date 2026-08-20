<?php
// 1. Add Messages to Parent Sidebar
$f = 'C:/xampp/htdocs/cit_ums/app/Views/parent/index.php';
$c = file_get_contents($f);
$c = str_replace('<a href="/cit_ums/parent" class="nav-link active">Parent Portal</a>', '<a href="/cit_ums/parent" class="nav-link active">Parent Portal</a>
            <a href="/cit_ums/message" class="nav-link">Messages & Notifications</a>', $c);
file_put_contents($f, $c);

// 2. Add PARENTS to MessageController Broadcast Logic
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$oldLogic = <<<PHP
                if (\$group === 'STUDENTS') \$query .= " WHERE role = 'student'";
                else if (\$group === 'FACULTY') \$query .= " WHERE role = 'faculty'";
PHP;

$newLogic = <<<PHP
                if (\$group === 'STUDENTS') \$query .= " WHERE role = 'student'";
                else if (\$group === 'FACULTY') \$query .= " WHERE role = 'faculty'";
                else if (\$group === 'PARENTS') \$query .= " WHERE role = 'parent'";
PHP;

// Also add alias for 'parent' or 'parents'
$oldAlias = <<<PHP
                } else if (\$lower_username === 'faculty') {
                    \$receiver_username = '@GROUP:FACULTY';
                }
PHP;
$newAlias = <<<PHP
                } else if (\$lower_username === 'faculty') {
                    \$receiver_username = '@GROUP:FACULTY';
                } else if (\$lower_username === 'parent' || \$lower_username === 'parents') {
                    \$receiver_username = '@GROUP:PARENTS';
                }
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
$c = str_replace($oldAlias, $newAlias, $c);
file_put_contents($f, $c);

// 3. Add PARENTS to Message Index Dropdown
$f = 'C:/xampp/htdocs/cit_ums/app/Views/message/index.php';
$c = file_get_contents($f);
$c = str_replace('<option value="@GROUP:FACULTY">Broadcast to ALL FACULTY</option>', '<option value="@GROUP:FACULTY">Broadcast to ALL FACULTY</option>
                                  <option value="@GROUP:PARENTS">Broadcast to ALL PARENTS</option>', $c);
file_put_contents($f, $c);

echo "Parent messaging fully integrated.";
