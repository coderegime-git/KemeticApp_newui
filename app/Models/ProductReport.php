<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $table = 'product_report';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'reason',
        'description',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}