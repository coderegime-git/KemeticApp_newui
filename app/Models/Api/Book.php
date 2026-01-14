<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\CascadeDeletes;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Jorenvh\Share\ShareFacade;

class Book extends Model
{
    use HasFactory;
    use Translatable;
    use Sluggable;
    use CascadeDeletes;

    protected $table = 'book';
    public $timestamps = false;

    public $translatedAttributes = ['title', 'description', 'content'];

    protected $fillable = [
        'creator_id',
        'category_id',
        'slug',
        'image_cover',
        'url',
        'price',
        'shipping_price',
        'book_price',
        'cover_pdf',
        'page_count',
        'type',
        'created_at',
        'updated_at',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BookTranslation::class, 'book_id');
    }

    public function translation($locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        return $this->hasOne(BookTranslation::class, 'book_id')
                    ->where('locale', $locale);
    }

    public static function makeSlug($title)
    {
        return SlugService::createSlug(self::class, 'slug', $title);
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id','id');
    }

    public function categories()
    {
        return $this->belongsTo(BookCategory::class, 'category_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(BookLike::class, 'book_id');
    }

    public function comments()
    {
        return $this->hasMany(BookComment::class, 'book_id');
    }

    public function share()
    {
        return $this->hasMany(BookShare::class, 'book_id');
    }

    public function gift()
    {
        return $this->hasMany(BookGift::class, 'book_id');
    }

    public function savedItems()
    {
        return $this->hasMany(BookSaved::class, 'book_id');
    }

    public function getLikeCountAttribute()
    {
        return $this->likes()->count();
    }

    // Accessor for share_count
    public function getShareCountAttribute()
    {
        return $this->share()->count();
    }

    // Accessor for gift_count
    public function getGiftCountAttribute()
    {
        return $this->gift()->count();
    }

    // Accessor for comment_count
    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }

    // Accessor for saved_count
    public function getSavedCountAttribute()
    {
        return $this->savedItems()->count();
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price ? number_format($this->price, 2) : null;
    }

    // Check if book is free
    public function getIsFreeAttribute()
    {
        return $this->price === null || $this->price == 0;
    }
}