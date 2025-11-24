<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ReelSaved extends Model
{
    protected $table = 'reel_saved';
    protected $fillable = ['reel_id', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}
