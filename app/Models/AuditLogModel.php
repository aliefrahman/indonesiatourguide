<?php
// File: app/Models/AuditLogModel.php

namespace App\Models;

use App\Core\Model;

class AuditLogModel extends Model {
    
    public static function getAll($limit = 100, $offset = 0) {
        $filePath = __DIR__ . '/../../storage/audit_logs.json';
        if (!file_exists($filePath)) {
            return [];
        }

        $logs = [];
        $fp = @fopen($filePath, 'r');
        if ($fp) {
            if (flock($fp, LOCK_SH)) {
                clearstatcache(true, $filePath);
                $size = filesize($filePath);
                if ($size > 0) {
                    $content = fread($fp, $size);
                    $logs = json_decode($content, true);
                    if (!is_array($logs)) {
                        $logs = [];
                    }
                }
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

        // Urutkan terbalik (descending berdasarkan ID/urutan waktu penulisan)
        $logs = array_reverse($logs);

        // Ambil potongan sesuai offset dan limit
        return array_slice($logs, $offset, $limit);
    }

    public static function getCount() {
        $filePath = __DIR__ . '/../../storage/audit_logs.json';
        if (!file_exists($filePath)) {
            return 0;
        }

        $count = 0;
        $fp = @fopen($filePath, 'r');
        if ($fp) {
            if (flock($fp, LOCK_SH)) {
                clearstatcache(true, $filePath);
                $size = filesize($filePath);
                if ($size > 0) {
                    $content = fread($fp, $size);
                    $logs = json_decode($content, true);
                    if (is_array($logs)) {
                        $count = count($logs);
                    }
                }
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

        return $count;
    }
}
