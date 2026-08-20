<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/edit.php';
$c = file_get_contents($f);

$oldHTML = <<<HTML
                                                    <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" onsubmit="return confirm('Delete this document forever?');">
                                                        <input type="hidden" name="action" value="delete_doc">
                                                        <input type="hidden" name="document_id" value="<?= \$doc['id'] ?>">
                                                        <button type="submit" class="text-red-500 hover:underline text-xs bg-red-50 px-2 py-1 rounded">Delete</button>
                                                    </form>
HTML;

$newHTML = <<<HTML
                                                    <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" style="display:inline;" onsubmit="let n = prompt('Enter new document name:', '<?= htmlspecialchars(addslashes(\$doc['document_name'])) ?>'); if(!n) return false; this.querySelector('input[name=new_name]').value = n; return true;">
                                                        <input type="hidden" name="action" value="update_doc">
                                                        <input type="hidden" name="document_id" value="<?= \$doc['id'] ?>">
                                                        <input type="hidden" name="new_name" value="">
                                                        <button type="submit" class="text-blue-500 hover:underline text-xs bg-blue-50 px-2 py-1 rounded mr-1">Rename</button>
                                                    </form>
                                                    <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this document forever?');">
                                                        <input type="hidden" name="action" value="delete_doc">
                                                        <input type="hidden" name="document_id" value="<?= \$doc['id'] ?>">
                                                        <button type="submit" class="text-red-500 hover:underline text-xs bg-red-50 px-2 py-1 rounded">Delete</button>
                                                    </form>
HTML;

$c = str_replace($oldHTML, $newHTML, $c);
file_put_contents($f, $c);

// Controller
$f2 = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c2 = file_get_contents($f2);

$oldLogic = <<<PHP
            } else if (isset(\$_POST['action']) && \$_POST['action'] === 'delete_doc') {
PHP;

$newLogic = <<<PHP
            } else if (isset(\$_POST['action']) && \$_POST['action'] === 'update_doc') {
                \$doc_id = \$_POST['document_id'];
                \$new_name = filter_input(INPUT_POST, 'new_name', FILTER_SANITIZE_STRING);
                if (\$new_name) {
                    \$stmt = \$db->prepare("UPDATE student_documents SET document_name = ? WHERE id = ?");
                    \$stmt->execute([\$new_name, \$doc_id]);
                    \$success = 'Document renamed successfully!';
                }
            } else if (isset(\$_POST['action']) && \$_POST['action'] === 'delete_doc') {
PHP;

$c2 = str_replace($oldLogic, $newLogic, $c2);
file_put_contents($f2, $c2);

echo "Rename logic added.";
