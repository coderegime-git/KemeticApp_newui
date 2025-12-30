<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOrder extends Model
{
    use HasFactory;
    
    protected $table = 'book_order';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'book_id',
        'seller_id',
        'buyer_id',
        'sale_id',
        'quantity',
        'message_to_seller',
        'tracking_code',
        'status',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'id' => 'integer',
        'book_id' => 'integer',
        'seller_id' => 'integer',
        'sale_id' => 'integer',
        'quantity' => 'integer'
    ];
    
    public $timestamps = false;
    
    public static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'waiting_delivery' => 'Waiting Delivery',
            'shipped' => 'Shipped',
            'success' => 'Success',
            'canceled' => 'Canceled'
        ];
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
    
    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }
    
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }
    
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }
    
    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }
    
    public function getReadableStatusAttribute(): string
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }
    
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}