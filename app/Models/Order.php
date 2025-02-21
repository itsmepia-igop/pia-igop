<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public static function boot() {
        parent::boot();
    
        static::created(function ($item) {
            Cache::forget('pending');
            Cache::forget('to_pay');
            Cache::forget('to_review');
            Cache::forget('on_process');
            Cache::forget('to_deliver');
            Cache::forget('rejected');
            Cache::forget('completed');
        });

        static::updated(function ($item) {
            Cache::forget('pending');
            Cache::forget('to_pay');
            Cache::forget('to_review');
            Cache::forget('on_process');
            Cache::forget('to_deliver');
            Cache::forget('rejected');
            Cache::forget('completed');
        });
    }

    protected $guarded = [];

    protected $appends = ['reference'];

    protected $casts = [
        'estimate_delivery' => 'date'
    ];

    public function getReferenceAttribute()
    {
        return $this->created_at->format('Y') . '-' . sprintf("%06d", $this->id);
    }

    public function status()
    {
        return $this->belongsTo('App\Models\OrderStatus', 'order_status_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function design()
    {
        return $this->belongsTo('App\Models\File', 'file_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function payment_file()
    {
        return $this->belongsTo('App\Models\File', 'payment', 'id');
    }
}
