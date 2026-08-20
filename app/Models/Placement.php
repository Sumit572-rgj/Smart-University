<?php
// app/Models/Placement.php

class Placement extends Model {
    public function getAllJobs() {
        $stmt = $this->db->query("SELECT * FROM placement_jobs ORDER BY deadline ASC");
        return $stmt->fetchAll();
    }

    public function applyForJob($job_id, $student_id, $resume_link) {
        $stmt = $this->db->prepare("
            INSERT INTO placement_applications (job_id, student_id, resume_link) 
            VALUES (:job_id, :student_id, :resume_link)
        ");
        return $stmt->execute([
            'job_id' => $job_id,
            'student_id' => $student_id,
            'resume_link' => $resume_link
        ]);
    }
}
