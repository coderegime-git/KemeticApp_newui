<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelCategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'reel_category_translations';
    public $timestamps = false;

    protected $fillable = [
        'reel_category_id',
        'locale',
        'title',
    ];

    public function category()
    {
        return $this->belongsTo(ReelCategory::class, 'reel_category_id');
    }
}