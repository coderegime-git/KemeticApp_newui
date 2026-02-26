<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarGift extends Model
{
    use HasFactory;

    protected $table = 'webinar_gift';
    public $timestamps = false;
    protected $fillable = ['webinar_id', 'user_id','gift_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }
    
    public function gift()
    {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
}