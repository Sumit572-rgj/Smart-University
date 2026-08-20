<?php
// app/Models/Analytics.php

class Analytics extends Model {
    public function getDashboardStats() {
        return [
            'total_students' => $this->db->query("SELECT COUNT(*) FROM students")->fetchColumn(),
            'total_faculty' => $this->db->query("SELECT COUNT(*) FROM faculty")->fetchColumn(),
            'total_revenue' => $this->db->query("SELECT SUM(amount) FROM fees WHERE status = 'paid'")->fetchColumn() ?: 0,
            'active_outpasses' => $this->db->query("SELECT COUNT(*) FROM outpass WHERE status = 'warden_approved' OR status = 'faculty_approved'")->fetchColumn()
        ];
    }
}
