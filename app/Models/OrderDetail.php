<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Orchid\Screen\AsSource;
/**
 * Модель деталей заказа.
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $amount
 *
 * @property \App\Models\Order $order
 * @property \App\Models\Shop $product
 * @property \Illuminate\Database\Eloquent\Collection $shop
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereProductId($value)
 */
class OrderDetail extends Model
{
    use HasFactory, AsSource, Notifiable;

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'product_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop(): HasMany
    {
        return $this->hasMany(Shop::class, 'id');
    }
}
