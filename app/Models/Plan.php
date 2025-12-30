<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';

    public $timestamps = false;

    protected $casts = [
        'price' => 'integer',
        'duration_days' => 'integer',
        'is_membership' => 'boolean',
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    protected $fillable = [
        'code',
        'title',
        'price',
        'duration_days',
        'is_membership',
        'created_at',
        'updated_at'
    ];
}
