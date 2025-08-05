<?php

declare(strict_types=1);

use App\Services\AuthService;
use App\Services\GladiusService;
use App\Services\UtilityBillService;
use DI\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (Container $container) {
    // Logger
    $container->set(LoggerInterface::class, function (ContainerInterface $c) {
        $settings = $c->get('settings')['logger'];
        $logger = new Logger('flats-app');
        
        // Try to find a writable log directory
        $logPath = $settings['path'];
        
        // Check if the configured path is writable
        if (!is_writable($logPath)) {
            // Try /tmp as fallback
            if (is_dir('/tmp') && is_writable('/tmp')) {
                $logPath = '/tmp';
                error_log("Using /tmp for logging");
            } else {
                // If no writable directory found, skip file logging
                error_log("No writable log directory found, skipping file handler");
                return $logger;
            }
        }
        
        // Only try to create directory if it doesn't exist and parent is writable
        if (!is_dir($logPath)) {
            $parentDir = dirname($logPath);
            if (is_writable($parentDir)) {
                try {
                    mkdir($logPath, 0755, true);
                } catch (Exception $e) {
                    error_log("Failed to create log directory {$logPath}: " . $e->getMessage());
                    // Try to use parent directory instead
                    $logPath = $parentDir;
                }
            } else {
                // If parent is not writable, try to use it directly
                $logPath = $parentDir;
            }
        }
        
        // Only add file handler if the directory is writable
        if (is_writable($logPath)) {
            try {
                $handler = new StreamHandler($logPath . '/app.log', $settings['level']);
                $logger->pushHandler($handler);
            } catch (Exception $e) {
                error_log("Failed to create log handler: " . $e->getMessage());
            }
        } else {
            error_log("Log directory is not writable: {$logPath}");
        }
        
        return $logger;
    });

    // Twig - zarejestruj pod kluczem 'view' dla middleware
    $container->set('view', function (ContainerInterface $c) {
        $settings = $c->get('settings')['twig'];
        $templatesPath = __DIR__ . '/../templates';
        
        // Handle serverless environment for Twig cache
        if (isset($settings['cache']) && $settings['cache'] !== false) {
            // Check if cache directory is writable
            if (!is_writable(dirname($settings['cache']))) {
                // Try to use /tmp for cache
                if (is_dir('/tmp') && is_writable('/tmp')) {
                    $settings['cache'] = '/tmp/twig_cache';
                    error_log("Using /tmp/twig_cache for Twig cache");
                } else {
                    // Disable cache if no writable directory available
                    $settings['cache'] = false;
                    error_log("Disabling Twig cache - no writable directory available");
                }
            }
        }
        
        $twig = Twig::create($templatesPath, $settings);
        
        // Dodanie globalnych zmiennych
        $twig->getEnvironment()->addGlobal('app_name', $c->get('settings')['app']['name']);
        
        // Dodanie funkcji CSRF token
        $twig->getEnvironment()->addFunction(new \Twig\TwigFunction('csrf_token', function() {
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            return $_SESSION['csrf_token'];
        }));
        
        return $twig;
    });
    
    // Alias dla Twig::class
    $container->set(Twig::class, function (ContainerInterface $c) {
        return $c->get('view');
    });

    // Gladius Service
    $container->set(GladiusService::class, function (ContainerInterface $c) {
        $settings = $c->get('settings')['gladius'];
        return new GladiusService($settings['db_path']);
    });

    // Auth Service
    $container->set(AuthService::class, function (ContainerInterface $c) {
        $settings = $c->get('settings')['admin'];
        return new AuthService($settings['username'], $settings['password']);
    });

    // Utility Bill Service
    $container->set(UtilityBillService::class, function (ContainerInterface $c) {
        return new UtilityBillService(
            $c->get(GladiusService::class),
            $c->get(LoggerInterface::class),
            $c->get('settings')
        );
    });
};
