<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PayrollExport implements FromCollection, WithHeadings
{
   protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return ["Employee", "Pay Period", "Gross Pay", "Medical Benefits", "Social Security", "Education Levy", "Additions", "Deductions"]; // Customize your headings
    }
}
