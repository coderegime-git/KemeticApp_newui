<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reel extends Model
{
    // use SoftDeletes;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'is_processed'   => 'boolean',
        'is_hidden'      => 'boolean',
        'category_id'    => 'integer',
        'duration'       => 'integer',
        'views_count'    => 'integer',
        'likes_count'    => 'integer',
        'comments_count' => 'integer',
        'review_count'   => 'integer',
        'reports_count'  => 'integer',
        'gift_count'     => 'integer',
        'share_count'    => 'integer',
        'saved_count'    => 'integer',
    ];

    protected $attributes = [
        'title' => '',
        'caption' => '',
        'video_path' => '',
        'category_id' => 0,
        'thumbnail_path' => '',
        'processed_video_path' => '',
        'duration' => 0,
        'views_count' => 0,
        'likes_count' => 0,
        'comments_count' => 0,
        'review_count' => 0,
        'reports_count' => 0,
        'gift_count' => 0,
        'share_count' => 0,
        'saved_count' => 0,
        'is_processed' => false,
        'is_hidden' => false
    ];

    // Accessors that should always be appended in JSON
    protected $appends = ['video_url', 'thumbnail_url', 'processed_video_url'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(ReelLike::class);
    }

    public function comments()
    {
        return $this->hasMany(ReelComment::class);
    }

    public function review()
    {
        return $this->hasMany(ReelReview::class);
    }

    public function shares()
    {
        return $this->hasMany(ReelShare::class);
    }

    public function gifts()
    {
        return $this->hasMany(ReelGift::class);
    }

    public function savedreel()
    {
        return $this->hasMany(ReelSaved::class);
    }

    public function reports()
    {
        return $this->hasMany(ReelReport::class);
    }

    public function views()
    {
        return $this->hasMany(ReelView::class);
    }

    public function category()
    {
        return $this->belongsTo(ReelCategory::class, 'category_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false)
                     ->where('is_processed', true);
    }

    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getVideoUrlAttribute()
    {
        if (!$this->video_path) {
            return '';
        }

        $basePath = request()->getSchemeAndHttpHost();

        // If processed video exists, return that, otherwise original video
        $videoPath = $this->is_processed && $this->processed_video_path
            ? $this->processed_video_path
            : $this->video_path;

        return $basePath . '/store/reels/videos/' . $videoPath;
    }

    public function getProcessedVideoUrlAttribute()
    {
        if (!$this->processed_video_path) {
            return '';
        }

        $basePath = request()->getSchemeAndHttpHost();
        return $basePath . '/store/reels/videos/' . $this->processed_video_path;
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail_path) {
            return '';
        }

        $basePath = request()->getSchemeAndHttpHost();
        return $basePath . '/store/reels/thumbnails/' . $this->thumbnail_path;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isViewedBy(User $user)
    {
        return $this->views()->where('user_id', $user->id)->exists();
    }

    public function isReportedBy(User $user)
    {
        return $this->reports()->where('user_id', $user->id)->exists();
    }

    public function shouldBeHidden()
    {
        return $this->reports_count >= 15;
    }

    public function checkAndUpdateHiddenStatus()
    {
        if ($this->shouldBeHidden() && !$this->is_hidden) {
            $this->update(['is_hidden' => true]);
            // Optional: trigger notification to admin here
        }
    }
}
