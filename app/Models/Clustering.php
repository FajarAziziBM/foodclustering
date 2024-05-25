<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clustering extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function Clustering()
    {
        return $this->belongsTo(Province::class, 'provinsiId');
    }

}
