<?php
// app/Controllers/LeaderboardController.php

class LeaderboardController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        // Mock leaderboard data
        $leaders = [
            ['name' => 'Alice Johnson', 'dept' => 'CSE', 'score' => 985, 'badge' => '🏆 Top Coder'],
            ['name' => 'Bob Smith', 'dept' => 'ECE', 'score' => 950, 'badge' => '⭐ Perfect Attendance'],
            ['name' => 'Charlie Davis', 'dept' => 'MECH', 'score' => 920, 'badge' => '📚 Bookworm'],
            ['name' => 'Diana Prince', 'dept' => 'IT', 'score' => 890, 'badge' => '🎓 Fast Learner']
        ];

        $data = ['user' => $user, 'title' => 'Student Leaderboard', 'leaders' => $leaders];
        $this->view('leaderboard/index', $data);
    }
}
