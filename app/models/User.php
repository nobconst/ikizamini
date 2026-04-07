<?php

require_once '../core/Database.php';

class User {
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function register($name, $phone, $password) {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            
            $stmt = $this->db->prepare("
                INSERT INTO users (name, phone, password, role)
                VALUES (?, ?, ?, 'user')
            ");
            
            if ($stmt->execute([$name, $phone, $hashed_password])) {
                $user_id = $this->db->lastInsertId();
                
                // Create user access record
                $access_stmt = $this->db->prepare("
                    INSERT INTO user_access (user_id, remaining_tests)
                    VALUES (?, 0)
                ");
                $access_stmt->execute([$user_id]);
                
                return $user_id;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($phone, $password) {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE phone = ? AND status = 'active'
        ");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByPhone($phone) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function changePassword($id, $old_password, $new_password) {
        $user = $this->getById($id);
        
        if ($user && password_verify($old_password, $user['password'])) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
            return $this->update($id, ['password' => $hashed]);
        }
        return false;
    }

    public function getAllUsers($limit = 50, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM users");
        return $stmt->fetch()['count'];
    }

    public function blockUser($id) {
        return $this->update($id, ['status' => 'blocked']);
    }

    public function unblockUser($id) {
        return $this->update($id, ['status' => 'active']);
    }
}
