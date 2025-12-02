<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStory extends Model
{
    protected $table = 'user_stories';
    
    protected $fillable = [
        'user_id',
        'title',
        'media_url',
        'media_type',
        'thumbnail_url',
        'link',
        'views',
        'is_active',
        'expires_at',
        'created_at',
        'updated_at',
    ];
    
    protected $dates = ['expires_at'];
     public $timestamps = false;
    
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'integer', // Cast to integer
        'updated_at' => 'integer', // Cast to integer
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($story) {
            // Set expiration to 24 hours from creation
            $story->expires_at = now()->addHours(24);
        });
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function views(): HasMany
    {
        return $this->hasMany(UserStoryView::class, 'story_id');
    }
    
    public function viewedByCurrentUser()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return $this->views()->where('user_id', auth()->id())->exists();
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('expires_at', '>', now());
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->active()
                     ->orderBy('created_at', 'desc');
    }
}