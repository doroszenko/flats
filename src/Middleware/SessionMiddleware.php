<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Sprawdź czy jesteśmy w środowisku serverless
        $isServerless = getenv('VERCEL') !== false || 
                       getenv('AWS_LAMBDA_FUNCTION_NAME') !== false ||
                       getenv('FUNCTION_TARGET') !== false ||
                       getenv('K_SERVICE') !== false;

        // Konfiguracja sesji
        if (session_status() === PHP_SESSION_NONE) {
            // W środowisku serverless, ustaw katalog sesji na /tmp
            if ($isServerless && is_dir('/tmp') && is_writable('/tmp')) {
                ini_set('session.save_handler', 'files');
                ini_set('session.save_path', '/tmp');
            }
            
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', '0'); // Ustaw na '1' dla HTTPS
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
        }
        
        return $handler->handle($request);
    }
}
