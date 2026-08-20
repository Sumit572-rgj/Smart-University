<?php
// app/Models/Timetable.php

class Timetable extends Model {
    public function getStudentTimetable($department, $semester) {
        $stmt = $this->db->prepare("
            SELECT t.*, f.first_name, f.last_name 
            FROM timetable t 
            LEFT JOIN faculty f ON t.faculty_id = f.id 
            WHERE t.department = :department AND t.semester = :semester 
            ORDER BY t.day_of_week, t.start_time
        ");
        $stmt->execute(['department' => $department, 'semester' => $semester]);
        
        $results = $stmt->fetchAll();
        $formatted = [];
        foreach ($results as $row) {
            $formatted[$row['day_of_week']][] = $row;
        }
        return $formatted;
    }
}
