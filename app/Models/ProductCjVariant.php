<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCjVariant extends Model
{
    protected $table = 'product_cj_variants';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'cj_pid',
        'vid',
        'variant_name',
        'variant_key',
        'variant_sku',
        'sell_price',
        'variant_image',
        'is_selected',
    ];

    protected $casts = [
        'sell_price' => 'float',
        'is_selected' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
