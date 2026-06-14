<?php
// File: app/Models/UserModel.php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model {
    
    public static function find($id) {
        $stmt = self::query("SELECT * FROM users WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public static function findByEmail($email) {
        $stmt = self::query("SELECT * FROM users WHERE email = ?", [$email]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $sql = "INSERT INTO users (name, email, password, role, phone, avatar) VALUES (?, ?, ?, ?, ?, ?)";
        self::query($sql, [
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['role'] ?? 'traveler',
            $data['phone'] ?? null,
            $data['avatar'] ?? null
        ]);
        return self::db()->lastInsertId();
    }

    public static function update($id, $data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'password' && !empty($value)) {
                $fields[] = "password = ?";
                $params[] = password_hash($value, PASSWORD_BCRYPT);
            } elseif ($key !== 'password' && $key !== 'id') {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::query($sql, $params);
    }

    public static function delete($id) {
        return self::query("DELETE FROM users WHERE id = ?", [$id]);
    }

    public static function getAll($limit = 100, $offset = 0) {
        // Optimasi Memori: gunakan fetch() berurutan dalam loop (Rule #4) jika data banyak, 
        // tapi di model kita sediakan query generator atau standard fetch.
        $stmt = self::query("SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?", [(int)$limit, (int)$offset]);
        return $stmt->fetchAll();
    }
}
