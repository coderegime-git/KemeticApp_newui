<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FmdComment extends Model
{
    protected $table = 'fmd_comment';
    
    public $timestamps = false;
    
    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
        'deleted_at' => 'integer',
    ];
    
    protected $fillable = [
        'user_id',
        'fmd_id',
        'content',
        'created_at',
        'updated_at',
        'deleted_at'
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