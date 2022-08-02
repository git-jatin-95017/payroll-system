<?php

namespace App\Imports;

use App\Models\LocationPriceSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class LocationPriceSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LocationPriceSample([
            'location_codes' => $row['location_codes'],
            'item_codes' => $row['item_codes'],
            'zip_codes' => $row['zip_codes'],
            'product' => $row['product'],
            'price' => $row['price'],
            'currency_code' => $row['currency_code'],
            'amount' => $row['amount'],
            'units' => $row['units'],
            'website' => $row['website'],
            'store' => $row['store'],
            'store_address' => $row['store_address'],
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
