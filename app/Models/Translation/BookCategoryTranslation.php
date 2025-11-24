<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class BookCategoryTranslation extends Model
{
    protected $table = 'book_category_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}