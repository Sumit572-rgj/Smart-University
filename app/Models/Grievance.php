<?php
// app/Models/Grievance.php

class Grievance extends Model {
    public function submitGrievance($student_id, $category, $subject, $description) {
        $stmt = $this->db->prepare("
            INSERT INTO grievances (student_id, category, subject, description) 
            VALUES (:student_id, :category, :subject, :description)
        ");
        return $stmt->execute([
            'student_id' => $student_id, // Can be NULL for anonymous
            'category' => $category,
            'subject' => $subject,
            'description' => $description
        ]);
    }
    
    public function getStudentGrievances($student_id) {
        $stmt = $this->db->prepare("SELECT * FROM grievances WHERE student_id = :student_id ORDER BY created_at DESC");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }
}
