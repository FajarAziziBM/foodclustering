<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilCluster extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = [
        'cluster', 'anggota_cluster'
    ];

    public function HasilClustering()
    {
        return $this->belongsTo(Province::class, 'clustergId');
    }
}
