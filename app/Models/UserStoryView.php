<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStoryView extends Model
{
    protected $table = 'user_story_views';
    
    protected $fillable = ['story_id', 'user_id'];
    public $timestamps = false;
    
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class, 'story_id');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
