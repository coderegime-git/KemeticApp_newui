<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivestreamGift extends Model
{
    protected $table = 'livestream_gift';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'livestream_id',
        'gift_id',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function livestream()
    {
        return $this->belongsTo('App\Models\Livestream', 'livestream_id', 'id');
    }
    
    public function gift()
    {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
}