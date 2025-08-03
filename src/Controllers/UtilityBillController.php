<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UtilityBillService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class UtilityBillController
{
    private Twig $twig;
    private UtilityBillService $billService;

    public function __construct(Twig $twig, UtilityBillService $billService)
    {
        $this->twig = $twig;
        $this->billService = $billService;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $flat = $this->billService->getFlat($flatId);

        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        $bills = $this->billService->getFlatBills($flatId);

        // Sortuj według okresu (najnowsze pierwsze)
        uasort($bills, function ($a, $b) {
            return strcmp($b['period'], $a['period']);
        });

        $data = [
            'flat' => $flat,
            'bills' => $bills,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null,
        ];

        unset($_SESSION['success'], $_SESSION['error']);

        return $this->twig->render($response, 'bills/index.twig', $data);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $billId = $args['billId'];

        $flat = $this->billService->getFlat($flatId);
        $bill = $this->billService->getBill($flatId, $billId);

        if (!$flat || !$bill) {
            $_SESSION['error'] = 'Rozliczenie nie zostało znalezione';
            return $response->withHeader('Location', "/flats/{$flatId}/bills")->withStatus(302);
        }

        $data = [
            'flat' => $flat,
            'bill' => $bill,
        ];

        return $this->twig->render($response, 'bills/show.twig', $data);
    }

    public function createForm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $flat = $this->billService->getFlat($flatId);

        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        // Pobierz stawki
        $utilityRates = $this->billService->getUtilityRates();
        
        // Pobierz poprzednie odczyty
        $previousReadings = $this->billService->getPreviousReadings($flatId);

        $data = [
            'flat' => $flat,
            'utility_rates' => $utilityRates,
            'previous_readings' => $previousReadings,
            'current_period' => date('Y-m'),
            'csrf_token' => $this->generateCsrfToken(),
        ];

        return $this->twig->render($response, 'bills/create.twig', $data);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $data = $request->getParsedBody();

        // Pobierz dane mieszkania dla walidacji
        $flat = $this->billService->getFlat($flatId);
        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        // CSRF Protection
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Nieprawidłowy token CSRF';
            return $response->withHeader('Location', "/flats/{$flatId}/bills/create")->withStatus(302);
        }

        // Walidacja
        $errors = $this->validateBillData($data, $flat);
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            return $response->withHeader('Location', "/flats/{$flatId}/bills/create")->withStatus(302);
        }

        try {
            $currentReadings = $data['readings'] ?? [];
            $fixedCosts = $data['fixed_costs'] ?? [];
            
            // Pobierz poprzednie odczyty i oblicz koszty automatycznie
            $previousReadings = $this->billService->getPreviousReadings($flatId);
            $costs = $this->billService->calculateUtilityCosts(
                $currentReadings, 
                $previousReadings, 
                $fixedCosts, 
                $flat
            );
            
            $billData = [
                'period' => $data['period'],
                'readings' => $currentReadings,
                'costs' => $costs,
                'fixed_costs' => $fixedCosts,
                'total_cost' => array_sum($costs),
                'consumption' => $this->calculateConsumption($currentReadings, $previousReadings, $flat),
                'previous_readings' => $previousReadings,
            ];

            $billId = $this->billService->createBill($flatId, $billData);

            $_SESSION['success'] = 'Rozliczenie zostało utworzone pomyślnie';
            return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}")->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas tworzenia rozliczenia: ' . $e->getMessage();
            return $response->withHeader('Location', "/flats/{$flatId}/bills/create")->withStatus(302);
        }
    }

    public function editForm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $billId = $args['billId'];

        $flat = $this->billService->getFlat($flatId);
        $bill = $this->billService->getBill($flatId, $billId);

        if (!$flat || !$bill) {
            $_SESSION['error'] = 'Rozliczenie nie zostało znalezione';
            return $response->withHeader('Location', "/flats/{$flatId}/bills")->withStatus(302);
        }

        if ($bill['status'] === 'confirmed') {
            $_SESSION['error'] = 'Nie można edytować zatwierdzonego rozliczenia';
            return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}")->withStatus(302);
        }

        // Pobierz stawki
        $utilityRates = $this->billService->getUtilityRates();
        
        // Pobierz poprzednie odczyty
        $previousReadings = $this->billService->getPreviousReadings($flatId);

        $data = [
            'flat' => $flat,
            'bill' => $bill,
            'utility_rates' => $utilityRates,
            'previous_readings' => $previousReadings,
            'csrf_token' => $this->generateCsrfToken(),
        ];

        return $this->twig->render($response, 'bills/edit.twig', $data);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $billId = $args['billId'];
        $data = $request->getParsedBody();

        // Pobierz dane mieszkania dla walidacji
        $flat = $this->billService->getFlat($flatId);
        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        // CSRF Protection
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Nieprawidłowy token CSRF';
            return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}/edit")->withStatus(302);
        }

        // Walidacja
        $errors = $this->validateBillData($data, $flat);
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}/edit")->withStatus(302);
        }

        try {
            $currentReadings = $data['readings'] ?? [];
            $fixedCosts = $data['fixed_costs'] ?? [];
            
            // Pobierz poprzednie odczyty i oblicz koszty automatycznie
            $previousReadings = $this->billService->getPreviousReadings($flatId);
            $costs = $this->billService->calculateUtilityCosts(
                $currentReadings, 
                $previousReadings, 
                $fixedCosts, 
                $flat
            );
            
            $billData = [
                'period' => $data['period'],
                'readings' => $currentReadings,
                'costs' => $costs,
                'fixed_costs' => $fixedCosts,
                'total_cost' => array_sum($costs),
                'consumption' => $this->calculateConsumption($currentReadings, $previousReadings, $flat),
                'previous_readings' => $previousReadings,
            ];

            $success = $this->billService->updateBill($flatId, $billId, $billData);

            if ($success) {
                $_SESSION['success'] = 'Rozliczenie zostało zaktualizowane pomyślnie';
                return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}")->withStatus(302);
            } else {
                $_SESSION['error'] = 'Błąd podczas aktualizacji rozliczenia';
                return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}/edit")->withStatus(302);
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas aktualizacji rozliczenia: ' . $e->getMessage();
            return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}/edit")->withStatus(302);
        }
    }

    public function confirm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $billId = $args['billId'];

        try {
            $success = $this->billService->confirmBill($flatId, $billId);

            if ($success) {
                $_SESSION['success'] = 'Rozliczenie zostało zatwierdzone i dodane do historii';
            } else {
                $_SESSION['error'] = 'Błąd podczas zatwierdzania rozliczenia';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas zatwierdzania rozliczenia: ' . $e->getMessage();
        }

        return $response->withHeader('Location', "/flats/{$flatId}/bills/{$billId}")->withStatus(302);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $billId = $args['billId'];

        try {
            $success = $this->billService->deleteBill($flatId, $billId);

            if ($success) {
                $_SESSION['success'] = 'Rozliczenie zostało usunięte pomyślnie';
            } else {
                $_SESSION['error'] = 'Błąd podczas usuwania rozliczenia';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas usuwania rozliczenia: ' . $e->getMessage();
        }

        return $response->withHeader('Location', "/flats/{$flatId}/bills")->withStatus(302);
    }

    public function history(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        $flat = $this->billService->getFlat($flatId);

        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        $history = $this->billService->getHistory($flatId);

        $data = [
            'flat' => $flat,
            'history' => $history,
        ];

        return $this->twig->render($response, 'bills/history.twig', $data);
    }

    public function chartData(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['flatId'];
        
        // Pobierz dane mieszkania dla poprawnego mapowania nazw liczników
        $flat = $this->billService->getFlat($flatId);
        if (!$flat) {
            $response->getBody()->write(json_encode(['error' => 'Mieszkanie nie zostało znalezione']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        
        $chartData = $this->billService->getChartData($flatId, $flat);

        $response->getBody()->write(json_encode($chartData));
        return $response->withHeader('Content-Type', 'application/json');
    }



    private function validateBillData(array $data, array $flat = null): array
    {
        $errors = [];

        if (empty($data['period']) || !preg_match('/^\d{4}-\d{2}$/', $data['period'])) {
            $errors[] = 'Nieprawidłowy format okresu (wymagany: YYYY-MM)';
        }

        if (isset($data['costs']) && is_array($data['costs'])) {
            foreach ($data['costs'] as $utilityId => $cost) {
                if (!is_numeric($cost) || $cost < 0) {
                    $utilityName = $this->getUtilityDisplayName($utilityId, $flat);
                    $errors[] = "Nieprawidłowa wartość kosztu dla {$utilityName}";
                }
            }
        }

        if (isset($data['fixed_costs']) && is_array($data['fixed_costs'])) {
            foreach ($data['fixed_costs'] as $utilityId => $cost) {
                if (!is_numeric($cost) || $cost < 0) {
                    $utilityName = $this->getUtilityDisplayName($utilityId, $flat);
                    $errors[] = "Nieprawidłowa wartość opłaty stałej dla {$utilityName}";
                }
            }
        }

        if (isset($data['readings']) && is_array($data['readings'])) {
            foreach ($data['readings'] as $utilityId => $reading) {
                if (!empty($reading) && (!is_numeric($reading) || $reading < 0)) {
                    $utilityName = $this->getUtilityDisplayName($utilityId, $flat);
                    $errors[] = "Nieprawidłowa wartość odczytu dla {$utilityName}";
                }
            }
        }

        return $errors;
    }

    private function calculateConsumption(array $currentReadings, array $previousReadings, array $flat): array
    {
        $consumption = [];
        
        foreach ($currentReadings as $utilityId => $currentReading) {
            if (!isset($flat['utilities'][$utilityId])) {
                continue;
            }
            
            $utility = $flat['utilities'][$utilityId];
            // Użyj poprzedniego odczytu jeśli istnieje, w przeciwnym razie stan początkowy
            $previousReading = $previousReadings[$utilityId] ?? ($utility['initial_reading'] ?? 0);
            $consumption[$utilityId] = max(0, $currentReading - $previousReading);
        }
        return $consumption;
    }

    private function calculateTotalCost(array $costs, array $fixedCosts = []): float
    {
        $totalCosts = array_sum(array_map('floatval', $costs));
        $totalFixedCosts = array_sum(array_map('floatval', $fixedCosts));
        return $totalCosts + $totalFixedCosts;
    }

    private function getUtilityDisplayName(string $utilityId, array $flat = null): string
    {
        if ($flat && isset($flat['utilities'][$utilityId])) {
            $utility = $flat['utilities'][$utilityId];
            $typeName = $this->getUtilityTypeName($utility['type']);
            
            if (!empty($utility['name'])) {
                return "{$typeName} ({$utility['name']})";
            } else {
                return $typeName;
            }
        }
        
        return $utilityId; // Fallback jeśli nie można znaleźć nazwy
    }

    private function getUtilityTypeName(string $type): string
    {
        $typeNames = [
            'gas' => 'Gaz',
            'electricity' => 'Prąd',
            'cold_water' => 'Woda zimna',
            'hot_water' => 'Woda ciepła'
        ];

        return $typeNames[$type] ?? ucfirst($type);
    }

    private function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
