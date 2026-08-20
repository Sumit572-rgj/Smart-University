<?php
// app/Models/Student.php

class Student extends Model {
        public function getStudentsByDepartment($dept) {
        $stmt = $this->db->prepare("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.department = ? ORDER BY s.enrollment_no DESC");
        $stmt->execute([$dept]);
        return $stmt->fetchAll();
    }

    public function getAllStudents() {
        $stmt = $this->db->query("
            SELECT s.*, u.email, u.status 
            FROM students s 
            JOIN users u ON s.user_id = u.id 
            ORDER BY s.enrollment_no DESC
        ");
        return $stmt->fetchAll();
    }

    public function getStudentByUserId($user_id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.email 
            FROM students s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch();
    }

    public function createStudent($data) {
        try {
            $this->db->beginTransaction();

            // Insert User
            $stmtUser = $this->db->prepare("
                INSERT INTO users (username, email, password_hash, role) 
                VALUES (:username, :email, :password_hash, 'student')
            ");
            $password_hash = password_hash($data['enrollment_no'], PASSWORD_DEFAULT); // Default pass is enrollment no
            $stmtUser->execute([
                'username' => strtolower($data['enrollment_no']),
                'email' => $data['email'],
                'password_hash' => $password_hash
            ]);
            
            $user_id = $this->db->lastInsertId();

            // Insert Student
            $stmtStudent = $this->db->prepare("
                INSERT INTO students (user_id, enrollment_no, first_name, last_name, dob, department, batch, phone, admission_status, current_semester, cgpa, section) VALUES (:user_id, :enrollment_no, :first_name, :last_name, :dob, :department, :batch, :phone, :admission_status, :current_semester, :cgpa, :section)
            ");
            $stmtStudent->execute([
                'user_id' => $user_id,
                'enrollment_no' => $data['enrollment_no'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                  'dob' => $data['dob'] ?? '2000-01-01',
                'department' => $data['department'],
                'batch' => $data['batch'],
                'phone' => $data['phone'],
                'admission_status' => $data['admission_status'],
                'current_semester' => $data['current_semester'],
                'cgpa' => $data['cgpa'],
                'section' => $data['section'] ?? null
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return $e->getMessage();
        }
    }

        public function updateStudent($user_id, $data) {
        $stmt = $this->db->prepare("UPDATE students SET 
            enrollment_no = :enrollment_no, 
            first_name = :first_name, 
            last_name = :last_name, 
            department = :department, 
            section = :section, 
            batch = :batch, 
            phone = :phone, 
            admission_status = :admission_status, 
            current_semester = :current_semester, 
            cgpa = :cgpa 
            WHERE user_id = :user_id");
            
        return $stmt->execute([
            'enrollment_no' => $data['enrollment_no'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
                  'dob' => $data['dob'] ?? '2000-01-01',
            'department' => $data['department'],
            'section' => $data['section'] ?? null,
            'batch' => $data['batch'],
            'phone' => $data['phone'],
            'admission_status' => $data['admission_status'],
            'current_semester' => $data['current_semester'],
            'cgpa' => $data['cgpa'],
            'user_id' => $user_id
        ]);
    }
    


    public function deleteStudent($user_id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :user_id AND role = 'student'");
        return $stmt->execute(['user_id' => $user_id]);
    }
}
