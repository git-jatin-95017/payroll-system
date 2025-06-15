<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployerPaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Employee',
            'Pay Period',
            'Employee Pay',
            'Employee Taxes',
            'Employer Taxes',
            'Subtotal'
        ];
    }

    public function map($row): array
    {
        return [
            $row['employee'],
            $row['pay_period'],
            $row['employee_pay'],
            $row['employee_taxes'],
            $row['employer_taxes'],
            $row['subtotal']
        ];
    }
} 