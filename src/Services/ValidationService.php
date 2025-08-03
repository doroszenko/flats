<?php

declare(strict_types=1);

namespace App\Services;

class ValidationService
{
    public function validateFlatData(array $data): array
    {
        $errors = [];

        // Nazwa mieszkania
        if (empty($data['name'])) {
            $errors[] = 'Nazwa mieszkania jest wymagana';
        } elseif (strlen(trim($data['name'])) < 2) {
            $errors[] = 'Nazwa mieszkania musi mieć co najmniej 2 znaki';
        } elseif (strlen(trim($data['name'])) > 100) {
            $errors[] = 'Nazwa mieszkania nie może być dłuższa niż 100 znaków';
        }



        // Media
        if (isset($data['utilities'])) {
            if (!is_array($data['utilities'])) {
                $errors[] = 'Nieprawidłowy format mediów';
            } else {
                $allowedUtilities = ['gas', 'electricity', 'cold_water', 'hot_water'];
                foreach ($data['utilities'] as $utility) {
                    if (!in_array($utility, $allowedUtilities)) {
                        $errors[] = "Nieznane medium: {$utility}";
                    }
                }
            }
        }

        return $errors;
    }

    public function validateBillData(array $data): array
    {
        $errors = [];

        // Okres
        if (empty($data['period'])) {
            $errors[] = 'Okres rozliczeniowy jest wymagany';
        } elseif (!preg_match('/^\d{4}-\d{2}$/', $data['period'])) {
            $errors[] = 'Nieprawidłowy format okresu (wymagany: YYYY-MM)';
        } else {
            // Sprawdź czy data nie jest z przyszłości
            $periodDate = \DateTime::createFromFormat('Y-m', $data['period']);
            $currentDate = new \DateTime();
            $currentDate->modify('first day of this month');
            
            if ($periodDate > $currentDate) {
                $errors[] = 'Okres rozliczeniowy nie może być z przyszłości';
            }
            
            // Sprawdź czy data nie jest zbyt stara (maksymalnie 5 lat wstecz)
            $minDate = clone $currentDate;
            $minDate->modify('-5 years');
            
            if ($periodDate < $minDate) {
                $errors[] = 'Okres rozliczeniowy nie może być starszy niż 5 lat';
            }
        }

        // Koszty
        if (isset($data['costs']) && is_array($data['costs'])) {
            if (empty($data['costs'])) {
                $errors[] = 'Należy podać co najmniej jeden koszt';
            }
            
            foreach ($data['costs'] as $utility => $cost) {
                if (!is_numeric($cost)) {
                    $errors[] = "Nieprawidłowa wartość kosztu dla {$utility}";
                } elseif ($cost < 0) {
                    $errors[] = "Koszt dla {$utility} nie może być ujemny";
                } elseif ($cost > 999999.99) {
                    $errors[] = "Koszt dla {$utility} jest zbyt wysoki (maksymalnie 999,999.99 zł)";
                }
            }
        } else {
            $errors[] = 'Koszty są wymagane';
        }

        // Odczyty (opcjonalne)
        if (isset($data['readings']) && is_array($data['readings'])) {
            foreach ($data['readings'] as $utility => $reading) {
                if (!empty($reading) && !is_numeric($reading)) {
                    $errors[] = "Nieprawidłowa wartość odczytu dla {$utility}";
                } elseif (!empty($reading) && $reading < 0) {
                    $errors[] = "Odczyt dla {$utility} nie może być ujemny";
                } elseif (!empty($reading) && $reading > 999999999) {
                    $errors[] = "Odczyt dla {$utility} jest zbyt wysoki";
                }
            }
        }

        return $errors;
    }

    public function sanitizeString(string $input, int $maxLength = null): string
    {
        // Usuń niebezpieczne znaki
        $sanitized = htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        
        // Ogranicz długość jeśli podano
        if ($maxLength && strlen($sanitized) > $maxLength) {
            $sanitized = substr($sanitized, 0, $maxLength);
        }
        
        return $sanitized;
    }

    public function sanitizeFloat(mixed $input): float
    {
        if (is_numeric($input)) {
            return round((float)$input, 2);
        }
        return 0.0;
    }

    public function sanitizeArray(array $input, array $allowedKeys): array
    {
        $sanitized = [];
        foreach ($allowedKeys as $key) {
            if (isset($input[$key])) {
                $sanitized[$key] = $input[$key];
            }
        }
        return $sanitized;
    }
}
