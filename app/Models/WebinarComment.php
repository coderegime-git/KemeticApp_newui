<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebinarComment extends Model
{
    // use SoftDeletes;

    protected $table = 'webinar_comment';
    public $timestamps = false;
    // protected $dateFormat = 'U';
    protected $fillable = ['user_id', 'webinar_id', 'content','created_at'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }
}