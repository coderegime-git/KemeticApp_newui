<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $fillable = ['webinar_id', 'blog_id', 'user_id', 'ip_address', 'likes', 'views', 'shares'];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }
}
