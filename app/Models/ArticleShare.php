<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleShare extends Model
{
    protected $table = 'article_share';
    public $timestamps = true;
    protected $fillable = ['user_id', 'article_id'];

    /**
     * Get the user that owns the share record.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get the article that was shared.
     */
    public function article()
    {
        return $this->belongsTo('App\Models\Blog', 'article_id', 'id');
    }
}