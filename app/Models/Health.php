<?php
// app/Models/Health.php

class Health extends Model {
    public function getRecordByStudent($student_id) {
        $stmt = $this->db->prepare("SELECT * FROM health_records WHERE student_id = :student_id");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetch();
    }

    public function saveRecord($student_id, $data) {
        $existing = $this->getRecordByStudent($student_id);
        
        if ($existing) {
            $stmt = $this->db->prepare("
                UPDATE health_records 
                SET blood_group = :bg, medical_conditions = :mc, allergies = :al, emergency_contact = :ec, emergency_phone = :ep 
                WHERE student_id = :student_id
            ");
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO health_records (student_id, blood_group, medical_conditions, allergies, emergency_contact, emergency_phone) 
                VALUES (:student_id, :bg, :mc, :al, :ec, :ep)
            ");
        }

        return $stmt->execute([
            'student_id' => $student_id,
            'bg' => $data['blood_group'],
            'mc' => $data['medical_conditions'],
            'al' => $data['allergies'],
            'ec' => $data['emergency_contact'],
            'ep' => $data['emergency_phone']
        ]);
    }
}
