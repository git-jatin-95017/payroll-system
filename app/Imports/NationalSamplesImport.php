<?php

namespace App\Imports;

use App\Models\NationalSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class NationalSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new NationalSample([
            'location_codes' => $row['location_codes'],
            'item_codes' => $row['item_codes'],
            'product' => $row['product'],
            'price' => $row['price'],
            'currency' => $row['currency'],
            'website' => $row['website'],
            'store' => $row['store'],
            'notes' => $row['notes']
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
