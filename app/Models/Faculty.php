<?php
// app/Models/Faculty.php

class Faculty extends Model {
    public function getAllFaculty() {
        $stmt = $this->db->query("
            SELECT f.*, u.email, u.status 
            FROM faculty f 
            JOIN users u ON f.user_id = u.id 
            ORDER BY f.first_name ASC
        ");
        return $stmt->fetchAll();
    }

    public function createFaculty($data) {
        try {
            $this->db->beginTransaction();

            // Insert User
            $stmtUser = $this->db->prepare("
                INSERT INTO users (username, email, password_hash, role) 
                VALUES (:username, :email, :password_hash, 'faculty')
            ");
            $password_hash = password_hash($data['employee_id'], PASSWORD_DEFAULT);
            $stmtUser->execute([
                'username' => strtolower($data['employee_id']),
                'email' => $data['email'],
                'password_hash' => $password_hash
            ]);
            
            $user_id = $this->db->lastInsertId();

            // Insert Faculty
            $stmtFaculty = $this->db->prepare("
                INSERT INTO faculty (user_id, employee_id, first_name, last_name, department, designation) 
                VALUES (:user_id, :employee_id, :first_name, :last_name, :department, :designation)
            ");
            $stmtFaculty->execute([
                'user_id' => $user_id,
                'employee_id' => $data['employee_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'department' => $data['department'],
                'designation' => $data['designation']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteFaculty($user_id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :user_id AND role = 'faculty'");
        return $stmt->execute(['user_id' => $user_id]);
    }
}
