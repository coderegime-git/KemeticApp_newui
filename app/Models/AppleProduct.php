<?php

namespace App\Models;

use App\Models\Traits\CascadeDeletes;
use Illuminate\Database\Eloquent\Model;


class AppleProduct extends Model 
{
    
    use CascadeDeletes;

    protected $table = 'apple_product_table';
    
    public function course()
    {
        return $this->belongsTo('App\Models\Webinar', 'reference_id', 'id');
    }
}
