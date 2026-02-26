<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livestream extends Model
{
    use HasFactory;

    protected $table = 'livestream';
    public $timestamps = false;

    protected $fillable = [
        'channel_name',
        'channel_arn',
        'ingest_endpoint',
        'stream_key',
        'stream_key_arn',
        'playback_url',
        'channel_id',
        'region',
        'type',
        'is_active',
        'recording_configuration_arn',
        'tags',
        'camera',
        'platform',
        'country',
        'livestream_end',
        'creator_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tags' => 'array'
    ];

    // Helper method to get full playback URL
    public function getFullPlaybackUrlAttribute()
    {
        return "https://{$this->playback_url}/index.m3u8";
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Helper method to get RTMPS URL
    public function getRtmpsUrlAttribute()
    {
        return "rtmps://{$this->ingest_endpoint}:443/app/";
    }

    // Helper method to get RTMP URL
    public function getRtmpUrlAttribute()
    {
        return "rtmp://{$this->ingest_endpoint}:1935/app/";
    }

    // Scope for active channels
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function likes()
    {
        return $this->hasMany(LivestreamLike::class, 'livestream_id');
    }

    public function comments()
    {
        return $this->hasMany(LivestreamComment::class, 'livestream_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\LivestreamReport', 'Livestream_id', 'id');
    }

    public function share()
    {
        return $this->hasMany('App\Models\LivestreamShare', 'Livestream_id', 'id');
    }

    public function gift()
    {
        return $this->hasMany('App\Models\LivestreamGift', 'Livestream_id', 'id');
    }

    public function review()
    {
        return $this->hasMany('App\Models\LivestreamReview', 'Livestream_id', 'id');
    }

    public function savedItems()
    {
        return $this->hasMany('App\Models\LivestreamSaved', 'Livestream_id', 'id');
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

    public function getLikeCountAttribute()
    {
        return $this->likes()->count();
    }
    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }
}