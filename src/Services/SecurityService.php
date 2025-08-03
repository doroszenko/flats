<?php

declare(strict_types=1);

namespace App\Services;

class SecurityService
{
    public function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function regenerateCsrfToken(): string
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    public function rateLimitCheck(string $identifier, int $maxAttempts = 5, int $timeWindow = 300): bool
    {
        $key = "rate_limit_{$identifier}";
        $now = time();
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => $now];
        }
        
        $data = $_SESSION[$key];
        
        // Reset if time window passed
        if ($now - $data['first_attempt'] > $timeWindow) {
            $_SESSION[$key] = ['count' => 1, 'first_attempt' => $now];
            return true;
        }
        
        // Check if limit exceeded
        if ($data['count'] >= $maxAttempts) {
            return false;
        }
        
        // Increment counter
        $_SESSION[$key]['count']++;
        return true;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function sanitizeFilename(string $filename): string
    {
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);
        
        // Limit length
        if (strlen($filename) > 100) {
            $filename = substr($filename, 0, 100);
        }
        
        return $filename;
    }

    public function validateFileUpload(array $file, array $allowedTypes = [], int $maxSize = 5242880): array
    {
        $errors = [];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Błąd podczas przesyłania pliku';
            return $errors;
        }
        
        if ($file['size'] > $maxSize) {
            $errors[] = 'Plik jest zbyt duży (maksymalnie ' . ($maxSize / 1024 / 1024) . ' MB)';
        }
        
        if (!empty($allowedTypes)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $errors[] = 'Niedozwolony typ pliku';
            }
        }
        
        return $errors;
    }

    public function logSecurityEvent(string $event, array $context = []): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'session_id' => session_id(),
            'context' => $context
        ];
        
        $logFile = 'storage/logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }

    public function detectSuspiciousActivity(array $data): bool
    {
        // Sprawdź czy dane zawierają potencjalnie niebezpieczne wzorce
        $suspiciousPatterns = [
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>.*?<\/iframe>/i',
            '/\bunion\b.*\bselect\b/i',
            '/\bselect\b.*\bfrom\b/i',
            '/\binsert\b.*\binto\b/i',
            '/\bupdate\b.*\bset\b/i',
            '/\bdelete\b.*\bfrom\b/i',
        ];
        
        $dataString = json_encode($data);
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $dataString)) {
                $this->logSecurityEvent('suspicious_activity_detected', [
                    'pattern' => $pattern,
                    'data_sample' => substr($dataString, 0, 200)
                ]);
                return true;
            }
        }
        
        return false;
    }
}
