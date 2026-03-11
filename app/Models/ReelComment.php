<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class ReelComment extends Model
{
    use SoftDeletes;
    
    public $timestamps = false;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    public function replies()
    {
        return $this->hasMany(ReelComment::class, 'reply_id', 'id')->orderBy('created_at', 'asc');
    }
}
