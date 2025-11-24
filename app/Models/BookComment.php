<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'book_comment';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'content',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    // public function replies()
    // {
    //     return $this->hasMany(BookCommentReply::class, 'comment_id'); // If you have reply functionality
    // }
}