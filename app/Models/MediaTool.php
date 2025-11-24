<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jorenvh\Share\ShareFacade;

class MediaTool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'icon',
        'status'
    ];
    
}
