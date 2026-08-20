<?php
// app/Models/Discipline.php

class Discipline extends Model {
    public function getStudentRecords($student_id) {
        $stmt = $this->db->prepare("SELECT * FROM disciplinary_records WHERE student_id = :student_id ORDER BY incident_date DESC");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }
}
