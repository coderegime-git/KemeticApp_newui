<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Cviebrock\EloquentSluggable\Services\SlugService;

class BookCategory extends Model
{
    use HasFactory;
     use Translatable;
    use Sluggable;

    protected $table = 'book_categories';
    public $timestamps = false;

    public $translatedAttributes = ['title'];
    protected $fillable = [
        'slug',
    ];

     /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function makeSlug($title)
    {
        return SlugService::createSlug(self::class, 'slug', $title);
    }

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BookCategoryTranslation::class, 'book_category_id');
    }

    // Relationship with books (many-to-many)
    public function books()
    {
        return $this->hasMany(Book::class, 'category_id','id');
    }
}