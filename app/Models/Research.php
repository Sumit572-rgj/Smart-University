<?php
// app/Models/Research.php

class Research extends Model {
    public function getAllPublications() {
        $stmt = $this->db->query("
            SELECT rp.*, f.first_name, f.last_name, f.department 
            FROM research_papers rp 
            JOIN faculty f ON rp.faculty_id = f.id 
            ORDER BY rp.publication_date DESC
        ");
        return $stmt->fetchAll();
    }
}
