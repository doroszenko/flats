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
        
        if (!is_dir($settings['path'])) {
            mkdir($settings['path'], 0755, true);
        }
        
        $handler = new StreamHandler($settings['path'] . '/app.log', $settings['level']);
        $logger->pushHandler($handler);
        
        return $logger;
    });

    // Twig - zarejestruj pod kluczem 'view' dla middleware
    $container->set('view', function (ContainerInterface $c) {
        $settings = $c->get('settings')['twig'];
        $templatesPath = __DIR__ . '/../templates';
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
