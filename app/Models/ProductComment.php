<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComment extends Model
{
    use SoftDeletes;

    protected $table = 'product_comment';
    public $timestamps = true;
    protected $dateFormat = 'U';
    protected $fillable = ['user_id', 'product_id', 'content'];
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
}