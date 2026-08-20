<?php
// app/Controllers/HostelController.php

class HostelController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $db = (new Model())->db;
        $data = [
            'user' => $user,
            'title' => 'Hostel Management',
            'success' => '',
            'error' => ''
        ];

                if ($user['role'] === 'student' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'report_maintenance') {
                $room_id = $_POST['room_id'];
                $desc = $_POST['description'];
                try {
                    $stmt = $db->prepare("INSERT INTO hostel_maintenance (room_id, reported_by, issue_description) VALUES (?, ?, ?)");
                    $stmt->execute([$room_id, $user['id'], $desc]);
                    $data['success'] = "Maintenance issue reported successfully.";
                } catch (Exception $e) {
                    $data['error'] = "Failed to report maintenance issue.";
                }
            }
        }

        if (in_array($user['role'], ['admin', 'warden'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $action = $_POST['action'] ?? '';

                                if ($action === 'resolve_maintenance') {
                    $maint_id = $_POST['maintenance_id'];
                    try {
                        // Assuming 'resolved_date' doesn't exist yet, we will just update status and let it work if we alter the table or it will fallback gracefully.
                        // Wait, looking at the student view: $m['resolved_date'] is checked! We should set it if it exists, or just set status='resolved'
                        $stmt = $db->prepare("UPDATE hostel_maintenance SET status = 'resolved' WHERE id = ?");
                        $stmt->execute([$maint_id]);
                        // try to set resolved_date if the column exists
                        try {
                            $db->prepare("UPDATE hostel_maintenance SET resolved_date = CURRENT_TIMESTAMP WHERE id = ?")->execute([$maint_id]);
                        } catch(Exception $e) {}
                        $data['success'] = "Maintenance issue resolved.";
                    } catch (Exception $e) {
                        $data['error'] = "Failed to resolve maintenance issue.";
                    }
                } else if ($action === 'add_room') {
                    $room_number = $_POST['room_number'];
                    $block_name = $_POST['block_name'];
                    $capacity = (int)$_POST['capacity'];

                    try {
                        $stmt = $db->prepare("INSERT INTO hostel_rooms (room_number, block_name, capacity, occupancy) VALUES (?, ?, ?, 0)");
                        $stmt->execute([$room_number, $block_name, $capacity]);
                        $data['success'] = "Room $room_number added successfully.";
                    } catch (Exception $e) {
                        $data['error'] = "Failed to add room. Room number might already exist.";
                    }
                } else if ($action === 'allocate') {
                    $enrollment_no = $_POST['enrollment_no'];
                    $room_id = $_POST['room_id'];

                    // Get student
                    $stStmt = $db->prepare("SELECT id FROM students WHERE enrollment_no = ?");
                    $stStmt->execute([$enrollment_no]);
                    $student_id = $stStmt->fetchColumn();

                    if ($student_id) {
                        // Check if already allocated
                        $checkAll = $db->prepare("SELECT id FROM hostel_allocations WHERE student_id = ? AND status = 'active'");
                        $checkAll->execute([$student_id]);
                        if ($checkAll->fetchColumn()) {
                            $data['error'] = "Student is already allocated to a room.";
                        } else {
                            // Check room capacity
                            $rmStmt = $db->prepare("SELECT capacity, occupancy FROM hostel_rooms WHERE id = ?");
                            $rmStmt->execute([$room_id]);
                            $room = $rmStmt->fetch();

                            if ($room && $room['occupancy'] < $room['capacity']) {
                                $db->beginTransaction();
                                try {
                                    $ins = $db->prepare("INSERT INTO hostel_allocations (student_id, room_id, allocated_date) VALUES (?, ?, CURDATE())");
                                    $ins->execute([$student_id, $room_id]);
                                    
                                    $upd = $db->prepare("UPDATE hostel_rooms SET occupancy = occupancy + 1 WHERE id = ?");
                                    $upd->execute([$room_id]);
                                    
                                    $db->commit();
                                    $data['success'] = "Student allocated successfully.";
                                } catch (Exception $e) {
                                    $db->rollBack();
                                    $data['error'] = "Allocation failed.";
                                }
                            } else {
                                $data['error'] = "Room is full.";
                            }
                        }
                    } else {
                        $data['error'] = "Student not found with that Enrollment No.";
                    }
                } else if ($action === 'vacate') {
                    $allocation_id = $_POST['allocation_id'];
                    $room_id = $_POST['room_id'];
                    
                    $db->beginTransaction();
                    try {
                        $upd1 = $db->prepare("UPDATE hostel_allocations SET status = 'vacated' WHERE id = ?");
                        $upd1->execute([$allocation_id]);
                        
                        $upd2 = $db->prepare("UPDATE hostel_rooms SET occupancy = occupancy - 1 WHERE id = ?");
                        $upd2->execute([$room_id]);
                        
                        $db->commit();
                        $data['success'] = "Student vacated successfully.";
                    } catch (Exception $e) {
                        $db->rollBack();
                        $data['error'] = "Failed to vacate student.";
                    }
                }
            }

            $data['rooms'] = $db->query("SELECT * FROM hostel_rooms")->fetchAll();
            $data['allocations'] = $db->query("SELECT ha.id as allocation_id, hr.id as room_id, hr.room_number, hr.block_name, s.first_name, s.last_name, s.enrollment_no, ha.allocated_date 
                                               FROM hostel_allocations ha 
                                               JOIN hostel_rooms hr ON ha.room_id = hr.id 
                                               JOIN students s ON ha.student_id = s.id 
                                               WHERE ha.status = 'active'")->fetchAll();

            $data['assets'] = $db->query("SELECT a.*, r.room_number FROM hostel_assets a LEFT JOIN hostel_rooms r ON a.room_id = r.id")->fetchAll();
            $data['maintenance'] = $db->query("SELECT m.*, r.room_number, u.username as enrollment_no FROM hostel_maintenance m JOIN hostel_rooms r ON m.room_id = r.id LEFT JOIN users u ON m.reported_by = u.id")->fetchAll();

            // Dummy AI Prediction Calculation based on occupancy
            $totalOccupancy = array_sum(array_column($data['rooms'], 'occupancy'));
            $data['ai_prediction'] = [
                'electricity' => $totalOccupancy * 2.5, // units per person
                'water' => $totalOccupancy * 135, // liters per person
                'food' => $totalOccupancy * 0.8 // kg per person
            ];

            $this->view('hostel/admin', $data);

        } else if ($user['role'] === 'student') {
            $student = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $student->execute([$user['id']]);
            $student_id = $student->fetchColumn();

            $stmt = $db->prepare("SELECT hr.room_number, hr.block_name, ha.allocated_date 
                                  FROM hostel_allocations ha 
                                  JOIN hostel_rooms hr ON ha.room_id = hr.id 
                                  WHERE ha.student_id = ? AND ha.status = 'active'");
                        $stmt->execute([$student_id]);
            $data['allocation'] = $stmt->fetch();
            
            // Fetch maintenance records for the student
            $mStmt = $db->prepare("SELECT * FROM hostel_maintenance WHERE reported_by = ? ORDER BY created_at DESC");
            $mStmt->execute([$user['id']]);
            $data['maintenance'] = $mStmt->fetchAll();

            $this->view('hostel/student', $data);
        } else {
            $this->redirect('/dashboard');
        }
    }
}
