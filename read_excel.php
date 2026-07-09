<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$files = [
    'ABRIL' => 'C:/Practica/Liquidacion/LLIQUIDACION MES DE ABRIL NUEVO ACTUALIZADO.xlsx',
    'MAYO' => 'C:/Practica/Liquidacion/LLIQUIDACION MES DE MAYO NUEVO.xlsx',
    'JUNIO' => 'C:/Practica/Liquidacion/LLIQUIDACION MES DE JUNIO NUEVO.xlsx',
];

foreach ($files as $month => $path) {
    echo "========== $month ==========\n";
    if (!file_exists($path)) { echo "FILE NOT FOUND: $path\n\n"; continue; }
    $spreadsheet = IOFactory::load($path);
    $ws = $spreadsheet->getActiveSheet();
    $highestRow = $ws->getHighestRow();
    $highestCol = $ws->getHighestColumn();
    $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);
    echo "Dimensions: rows=$highestRow cols=$highestCol\n";
    for ($row = 1; $row <= $highestRow; $row++) {
        $parts = [];
        $allEmpty = true;
        for ($c = 1; $c <= $colIndex; $c++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
            $val = $ws->getCell($colLetter . $row)->getCalculatedValue();
            if ($val !== null && $val !== '') $allEmpty = false;
            if (is_string($val)) $val = trim($val);
            $display = ($val === null || $val === '') ? '' : (is_numeric($val) ? $val : "'$val'");
            $parts[] = "$colLetter=$display";
        }
        if (!$allEmpty) {
            echo "Row $row: " . implode(' ', $parts) . "\n";
        }
    }
    echo "\n";
}

// Read March section from Reporte de Caja
echo "========== REPORTE DE CAJA (March section ~rows 47-64, then sections for Abril/Mayo/Junio/Julio) ==========\n";
$reportePath = 'C:/Practica/Reporte de Caja Completo y Actualizado.xlsx';
if (!file_exists($reportePath)) { echo "FILE NOT FOUND\n"; exit; }
$spreadsheet = IOFactory::load($reportePath);
$ws = $spreadsheet->getActiveSheet();
$highestRow = $ws->getHighestRow();
$highestCol = $ws->getHighestColumn();
$colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);
echo "Dimensions: rows=$highestRow cols=$highestCol\n";
for ($row = 1; $row <= $highestRow; $row++) {
    $parts = [];
    $allEmpty = true;
    for ($c = 1; $c <= $colIndex; $c++) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
        $val = $ws->getCell($colLetter . $row)->getCalculatedValue();
        if ($val !== null && $val !== '') $allEmpty = false;
        if (is_string($val)) $val = trim($val);
        $display = ($val === null || $val === '') ? '' : (is_numeric($val) ? $val : "'$val'");
        $parts[] = "$colLetter=$display";
    }
    if (!$allEmpty) {
        echo "Row $row: " . implode(' ', $parts) . "\n";
    }
}
echo "\nDone.\n";