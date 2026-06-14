<?php
// File: app/Core/Model.php

namespace App\Core;

class Model {
    // Mendapatkan instance database secara instan
    protected static function db() {
        return Database::getInstance();
    }

    // Melakukan query dan mengembalikan statement PDO
    protected static function query($sql, $params = []) {
        return self::db()->query($sql, $params);
    }
}
