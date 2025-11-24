<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGift extends Model
{
    use SoftDeletes;

    protected $table = 'product_gift';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = ['user_id', 'product_id', 'gift_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /*
     * Relations
     * */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function gift()
    {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
}