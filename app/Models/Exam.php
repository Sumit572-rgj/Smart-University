<?php
// app/Models/Exam.php

class Exam extends Model {
    public function getAllExams($faculty_id = null) {
        $sql = "
            SELECT e.*, f.first_name, f.last_name, c.course_code as new_course_code, c.course_title 
            FROM exams e 
            LEFT JOIN faculty f ON e.faculty_id = f.id 
            LEFT JOIN courses c ON e.course_id = c.id
        ";
        if ($faculty_id) {
            $sql .= " WHERE e.faculty_id = :faculty_id ";
        }
        $sql .= " ORDER BY e.start_time DESC";
        $stmt = $this->db->prepare($sql);
        if ($faculty_id) {
            $stmt->execute(['faculty_id' => $faculty_id]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function createExam($faculty_id, $title, $course_id, $start_time, $duration_minutes) {
        $stmt = $this->db->prepare("
            INSERT INTO exams (faculty_id, title, course_id, start_time, duration_minutes) 
            VALUES (:faculty_id, :title, :course_id, :start_time, :duration_minutes)
        ");
        return $stmt->execute([
            'faculty_id' => $faculty_id,
            'title' => $title,
            'course_id' => $course_id,
            'start_time' => $start_time,
            'duration_minutes' => $duration_minutes
        ]);
    }

    public function getExamById($id) {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function submitResult($exam_id, $student_id, $score, $total_marks) {
        $stmt = $this->db->prepare("
            INSERT INTO exam_results (exam_id, student_id, score, total_marks) 
            VALUES (:exam_id, :student_id, :score, :total_marks)
        ");
        return $stmt->execute([
            'exam_id' => $exam_id,
            'student_id' => $student_id,
            'score' => $score,
            'total_marks' => $total_marks
        ]);
    }

    public function getResultsByStudent($student_id) {
        $stmt = $this->db->prepare("
            SELECT er.*, e.title, e.course_code 
            FROM exam_results er 
            JOIN exams e ON er.exam_id = e.id 
            WHERE er.student_id = :student_id 
            ORDER BY er.submitted_at DESC
        ");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    public function deleteExam($exam_id) {
        $stmt = $this->db->prepare("DELETE FROM exams WHERE id = :id");
        return $stmt->execute(['id' => $exam_id]);
    }

    public function getQuestions($exam_id) {
        $stmt = $this->db->prepare("SELECT * FROM exam_questions WHERE exam_id = :exam_id ORDER BY id ASC");
        $stmt->execute(['exam_id' => $exam_id]);
        return $stmt->fetchAll();
    }

    public function addQuestion($exam_id, $question_text, $opt_a, $opt_b, $opt_c, $opt_d, $correct_option, $marks = 1) {
        $stmt = $this->db->prepare("
            INSERT INTO exam_questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$exam_id, $question_text, $opt_a, $opt_b, $opt_c, $opt_d, $correct_option, $marks]);
    }

    public function deleteQuestion($question_id) {
        $stmt = $this->db->prepare("DELETE FROM exam_questions WHERE id = :id");
        return $stmt->execute(['id' => $question_id]);
    }

    public function startAttempt($student_id, $exam_id) {
        // Check if attempt exists
        $stmt = $this->db->prepare("SELECT * FROM exam_attempts WHERE student_id = ? AND exam_id = ?");
        $stmt->execute([$student_id, $exam_id]);
        $attempt = $stmt->fetch();
        if ($attempt) return $attempt['id'];

        $stmt = $this->db->prepare("INSERT INTO exam_attempts (student_id, exam_id, start_time) VALUES (?, ?, NOW())");
        $stmt->execute([$student_id, $exam_id]);
        return $this->db->lastInsertId();
    }

    public function getAttempt($student_id, $exam_id) {
        $stmt = $this->db->prepare("SELECT * FROM exam_attempts WHERE student_id = ? AND exam_id = ?");
        $stmt->execute([$student_id, $exam_id]);
        return $stmt->fetch();
    }

    public function submitExam($attempt_id, $answers) {
        // Get attempt
        $stmt = $this->db->prepare("SELECT * FROM exam_attempts WHERE id = ?");
        $stmt->execute([$attempt_id]);
        $attempt = $stmt->fetch();
        if (!$attempt || $attempt['status'] === 'completed') return false;

        $exam_id = $attempt['exam_id'];
        $questions = $this->getQuestions($exam_id);
        $score = 0;
        $total = 0;

        $this->db->beginTransaction();
        try {
            foreach ($questions as $q) {
                $q_id = $q['id'];
                $total += $q['marks'];
                $selected = $answers[$q_id] ?? null;
                $is_correct = ($selected === $q['correct_option']) ? 1 : 0;
                if ($is_correct) {
                    $score += $q['marks'];
                }
                
                $ansStmt = $this->db->prepare("INSERT INTO exam_answers (attempt_id, question_id, selected_option, is_correct) VALUES (?, ?, ?, ?)");
                $ansStmt->execute([$attempt_id, $q_id, $selected, $is_correct]);
            }

            // Mark attempt as completed
            $upd = $this->db->prepare("UPDATE exam_attempts SET end_time = NOW(), score = ?, total_marks = ?, status = 'completed' WHERE id = ?");
            $upd->execute([$score, $total, $attempt_id]);

            // Save to exam_results for backward compatibility
            $this->submitResult($exam_id, $attempt['student_id'], $score, $total);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
