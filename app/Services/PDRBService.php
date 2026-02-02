<?php

namespace App\Services;

use App\Models\PDRBPengeluaranADHB;
use App\Models\PDRBPengeluaranADHK;
use App\Models\PDRBPengeluaranDistribusi;
use App\Models\PDRBPengeluaranLajuPDRB;
use App\Models\PDRBPengeluaranLajuYtoY;
use App\Models\PDRBPengeluaranLajuQtoQ;
use App\Models\PDRBPengeluaranLajuCtoC;
use App\Models\PDRBPengeluaranADHBTriwulanan;
use App\Models\PDRBPengeluaranADHKTriwulanan;
use App\Models\PDRBPengeluaranDistribusiTriwulanan;
use App\Models\PDRBLapanganUsahaADHB;
use App\Models\PDRBLapanganUsahaADHK;
use App\Models\PDRBLapanganUsahaDistribusi;
use App\Models\PDRBLapanganUsahaLajuPDRB;
use App\Models\PDRBLapanganUsahaLajuYtoY;
use App\Models\PDRBLapanganUsahaLajuQtoQ;
use App\Models\PDRBLapanganUsahaLajuCtoC;
use App\Models\PDRBLapanganUsahaLajuImplisit;
use App\Models\PDRBLapanganUsahaADHBTriwulanan;
use App\Models\PDRBLapanganUsahaADHKTriwulanan;
use App\Models\PDRBLapanganUsahaDistribusiTriwulanan;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data PDRB dari Google Sheets
 */
class PDRBService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Sync PDRB Pengeluaran ADHB (Atas Dasar Harga Berlaku)
     */
    public function syncPengeluaranADHB(string $sheetName = 'PDRB Pengeluaran_ADHB'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranADHB::class,
            $sheetName,
            ['expenditure_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran ADHK (Atas Dasar Harga Konstan)
     */
    public function syncPengeluaranADHK(string $sheetName = 'PDRB Pengeluaran_ADHK'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranADHK::class,
            $sheetName,
            ['expenditure_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran Distribusi
     */
    public function syncPengeluaranDistribusi(string $sheetName = 'PDRB Pengeluaran_Distribusi'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranDistribusi::class,
            $sheetName,
            ['expenditure_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran Laju PDRB
     */
    public function syncPengeluaranLajuPDRB(string $sheetName = 'PDRB Pengeluaran_Laju PDRB'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranLajuPDRB::class,
            $sheetName,
            ['expenditure_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran Laju Y-to-Y
     */
    public function syncPengeluaranLajuYtoY(string $sheetName = 'Laju Pertumbuhan_y-to-y_PDRB Pengeluaran_Triwulan'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranLajuYtoY::class,
            $sheetName,
            ['expenditure_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran Laju Q-to-Q
     */
    public function syncPengeluaranLajuQtoQ(string $sheetName = 'Laju Pertumbuhan_q-to-q_PDRB Pengeluaran_Triwulan'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranLajuQtoQ::class,
            $sheetName,
            ['expenditure_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran Laju C-to-C
     */
    public function syncPengeluaranLajuCtoC(string $sheetName = 'Laju Pertumbuhan_c-to-c_PDRB Pengeluaran_Triwulan'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranLajuCtoC::class,
            $sheetName,
            ['expenditure_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran ADHB Triwulanan
     */
    public function syncPengeluaranADHBTriwulanan(string $sheetName = 'PDRB Pengeluaran_ADHB_Triwulanan'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranADHBTriwulanan::class,
            $sheetName,
            ['expenditure_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran ADHK Triwulanan
     */
    public function syncPengeluaranADHKTriwulanan(string $sheetName = 'PDRB Pengeluaran_ADHK_Triwulanan'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranADHKTriwulanan::class,
            $sheetName,
            ['expenditure_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Pengeluaran Distribusi Triwulanan
     */
    public function syncPengeluaranDistribusiTriwulanan(string $sheetName = 'PDRB Pengeluaran_Distribusi_Triwulanan'): array
    {
        return $this->syncGeneric(
            PDRBPengeluaranDistribusiTriwulanan::class,
            $sheetName,
            ['expenditure_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha ADHB
     */
    public function syncLapanganUsahaADHB(string $sheetName = 'PDRB Lapus_ADHB'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaADHB::class,
            $sheetName,
            ['industry_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha ADHK
     */
    public function syncLapanganUsahaADHK(string $sheetName = 'PDRB Lapus_ADHK'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaADHK::class,
            $sheetName,
            ['industry_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Distribusi
     */
    public function syncLapanganUsahaDistribusi(string $sheetName = 'PDRB Lapus_Distribusi'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaDistribusi::class,
            $sheetName,
            ['industry_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Laju PDRB
     */
    public function syncLapanganUsahaLajuPDRB(string $sheetName = 'PDRB Lapus_Laju PDRB'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaLajuPDRB::class,
            $sheetName,
            ['industry_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Laju Y-to-Y
     */
    public function syncLapanganUsahaLajuYtoY(string $sheetName = 'Laju Pertumbuhan_y-to-y_PDRB Lapus_Triwulan'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaLajuYtoY::class,
            $sheetName,
            ['industry_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Laju Q-to-Q
     */
    public function syncLapanganUsahaLajuQtoQ(string $sheetName = 'Laju Pertumbuhan_q-to-q_PDRB Lapus_Triwulan'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaLajuQtoQ::class,
            $sheetName,
            ['industry_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Laju C-to-C
     */
    public function syncLapanganUsahaLajuCtoC(string $sheetName = 'Laju Pertumbuhan_c-to-c_PDRB Lapus_Triwulan'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaLajuCtoC::class,
            $sheetName,
            ['industry_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Laju Implisit
     */
    public function syncLapanganUsahaLajuImplisit(string $sheetName = 'PDRB Lapus_Laju Implisit'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaLajuImplisit::class,
            $sheetName,
            ['industry_category', 'year', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha ADHB Triwulanan
     */
    public function syncLapanganUsahaADHBTriwulanan(string $sheetName = 'PDRB Lapus_ADHB_Triwulanan'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaADHBTriwulanan::class,
            $sheetName,
            ['industry_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha ADHK Triwulanan
     */
    public function syncLapanganUsahaADHKTriwulanan(string $sheetName = 'PDRB Lapus_ADHK_Triwulanan'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaADHKTriwulanan::class,
            $sheetName,
            ['industry_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Sync PDRB Lapangan Usaha Distribusi Triwulanan
     */
    public function syncLapanganUsahaDistribusiTriwulanan(string $sheetName = 'PDRB Lapus_Distribusi_Triwulanan'): array
    {
        return $this->syncGeneric(
            PDRBLapanganUsahaDistribusiTriwulanan::class,
            $sheetName,
            ['industry_category', 'year', 'quarter', 'preliminary_flag', 'value']
        );
    }

    /**
     * Parse year with preliminary flag from string (e.g., "2023*" -> 2023, "*")
     */
    protected function parseYearWithFlag(string $yearStr): array
    {
        $yearStr = trim($yearStr);
        
        // Remove asterisks and other flags
        $flag = '';
        if (preg_match('/(\*+)$/', $yearStr, $matches)) {
            $flag = $matches[1];
            $yearStr = str_replace($flag, '', $yearStr);
        }
        
        // Extract year (4 digits)
        if (preg_match('/(\d{4})/', $yearStr, $matches)) {
            $year = (int) $matches[1];
            if ($year >= 2000 && $year <= 2100) {
                return [$year, $flag];
            }
        }
        
        return [null, ''];
    }

    /**
     * Process PDRB data from raw sheet data
     * Supports both annual and quarterly formats
     * 
     * @param array $rawData Raw data from Google Sheets
     * @param bool $isQuarterly Whether data is quarterly (has I, II, III, IV columns)
     * @param string $categoryField Field name for category (expenditure_category or industry_category)
     * @return array Processed records
     */
    protected function processPDRBData(array $rawData, bool $isQuarterly = false, string $categoryField = 'expenditure_category'): array
    {
        if (empty($rawData) || count($rawData) < 2) {
            return [];
        }

        // Find category column index
        // For Lapangan Usaha: always column A (index 0)
        // For Pengeluaran: check first few rows
        $categoryColIdx = 0;
        
        if ($categoryField === 'industry_category') {
            // Lapangan Usaha: kategori selalu di kolom A (index 0)
            $categoryColIdx = 0;
        } else {
            // Pengeluaran: check first few rows to find category column
            for ($rowIdx = 0; $rowIdx < min(3, count($rawData)); $rowIdx++) {
                $row = $rawData[$rowIdx];
                for ($colIdx = 0; $colIdx < min(2, count($row)); $colIdx++) {
                    $val = trim(strtoupper($row[$colIdx] ?? ''));
                    if ($val && (
                        stripos($val, 'PENGELUARAN') !== false || 
                        stripos($val, 'COMPONENT') !== false ||
                        stripos($val, 'JENIS') !== false ||
                        stripos($val, 'KATEGORI') !== false
                    )) {
                        $categoryColIdx = $colIdx;
                        break 2;
                    }
                }
            }
        }

        $records = [];

        if ($isQuarterly) {
            // Quarterly format:
            // For Lapangan Usaha: Row 0 = Years, Row 1 = Quarters, Row 2 = empty, Row 3+ = Data
            // For Pengeluaran: Row 0 = Years, Row 1 = Quarters, Row 2+ = Data
            if (count($rawData) < 3) {
                echo "⚠️ Not enough rows for quarterly data\n";
                return [];
            }

            $yearRow = $rawData[0];
            $quarterRow = $rawData[1];
            
            // For Lapangan Usaha, data starts from row 4 (index 3), skip row 2 (index 2) which is empty
            // For Pengeluaran, data starts from row 3 (index 2)
            $dataStartIdx = ($categoryField === 'industry_category') ? 3 : 2;
            $dataRows = array_slice($rawData, $dataStartIdx);

            // Build column mapping: col_idx -> (year, flag, quarter)
            $colMapping = [];
            $currentYear = null;
            $currentFlag = '';

            // For Lapangan Usaha: start from column B (index 1), skip column A (index 0)
            // For Pengeluaran: start from column after category column
            $startCol = ($categoryField === 'industry_category') ? 1 : $categoryColIdx + 1;
            
            for ($colIdx = $startCol; $colIdx < count($yearRow); $colIdx++) {
                $yearVal = trim($yearRow[$colIdx] ?? '');
                $quarterVal = trim(strtoupper($quarterRow[$colIdx] ?? ''));

                // Parse year
                if ($yearVal) {
                    [$year, $flag] = $this->parseYearWithFlag($yearVal);
                    if ($year !== null) {
                        $currentYear = $year;
                        $currentFlag = $flag;
                    }
                }

                // Parse quarter (skip if it's a label like "Lapangan Usaha")
                if ($quarterVal && $currentYear !== null) {
                    // Skip if it's a category label
                    $skipLabels = ['LAPANGAN USAHA', 'LAPUS', 'PENGELUARAN', 'COMPONENT', 'JENIS'];
                    $isLabel = false;
                    foreach ($skipLabels as $label) {
                        if (stripos($quarterVal, $label) !== false) {
                            $isLabel = true;
                            break;
                        }
                    }
                    
                    if ($isLabel) {
                        continue;
                    }
                    
                    $quarter = null;
                    $quarterUpper = strtoupper($quarterVal);
                    
                    if ($quarterUpper === 'I' || strpos($quarterUpper, ' I ') !== false || substr($quarterUpper, -2) === ' I') {
                        $quarter = 'I';
                    } elseif ($quarterUpper === 'II' || strpos($quarterUpper, ' II ') !== false || substr($quarterUpper, -3) === ' II') {
                        $quarter = 'II';
                    } elseif ($quarterUpper === 'III' || strpos($quarterUpper, ' III ') !== false || substr($quarterUpper, -4) === ' III') {
                        $quarter = 'III';
                    } elseif ($quarterUpper === 'IV' || strpos($quarterUpper, ' IV ') !== false || substr($quarterUpper, -3) === ' IV') {
                        $quarter = 'IV';
                    } elseif (stripos($quarterUpper, 'JUMLAH') !== false || $quarterUpper === 'TOTAL' || $quarterUpper === 'TOT') {
                        $quarter = 'TOTAL';
                    }

                    if ($quarter) {
                        $colMapping[$colIdx] = [$currentYear, $currentFlag, $quarter];
                    }
                }
            }

            // Process data rows
            foreach ($dataRows as $row) {
                if (count($row) <= $categoryColIdx) {
                    continue;
                }

                $category = trim((string) ($row[$categoryColIdx] ?? ''));
                
                // Skip completely empty rows (no category at all)
                if (empty($category)) {
                    continue;
                }
                
                // Skip if category is too short (likely not a real category)
                if (strlen($category) < 3) {
                    continue;
                }

                $categoryUpper = strtoupper($category);
                
                // Skip header rows (short header labels)
                $skipWords = ['PENGELUARAN', 'COMPONENT', 'JENIS', 'LAPANGAN', 'KATEGORI', 'EXPENDITURE'];
                if (array_filter($skipWords, fn($word) => stripos($categoryUpper, $word) !== false) && strlen($category) < 30) {
                    continue;
                }

                foreach ($colMapping as $colIdx => [$year, $flag, $quarter]) {
                    // Always create record, even if value is null/empty
                    $valueStr = isset($row[$colIdx]) ? trim((string) $row[$colIdx]) : '';
                    $value = $this->parseDecimal($valueStr);
                    
                    // Create record with null value if cell is empty, '-', or 'error'
                    $record = [
                        $categoryField => $category,
                        'year' => $year,
                        'quarter' => $quarter,
                        'preliminary_flag' => $flag,
                        'value' => $value // Can be null
                    ];
                    $records[] = $record;
                }
            }
        } else {
            // Annual format:
            // For Lapangan Usaha: Row 1 = "JENIS LAPANGAN USAHA" label, Row 2 = empty/header, Row 3 = Year headers, Row 4+ = Data
            // For Pengeluaran: Row 0 or 1 = Year headers, Row 1+ or 2+ = Data
            $headerRowIdx = null;
            $dataStartRow = null;

            // For Lapangan Usaha, check row 2 (index 1) first for year headers
            // Based on image: Row 1 = empty, Row 2 = "JENIS LAPANGAN USAHA" in A2 and years in B2-J2, Row 3 = merged header, Row 4+ = Data
            if ($categoryField === 'industry_category' && count($rawData) > 1) {
                $row1 = $rawData[1];
                $yearFound = false;
                // Check columns starting from B (index 1), skip column A (index 0)
                for ($colIdx = 1; $colIdx < count($row1); $colIdx++) {
                    $val = trim((string) ($row1[$colIdx] ?? ''));
                    [$year, ] = $this->parseYearWithFlag($val);
                    if ($year !== null) {
                        $yearFound = true;
                        break;
                    }
                }

                if ($yearFound) {
                    $headerRowIdx = 1;
                    $dataStartRow = 3; // Data starts from row 4 (index 3), skip row 3 which is merged header
                }
            }

            // If not found or not Lapangan Usaha, check row 0
            if ($headerRowIdx === null && count($rawData) > 0) {
                $row0 = $rawData[0];
                $yearFound = false;
                foreach ($row0 as $val) {
                    [$year, ] = $this->parseYearWithFlag(trim((string) $val));
                    if ($year !== null) {
                        $yearFound = true;
                        break;
                    }
                }

                if ($yearFound) {
                    $headerRowIdx = 0;
                    $dataStartRow = 1;
                } elseif (count($rawData) > 1) {
                    // Check row 1 for year headers
                    $row1 = $rawData[1];
                    $yearFound = false;
                    foreach ($row1 as $val) {
                        [$year, ] = $this->parseYearWithFlag(trim((string) $val));
                        if ($year !== null) {
                            $yearFound = true;
                            break;
                        }
                    }

                    if ($yearFound) {
                        $headerRowIdx = 1;
                        $dataStartRow = 2;
                    }
                }
            }

            if ($headerRowIdx === null) {
                echo "⚠️ No year columns found in headers\n";
                return [];
            }

            $headers = $rawData[$headerRowIdx];
            $dataRows = array_slice($rawData, $dataStartRow);

            // Find year columns
            // For Lapangan Usaha: years start from column B (index 1), skip column A (index 0)
            // For Pengeluaran: check all columns except category column
            $yearCols = [];
            $startCol = ($categoryField === 'industry_category') ? 1 : 0;
            
            foreach ($headers as $idx => $header) {
                if ($idx === $categoryColIdx) {
                    continue;
                }
                
                // For Lapangan Usaha, only check columns from B onwards
                if ($categoryField === 'industry_category' && $idx < 1) {
                    continue;
                }

                [$year, $flag] = $this->parseYearWithFlag(trim((string) $header));
                if ($year !== null) {
                    $yearCols[$idx] = [$year, $flag];
                }
            }

            if (empty($yearCols)) {
                echo "⚠️ No valid year columns found\n";
                return [];
            }

            // Process data rows
            foreach ($dataRows as $row) {
                if (count($row) <= $categoryColIdx) {
                    continue;
                }

                $category = trim((string) ($row[$categoryColIdx] ?? ''));
                
                // Skip completely empty rows (no category at all)
                if (empty($category)) {
                    continue;
                }
                
                // Skip if category is too short (likely not a real category)
                if (strlen($category) < 3) {
                    continue;
                }

                $categoryUpper = strtoupper($category);
                
                // Skip header rows (short header labels)
                $skipWords = ['PENGELUARAN', 'COMPONENT', 'JENIS', 'LAPANGAN', 'KATEGORI', 'EXPENDITURE', 'TYPE'];
                if (array_filter($skipWords, fn($word) => stripos($categoryUpper, $word) !== false) && strlen($category) < 20) {
                    continue;
                }

                // Skip if category is only digits
                if (ctype_digit($category)) {
                    continue;
                }

                foreach ($yearCols as $colIdx => [$year, $flag]) {
                    // Always create record, even if value is null/empty
                    $valueStr = isset($row[$colIdx]) ? trim((string) $row[$colIdx]) : '';
                    $value = $this->parseDecimal($valueStr);
                    
                    // Create record with null value if cell is empty, '-', or 'error'
                    $record = [
                        $categoryField => $category,
                        'year' => $year,
                        'preliminary_flag' => $flag,
                        'value' => $value // Can be null
                    ];
                    $records[] = $record;
                }
            }
        }

        return $records;
    }

    /**
     * Generic sync method for PDRB models
     */
    protected function syncGeneric(string $modelClass, string $sheetName, array $requiredFields): array
    {
        try {
            $modelName = class_basename($modelClass);
            echo "📊 Syncing {$modelName} data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Determine if quarterly based on required fields
            $isQuarterly = in_array('quarter', $requiredFields);
            
            // Determine category field name
            $categoryField = in_array('expenditure_category', $requiredFields) 
                ? 'expenditure_category' 
                : 'industry_category';

            // Process data dengan format khusus PDRB (matrix format)
            $processedRecords = $this->processPDRBData($rawData, $isQuarterly, $categoryField);
            
            if (empty($processedRecords)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedRecords) . "\n";
            
            $createdCount = 0;
            $updatedCount = 0;

            foreach ($processedRecords as $record) {
                try {
                    // Build query for finding existing record
                    $query = call_user_func([$modelClass, 'query']);
                    
                    foreach ($requiredFields as $field) {
                        if ($field !== 'value' && $field !== 'preliminary_flag' && isset($record[$field])) {
                            $query->where($field, $record[$field]);
                        }
                    }
                    
                    $existing = $query->first();

                    if ($existing) {
                        $existing->update($record);
                        $updatedCount++;
                    } else {
                        call_user_func([$modelClass, 'create'], $record);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving record: " . $e->getMessage() . "\n";
                    Log::error("Error saving PDRB record: " . $e->getMessage(), ['record' => $record, 'model' => $modelName]);
                    continue;
                }
            }

            echo "✅ PDRB {$modelName} sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("PDRB {$modelName} sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            $modelName = class_basename($modelClass);
            Log::error("Error syncing PDRB {$modelName}: " . $e->getMessage());
            echo "❌ Error syncing PDRB {$modelName}: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Sync all PDRB Pengeluaran data
     */
    public function syncAllPengeluaran(): array
    {
        return [
            'pengeluaran_adhb' => $this->syncPengeluaranADHB(),
            'pengeluaran_adhk' => $this->syncPengeluaranADHK(),
            'pengeluaran_distribusi' => $this->syncPengeluaranDistribusi(),
            'pengeluaran_laju_pdrb' => $this->syncPengeluaranLajuPDRB(),
            'pengeluaran_laju_y_to_y' => $this->syncPengeluaranLajuYtoY(),
            'pengeluaran_laju_q_to_q' => $this->syncPengeluaranLajuQtoQ(),
            'pengeluaran_laju_c_to_c' => $this->syncPengeluaranLajuCtoC(),
            'pengeluaran_adhb_triwulanan' => $this->syncPengeluaranADHBTriwulanan(),
            'pengeluaran_adhk_triwulanan' => $this->syncPengeluaranADHKTriwulanan(),
            'pengeluaran_distribusi_triwulanan' => $this->syncPengeluaranDistribusiTriwulanan(),
        ];
    }

    /**
     * Sync all PDRB Lapangan Usaha data
     */
    public function syncAllLapanganUsaha(): array
    {
        return [
            'lapangan_usaha_adhb' => $this->syncLapanganUsahaADHB(),
            'lapangan_usaha_adhk' => $this->syncLapanganUsahaADHK(),
            'lapangan_usaha_distribusi' => $this->syncLapanganUsahaDistribusi(),
            'lapangan_usaha_laju_pdrb' => $this->syncLapanganUsahaLajuPDRB(),
            'lapangan_usaha_laju_y_to_y' => $this->syncLapanganUsahaLajuYtoY(),
            'lapangan_usaha_laju_q_to_q' => $this->syncLapanganUsahaLajuQtoQ(),
            'lapangan_usaha_laju_c_to_c' => $this->syncLapanganUsahaLajuCtoC(),
            'lapangan_usaha_laju_implisit' => $this->syncLapanganUsahaLajuImplisit(),
            'lapangan_usaha_adhb_triwulanan' => $this->syncLapanganUsahaADHBTriwulanan(),
            'lapangan_usaha_adhk_triwulanan' => $this->syncLapanganUsahaADHKTriwulanan(),
            'lapangan_usaha_distribusi_triwulanan' => $this->syncLapanganUsahaDistribusiTriwulanan(),
        ];
    }

    /**
     * Sync all PDRB data
     */
    public function syncAll(): array
    {
        return [
            'pengeluaran' => $this->syncAllPengeluaran(),
            'lapangan_usaha' => $this->syncAllLapanganUsaha(),
        ];
    }

    /**
     * Parse decimal value from string
     * Handles both comma and dot as decimal separator, thousand separators
     * Returns null for empty, '-', 'error', 'nan', 'none', 'null'
     * Matches Python convert_value implementation
     */
    protected function parseDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Convert to string and trim
        $valueStr = trim((string) $value);
        
        // Handle empty string, "-", "error" (case insensitive)
        $valueLower = strtolower($valueStr);
        if (in_array($valueLower, ['-', 'error', '', 'nan', 'none', 'null'])) {
            return null;
        }
        
        try {
            // Handle negative sign
            $isNegative = strpos($valueStr, '-') === 0;
            if ($isNegative) {
                $valueStr = substr($valueStr, 1);
            }
            
            // Replace comma with dot for decimal
            $valueStr = str_replace(',', '.', $valueStr);
            
            // Remove spaces
            $valueStr = str_replace(' ', '', $valueStr);
            
            // Handle thousand separators (multiple dots)
            if (substr_count($valueStr, '.') > 1) {
                // Multiple dots = thousand separators
                $parts = explode('.', $valueStr);
                $valueStr = implode('', array_slice($parts, 0, -1)) . '.' . end($parts);
            }
            
            $floatValue = (float) $valueStr;
            if ($isNegative) {
                $floatValue = -$floatValue;
            }
            
            return is_numeric($floatValue) ? $floatValue : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

