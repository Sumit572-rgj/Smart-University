<?php
// app/Models/User.php

class User extends Model {
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username AND status = 'active'");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function createResetToken($email, $token) {
        $stmt = $this->db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        return $stmt->execute(['email' => $email, 'token' => $token]);
    }

    public function verifyResetToken($token) {
        $stmt = $this->db->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
        $stmt->execute(['token' => $token]);
        return $stmt->fetchColumn();
    }

    public function updatePassword($email, $new_password) {
        $hash = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
        if($stmt->execute(['hash' => $hash, 'email' => $email])) {
            // Delete tokens
            $del = $this->db->prepare("DELETE FROM password_resets WHERE email = :email");
            $del->execute(['email' => $email]);
            return true;
        }
        return false;
    }
}
