<?php
// app/Exports/BarangMasukExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class BarangMasukExport implements FromView, WithStyles
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function view(): View
    {
        return view('exports.barang-masuk-excel', $this->data);
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            4 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]],
        ];
    }
}