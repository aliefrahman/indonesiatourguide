<?php
// File: app/Core/AuditLogger.php

namespace App\Core;

class AuditLogger {
    public static function logAction($actionType, $description = null) {
        try {
            $filePath = __DIR__ . '/../../storage/audit_logs.json';
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $userId = $_SESSION['user_id'] ?? null;
            $userName = $_SESSION['user_name'] ?? null;
            $userEmail = $_SESSION['user_email'] ?? null;
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
            $createdAt = date('Y-m-d H:i:s');

            $newLog = [
                'id' => null, // Will be generated sequentially
                'user_id' => $userId,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'action_type' => $actionType,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'description' => $description,
                'created_at' => $createdAt
            ];

            $fp = @fopen($filePath, 'c+');
            if ($fp) {
                if (flock($fp, LOCK_EX)) {
                    clearstatcache(true, $filePath);
                    $size = filesize($filePath);
                    $logs = [];
                    if ($size > 0) {
                        $content = fread($fp, $size);
                        $logs = json_decode($content, true);
                        if (!is_array($logs)) {
                            $logs = [];
                        }
                    }

                    $maxId = 0;
                    foreach ($logs as $log) {
                        if (isset($log['id']) && is_numeric($log['id'])) {
                            if ((int)$log['id'] > $maxId) {
                                $maxId = (int)$log['id'];
                            }
                        }
                    }
                    $newLog['id'] = $maxId + 1;

                    $logs[] = $newLog;

                    ftruncate($fp, 0);
                    rewind($fp);
                    fwrite($fp, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    fflush($fp);
                    flock($fp, LOCK_UN);
                }
                fclose($fp);
            }
        } catch (\Exception $e) {
            error_log("Failed to write audit log to file: " . $e->getMessage());
        }
    }
}
