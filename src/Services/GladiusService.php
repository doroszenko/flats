<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class GladiusService
{
    private string $dbPath;

    public function __construct(string $dbPath)
    {
        $this->dbPath = $dbPath;
        $this->ensureDirectoryExists();
    }

    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->dbPath)) {
            mkdir($this->dbPath, 0755, true);
        }
    }

    public function save(string $collection, string $id, array $data): bool
    {
        try {
            $collectionPath = $this->dbPath . '/' . $collection;
            if (!is_dir($collectionPath)) {
                mkdir($collectionPath, 0755, true);
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
