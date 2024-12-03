<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Orchid\Attachment\Attachable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use HasFactory, AsSource, Notifiable, Attachable, Chartable;

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = [
        // id пользователя
        'user_id',
        // тип доставки
        'type_delivery',
        // фио
        'fio',
        // адрес
        'adress',
        // телефон
        'tel',
        // email
        'email',
        // ссылка на страницу vk
        'vk',
        // цена
        'price',
        // статус
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OrderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detailsUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
