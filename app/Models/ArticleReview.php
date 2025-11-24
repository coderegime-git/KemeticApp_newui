<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReview extends Model
{
    protected $table = 'article_reviews';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function blog()
    {
        return $this->belongsTo('App\Models\Blog', 'article_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }
}
