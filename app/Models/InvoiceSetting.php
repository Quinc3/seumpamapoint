<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone', 
        'company_email',
        'invoice_title',
        'footer_text',
        'terms_conditions',
        'show_logo',
        'logo_path',
        'show_cash_details',
        'show_payment_summary',
        'auto_calculate_change',
    ];

    protected $casts = [
        'show_logo' => 'boolean',
        'show_cash_details' => 'boolean',
        'show_payment_summary' => 'boolean', 
        'auto_calculate_change' => 'boolean',
    ];

    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'company_name' => 'Seumpama Coffee',
            'company_address' => 'Malangnengah Blok No.12A, Kadu Agung, Tigaraksa, Tangerang',
            'invoice_title' => 'INVOICE',
            'footer_text' => 'Thank you for your order!',
            'show_logo' => true,
            'show_cash_details' => true,
            'show_payment_summary' => true,
            'auto_calculate_change' => true,
        ]);
    }
}