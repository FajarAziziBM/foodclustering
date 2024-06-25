<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clustering extends Model
{
    use HasFactory;

    protected $table = 'clusterings';
    protected $guarded = ['id'];

    public function Clustering()
    {
        return $this->belongsTo(Province::class, 'provinsiId', 'id');
    }

    public function hasilClusters()
    {
        return $this->hasMany(HasilCluster::class, 'clusterId', 'id');
    }
}
