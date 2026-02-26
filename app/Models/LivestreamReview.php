<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LivestreamReview extends Model
{
    use SoftDeletes;
    
    protected $table = 'livestream_review';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'livestream_id',
        'review',
        'rating',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
        'deleted_at' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function livestream()
    {
        return $this->belongsTo('App\Models\Livestream', 'livestream_id', 'id');
    }
}