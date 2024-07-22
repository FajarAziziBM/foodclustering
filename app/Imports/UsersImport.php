<?php

namespace App\Imports;

use App\Models\Clustering;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow{

    /**
     * @param array $row
     *
     * @return User|null
     */

    private $year;

    public function __construct($year)
    {
        $this->year = $year;
    }
    public function model(array $row)
    {
        $rows = [
            'namaprovinsi' => $row['provinsi'],
            'luaspanen' => $row['luas_panen_tanaman_padi_ha'] ?? 0,
            'produktivitas' => $row['produktivitas_tanaman_padi_kuha'] ?? 0,
            'produksi' => $row['rekap_produksi_padi_ton'] ?? 0,
            'tahun' => $this->year,
        ];

        return new Province([
            'namaprovinsi' => $rows['namaprovinsi'],
            'luaspanen' => $rows['luaspanen'],
            'produktivitas' => $rows['produktivitas'],
            'produksi' => $rows['produksi'],
            'tahun' => $rows['tahun'],
        ]);
    }

}


