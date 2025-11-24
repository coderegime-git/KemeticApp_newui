<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ProductFile extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'product_files';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $Active = 'active';
    static $Inactive = 'inactive';
    static $fileStatus = ['active', 'inactive'];

    public $translatedAttributes = ['title', 'description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function getDownloadUrl()
    {
        return '/panel/store/products/files/' . $this->id . '/download';
    }

    public function getOnlineViewUrl()
    {
        // $parts = explode('/', $this->path, 5);
        // $before = implode('/', array_slice($parts, 0, 4));
        // $after = $parts[4] ?? '';
        // $afterReplaced = str_replace([' ', '.', '/'], '-', $after);
        // $result = $before . '/' . $afterReplaced;
        // return url($result);
        return url($this->path);
    }
}
