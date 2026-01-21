<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvsChatToken extends Model
{
    protected $table = 'ivs_chat_tokens';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'livestream_id',
        'chat_room_arn',
        'chat_room_title',
        'chat_token',
        'capabilities',
        'expires_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'capabilities' => 'array',
        'expires_at' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
        'deleted_at' => 'integer'
    ];
}
