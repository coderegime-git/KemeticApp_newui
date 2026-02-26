<?php

namespace App\Models\Api;

use App\Models\Blog as Model;
use App\Models\Traits\CascadeDeletes;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Blog extends Model implements TranslatableContract
{
    use Translatable;
    use Sluggable;
    use CascadeDeletes;

    protected $table = 'blog';
    public $timestamps = false;

    public $translatedAttributes = ['title', 'description', 'meta_description', 'content'];

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

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getMetaDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'meta_description');
    }

    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }

    public  function getDetailsAttribute()
    {
        $user = auth('api')->user();
        if($user)
        {
            $userid = $user->id;
        }
        else
        {
            $userid = null;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' =>($this->image)? url($this->image):null,
            'description' => truncate($this->description, 160),
            'content' => $this->content,
            'created_at' => $this->created_at,
            'locale'=>$this->locale ,
            'author' => $this->author->brief,
            'like_count' => $this->like()->count(),
            'is_liked' => $this->isLikedByUser($userid),
            'is_saved' => $this->isSavedByUser($userid),
            'share_count' => $this->share()->count(),
            'gift_count' => $this->gift()->count(), 
            'review_count' => $this->reviews()->count(), // Added review count
            'reviews' => $this->reviews()
                ->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'user_id' => $item->creator ? $item->creator->id : null,
                        'article_id' => $this->id,
                        'review' => $item->description,
                        'rating' => $item->rates,
                        'created_at' => $item->created_at,
                        'username' => $item->creator ? $item->creator->full_name  : 'Deleted User',
                        'avatar' =>  $item->creator ? url($item->creator->getAvatar()) : '',
                    ];
                }),
            'rating' => $this->getRate(),
            'comment_count' => $this->comments()->where('status','active')->count(),
            'comments' => $this->comments()->where('status','active')
                ->get()->map(function ($item) {
                    return $item->details;
                }),
            'category'=>$this->category->title ,
            'badges'=>$this->badges ?? [] ,
        ];
    }

    public function getBriefAttribute(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' =>($this->image)? url($this->image):null,
            'description' => truncate($this->description, 160),
            'created_at' => $this->created_at,
            'author' => $this->author->brief,
            'comment_count' => $this->comments->count(),
            'category'=>$this->category->title ,
        ];
    }

    public function isLikedByUser($userid = null)
    {
        if (!$userid) {
            $user = auth('api')->user();
            if($user)
            {
                $userid = $user->id;
            }
            else
            {
                $userid = null;
            }
        }
        
        if (!$userid) {
            return false;
        }

        return $this->like()
            ->where('user_id', $userid)
            ->exists();
    }

    public function isSavedByUser($userid = null)
    {
        if (!$userid) {
            $user = auth('api')->user();
            if($user)
            {
                $userid = $user->id;
            }
            else
            {
                $userid = null;
            }
        }
        
        if (!$userid) {
            return false;
        }

        return $this->saveditems()
            ->where('user_id', $userid)
            ->exists();
    }


    public function badges()
    {
        return $this->hasMany('App\Models\Api\ProductBadgeContent', 'targetable_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Api\User', 'author_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\ArticleReport', 'article_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Api\Comment', 'blog_id', 'id');
    }

    public function share()
    {
        return $this->hasMany('App\Models\ArticleShare', 'article_id', 'id');
    }

    public function like()
    {
        return $this->hasMany('App\Models\ArticleLike', 'article_id', 'id');
    }

    public function saveditems()
    {
        return $this->hasMany('App\Models\ArticleSaved', 'article_id', 'id');
    }

    public function gift()
    {
        return $this->hasMany('App\Models\ArticleGift', 'article_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\ArticleReview', 'article_id', 'id');
    }

    public function getRate()
    {
        $rate = 0;

        $reviews = $this->reviews()
            ->where('status', 'active')
            ->get();

        if (!empty($reviews) and $reviews->count() > 0) {
            $rate = number_format($reviews->avg('rates'), 2);
        }

        if ($rate > 5) {
            $rate = 5;
        }

        return $rate > 0 ? number_format($rate, 2) : 0;
    }


    public function scopeHandleFilters( $query)
    {
        $request=request() ;
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);
        $category=$request->get('cat',null) ;

        if (!empty($offset) && !empty($limit)) {
            $query->skip($offset);
        }
        if (!empty($limit)) {
            $query->take($limit);
        }

        if($category){
            $query->where('category_id',$category) ;
        }

        return $query;
    }

}
