<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['namaprovinsi', 'luaspanen', 'produktivitas', 'produksi', 'tahun'];

    public function Clustering()
    {
        return $this->hasMany(Clustering::class, "provinsiId", 'id');
    }

    public function hasilClusters()
    {
        return $this->hasMany(HasilCluster::class, 'clusterId', 'id');
    }

}
