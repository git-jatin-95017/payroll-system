<?php

namespace App\Imports;

use App\Models\PropertyTaxSample;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class PropertyTaxSamplesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PropertyTaxSample([
            'location_codes' => $row['location_codes'],
            'rate' => $row['rate']
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
