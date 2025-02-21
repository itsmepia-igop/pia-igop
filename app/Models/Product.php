<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'thickness' => 'array',
        'sizes' => 'array'
    ];

    public function raw_materials()
    {
        return $this->hasMany('App\Models\ProductRawMaterial');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\File', 'file_id', 'id');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Models\Feedback');
    }

    public function batches()
    {
        return $this->hasMany('App\Models\ProductBatch');
    }
}
