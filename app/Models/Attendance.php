<?php
// app/Models/Attendance.php

class Attendance extends Model {
    public function getStudentAttendance($student_id) {
        $stmt = $this->db->prepare("
            SELECT course_code, 
                   COUNT(*) as total_classes, 
                   SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_classes 
            FROM attendance 
            WHERE student_id = :student_id 
            GROUP BY course_code
        ");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }
}
