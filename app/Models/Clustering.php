<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clustering extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'eps', 'minpts', 'num_clusters', 'num_noise', 'num_clustered', 'silhouette_index', 'is_best'
    ];

    public function Clustering()
    {
        return $this->belongsTo(Province::class, 'provinsiId');
    }

    public function HasilClustering()
    {
        return $this->hasMany(HasilCluster::class, 'id');
    }
}
