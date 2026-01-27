<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleReport extends Model
{
    use HasFactory;

    protected $table = 'article_report';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'article_id',
        'reason',
        'description',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get the reported article.
     */
    public function article()
    {
        return $this->belongsTo('App\Models\Blog', 'article_id', 'id');
    }
}