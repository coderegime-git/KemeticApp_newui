<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FmdLike extends Model
{
    protected $table = 'fmd_like';
    
    public $timestamps = false;
    
    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];
    
    protected $fillable = [
        'user_id',
        'fmd_id',
        'created_at',
        'updated_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function fmd()
    {
        return $this->belongsTo(AdReel::class, 'fmd_id');
    }
}