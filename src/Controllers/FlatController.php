<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UtilityBillService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class FlatController
{
    private Twig $twig;
    private UtilityBillService $billService;

    public function __construct(Twig $twig, UtilityBillService $billService)
    {
        $this->twig = $twig;
        $this->billService = $billService;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $flats = $this->billService->getAllFlats();
        
        // Dodaj statystyki dla każdego mieszkania
        foreach ($flats as &$flat) {
            $bills = $this->billService->getFlatBills($flat['id']);
            $flat['bills_count'] = count($bills);
            $flat['pending_bills'] = count(array_filter($bills, fn($bill) => $bill['status'] === 'draft'));
            
            // Ostatnie rozliczenie
            if (!empty($bills)) {
                $lastBill = array_reduce($bills, function ($carry, $bill) {
                    return (!$carry || $bill['created_at'] > $carry['created_at']) ? $bill : $carry;
                });
                $flat['last_bill'] = $lastBill;
            }
        }

        $data = [
            'flats' => $flats,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null,
        ];

        // Wyczyść komunikaty z sesji
        unset($_SESSION['success'], $_SESSION['error']);

        return $this->twig->render($response, 'flats/index.twig', $data);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['id'];
        $flat = $this->billService->getFlat($flatId);

        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        $bills = $this->billService->getFlatBills($flatId);
        $history = $this->billService->getHistory($flatId);

        $data = [
            'flat' => $flat,
            'bills' => $bills,
            'history' => $history,
        ];

        return $this->twig->render($response, 'flats/show.twig', $data);
    }

    public function createForm(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = [
            'utilities' => $this->getAvailableUtilities(),
            'csrf_token' => $this->generateCsrfToken(),
        ];

        return $this->twig->render($response, 'flats/create.twig', $data);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        // CSRF Protection
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Nieprawidłowy token CSRF';
            return $response->withHeader('Location', '/flats/create')->withStatus(302);
        }

        // Walidacja
        $errors = $this->validateFlatData($data);
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            return $response->withHeader('Location', '/flats/create')->withStatus(302);
        }

        try {
            // Przetwórz liczniki mediów
            $utilities = [];
            if (isset($data['utilities']) && is_array($data['utilities'])) {
                foreach ($data['utilities'] as $utility) {
                    if (!empty($utility['type'])) {
                        $utilityId = uniqid();
                        $utilities[$utilityId] = [
                            'type' => $utility['type'],
                            'name' => $utility['name'] ?? '',
                            'initial_reading' => isset($utility['initial_reading']) ? (float)$utility['initial_reading'] : 0.0,
                            'fixed_cost' => isset($utility['fixed_cost']) ? (float)$utility['fixed_cost'] : 0.0,
                            'rate' => isset($utility['rate']) ? (float)$utility['rate'] : null,
                            'id' => $utilityId
                        ];
                    }
                }
            }

            $flatId = $this->billService->createFlat([
                'name' => $data['name'],
                'utilities' => $utilities,
            ]);

            $_SESSION['success'] = 'Mieszkanie zostało utworzone pomyślnie';
            return $response->withHeader('Location', "/flats/{$flatId}")->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas tworzenia mieszkania: ' . $e->getMessage();
            return $response->withHeader('Location', '/flats/create')->withStatus(302);
        }
    }

    public function editForm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['id'];
        $flat = $this->billService->getFlat($flatId);

        if (!$flat) {
            $_SESSION['error'] = 'Mieszkanie nie zostało znalezione';
            return $response->withHeader('Location', '/flats')->withStatus(302);
        }

        $data = [
            'flat' => $flat,
            'utilities' => $this->getAvailableUtilities(),
            'csrf_token' => $this->generateCsrfToken(),
        ];

        return $this->twig->render($response, 'flats/edit.twig', $data);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['id'];
        $data = $request->getParsedBody();

        // CSRF Protection
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Nieprawidłowy token CSRF';
            return $response->withHeader('Location', "/flats/{$flatId}/edit")->withStatus(302);
        }

        // Walidacja
        $errors = $this->validateFlatData($data);
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            return $response->withHeader('Location', "/flats/{$flatId}/edit")->withStatus(302);
        }

        try {
            // Przetwórz liczniki mediów
            $utilities = [];
            if (isset($data['utilities']) && is_array($data['utilities'])) {
                foreach ($data['utilities'] as $utility) {
                    if (!empty($utility['type'])) {
                        $utilityId = $utility['id'] ?? uniqid();
                        $utilities[$utilityId] = [
                            'type' => $utility['type'],
                            'name' => $utility['name'] ?? '',
                            'initial_reading' => isset($utility['initial_reading']) ? (float)$utility['initial_reading'] : 0.0,
                            'fixed_cost' => isset($utility['fixed_cost']) ? (float)$utility['fixed_cost'] : 0.0,
                            'rate' => isset($utility['rate']) ? (float)$utility['rate'] : null,
                            'id' => $utilityId
                        ];
                    }
                }
            }

            $success = $this->billService->updateFlat($flatId, [
                'name' => $data['name'],
                'utilities' => $utilities,
            ]);

            if ($success) {
                $_SESSION['success'] = 'Mieszkanie zostało zaktualizowane pomyślnie';
                return $response->withHeader('Location', "/flats/{$flatId}")->withStatus(302);
            } else {
                $_SESSION['error'] = 'Błąd podczas aktualizacji mieszkania';
                return $response->withHeader('Location', "/flats/{$flatId}/edit")->withStatus(302);
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas aktualizacji mieszkania: ' . $e->getMessage();
            return $response->withHeader('Location', "/flats/{$flatId}/edit")->withStatus(302);
        }
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flatId = $args['id'];

        try {
            $success = $this->billService->deleteFlat($flatId);

            if ($success) {
                $_SESSION['success'] = 'Mieszkanie zostało usunięte pomyślnie';
            } else {
                $_SESSION['error'] = 'Błąd podczas usuwania mieszkania';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas usuwania mieszkania: ' . $e->getMessage();
        }

        return $response->withHeader('Location', '/flats')->withStatus(302);
    }

    private function getAvailableUtilities(): array
    {
        return [
            'gas' => 'Gaz',
            'electricity' => 'Prąd',
            'cold_water' => 'Woda zimna',
            'hot_water' => 'Woda ciepła',
        ];
    }

    private function validateFlatData(array $data): array
    {
        $errors = [];

        if (empty($data['name']) || strlen(trim($data['name'])) < 2) {
            $errors[] = 'Nazwa mieszkania musi mieć co najmniej 2 znaki';
        }

        if (isset($data['utilities']) && !is_array($data['utilities'])) {
            $errors[] = 'Nieprawidłowy format mediów';
        }

        return $errors;
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
