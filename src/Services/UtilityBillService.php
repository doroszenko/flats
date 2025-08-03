<?php

declare(strict_types=1);

namespace App\Services;

use Psr\Log\LoggerInterface;

class UtilityBillService
{
    private GladiusService $gladius;
    private LoggerInterface $logger;
    private array $settings;

    public function __construct(GladiusService $gladius, LoggerInterface $logger, array $settings)
    {
        $this->gladius = $gladius;
        $this->logger = $logger;
        $this->settings = $settings;
    }

    public function createFlat(array $data): string
    {
        $id = $this->gladius->generateId();
        $flat = [
            'id' => $id,
            'name' => $data['name'],
            'utilities' => $data['utilities'] ?? [],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->gladius->save('flats', $id, $flat);
        $this->logger->info("Utworzono mieszkanie: {$flat['name']}", ['flat_id' => $id]);

        return $id;
    }

    public function updateFlat(string $id, array $data): bool
    {
        $flat = $this->gladius->load('flats', $id);
        if (!$flat) {
            return false;
        }

        $flat['name'] = $data['name'];
        $flat['utilities'] = $data['utilities'] ?? $flat['utilities'];
        $flat['updated_at'] = date('Y-m-d H:i:s');

        $result = $this->gladius->save('flats', $id, $flat);
        if ($result) {
            $this->logger->info("Zaktualizowano mieszkanie: {$flat['name']}", ['flat_id' => $id]);
        }

        return $result;
    }

    public function deleteFlat(string $id): bool
    {
        $flat = $this->gladius->load('flats', $id);
        if (!$flat) {
            return false;
        }

        // Usuń wszystkie rozliczenia mieszkania
        $bills = $this->gladius->loadAll("bills_flat_{$id}");
        foreach (array_keys($bills) as $billId) {
            $this->gladius->delete("bills_flat_{$id}", $billId);
        }

        // Usuń historię
        $this->gladius->delete('history', $id);

        // Usuń mieszkanie
        $result = $this->gladius->delete('flats', $id);
        if ($result) {
            $this->logger->info("Usunięto mieszkanie: {$flat['name']}", ['flat_id' => $id]);
        }

        return $result;
    }

    public function getFlat(string $id): ?array
    {
        return $this->gladius->load('flats', $id);
    }

    public function getAllFlats(): array
    {
        return $this->gladius->loadAll('flats');
    }

    public function createBill(string $flatId, array $data): string
    {
        $flat = $this->getFlat($flatId);
        if (!$flat) {
            throw new \Exception('Mieszkanie nie istnieje');
        }

        $billId = $this->gladius->generateId();
        $bill = [
            'id' => $billId,
            'flat_id' => $flatId,
            'period' => $data['period'], // YYYY-MM
            'readings' => $data['readings'] ?? [],
            'costs' => $data['costs'] ?? [],
            'total_cost' => $data['total_cost'] ?? 0,
            'status' => 'draft', // draft, confirmed
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->gladius->save("bills_flat_{$flatId}", $billId, $bill);
        $this->logger->info("Utworzono rozliczenie", ['flat_id' => $flatId, 'bill_id' => $billId]);

        return $billId;
    }

    public function updateBill(string $flatId, string $billId, array $data): bool
    {
        $bill = $this->gladius->load("bills_flat_{$flatId}", $billId);
        if (!$bill) {
            return false;
        }

        $bill['period'] = $data['period'] ?? $bill['period'];
        $bill['readings'] = $data['readings'] ?? $bill['readings'];
        $bill['costs'] = $data['costs'] ?? $bill['costs'];
        $bill['total_cost'] = $data['total_cost'] ?? $bill['total_cost'];
        $bill['updated_at'] = date('Y-m-d H:i:s');

        $result = $this->gladius->save("bills_flat_{$flatId}", $billId, $bill);
        if ($result) {
            $this->logger->info("Zaktualizowano rozliczenie", ['flat_id' => $flatId, 'bill_id' => $billId]);
        }

        return $result;
    }

    public function confirmBill(string $flatId, string $billId): bool
    {
        $bill = $this->gladius->load("bills_flat_{$flatId}", $billId);
        if (!$bill || $bill['status'] === 'confirmed') {
            return false;
        }

        $bill['status'] = 'confirmed';
        $bill['confirmed_at'] = date('Y-m-d H:i:s');

        // Zapisz do historii
        $this->addToHistory($flatId, $bill);

        $result = $this->gladius->save("bills_flat_{$flatId}", $billId, $bill);
        if ($result) {
            $this->logger->info("Zatwierdzono rozliczenie", ['flat_id' => $flatId, 'bill_id' => $billId]);
        }

        return $result;
    }

    public function deleteBill(string $flatId, string $billId): bool
    {
        $bill = $this->gladius->load("bills_flat_{$flatId}", $billId);
        if (!$bill) {
            return false;
        }

        // Jeśli rozliczenie jest zatwierdzone, usuń je również z historii
        if ($bill['status'] === 'confirmed') {
            $this->removeFromHistory($flatId, $billId);
        }

        $result = $this->gladius->delete("bills_flat_{$flatId}", $billId);
        if ($result) {
            $this->logger->info("Usunięto rozliczenie", ['flat_id' => $flatId, 'bill_id' => $billId, 'status' => $bill['status']]);
        }

        return $result;
    }

    public function getBill(string $flatId, string $billId): ?array
    {
        return $this->gladius->load("bills_flat_{$flatId}", $billId);
    }

    public function getFlatBills(string $flatId): array
    {
        return $this->gladius->loadAll("bills_flat_{$flatId}");
    }

    private function addToHistory(string $flatId, array $bill): void
    {
        $history = $this->gladius->load('history', $flatId) ?? ['flat_id' => $flatId, 'bills' => []];
        
        $historyEntry = [
            'bill_id' => $bill['id'],
            'period' => $bill['period'],
            'readings' => $bill['readings'],
            'costs' => $bill['costs'],
            'fixed_costs' => $bill['fixed_costs'] ?? [],
            'total_cost' => $bill['total_cost'],
            'confirmed_at' => $bill['confirmed_at'],
        ];

        $history['bills'][] = $historyEntry;

        // Sortuj według okresu (najnowsze pierwsze)
        usort($history['bills'], function ($a, $b) {
            return strcmp($b['period'], $a['period']);
        });

        $this->gladius->save('history', $flatId, $history);
    }

    private function removeFromHistory(string $flatId, string $billId): void
    {
        $history = $this->gladius->load('history', $flatId);
        if (!$history) {
            return;
        }

        // Usuń rozliczenie z historii
        $history['bills'] = array_filter($history['bills'], function ($bill) use ($billId) {
            return $bill['bill_id'] !== $billId;
        });

        // Jeśli historia jest pusta, usuń cały plik
        if (empty($history['bills'])) {
            $this->gladius->delete('history', $flatId);
        } else {
            $this->gladius->save('history', $flatId, $history);
        }
    }

    public function getHistory(string $flatId): array
    {
        $history = $this->gladius->load('history', $flatId);
        return $history ? $history['bills'] : [];
    }

    public function getChartData(string $flatId, array $flat = null): array
    {
        $history = $this->getHistory($flatId);
        
        $chartData = [
            'labels' => [],
            'datasets' => []
        ];

        if (empty($history)) {
            return $chartData;
        }

        // Przygotuj etykiety (okresy)
        $chartData['labels'] = array_column($history, 'period');

        // Przygotuj dane dla każdego medium
        $utilities = [];
        foreach ($history as $bill) {
            foreach ($bill['costs'] as $utilityId => $cost) {
                if (!isset($utilities[$utilityId])) {
                    $utilities[$utilityId] = [];
                }
                // Dodaj koszt zużycia + opłatę stałą
                $fixedCost = $bill['fixed_costs'][$utilityId] ?? 0;
                $utilities[$utilityId][] = $cost + $fixedCost;
            }
        }

        // Kolory dla wykresów (mapowane według typu mediu)
        $colors = [
            'gas' => 'rgb(255, 99, 132)',
            'electricity' => 'rgb(255, 205, 86)',
            'cold_water' => 'rgb(54, 162, 235)',
            'hot_water' => 'rgb(255, 159, 64)',
        ];

        foreach ($utilities as $utilityId => $data) {
            $utilityInfo = $this->getUtilityInfo($utilityId, $flat);
            $utilityType = $utilityInfo['type'];
            
            $chartData['datasets'][] = [
                'label' => $utilityInfo['label'],
                'data' => array_reverse($data), // Odwróć aby najstarsze były pierwsze
                'borderColor' => $colors[$utilityType] ?? 'rgb(75, 192, 192)',
                'backgroundColor' => ($colors[$utilityType] ?? 'rgb(75, 192, 192)') . '20',
                'tension' => 0.1,
            ];
        }

        // Odwróć etykiety aby najstarsze były pierwsze
        $chartData['labels'] = array_reverse($chartData['labels']);

        return $chartData;
    }

    private function getUtilityInfo(string $utilityId, array $flat = null): array
    {
        if ($flat && isset($flat['utilities'][$utilityId])) {
            $utility = $flat['utilities'][$utilityId];
            $typeName = $this->getUtilityTypeName($utility['type']);
            
            $label = $typeName;
            if (!empty($utility['name'])) {
                $label .= " ({$utility['name']})";
            }
            
            return [
                'type' => $utility['type'],
                'label' => $label
            ];
        }
        
        // Fallback jeśli nie można znaleźć informacji o liczniku
        return [
            'type' => 'unknown',
            'label' => $utilityId
        ];
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

    private function getUtilityLabel(string $utility): string
    {
        $labels = [
            'gas' => 'Gaz',
            'electricity' => 'Prąd',
            'cold_water' => 'Woda zimna',
            'hot_water' => 'Woda ciepła',
        ];

        return $labels[$utility] ?? ucfirst($utility);
    }

    public function getPreviousReadings(string $flatId): array
    {
        // Pobierz ostatnie rozliczenie z historii
        $history = $this->getHistory($flatId);
        
        if (empty($history)) {
            return []; // Brak poprzednich odczytów
        }
        
        // Znajdź najnowsze rozliczenie
        $latestBill = $history[0]; // Historia jest sortowana malejąco
        
        return $latestBill['readings'] ?? [];
    }

    public function getUtilityRates(): array
    {
        return $this->settings['utility_rates'];
    }

    public function calculateUtilityCosts(array $currentReadings, array $previousReadings, array $fixedCosts, array $flat): array
    {
        $costs = [];
        $defaultRates = $this->settings['utility_rates'];
        
        // Oblicz koszty dla każdego licznika
        foreach ($currentReadings as $utilityId => $currentReading) {
            if (!isset($flat['utilities'][$utilityId])) {
                continue; // Licznik nie istnieje
            }
            
            $utility = $flat['utilities'][$utilityId];
            $type = $utility['type'];
            
            // Użyj poprzedniego odczytu jeśli istnieje, w przeciwnym razie stan początkowy
            $previousReading = $previousReadings[$utilityId] ?? ($utility['initial_reading'] ?? 0);
            $consumption = max(0, $currentReading - $previousReading); // Zużycie od poprzedniego odczytu
            // Użyj opłaty stałej z formularza lub z konfiguracji licznika jako domyślnej
            $fixedCost = $fixedCosts[$utilityId] ?? ($utility['fixed_cost'] ?? 0);
            
            // Użyj indywidualnej stawki licznika lub domyślnej dla typu
            $rate = $utility['rate'] ?? $defaultRates[$type] ?? 0;
            
            // Oblicz koszt zmienną
            $variableCost = $consumption * $rate;
            
            // Dla wody zimnej nie ma opłaty stałej zgodnie z wymaganiami
            if ($type === 'cold_water') {
                $fixedCost = 0;
            }
            
            $costs[$utilityId] = $variableCost + $fixedCost;
        }
        
        return $costs;
    }


}
