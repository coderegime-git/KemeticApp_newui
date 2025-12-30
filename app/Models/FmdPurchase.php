<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FmdPurchase extends Model
{
    protected $table = 'fmdpurchases';
    public $timestamps = false;

     protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];


    protected $fillable = [
        'user_id',
        'plan_code',
        'amount',
        'payment_status',
        'starts_at',
        'expires_at'
    ];
}
