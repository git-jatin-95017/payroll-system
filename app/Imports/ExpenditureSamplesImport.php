<?php

namespace App\Imports;

use App\Models\ExpenditureSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ExpenditureSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {                
        return new ExpenditureSample([
            'country' => $row['country'],
            'currency' => $row['currency'],
            'type' => $row['type'],
            'num_of_adult' => $row['num_of_adult'],
            'num_of_child' => $row['num_of_child'],
            'coefficient_a' => $row['coefficient_a'],
            'coefficient_b' => $row['coefficient_b'],
            'price_date' => !empty($row['price_date']) ? $this->transformDate($row['price_date']) : NULL
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
