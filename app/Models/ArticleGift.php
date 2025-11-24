<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleGift extends Model
{
    protected $table = 'article_gift';
    public $timestamps = false;
    protected $fillable = ['user_id', 'article_id', 'gift_id'];

    /**
     * Get the user that sent the gift.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get the article that received the gift.
     */
    public function article()
    {
        return $this->belongsTo('App\Models\Blog', 'article_id', 'id');
    }

    /**
     * Get the gift details.
     */
    public function gift()
    {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
}