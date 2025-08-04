<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class AuthController
{
    private Twig $twig;
    private AuthService $authService;

    public function __construct(Twig $twig, AuthService $authService)
    {
        $this->twig = $twig;
        $this->authService = $authService;
    }

    public function loginForm(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->authService->isAuthenticated()) {
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        $data = [
            'error' => $_SESSION['error'] ?? null,
        ];

        unset($_SESSION['error']);

        return $this->twig->render($response, 'auth/login.twig', $data);
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // CSRF Protection
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Nieprawidłowy token CSRF';
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if ($this->authService->authenticate($username, $password)) {
            $this->authService->regenerateSession();
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        $_SESSION['error'] = 'Nieprawidłowy login lub hasło';
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->authService->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    private function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
