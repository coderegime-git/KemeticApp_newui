<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterTest extends Model
{
    protected $table = 'newsletter';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
