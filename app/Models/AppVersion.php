<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'android_app_version',
        'ios_app_version',
        'force_update',
        'update_message',
        'status',
    ];
    
}