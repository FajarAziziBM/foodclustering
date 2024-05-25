<?php

namespace App\Imports;

use App\Models\Clustering;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */

    public function model(array $row)
    {
        // dd($row);

        return new Province([
           'namaprovinsi'     => $row['provinsi'],
           'luaspanen'    => $row['luas_panen_tanaman_padi_ha_ha'],
           'produktivitas' => $row['produktivitas_tanaman_padi_kuha_kuha'],
           'produksi' => $row['rekap_produksi_padi_ton_ton'],
        ]);
    }
}
