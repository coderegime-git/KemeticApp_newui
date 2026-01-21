<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ReelReview extends Model
{
    public $timestamps = false;
    protected $table = 'reel_review';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}
