<?php

namespace App\Imports;

use App\Models\SupermarketSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class SupermarketSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SupermarketSample([
            'location_codes' => $row['location_codes'],
            'item_codes' => $row['item_codes'],
            'item_id' => $row['item_id'],
            'postal_code' => $row['postal_code'],
            'url' => $row['url'],
            'name' => $row['name'],
            'price' => $row['price'],
            'currency' => $row['currency'],
            'source' => $row['source'],
            'number_of_units' => $row['number_of_units'],
            'final_units' => $row['final_units'],
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
