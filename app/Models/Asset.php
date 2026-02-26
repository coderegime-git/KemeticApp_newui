<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
     protected $table = 'asset';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'path',
        'type',
    ];
    
    public function getFileUrlAttribute()
    {
        
        if ($this->path) {
            return url('/store/assets/' . $this->path);
        }
        return null;
    }
    
    public function getFileNameAttribute()
    {
        if ($this->path) {
            //return basename($this->path);
        }
        return null;
    }
}