<?php
// app/Controllers/TransportController.php

class TransportController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        if ($user['role'] === 'admin') {
            $this->admin();
        } elseif ($user['role'] === 'student') {
            $this->student();
        } else {
            $this->redirect('/dashboard');
        }
    }

    public function admin() {
        $user = JWT::getToken();
        $transportModel = $this->model('Transport');

        $data = [
            'user' => $user,
            'title' => 'Transport Management - Admin',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] == 'create_route') {
                $routeData = [
                    'route_name' => filter_input(INPUT_POST, 'route_name', FILTER_SANITIZE_STRING),
                    'bus_number' => filter_input(INPUT_POST, 'bus_number', FILTER_SANITIZE_STRING),
                    'driver_name' => filter_input(INPUT_POST, 'driver_name', FILTER_SANITIZE_STRING),
                    'driver_phone' => filter_input(INPUT_POST, 'driver_phone', FILTER_SANITIZE_STRING),
                    'capacity' => filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT),
                    'license_number' => filter_input(INPUT_POST, 'license_number', FILTER_SANITIZE_STRING),
                    'maintenance_date' => filter_input(INPUT_POST, 'maintenance_date', FILTER_SANITIZE_STRING)
                ];

                if ($transportModel->createRoute($routeData)) {
                    $data['success'] = 'Route created successfully.';
                } else {
                    $data['error'] = 'Failed to create route.';
                }
            } elseif (isset($_POST['action']) && $_POST['action'] == 'send_alert') {
                $route_id = $_POST['route_id'];
                $message = filter_input(INPUT_POST, 'alert_message', FILTER_SANITIZE_STRING);
                
                // Fetch all students allocated to this route
                $stmt = $transportModel->db->prepare("SELECT student_id FROM transport_allocations WHERE route_id = ? AND status = 'active'");
                $stmt->execute([$route_id]);
                $allocations = $stmt->fetchAll();
                
                // Get users corresponding to these students to send notifications
                $notifStmt = $transportModel->db->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'transport')");
                foreach ($allocations as $alloc) {
                    $stuStmt = $transportModel->db->prepare("SELECT user_id FROM students WHERE id = ?");
                    $stuStmt->execute([$alloc['student_id']]);
                    $stu = $stuStmt->fetch();
                    if ($stu) {
                        $notifStmt->execute([$stu['user_id'], 'Bus Alert', $message]);
                    }
                }
                
                $data['success'] = 'Alert dispatched to all students on this route.';
            } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_route') {
                if ($transportModel->deleteRoute($_POST['route_id'])) {
                    $data['success'] = 'Route deleted successfully.';
                } else {
                    $data['error'] = 'Failed to delete route.';
                }
            } elseif (isset($_POST['action']) && $_POST['action'] == 'update_route') {
                $route_id = $_POST['route_id'];
                $routeData = [
                    'route_name' => filter_input(INPUT_POST, 'route_name', FILTER_SANITIZE_STRING),
                    'bus_number' => filter_input(INPUT_POST, 'bus_number', FILTER_SANITIZE_STRING),
                    'driver_name' => filter_input(INPUT_POST, 'driver_name', FILTER_SANITIZE_STRING),
                    'driver_phone' => filter_input(INPUT_POST, 'driver_phone', FILTER_SANITIZE_STRING),
                    'capacity' => filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT),
                    'license_number' => filter_input(INPUT_POST, 'license_number', FILTER_SANITIZE_STRING),
                    'maintenance_date' => filter_input(INPUT_POST, 'maintenance_date', FILTER_SANITIZE_STRING)
                ];
                if ($transportModel->updateRoute($route_id, $routeData)) {
                    $data['success'] = 'Route updated successfully.';
                } else {
                    $data['error'] = 'Failed to update route.';
                }
            } elseif (isset($_POST['action']) && $_POST['action'] == 'allocate_student') {
                $enrollment_no = filter_input(INPUT_POST, 'enrollment_no', FILTER_SANITIZE_STRING);
                $route_id = filter_input(INPUT_POST, 'route_id', FILTER_SANITIZE_NUMBER_INT);
                $boarding_point = filter_input(INPUT_POST, 'boarding_point', FILTER_SANITIZE_STRING);

                $student = $transportModel->getStudentByEnrollmentNo($enrollment_no);
                
                if ($student) {
                    if ($transportModel->allocateTransport($student['id'], $route_id, $boarding_point)) {
                        $data['success'] = 'Student allocated to route successfully.';
                    } else {
                        $data['error'] = 'Failed to allocate student. Bus might be full or student already has an active allocation.';
                    }
                } else {
                    $data['error'] = 'Student with given enrollment number not found.';
                }
            }
        }

        $data['routes'] = $transportModel->getAllRoutes();
        $this->view('transport/admin', $data);
    }

    public function student() {
        $user = JWT::getToken();
        $transportModel = $this->model('Transport');
        $studentModel = $this->model('Student');
        
        $student = $studentModel->getStudentByUserId($user['id']);
        if (!$student) {
            die("Student profile not found.");
        }

        $data = [
            'user' => $user,
            'title' => 'My Transport',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'book') {
            $route_id = $_POST['route_id'];
            $boarding_point = filter_input(INPUT_POST, 'boarding_point', FILTER_SANITIZE_STRING);
            
            if ($transportModel->allocateTransport($student['id'], $route_id, $boarding_point)) {
                $data['success'] = 'Transport seat booked successfully.';
            } else {
                $data['error'] = 'Failed to book seat. Bus might be full or you already have an allocation.';
            }
        }

        $data['routes'] = $transportModel->getAllRoutes();
        $data['allocation'] = $transportModel->getStudentAllocation($student['id']);

        $this->view('transport/student', $data);
    }
}
