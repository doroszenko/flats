<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class GladiusService
{
    private string $dbPath;

    public function __construct(string $dbPath)
    {
        $this->dbPath = $this->getWritablePath($dbPath);
        $this->ensureDirectoryExists();
    }

    private function getWritablePath(string $originalPath): string
    {
        // In serverless environments, use /tmp if the original path is not writable
        if (!is_writable(dirname($originalPath)) && is_dir('/tmp')) {
            $tmpPath = '/tmp/gladius';
            error_log("Using temporary directory for storage: $tmpPath");
            return $tmpPath;
        }
        
        return $originalPath;
    }

    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->dbPath)) {
            try {
                mkdir($this->dbPath, 0755, true);
            } catch (Exception $e) {
                error_log("Failed to create directory {$this->dbPath}: " . $e->getMessage());
                // In serverless environments, we might not be able to create directories
                // The service will still work for reading if directories exist
            }
        }
    }

    public function save(string $collection, string $id, array $data): bool
    {
        try {
            $collectionPath = $this->dbPath . '/' . $collection;
            
            // Only try to create directory if we can write to the parent directory
            if (!is_dir($collectionPath) && is_writable($this->dbPath)) {
                try {
                    mkdir($collectionPath, 0755, true);
                } catch (Exception $e) {
                    error_log("Failed to create collection directory {$collectionPath}: " . $e->getMessage());
                    return false;
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
