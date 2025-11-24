<?php
// app/Models/NotificationSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;
     public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'push_notifications',
        'email_updates',
        'sms_whatsapp',
        'in_app_banners',
        'reels',
        'courses',
        'books',
        'live_tv',
        'shop_orders',
        'chat_cowatch',
        'global_ranking'
    ];

    protected $casts = [
        'push_notifications' => 'boolean',
        'email_updates' => 'boolean',
        'sms_whatsapp' => 'boolean',
        'in_app_banners' => 'boolean',
        'reels' => 'boolean',
        'courses' => 'boolean',
        'books' => 'boolean',
        'live_tv' => 'boolean',
        'shop_orders' => 'boolean',
        'chat_cowatch' => 'boolean',
        'global_ranking' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserSettings($user_id)
    {
        return static::firstOrCreate(
            ['user_id' => $user_id]
            // No default values - database defaults (0/false) will be used
        );
    }
}