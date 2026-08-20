<?php
// app/Models/Event.php

class Event extends Model {
    public function getVisibleEvents($role) {
        // Map user role to visibility group
        $visibility_groups = ["'all'"];
        if ($role === 'student') $visibility_groups[] = "'students'";
        else if ($role === 'faculty') $visibility_groups[] = "'faculty'";
        else if ($role === 'warden') $visibility_groups[] = "'warden'";
        else $visibility_groups = ["'all'", "'students'", "'faculty'", "'warden'"]; // admin sees all

        $in_clause = implode(',', $visibility_groups);
        $stmt = $this->db->query("SELECT * FROM events WHERE visibility IN ($in_clause) ORDER BY event_date ASC");
        return $stmt->fetchAll();
    }

    public function getAllEvents() {
        $stmt = $this->db->query("SELECT * FROM events ORDER BY event_date ASC");
        return $stmt->fetchAll();
    }

    public function addEvent($title, $description, $event_date, $venue, $type = 'event', $visibility = 'all', $notify = 0) {
        $stmt = $this->db->prepare("INSERT INTO events (title, description, event_date, venue, type, visibility, notify) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $event_date, $venue, $type, $visibility, $notify]);
    }

    public function deleteEvent($id) {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
