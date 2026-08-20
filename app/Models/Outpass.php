<?php
// app/Models/Outpass.php

class Outpass extends Model {
    public function createRequest($student_id, $leave_date, $return_date, $reason, $destination) {
        $stmt = $this->db->prepare("INSERT INTO outpass (student_id, leave_date, return_date, reason, destination, status) VALUES (:student_id, :leave_date, :return_date, :reason, :destination, 'pending_faculty')");
        return $stmt->execute([
            'student_id' => $student_id,
            'leave_date' => $leave_date,
            'return_date' => $return_date,
            'reason' => $reason,
            'destination' => $destination
        ]);
    }

    public function getRequestsByStudent($student_id) {
        $stmt = $this->db->prepare("SELECT * FROM outpass WHERE student_id = :student_id ORDER BY created_at DESC");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    public function getPendingRequestsByStatus($status) {
        $stmt = $this->db->prepare("
            SELECT o.*, s.first_name, s.last_name, s.enrollment_no 
            FROM outpass o 
            JOIN students s ON o.student_id = s.id 
            WHERE o.status = :status
            ORDER BY o.created_at ASC
        ");
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $new_status) {
        $stmt = $this->db->prepare("UPDATE outpass SET status = :status WHERE id = :id");
        return $stmt->execute([
            'status' => $new_status,
            'id' => $id
        ]);
    }

    public function updateStatusAndQR($id, $new_status, $qr_code) {
        $stmt = $this->db->prepare("UPDATE outpass SET status = :status, qr_code = :qr_code WHERE id = :id");
        return $stmt->execute([
            'status' => $new_status,
            'qr_code' => $qr_code,
            'id' => $id
        ]);
    }
}
