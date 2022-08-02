<?php

namespace App\Imports;

use App\Models\GsCodeSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class GsCodeSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {                        
        return new GsCodeSample([
            'item_codes' => $row['item_codes'],
            'master_item_codes' => $row['master_item_codes'],
            'final_item' => $row['final_item'],
            'component_items' => $row['component_items'],
            'category' => $row['category'],
            'store_type' => $row['store_type'],
            'details' => $row['details'],
            'standard_amounts' => $row['standard_amounts'],
            'standard_units' => $row['standard_units'],
            'unit_type' => $row['unit_type'],
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
