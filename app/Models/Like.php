<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

/**
 * Модель_like - таблица likes в базе данных.
 * Данная таблица используется для хранения данных о лайках пользователем к товарам.
 *
 * @property int $id - уникальный идентификатор записи.
 * @property int $user_id - идентификатор пользователя, который оставил лайк.
 * @property int $product_id - идентификатор товара, к которому оставлен лайк.
 * @property timestamp $created_at - время создания записи.
 * @property timestamp $updated_at - время последнего обновления записи.
 */
class Like extends Model
{
    use HasFactory, AsSource, Attachable;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * Возвращает связь с товаром, к которому оставлен лайк.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'product_id');
    }
}
