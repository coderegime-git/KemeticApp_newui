<?php

namespace App\Models\Api;

use App\Models\AppleProduct as Model;

class AppleProduct extends Model
{


    public function course()
    {
        return $this->belongsTo('App\Models\Api\Webinar', 'reference_id', 'id');
    }


}
