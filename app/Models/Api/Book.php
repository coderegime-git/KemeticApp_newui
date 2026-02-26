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
use App\Models\BookOrder;

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
        'print_price',
        'platform_price',
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

    public function reviews()
    {
        return $this->hasMany(BookReview::class, 'book_id');
    }

    public function reports()
    {
        return $this->hasMany(BookReport::class, 'book_id');
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

    public function checkBookForSale($user)
    {
        // if ($this->getAvailability() < 1) {
        //     return apiResponse2(0, 'not_availability', trans('Scrolls are not available for sale'));
        // }

        if ($this->creator_id == $user->id) {

            return apiResponse2(0, 'same_user', trans('Cant purchase your own Scrolls'));
        }

        return 'ok';
    }

    public function checkUserHasBought($user = null): bool
    {
        $hasBought = false;

        if (empty($user)) {
            $user = auth()->user();
        }
        elseif (is_numeric($user)) {
            // If $user is an ID (integer or numeric string)
            $user = User::find($user);
        } 

        if (!empty($user)) {
            $giftsIds = Gift::query()->where('email', $user->email)
                ->where('status', 'active')
                ->whereNotNull('book_id')
                ->where(function ($query) {
                    $query->whereNull('date');
                    $query->orWhere('date', '<', time());
                })
                ->whereHas('sale')
                ->pluck('id')
                ->toArray();

            $order = BookOrder::query()->where('book_id', $this->id)
                ->where(function ($query) use ($user, $giftsIds) {
                    $query->where('buyer_id', $user->id);
                    $query->orWhereIn('gift_id', $giftsIds);
                })
                ->whereHas('sale', function ($query) use ($user) {
                    $query->whereIn('type', ['book', 'gift'])
                        ->where('access_to_purchased_item', true)
                        ->whereNull('refund_at');
                })->first();

            $hasBought = !empty($order);
        }

        return $hasBought;
    }

    public function getRate()
    {
        $rate = 0;

        $reviews = $this->reviews()
            ->get();

        if (!empty($reviews) and $reviews->count() > 0) {
            $rate = number_format($reviews->avg('rates'), 2);
        }

        if ($rate > 5) {
            $rate = 5;
        }

        return $rate > 0 ? number_format($rate, 2) : 0;
    }
}