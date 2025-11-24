<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'book_category_translations';

    public $timestamps = false;

    protected $fillable = [
        'book_category_id',
        'locale',
        'title',
    ];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }
}