<?php
// app/Models/Fee.php

class Fee extends Model {
    public function getAllInvoices() {
        $stmt = $this->db->query("
            SELECT f.*, s.enrollment_no, s.first_name, s.last_name 
            FROM fees f 
            JOIN students s ON f.student_id = s.id 
            ORDER BY f.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getInvoicesByStudentUserId($user_id) {
        $stmt = $this->db->prepare("
            SELECT f.*, s.enrollment_no, s.first_name, s.last_name 
            FROM fees f 
            JOIN students s ON f.student_id = s.id 
            WHERE s.user_id = :user_id 
            ORDER BY f.due_date ASC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function createInvoice($student_id, $fee_type, $amount, $due_date, $discount = 0, $fine = 0) {
        $invoice_no = strtoupper('INV-' . date('Ymd') . '-' . uniqid());
        try {
            $stmt = $this->db->prepare("
                INSERT INTO fees (student_id, invoice_no, fee_type, amount, due_date, discount_amount, fine_amount) 
                VALUES (:student_id, :invoice_no, :fee_type, :amount, :due_date, :discount, :fine)
            ");
            return $stmt->execute([
                'student_id' => $student_id,
                'invoice_no' => $invoice_no,
                'fee_type' => $fee_type,
                'amount' => $amount,
                'due_date' => $due_date,
                'discount' => $discount,
                'fine' => $fine
            ]);
        } catch (Exception $e) {
            error_log("Fee creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function markAsPaid($invoice_id, $reference_no) {
        $stmt = $this->db->prepare("UPDATE fees SET status = 'paid', reference_no = :ref WHERE id = :id");
        return $stmt->execute(['id' => $invoice_id, 'ref' => $reference_no]);
    }

    public function markAsVerified($invoice_id) {
        $stmt = $this->db->prepare("UPDATE fees SET status = 'verified' WHERE id = :id");
        return $stmt->execute(['id' => $invoice_id]);
    }

    public function deleteInvoice($invoice_id) {
        $stmt = $this->db->prepare("DELETE FROM fees WHERE id = :id");
        return $stmt->execute(['id' => $invoice_id]);
    }
}
