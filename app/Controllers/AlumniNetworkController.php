<?php
// app/Controllers/AlumniNetworkController.php

class AlumniNetworkController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;

        // Check if user has an alumni profile, if they are an alumni role
        $alumni_profile = null;
        if ($user['role'] === 'alumni') {
            $stmt = $db->prepare("SELECT * FROM alumni_profiles WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $alumni_profile = $stmt->fetch();
            
            if (!$alumni_profile) {
                // Auto create empty profile
                $db->prepare("INSERT INTO alumni_profiles (user_id) VALUES (?)")->execute([$user['id']]);
                $stmt->execute([$user['id']]);
                $alumni_profile = $stmt->fetch();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'update_profile' && $alumni_profile) {
                $db->prepare("UPDATE alumni_profiles SET graduation_year=?, degree=?, company=?, job_title=?, linkedin_url=?, bio=?, mentor_opt_in=? WHERE id=?")
                   ->execute([
                       $_POST['graduation_year'], $_POST['degree'], $_POST['company'], 
                       $_POST['job_title'], $_POST['linkedin_url'], $_POST['bio'], 
                       isset($_POST['mentor_opt_in']) ? 1 : 0, $alumni_profile['id']
                   ]);
                $this->redirect('/alumninetwork?success=Profile+Updated');
            }
            elseif ($action === 'donate' && $alumni_profile) {
                $db->prepare("INSERT INTO alumni_donations (alumni_id, amount, purpose) VALUES (?, ?, ?)")
                   ->execute([$alumni_profile['id'], $_POST['amount'], $_POST['purpose']]);
                $this->redirect('/alumninetwork?success=Thank+you+for+your+donation!');
            }
            elseif ($action === 'post_job' && $alumni_profile) {
                $db->prepare("INSERT INTO alumni_jobs (alumni_id, company, position, description, link) VALUES (?, ?, ?, ?, ?)")
                   ->execute([$alumni_profile['id'], $_POST['company'], $_POST['position'], $_POST['description'], $_POST['link']]);
                $this->redirect('/alumninetwork?success=Job+Opportunity+Posted!');
            }
        }

        $data = [
            'user' => $user,
            'title' => 'Alumni Network & Careers',
            'success' => $_GET['success'] ?? '',
            'alumni_profile' => $alumni_profile
        ];
        
        // Fetch All Alumni Network
        $data['alumni_list'] = $db->query("SELECT p.*, u.username, u.email FROM alumni_profiles p JOIN users u ON p.user_id = u.id ORDER BY p.graduation_year DESC")->fetchAll();
        
        // Fetch Job Referrals
        $data['jobs'] = $db->query("SELECT j.*, p.company as poster_company, u.username as poster_name FROM alumni_jobs j JOIN alumni_profiles p ON j.alumni_id = p.id JOIN users u ON p.user_id = u.id ORDER BY j.created_at DESC")->fetchAll();

        // Fetch Total Donations
        $data['total_donations'] = $db->query("SELECT SUM(amount) FROM alumni_donations")->fetchColumn() ?: 0;
        
        // Fetch Upcoming Alumni Events
        $data['alumni_events'] = $db->query("SELECT * FROM events WHERE visibility IN ('all', 'alumni') AND event_date >= CURDATE() ORDER BY event_date ASC LIMIT 5")->fetchAll();

        $this->view('alumninetwork/index', $data);
    }
}
