<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdReel extends Model
{
    protected $table = 'ad_reels';

    public $timestamps = false;

     protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'media_image',
        'stars',
        'reviews',
        'product_id',
        'plan_code',
        'trending_score',
        'starts_at',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_code', 'code');
    }
    
    public function purchases()
    {
        return $this->hasMany(FmdPurchase::class, 'reel_id');
    }

    public function isMember()
    {
        return in_array($this->plan_code, [
            'small_weekly',
            'medium_monthly',
            'large_yearly'
        ]);
    }

    public function getVideoUrlAttribute()
    {
        return url('/store/reels/videos/' . $this->media_image);
    }
    
    public function getThumbnailUrlAttribute()
    {
        // You can generate thumbnail URL here or use a default
        return asset('assets/default/img/video-thumbnail.jpg');
    }
}
