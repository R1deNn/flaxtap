<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;


/**
 * Модель корзины пользователя.
 *
 * @property int $id_user идентификатор пользователя
 * @property int $id_product идентификатор товара
 * @property int $amount количество товара в корзине
 *
 * @property \App\Models\Shop $product связь с моделью товара
 */
class Cart extends Model
{
    use HasFactory, AsSource, Attachable;

    protected $fillable = [
        'id_user',
        'id_product',
        'amount',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'id_product');
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'id_user');
    }
}
