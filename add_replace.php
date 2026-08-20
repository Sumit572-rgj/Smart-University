<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/edit.php';
$c = file_get_contents($f);

$oldHTML = <<<HTML
                                                    <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this document forever?');">
                                                        <input type="hidden" name="action" value="delete_doc">
                                                        <input type="hidden" name="document_id" value="<?= \$doc['id'] ?>">
                                                        <button type="submit" class="text-red-500 hover:underline text-xs bg-red-50 px-2 py-1 rounded">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
HTML;

$newHTML = <<<HTML
                                                    <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this document forever?');">
                                                        <input type="hidden" name="action" value="delete_doc">
                                                        <input type="hidden" name="document_id" value="<?= \$doc['id'] ?>">
                                                        <button type="submit" class="text-red-500 hover:underline text-xs bg-red-50 px-2 py-1 rounded">Delete</button>
                                                    </form>
                                                    <form action="/cit_ums/student/edit/<?= \$student['user_id'] ?>" method="POST" enctype="multipart/form-data" class="mt-2" style="display:flex; gap:0.5rem; align-items:center;">
                                                        <input type="hidden" name="action" value="replace_doc">
                                                        <input type="hidden" name="document_id" value="<?= \$doc['id'] ?>">
                                                        <input type="file" name="document" class="text-xs" style="width: 150px;" required>
                                                        <button type="submit" class="text-green-600 hover:underline text-xs bg-green-50 px-2 py-1 rounded">Replace</button>
                                                    </form>
                                                </td>
                                            </tr>
HTML;

$c = str_replace($oldHTML, $newHTML, $c);
file_put_contents($f, $c);

// Controller
$f2 = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c2 = file_get_contents($f2);

// I will also add str_replace for space in the filename so it doesn't cause problems even if [B] fails
$oldUpload = <<<PHP
                    \$filename = time() . '_' . basename(\$_FILES['document']['name']);
                    \$destPath = \$uploadDir . \$filename;
PHP;

$newUpload = <<<PHP
                    \$safe_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename(\$_FILES['document']['name']));
                    \$filename = time() . '_' . \$safe_name;
                    \$destPath = \$uploadDir . \$filename;
PHP;
$c2 = str_replace($oldUpload, $newUpload, $c2);

$oldLogic = <<<PHP
            } else if (isset(\$_POST['action']) && \$_POST['action'] === 'delete_doc') {
PHP;

$newLogic = <<<PHP
            } else if (isset(\$_POST['action']) && \$_POST['action'] === 'replace_doc') {
                \$doc_id = \$_POST['document_id'];
                if (isset(\$_FILES['document']) && \$_FILES['document']['error'] === UPLOAD_ERR_OK) {
                    // Fetch old to delete
                    \$stmt = \$db->prepare("SELECT file_path FROM student_documents WHERE id = ? AND student_id = ?");
                    \$stmt->execute([\$doc_id, \$student['id']]);
                    \$doc = \$stmt->fetch();
                    if (\$doc) {
                        \$path = __DIR__ . '/../../' . str_replace('/cit_ums/', '', \$doc['file_path']);
                        if (file_exists(\$path)) @unlink(\$path);
                    }
                    
                    \$uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir(\$uploadDir)) mkdir(\$uploadDir, 0777, true);
                    \$safe_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename(\$_FILES['document']['name']));
                    \$filename = time() . '_' . \$safe_name;
                    \$destPath = \$uploadDir . \$filename;
                    
                    if (move_uploaded_file(\$_FILES['document']['tmp_name'], \$destPath)) {
                        \$db->prepare("UPDATE student_documents SET document_name = ?, file_path = ? WHERE id = ?")
                           ->execute([basename(\$_FILES['document']['name']), '/cit_ums/uploads/' . \$filename, \$doc_id]);
                        \$success = 'Document replaced successfully!';
                    }
                }
            } else if (isset(\$_POST['action']) && \$_POST['action'] === 'delete_doc') {
PHP;

$c2 = str_replace($oldLogic, $newLogic, $c2);
file_put_contents($f2, $c2);

echo "Replace logic added.";
