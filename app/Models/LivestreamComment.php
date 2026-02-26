<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestreamComment extends Model
{
    use HasFactory;
    
    protected $table = 'livestream_comment';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'livestream_id',
        'content',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function livestream()
    {
        return $this->belongsTo(Livestream::class, 'livestream_id');
    }
}