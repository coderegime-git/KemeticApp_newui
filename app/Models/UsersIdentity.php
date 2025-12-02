<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersIdentity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_identity';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'legal_name',
        'country_id',
        'dob',
        'city',
        'identity_scan',
        'certificate',
        'notes',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date', // If you want to cast dob as Carbon date
        'created_at' => 'datetime', // Cast UNIX timestamp to datetime
        'country_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Since you have created_at as INT UNSIGNED

    /**
     * Get the user that owns the identity.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the country associated with the identity.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Set the created_at attribute.
     * Converts datetime to UNIX timestamp for storage.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = is_numeric($value) ? $value : strtotime($value);
    }

    /**
     * Get the created_at attribute.
     * Returns Carbon instance from UNIX timestamp.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Carbon|null
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromTimestamp($value) : null;
    }

    /**
     * Get dob as formatted date string.
     *
     * @return string|null
     */
    public function getFormattedDobAttribute()
    {
        return $this->dob ? $this->dob->format('Y-m-d') : null;
    }
}