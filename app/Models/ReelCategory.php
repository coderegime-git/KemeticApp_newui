<?php
// app/Models/ReelCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ReelCategory extends Model
{
    use HasFactory, Translatable, Sluggable;

    protected $table = 'reel_categories';
    public $timestamps = false;

    public $translatedAttributes = ['title'];
    protected $fillable = [
        'slug',
    ];
    
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

    public function translations(): HasMany
    {
        return $this->hasMany(ReelCategoryTranslation::class, 'reel_category_id');
    }
    
    public function reels()
    {
        return $this->hasMany(Reel::class, 'category_id', 'id');
    }
    
    public function getDetailsAttribute(){
        return [
            'id'=>$this->id ,
            'title'=>$this->title
        ] ;
    }
}