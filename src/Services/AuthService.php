<?php

declare(strict_types=1);

namespace App\Services;

class AuthService
{
    private string $adminUsername;
    private string $adminPassword;

    public function __construct(string $adminUsername, string $adminPassword)
    {
        $this->adminUsername = $adminUsername;
        $this->adminPassword = $adminPassword;
    }

    public function authenticate(string $username, string $password): bool
    {
        // Proste uwierzytelnianie - w produkcji użyj hashowania hasła
        if ($username === $this->adminUsername && $password === $this->adminPassword) {
            $_SESSION['user_id'] = 'admin';
            $_SESSION['username'] = $username;
            $_SESSION['login_time'] = time();
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'] ?? 'admin',
            'login_time' => $_SESSION['login_time'] ?? time(),
        ];
    }

    public function regenerateSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
