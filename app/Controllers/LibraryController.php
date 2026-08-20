<?php
// app/Controllers/LibraryController.php

class LibraryController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $libraryModel = $this->model('Library');
        
        $data = [
            'user' => $user,
            'title' => 'E-Library System',
            'success' => '',
            'error' => ''
        ];

        $query = $_GET['q'] ?? '';
        $data['books'] = $libraryModel->searchBooks($query);
        $data['search_query'] = $query;

        if (in_array($user['role'], ['admin', 'faculty', 'warden'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $action = $_POST['action'] ?? '';
                if ($action === 'add_book') {
                    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
                    $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_STRING);
                    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
                    $resource_type = $_POST['resource_type'] ?? 'physical';
                    $digital_link = filter_input(INPUT_POST, 'digital_link', FILTER_SANITIZE_URL);
                    $copies = (int)$_POST['copies'];

                    $db = $libraryModel->db;
                    $stmt = $db->prepare("INSERT INTO library_books (title, author, isbn, subject, resource_type, digital_link, available_copies, total_copies) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    try {
                        $stmt->execute([$title, $author, $isbn, $subject, $resource_type, $digital_link, $copies, $copies]);
                        $data['success'] = "Book added successfully.";
                        $data['books'] = $libraryModel->searchBooks('');
                    } catch (Exception $e) {
                        $data['error'] = "Failed to add book: " . $e->getMessage();
                    }
                } else if ($action === 'return_book') {
                    $issue_id = $_POST['issue_id'];
                    $book_id = $_POST['book_id'];
                    
                    $db = $libraryModel->db;
                    $db->beginTransaction();
                    try {
                        $upd = $db->prepare("UPDATE library_issues SET status = 'returned', return_date = CURDATE() WHERE id = ?");
                        $upd->execute([$issue_id]);
                        
                        $upd2 = $db->prepare("UPDATE library_books SET available_copies = available_copies + 1 WHERE id = ?");
                        $upd2->execute([$book_id]);
                        
                        $db->commit();
                        $data['success'] = "Book returned successfully.";
                    } catch (Exception $e) {
                        $db->rollBack();
                        $data['error'] = "Failed to return book.";
                    }
                }
            }

            $libraryModel->autoCalculateFines();
            $data['issued'] = $libraryModel->db->query("SELECT li.id, li.book_id, lb.title, s.first_name, s.last_name, s.enrollment_no, li.issue_date, li.due_date, li.status, li.fine_amount FROM library_issues li JOIN library_books lb ON li.book_id = lb.id JOIN students s ON li.student_id = s.id ORDER BY li.id DESC")->fetchAll();

            $this->view('library/admin', $data);

        } else if ($user['role'] === 'student') {
            $studentModel = $this->model('Student');
            $student = $studentModel->getStudentByUserId($user['id']);
            $student_id = $student ? $student['id'] : null;

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'issue') {
                $book_id = $_POST['book_id'];
                if ($student_id && $libraryModel->issueBook($student_id, $book_id)) {
                    $data['success'] = 'Book issued successfully. Please collect from counter.';
                    $data['books'] = $libraryModel->searchBooks($query); // Refresh
                } else {
                    $data['error'] = 'Failed to issue book. No copies available or profile error.';
                }
            }

            if ($student_id) {
                $data['issued_books'] = $libraryModel->getStudentIssuedBooks($student_id);
            } else {
                $data['issued_books'] = [];
            }
            $this->view('library/student', $data);
        } else {
            $this->redirect('/dashboard');
        }
    }
}
