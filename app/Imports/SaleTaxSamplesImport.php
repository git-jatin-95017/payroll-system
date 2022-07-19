<?php

namespace App\Imports;

use App\Models\SaleTaxSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class SaleTaxSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SaleTaxSample([
            'location_codes' => $row['location_codes'],
            'item_codes' => $row['item_codes'],
            'rate' => $row['rate']
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
