<?php
// app/Models/Visitor.php

class Visitor extends Model {
    public function getActiveVisitors() {
        $stmt = $this->db->query("SELECT * FROM visitors WHERE status = 'checked_in' ORDER BY check_in DESC");
        return $stmt->fetchAll();
    }
}
