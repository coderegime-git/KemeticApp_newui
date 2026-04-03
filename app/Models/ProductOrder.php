<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $table = 'product_orders';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $status = ['pending', 'waiting_delivery', 'shipped', 'success', 'canceled'];
    static $waitingDelivery = 'waiting_delivery';
    static $shipped = 'shipped';
    static $success = 'success';
    static $canceled = 'canceled';
    static $pending = 'pending';

    const CJ_STATUS_SUBMITTED       = 'submitted';
    const CJ_STATUS_CART_FAILED     = 'cart_failed';
    const CJ_STATUS_PAYMENT_PENDING = 'payment_pending';
    const CJ_STATUS_PAYMENT_FAILED  = 'payment_failed';
    const CJ_STATUS_SHIPPED         = 'shipped';
    const CJ_STATUS_DELIVERED       = 'delivered';
    const CJ_STATUS_CANCELLED       = 'cancelled';

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo('App\User', 'seller_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo('App\User', 'buyer_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id', 'id');
    }

    public function gift()
    {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
    
    public function getBuyerInfoAttribute()
    {
        return \App\User::where('id', $this->buyer_id)
            ->orWhere('device_id_or_ip_address', $this->buyer_id)
            ->first();
    }

    public function getCjSpecificationsAttribute(): array
    {
        $specs = json_decode($this->specifications ?? '{}', true);
        return is_array($specs) ? $specs : [];
    }
 
    /**
     * Returns true when this ProductOrder was created from a
     * CJ Dropshipping product (either proxy or direct cj_vid).
     */
    public function getIsCjProductAttribute(): bool
    {
        $specs = $this->cj_specifications;
        if (($specs['source'] ?? '') === 'cj_dropship') return true;
 
        // Also check the Product model's is_cj_product flag
        return optional($this->product)->is_cj_product ?? false;
    }
 
    /**
     * True when the CJ order has been submitted but tracking
     * is not yet available.
     */
    public function getCjPendingTrackingAttribute(): bool
    {
        return $this->cj_order_id
            && empty($this->cj_tracking_number)
            && $this->cj_status === self::CJ_STATUS_SUBMITTED;
    }
}
