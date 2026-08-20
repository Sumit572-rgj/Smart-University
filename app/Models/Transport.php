<?php
// app/Models/Transport.php

class Transport extends Model {
    public function getAllRoutes() {
        $stmt = $this->db->query("SELECT * FROM transport_routes ORDER BY route_name");
        return $stmt->fetchAll();
    }

    public function getStudentAllocation($student_id) {
        $stmt = $this->db->prepare("
            SELECT ta.*, tr.route_name, tr.bus_number, tr.driver_name, tr.driver_phone 
            FROM transport_allocations ta 
            JOIN transport_routes tr ON ta.route_id = tr.id 
            WHERE ta.student_id = :student_id AND ta.status = 'active'
        ");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetch();
    }

    public function allocateTransport($student_id, $route_id, $boarding_point) {
        $this->db->beginTransaction();
        try {
            $stmt1 = $this->db->prepare("
                INSERT INTO transport_allocations (student_id, route_id, boarding_point, allocated_date) 
                VALUES (:student_id, :route_id, :boarding_point, CURDATE())
            ");
            $stmt1->execute(['student_id' => $student_id, 'route_id' => $route_id, 'boarding_point' => $boarding_point]);
            
            $stmt2 = $this->db->prepare("UPDATE transport_routes SET occupancy = occupancy + 1 WHERE id = :route_id AND occupancy < capacity");
            $stmt2->execute(['route_id' => $route_id]);
            
            if ($stmt2->rowCount() == 0) {
                throw new Exception("Bus is full.");
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function createRoute($data) {
        $stmt = $this->db->prepare("
            INSERT INTO transport_routes (route_name, bus_number, driver_name, driver_phone, capacity, driver_license, next_maintenance)
            VALUES (:route_name, :bus_number, :driver_name, :driver_phone, :capacity, :driver_license, :next_maintenance)
        ");
        return $stmt->execute([
            'route_name' => $data['route_name'],
            'bus_number' => $data['bus_number'],
            'driver_name' => $data['driver_name'],
            'driver_phone' => $data['driver_phone'],
            'capacity' => $data['capacity'],
            'driver_license' => $data['license_number'] ?? null,
            'next_maintenance' => $data['maintenance_date'] ?? null
        ]);
    }

    public function getStudentByEnrollmentNo($enrollment_no) {
        $stmt = $this->db->prepare("SELECT id FROM students WHERE enrollment_no = :enrollment_no");
        $stmt->execute(['enrollment_no' => $enrollment_no]);
        return $stmt->fetch();
    }

    public function deleteRoute($id) {
        $stmt = $this->db->prepare("DELETE FROM transport_routes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateRoute($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE transport_routes 
            SET route_name = :route_name, bus_number = :bus_number, driver_name = :driver_name, 
                driver_phone = :driver_phone, capacity = :capacity, driver_license = :driver_license, 
                next_maintenance = :next_maintenance 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'route_name' => $data['route_name'],
            'bus_number' => $data['bus_number'],
            'driver_name' => $data['driver_name'],
            'driver_phone' => $data['driver_phone'],
            'capacity' => $data['capacity'],
            'driver_license' => $data['license_number'] ?? null,
            'next_maintenance' => $data['maintenance_date'] ?? null
        ]);
    }
}
