<?php
// app/Models/Alumni.php

class Alumni extends Model {
    public function getAllAlumni() {
        $stmt = $this->db->query("SELECT * FROM alumni ORDER BY graduation_year DESC");
        return $stmt->fetchAll();
    }
}
