<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    // Middleware dla obsługi błędów
    $app->addErrorMiddleware(true, true, true);
    
    // Middleware dla routing
    $app->addRoutingMiddleware();
    
    // Middleware dla parsowania body
    $app->addBodyParsingMiddleware();
    
    // Middleware dla sesji
    $app->add(SessionMiddleware::class);
    
    // Middleware dla Twig
    $app->add(TwigMiddleware::createFromContainer($app));
    

};
