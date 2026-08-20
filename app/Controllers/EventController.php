<?php
// app/Controllers/EventController.php

class EventController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $eventModel = $this->model('Event');
        $data = [
            'user' => $user, 
            'title' => 'Events & Notices', 
            'events' => $eventModel->getVisibleEvents($user['role'])
        ];
        $this->view('event/index', $data);
    }

    public function admin() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        if ($user['role'] !== 'admin' && $user['role'] !== 'warden') {
            $this->redirect('/event/index');
        }

        $eventModel = $this->model('Event');
        
        $data = [
            'user' => $user,
            'title' => 'Manage Events & Notices',
            'events' => $eventModel->getAllEvents(),
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'add';
            
            if ($action === 'add') {
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $event_date = trim($_POST['event_date'] ?? '');
                $venue = trim($_POST['venue'] ?? '');
                $type = $_POST['type'] ?? 'event';
                $visibility = $_POST['visibility'] ?? 'all';
                $notify = isset($_POST['notify']) ? 1 : 0;

                if ($title && $description && $event_date && $venue) {
                    if ($eventModel->addEvent($title, $description, $event_date, $venue, $type, $visibility, $notify)) {
                        $data['success'] = 'Added successfully.';
                        if ($notify) {
                            $data['success'] .= ' Push notifications and emails have been dispatched.';
                            require_once '../app/Core/Mail.php';
                            
                            $db = (new Model())->db;
                            $emails = [];
                            
                            if ($visibility === 'all') {
                                $stmt = $db->query("SELECT email FROM users");
                                $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            } else {
                                $stmt = $db->prepare("SELECT email FROM users WHERE role = ?");
                                $stmt->execute([$visibility]);
                                $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            }
                            
                            $typeLabel = ucfirst($type);
                            $msg = "A new {$typeLabel} has been posted to the Notice Board.<br><br><strong>{$title}</strong><br>Date: {$event_date}<br>Venue: {$venue}<br><br>" . nl2br($description);
                            
                            foreach ($emails as $email) {
                                Mail::sendTemplate($email, "New {$typeLabel}: {$title}", "New {$typeLabel} Alert", $msg, 'View Notice Board', 'http://localhost/cit_ums/event/index');
                            }
                            
                            error_log("PUSH NOTIFICATION DISPATCHED: [$visibility] $title");
                        }
                        $data['events'] = $eventModel->getAllEvents();
                    } else {
                        $data['error'] = 'Failed to add.';
                    }
                } else {
                    $data['error'] = 'All fields are required.';
                }
            } elseif ($action === 'delete') {
                $event_id = $_POST['event_id'] ?? '';
                if ($event_id && $eventModel->deleteEvent($event_id)) {
                    $data['success'] = 'Deleted successfully.';
                    $data['events'] = $eventModel->getAllEvents();
                } else {
                    $data['error'] = 'Failed to delete.';
                }
            }
        }

        $this->view('event/admin', $data);
    }
}
