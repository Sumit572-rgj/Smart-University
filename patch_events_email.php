<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/EventController.php';
$c = file_get_contents($f);

$oldNotify = <<<'PHP'
                        if ($notify) {
                            $data['success'] .= ' Push notifications have been dispatched.';
                            error_log("PUSH NOTIFICATION DISPATCHED: [$visibility] $title");
                        }
PHP;

$newNotify = <<<'PHP'
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
PHP;

$c = str_replace($oldNotify, $newNotify, $c);
file_put_contents($f, $c);
echo "Notice email hooks added.\n";
