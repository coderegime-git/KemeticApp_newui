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
}