<?php
// app/Models/Course.php

class Course extends Model {
    public function getAllCourses() {
        return $this->db->query("SELECT * FROM courses ORDER BY course_code")->fetchAll();
    }

    public function createCourse($code, $title, $credits) {
        $stmt = $this->db->prepare("INSERT INTO courses (course_code, course_title, credits) VALUES (?, ?, ?)");
        return $stmt->execute([$code, $title, $credits]);
    }

    public function assignFaculty($course_id, $faculty_id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO course_faculty (course_id, faculty_id) VALUES (?, ?)");
            return $stmt->execute([$course_id, $faculty_id]);
        } catch(PDOException $e) { return false; }
    }

    public function enrollStudent($course_id, $student_id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO course_students (course_id, student_id) VALUES (?, ?)");
            return $stmt->execute([$course_id, $student_id]);
        } catch(PDOException $e) { return false; }
    }

    public function updateSyllabus($course_id, $path) {
        $stmt = $this->db->prepare("UPDATE courses SET syllabus_path = ? WHERE id = ?");
        return $stmt->execute([$path, $course_id]);
    }

    public function getCoursesByFaculty($faculty_id) {
        $stmt = $this->db->prepare("
            SELECT c.* FROM courses c 
            JOIN course_faculty cf ON c.id = cf.course_id 
            WHERE cf.faculty_id = ?
        ");
        $stmt->execute([$faculty_id]);
        return $stmt->fetchAll();
    }

    public function getCoursesByStudent($student_id) {
        $stmt = $this->db->prepare("
            SELECT c.* FROM courses c 
            JOIN course_students cs ON c.id = cs.course_id 
            WHERE cs.student_id = ?
        ");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }

    public function getEnrolledStudents($course_id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.username 
            FROM students s 
            JOIN course_students cs ON s.id = cs.student_id 
            JOIN users u ON s.user_id = u.id
            WHERE cs.course_id = ?
        ");
        $stmt->execute([$course_id]);
        return $stmt->fetchAll();
    }

    public function addTimetable($course_id, $day, $start, $end, $room) {
        $stmt = $this->db->prepare("INSERT INTO timetables (course_id, day_of_week, start_time, end_time, room_number) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$course_id, $day, $start, $end, $room]);
    }
}
