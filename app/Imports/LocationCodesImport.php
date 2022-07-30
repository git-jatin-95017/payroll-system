<?php

namespace App\Imports;

use App\Models\LocationCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class LocationCodesImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
    // HeadingRowFormatter::default('none');

    // public function rules(): array
    // {
    //     return [
    //         '0' => 'required|string',
    //         '1' => 'required|string',
    //         '2' => 'required|numeric',
    //         // so on
    //     ];
    // }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {                
        return new LocationCode([
            'location_codes' => $row['location_codes'],
            'city' => $row['city'],
            'province' => $row['province'],
            'postal_code' => $row['postal_code'],
            'city_province' => $row['city_province'],
            'country' => $row['country'],
            'country_code' => "00".$row['country_codes'],
            'province_code' => $row['province_codes'],
            'metropolitan_codes' => $row['metropolitan_codes'],
            'sub_metropolitan_codes' => $row['sub_metropolitan_codes'],
            'region' => $row['region'],
            'iso_3166_alpha_2' => $row['iso_3166_alpha_2'],
            'iso_3166_alpha_3' => $row['iso_3166_alpha_3'],
            'iso_4217_currency_name' => $row['iso_4217_currency_name'],
            'iso_4217_alphabetic_Codes' => $row['iso_4217_alphabetic_codes'],
            'iso_4217_numeric_Codes' => $row['iso_4217_numeric_codes'],
            'tax_codes' => $row['tax_codes']
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
