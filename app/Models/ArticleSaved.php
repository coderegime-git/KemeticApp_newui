<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleSaved extends Model
{
    protected $table = 'article_saved';
    
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'article_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Blog', 'article_id', 'id');
    }
}