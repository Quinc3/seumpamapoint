<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($a) {
            return [
                $a->user->name,
                $a->shift,
                $a->clock_in,
                $a->clock_out,
                $a->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Shift',
            'Clock In',
            'Clock Out',
            'Status',
        ];
    }
}
