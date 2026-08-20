<?php
// app/Controllers/MessageController.php

class MessageController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $db = (new Model())->db;

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
            $msg_id = $_POST['delete_message'];
            
            $stmt = $db->prepare("SELECT subject, body, created_at, sender_id FROM messages WHERE id = ?");
            $stmt->execute([$msg_id]);
            $msg = $stmt->fetch();
            
            if ($msg && $msg['sender_id'] == $user['id']) {
                // Sender is deleting their sent message. Delete all identical grouped copies (like broadcasts/CCs)
                $delStmt = $db->prepare("DELETE FROM messages WHERE sender_id = ? AND subject = ? AND body = ? AND created_at = ?");
                $delStmt->execute([$user['id'], $msg['subject'], $msg['body'], $msg['created_at']]);
            } else {
                // Receiver is deleting from their inbox
                $delStmt = $db->prepare("DELETE FROM messages WHERE id = ? AND receiver_id = ?");
                $delStmt->execute([$msg_id, $user['id']]);
            }
            
            $this->redirect('/message?success=Message+deleted');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
                        $broadcast_group = $_POST['broadcast_group'] ?? '';
            $receiver_username = trim($_POST['receiver_username'] ?? '');
            
            if (!empty($broadcast_group)) {
                $receiver_username = $broadcast_group; // Treat as broadcast
            } else {
                // If they typed "all", "student", or "faculty" into the specific username text box, handle it smartly!
                $lower_username = strtolower($receiver_username);
                if ($lower_username === 'all') {
                    $receiver_username = '@GROUP:ALL';
                } else if ($lower_username === 'student' || $lower_username === 'students') {
                    $receiver_username = '@GROUP:STUDENTS';
                } else if ($lower_username === 'faculty') {
                    $receiver_username = '@GROUP:FACULTY';
                } else if ($lower_username === 'parent' || $lower_username === 'parents') {
                    $receiver_username = '@GROUP:PARENTS';
                }
            }
            $subject = trim($_POST['subject']);
            $body = trim($_POST['body']);
            $attachment = null;

            // Handle file upload
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $filename = time() . "_" . basename($_FILES["attachment"]["name"]);
                move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_dir . $filename);
                $attachment = BASE_URL . "uploads/" . $filename;
            }

            // Broadcast Logic (Only Admin/Faculty can broadcast)
            if (str_starts_with($receiver_username, '@GROUP:')) {
                if ($user['role'] !== 'admin' && $user['role'] !== 'faculty') {
                    $this->redirect('/message?error=Only+staff+can+broadcast');
                    exit;
                }
                
                $group = str_replace('@GROUP:', '', $receiver_username);
                $query = "SELECT id FROM users";
                if ($group === 'STUDENTS') $query .= " WHERE role = 'student'";
                else if ($group === 'FACULTY') $query .= " WHERE role = 'faculty'";
                else if ($group === 'PARENTS') $query .= " WHERE role = 'parent'";

                $recipients = $db->query($query)->fetchAll();
                $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body, attachment_path) VALUES (?, ?, ?, ?, ?)");
                
                $db->beginTransaction();
                foreach($recipients as $rec) {
                    $stmt->execute([$user['id'], $rec['id'], "[BROADCAST] $subject", $body, $attachment]);
                }
                $db->commit();
                
                error_log("PUSH NOTIFICATION: New Broadcast Message sent to $group");
                $this->redirect('/message?success=Broadcast+sent');
                exit;
            }

            // Direct Message Logic
            $recvStmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $recvStmt->execute([$receiver_username]);
            $receiver_id = $recvStmt->fetchColumn();

            if ($receiver_id) {
                $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body, attachment_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user['id'], $receiver_id, $subject, $body, $attachment]);
                error_log("PUSH NOTIFICATION: New DM to $receiver_username");
                $this->redirect('/message?success=Message+sent');
            } else {
                $this->redirect('/message?error=User+not+found');
            }
        }

        // Create Forum Logic
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_forum'])) {
            $title = trim($_POST['title']);
            $desc = trim($_POST['description']);
            $stmt = $db->prepare("INSERT INTO forums (title, description, created_by) VALUES (?, ?, ?)");
            $stmt->execute([$title, $desc, $user['id']]);
            $this->redirect('/message?success=Forum+Created');
        }

        // Post to Forum
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_forum'])) {
            $forum_id = $_POST['forum_id'];
            $content = trim($_POST['content']);
            $attachment = null;
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $filename = time() . "_" . basename($_FILES["attachment"]["name"]);
                move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_dir . $filename);
                $attachment = BASE_URL . "uploads/" . $filename;
            }
            $stmt = $db->prepare("INSERT INTO forum_posts (forum_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$forum_id, $user['id'], $content]);
            $this->redirect('/message?success=Posted+to+Forum');
        }

        // Mark as read logic
        if (isset($_GET['read'])) {
            $readStmt = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = ? AND receiver_id = ?");
            $readStmt->execute([$_GET['read'], $user['id']]);
            $this->redirect('/message');
        }

        $data = [
            'user' => $user,
            'title' => 'Internal Messaging & Forums',
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];

        // Fetch received messages
        $data['received'] = $db->query("
            SELECT m.*, u.username as sender_name 
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE m.receiver_id = {$user['id']} 
            ORDER BY m.created_at DESC
        ")->fetchAll();

        // Fetch sent messages
        $data['sent'] = $db->query("
            SELECT m.*, u.username as receiver_name 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = {$user['id']} 
            ORDER BY m.created_at DESC
        ")->fetchAll();

        // Fetch Forums
        $data['forums'] = $db->query("
            SELECT f.*, u.username as creator_name, (SELECT COUNT(*) FROM forum_posts WHERE forum_id = f.id) as post_count 
            FROM forums f JOIN users u ON f.created_by = u.id ORDER BY f.created_at DESC
        ")->fetchAll();

        // Fetch Posts for active forum
        $data['active_forum'] = $_GET['forum_id'] ?? null;
        if ($data['active_forum']) {
            $stmt = $db->prepare("SELECT p.*, u.username as author_name FROM forum_posts p JOIN users u ON p.user_id = u.id WHERE p.forum_id = ? ORDER BY p.created_at ASC");
            $stmt->execute([$data['active_forum']]);
            $data['forum_posts'] = $stmt->fetchAll();
        }

        $this->view('message/index', $data);
    }
}
