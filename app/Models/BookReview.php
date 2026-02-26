<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookReview extends Model
{
    use SoftDeletes;
    
    protected $table = 'book_review';
    
    protected $primaryKey = 'id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'book_id',
        'review',
        'rating',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
        'deleted_at' => 'integer'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function book()
    {
        return $this->belongsTo('App\Models\Book', 'book_id', 'id');
    }
}