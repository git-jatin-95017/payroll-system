<?php

namespace App\Imports;

use App\Models\HousingCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HousingCodesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new HousingCode([
            'item_codes' => $row['item_codes'],
            'price_type' => $row['price_type'],
            'housing_type' => $row['housing_type'],
            'bedroom_size' => $row['bedroom_size']
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
