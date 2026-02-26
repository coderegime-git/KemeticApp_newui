<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftReel extends Model
{
    use HasFactory;

    protected $table = 'giftreel';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'thumbnail',
        'created_at',
        'updated_at'
    ];
    
    public function reelGifts()
    {
        return $this->hasMany(ReelGift::class);
    }
}