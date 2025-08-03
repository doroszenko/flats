<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\FlatController;
use App\Controllers\UtilityBillController;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Strona główna - przekierowanie do logowania lub mieszkań
    $app->get('/', function ($request, $response) {
        if (isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }
        return $response->withHeader('Location', '/login')->withStatus(302);
    });

    // Publiczne trasy (bez autoryzacji)
    $app->group('', function (RouteCollectorProxy $group) {
        $group->get('/login', [AuthController::class, 'loginForm']);
        $group->post('/login', [AuthController::class, 'login']);
        $group->get('/logout', [AuthController::class, 'logout']);
        $group->post('/logout', [AuthController::class, 'logout']);
    });

    // Chronione trasy (wymagają autoryzacji)
    $app->group('', function (RouteCollectorProxy $group) {
        // Zarządzanie mieszkaniami
        $group->get('/flats', [FlatController::class, 'index']);
        $group->get('/flats/create', [FlatController::class, 'createForm']);
        $group->post('/flats', [FlatController::class, 'create']);
        $group->get('/flats/{id}', [FlatController::class, 'show']);
        $group->get('/flats/{id}/edit', [FlatController::class, 'editForm']);
        $group->post('/flats/{id}/update', [FlatController::class, 'update']);
        $group->post('/flats/{id}/delete', [FlatController::class, 'delete']);
        
        // Rozliczenia
        $group->get('/flats/{flatId}/bills', [UtilityBillController::class, 'index']);
        $group->get('/flats/{flatId}/bills/create', [UtilityBillController::class, 'createForm']);
        $group->post('/flats/{flatId}/bills', [UtilityBillController::class, 'create']);
        $group->get('/flats/{flatId}/bills/{billId}', [UtilityBillController::class, 'show']);
        $group->get('/flats/{flatId}/bills/{billId}/edit', [UtilityBillController::class, 'editForm']);
        $group->post('/flats/{flatId}/bills/{billId}/update', [UtilityBillController::class, 'update']);
        $group->post('/flats/{flatId}/bills/{billId}/delete', [UtilityBillController::class, 'delete']);
        $group->post('/flats/{flatId}/bills/{billId}/confirm', [UtilityBillController::class, 'confirm']);
        
        // Historia i wykresy
        $group->get('/flats/{flatId}/history', [UtilityBillController::class, 'history']);
        $group->get('/api/flats/{flatId}/chart-data', [UtilityBillController::class, 'chartData']);
        
    })->add(AuthMiddleware::class);
};
