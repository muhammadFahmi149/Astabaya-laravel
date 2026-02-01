<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service untuk mengakses Google Sheets API
 * Equivalent to Spreadsheet.py in Django
 */
class SpreadsheetService
{
    protected $client;
    protected $service;
    protected $sheetId;

    public function __construct()
    {
        $this->sheetId = config('services.google.sheet_id', env('GOOGLE_SHEET_ID', '1keS9YFYO1qzAawWgLh2U2pY6xX5ppKUnhbdHQYfU5HM'));
        $this->initializeClient();
    }

    /**
     * Initialize Google API Client
     */
    protected function initializeClient()
    {
        try {
            $this->client = new Google_Client();
            
            // Set authentication
            $credentialsPath = env('GOOGLE_CREDENTIALS_PATH');
            $credentialsJson = env('GOOGLE_CREDENTIALS_JSON');
            
            if ($credentialsPath && file_exists($credentialsPath)) {
                $this->client->setAuthConfig($credentialsPath);
            } elseif ($credentialsJson) {
                $credentials = json_decode($credentialsJson, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->client->setAuthConfig($credentials);
                } else {
                    throw new Exception('Invalid GOOGLE_CREDENTIALS_JSON format');
                }
            } else {
                // Fallback: try to use default path
                $defaultPath = storage_path('app/google-credentials.json');
                if (file_exists($defaultPath)) {
                    $this->client->setAuthConfig($defaultPath);
                } else {
                    throw new Exception('Google credentials not configured. Please set GOOGLE_CREDENTIALS_PATH or GOOGLE_CREDENTIALS_JSON in .env');
                }
            }
            
            // Add required scopes
            $this->client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);
            $this->client->setAccessType('offline');
            
            // Create service instance
            $this->service = new Google_Service_Sheets($this->client);
        } catch (Exception $e) {
            Log::error('Error initializing Google Sheets client: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get Google Sheets client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get Google Sheets service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Fetch data from Google Sheet worksheet
     * 
     * @param string $sheetName Name of the worksheet
     * @param string $range Optional range (default: A1:ZZ1000)
     * @return array
     */
    public function fetchWorksheetData(string $sheetName, string $range = 'A1:ZZ1000'): array
    {
        try {
            $fullRange = "{$sheetName}!{$range}";
            $response = $this->service->spreadsheets_values->get($this->sheetId, $fullRange);
            $values = $response->getValues();
            
            return $values ?? [];
        } catch (Exception $e) {
            Log::error("Error fetching worksheet data from '{$sheetName}': " . $e->getMessage());
            return [];
        }
    }

    /**
     * Process and transform data from Google Sheets
     * Converts rows to associative arrays using first row as headers
     * 
     * @param array $rawData Raw data from Google Sheets
     * @return array Processed data as associative arrays
     */
    public function processSheetData(array $rawData): array
    {
        if (empty($rawData)) {
            return [];
        }
        
        // First row is headers
        $headers = array_shift($rawData);
        
        // Clean headers (remove whitespace, convert to lowercase, replace spaces with underscores)
        $headers = array_map(function($header) {
            return trim(strtolower(str_replace(' ', '_', $header)));
        }, $headers);
        
        $processed = [];
        
        foreach ($rawData as $row) {
            $item = [];
            foreach ($headers as $index => $header) {
                $value = $row[$index] ?? null;
                
                // Clean empty strings
                if ($value === '' || $value === null) {
                    $value = null;
                }
                
                $item[$header] = $value;
            }
            $processed[] = $item;
        }
        
        $validRecords = count(array_filter($processed, function($item) {
            return !empty(array_filter($item, function($value) {
                return $value !== null && $value !== '';
            }));
        }));
        
        echo "[OK] Data processed. Total valid records: {$validRecords}\n";
        
        return $processed;
    }

    /**
     * Get sheet ID
     */
    public function getSheetId(): string
    {
        return $this->sheetId;
    }

    /**
     * Set sheet ID
     */
    public function setSheetId(string $sheetId): void
    {
        $this->sheetId = $sheetId;
    }

    /**
     * Get all worksheets from the spreadsheet
     * 
     * @return array Array of worksheet titles
     */
    public function getAllWorksheets(): array
    {
        try {
            $spreadsheet = $this->service->spreadsheets->get($this->sheetId);
            $sheets = $spreadsheet->getSheets();
            
            $worksheetTitles = [];
            foreach ($sheets as $sheet) {
                $properties = $sheet->getProperties();
                $worksheetTitles[] = $properties->getTitle();
            }
            
            return $worksheetTitles;
        } catch (Exception $e) {
            Log::error("Error fetching worksheets: " . $e->getMessage());
            return [];
        }
    }
}
