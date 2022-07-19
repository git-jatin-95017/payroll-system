<?php

namespace App\Imports;

use App\Models\HousingSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HousingSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new HousingSample([
            'location_codes' => $row['location_codes'],
            'source' => $row['source'],
            'url' => $row['url'],
            'price_type' => $row['price_type'],
            'house_type' => $row['house_type'],
            'price' => $row['price'],
            'currency' => $row['currency'],
            'bedrooms' => $row['bedrooms'],
            'bathrooms' => $row['bathrooms'],
            'size' => $row['size'],
            'size_units' => $row['size_units'],
            'address' => $row['address'],
            'housing_codes' => $row['housing_codes']
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
