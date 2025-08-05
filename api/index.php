<?php

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Ładowanie zmiennych środowiskowych
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    // Jeśli plik .env nie istnieje, używamy wartości domyślnych
    // Zmienne środowiskowe będą ładowane z wartości domyślnych w config/settings.php
}

// Tworzenie kontenera DI
$container = new Container();

// Konfiguracja aplikacji
$settings = require __DIR__ . '/../config/settings.php';
$container->set('settings', $settings);

// Rejestracja serwisów
(require __DIR__ . '/../config/dependencies.php')($container);

// Tworzenie aplikacji Slim
AppFactory::setContainer($container);
$app = AppFactory::create();

// Middleware
(require __DIR__ . '/../config/middleware.php')($app);

// Routing
(require __DIR__ . '/../config/routes.php')($app);

// Uruchomienie aplikacji
$app->run();
