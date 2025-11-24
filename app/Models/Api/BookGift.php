<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGift extends Model
{
    use HasFactory;

    protected $table = 'book_gift';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'gift_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id'); // Assuming you have a Gift model
    }
}