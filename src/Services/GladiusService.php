<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class GladiusService
{
    private string $dbPath;
    private bool $isServerless;

    public function __construct(string $dbPath)
    {
        $this->isServerless = $this->isServerlessEnvironment();
        $this->dbPath = $this->getWritablePath($dbPath);
        $this->ensureDirectoryExists();
    }

    private function isServerlessEnvironment(): bool
    {
        // Sprawdź czy jesteśmy w środowisku serverless (Vercel, AWS Lambda, etc.)
        return getenv('VERCEL') !== false || 
               getenv('AWS_LAMBDA_FUNCTION_NAME') !== false ||
               getenv('FUNCTION_TARGET') !== false ||
               getenv('K_SERVICE') !== false;
    }

    private function getWritablePath(string $originalPath): string
    {
        // W środowisku serverless, używamy /tmp jeśli jest dostępny
        if ($this->isServerless) {
            if (is_dir('/tmp') && is_writable('/tmp')) {
                return '/tmp/flats_db';
            }
            // Jeśli /tmp nie jest dostępny, zwróć oryginalną ścieżkę
            // ale nie próbuj tworzyć katalogów
            return $originalPath;
        }

        // W środowisku lokalnym - oryginalna logika
        if (!is_writable(dirname($originalPath))) {
            $dbDir = dirname($originalPath);
            if (is_dir($dbDir)) {
                // Spróbuj naprawić uprawnienia
                chmod($dbDir, 0755);
                if (!is_writable($dbDir)) {
                    throw new Exception("Katalog {$dbDir} nie jest zapisywalny i nie można naprawić uprawnień");
                }
            } else {
                // Spróbuj utworzyć katalog
                if (!mkdir($dbDir, 0755, true)) {
                    throw new Exception("Nie można utworzyć katalogu {$dbDir}");
                }
            }
        }
        
        return $originalPath;
    }

    private function ensureDirectoryExists(): void
    {
        // W środowisku serverless, nie próbuj tworzyć katalogów
        if ($this->isServerless) {
            return;
        }

        if (!is_dir($this->dbPath)) {
            try {
                mkdir($this->dbPath, 0755, true);
            } catch (Exception $e) {
                error_log("Failed to create directory {$this->dbPath}: " . $e->getMessage());
            }
        }
    }

    public function save(string $collection, string $id, array $data): bool
    {
        try {
            $collectionPath = $this->dbPath . '/' . $collection;
            
            // W środowisku serverless, sprawdź czy możemy pisać i utwórz katalog jeśli potrzeba
            if ($this->isServerless) {
                if (!is_writable($this->dbPath)) {
                    error_log("Cannot write to database directory in serverless environment: {$this->dbPath}");
                    return false;
                }
                // W serverless, spróbuj utworzyć katalog kolekcji jeśli nie istnieje
                if (!is_dir($collectionPath)) {
                    try {
                        mkdir($collectionPath, 0755, true);
                    } catch (Exception $e) {
                        error_log("Failed to create collection directory in serverless: {$collectionPath}: " . $e->getMessage());
                        return false;
                    }
                }
            } else {
                // Only try to create directory if we can write to the parent directory
                if (!is_dir($collectionPath) && is_writable($this->dbPath)) {
                    try {
                        mkdir($collectionPath, 0755, true);
                    } catch (Exception $e) {
                        error_log("Failed to create collection directory {$collectionPath}: " . $e->getMessage());
                        return false;
                    }
                }
            }

            // Check if we can write to the collection directory
            if (!is_dir($collectionPath) || !is_writable($collectionPath)) {
                error_log("Cannot write to collection directory: {$collectionPath}");
                return false;
            }

            $filePath = $collectionPath . '/' . $id . '.json';
            $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
            if ($jsonData === false) {
                throw new Exception('Błąd kodowania JSON');
            }

            return file_put_contents($filePath, $jsonData) !== false;
        } catch (Exception $e) {
            error_log("GladiusService save error: " . $e->getMessage());
            return false;
        }
    }

    public function load(string $collection, string $id): ?array
    {
        try {
            $filePath = $this->dbPath . '/' . $collection . '/' . $id . '.json';
            
            if (!file_exists($filePath)) {
                return null;
            }

            $jsonData = file_get_contents($filePath);
            if ($jsonData === false) {
                return null;
            }

            $data = json_decode($jsonData, true);
            return $data === null ? null : $data;
        } catch (Exception $e) {
            error_log("GladiusService load error: " . $e->getMessage());
            return null;
        }
    }

    public function loadAll(string $collection): array
    {
        try {
            $collectionPath = $this->dbPath . '/' . $collection;
            
            if (!is_dir($collectionPath)) {
                return [];
            }

            $files = glob($collectionPath . '/*.json');
            $data = [];

            foreach ($files as $file) {
                $id = basename($file, '.json');
                $content = $this->load($collection, $id);
                if ($content !== null) {
                    $data[$id] = $content;
                }
            }

            return $data;
        } catch (Exception $e) {
            error_log("GladiusService loadAll error: " . $e->getMessage());
            return [];
        }
    }

    public function delete(string $collection, string $id): bool
    {
        try {
            $filePath = $this->dbPath . '/' . $collection . '/' . $id . '.json';
            
            if (!file_exists($filePath)) {
                return true; // Plik już nie istnieje
            }

            return unlink($filePath);
        } catch (Exception $e) {
            error_log("GladiusService delete error: " . $e->getMessage());
            return false;
        }
    }

    public function exists(string $collection, string $id): bool
    {
        $filePath = $this->dbPath . '/' . $collection . '/' . $id . '.json';
        return file_exists($filePath);
    }

    public function generateId(): string
    {
        return uniqid('', true);
    }
}
