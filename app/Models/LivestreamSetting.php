<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivestreamSetting extends Model
{
    public $timestamps = false;
    protected $dateFormat = 'U';

    protected $table = 'livestream_settings';

    protected $fillable = [
        'app_id',
        'app_sign',
        'created_at',
        'updated_at',
    ];
}
