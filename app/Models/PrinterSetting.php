<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    protected $fillable = [
        'auto_print',
        'printer_name', 
        'printer_connection',
        'paper_size',
        'copies',
        'test_mode'
    ];

    protected $casts = [
        'auto_print' => 'boolean',
        'test_mode' => 'boolean',
        'copies' => 'integer'
    ];

    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'auto_print' => true, // Default enabled
            'printer_name' => 'Brother HL-T4000DW Printer',
            'printer_connection' => 'usb',
            'paper_size' => '80mm',
            'copies' => 1,
            'test_mode' => false
        ]);
    }

    // Untuk PDF printing
    public function getPaperDimensions()
    {
        return match ($this->paper_size) {
        '58mm' => [0, 0, 165, 841],   // 58mm ≈ 165 points
        '80mm' => [0, 0, 280, 841],   // 80mm ≈ 280 points
        'a4'   => [0, 0, 595, 842],   // A4
        'a5'   => [0, 0, 420, 595],   // A5
            default => [0, 0, 280, 841],
        };
    }

    // Untuk text printing
    public function getPaperWidth()
    {
        return match ($this->paper_size) {
            '58mm' => 32,
            '80mm' => 42,
            'A4' => 80,
            default => 42,
        };
    }

    // Untuk CSS di PDF
    public function getCssWidth()
    {
        return match ($this->paper_size) {
            '58mm' => '52mm',
            '80mm' => '74mm',
            'A4' => '190mm',
            default => '74mm',
        };
    }
}