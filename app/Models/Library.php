<?php
// app/Models/Library.php

class Library extends Model {
    public function searchBooks($query = '') {
        $sql = "SELECT * FROM library_books";
        if ($query) {
            $sql .= " WHERE title LIKE ? OR author LIKE ? OR subject LIKE ? OR isbn LIKE ?";
            $stmt = $this->db->prepare($sql);
            $val = "%$query%";
            $stmt->execute([$val, $val, $val, $val]);
            return $stmt->fetchAll();
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function autoCalculateFines() {
        // Calculate fine: Rs 10 per day overdue
        $this->db->query("
            UPDATE library_issues 
            SET fine_amount = DATEDIFF(CURDATE(), due_date) * 10,
                status = 'overdue'
            WHERE status = 'issued' AND CURDATE() > due_date
        ");
    }

    public function getStudentIssuedBooks($student_id) {
        $this->autoCalculateFines();
        $stmt = $this->db->prepare("
            SELECT li.*, lb.title, lb.author, lb.resource_type, lb.digital_link 
            FROM library_issues li 
            JOIN library_books lb ON li.book_id = lb.id 
            WHERE li.student_id = :student_id 
            ORDER BY li.issue_date DESC
        ");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    public function issueBook($student_id, $book_id) {
        $this->db->beginTransaction();
        try {
            $stmt1 = $this->db->prepare("
                INSERT INTO library_issues (book_id, student_id, issue_date, due_date) 
                VALUES (:book_id, :student_id, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY))
            ");
            $stmt1->execute(['book_id' => $book_id, 'student_id' => $student_id]);
            
            $stmt2 = $this->db->prepare("UPDATE library_books SET available_copies = available_copies - 1 WHERE id = :book_id AND available_copies > 0");
            $stmt2->execute(['book_id' => $book_id]);
            
            if ($stmt2->rowCount() == 0) {
                throw new Exception("No copies available.");
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
