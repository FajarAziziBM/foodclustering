<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilCluster extends Model
{
    use HasFactory;

    protected $table = 'hasil_clusters';
    protected $guarded = ['id'];
    protected $fillable = [
        'cluster', 'anggota_cluster'
    ];

    public function clustering()
    {
        return $this->belongsTo(Clustering::class, 'clusterId', 'id');
    }
}
