<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class CsrfMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $method = $request->getMethod();
        
        // Sprawdź CSRF tylko dla POST, PUT, DELETE
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $body = $request->getParsedBody();
            $token = $body['csrf_token'] ?? '';
            
            if (!$this->validateCsrfToken($token)) {
                $response = new Response();
                $response->getBody()->write('CSRF token validation failed');
                return $response->withStatus(403);
            }
        }
        
        return $handler->handle($request);
    }
    
    private function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
