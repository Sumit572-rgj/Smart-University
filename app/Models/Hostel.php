<?php
// app/Models/Hostel.php

class Hostel extends Model {
    public function getAllRooms() {
        $stmt = $this->db->query("SELECT * FROM hostel_rooms ORDER BY block_name, room_number");
        return $stmt->fetchAll();
    }

    public function allocateRoom($student_id, $room_id) {
        $this->db->beginTransaction();
        try {
            $stmt1 = $this->db->prepare("INSERT INTO hostel_allocations (student_id, room_id, allocated_date) VALUES (:student_id, :room_id, CURDATE())");
            $stmt1->execute(['student_id' => $student_id, 'room_id' => $room_id]);
            
            $stmt2 = $this->db->prepare("UPDATE hostel_rooms SET occupancy = occupancy + 1 WHERE id = :room_id AND occupancy < capacity");
            $stmt2->execute(['room_id' => $room_id]);
            
            if ($stmt2->rowCount() == 0) {
                throw new Exception("Room is full.");
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getStudentAllocation($student_id) {
        $stmt = $this->db->prepare("
            SELECT ha.*, hr.room_number, hr.block_name 
            FROM hostel_allocations ha 
            JOIN hostel_rooms hr ON ha.room_id = hr.id 
            WHERE ha.student_id = :student_id AND ha.status = 'active'
        ");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetch();
    }
}
