<?php

namespace App\Models;

use App\Cart\Money;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const PAYMENT_FAILD = 'payment_faild';
    const COMPLETED = 'completed';
    const REFUND_REQUESTED = 'refund_requested';
    const REFUNDING = 'refunding';
    const REFUND_COMPLETED = 'refund_completed';

    protected $fillable = [
        'user_id',
        'address_id',
        'shipping_method_id',
        'payment_method_id',
        'status',
        'subtotal'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($order){
            $order->status = self::PENDING;
        });
    }

    public function getSubTotalAttribute($subtotal)
    {
        return new Money($subtotal);
    }

    public function total()
    {
        return $this->subtotal->add($this->shipping_method->price);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function products()
    {
        return $this->belongsToMany(ProductVariation::class, 'product_variation_order')
                    ->withPivot(['quantity'])
                    ->withTimestamps();
    }
}
