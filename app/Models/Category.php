<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;


/**
 * Модель категории товаров.
 *
 * Она представляет собой сущность, которая хранит информацию о различных категориях товаров,
 * которые могут быть представлены на сайте. Каждая категория имеет свой уникальный
 * идентификатор, название и изображение.
 *
 * @property int $id Уникальный идентификатор категории.
 * @property string $title Название категории.
 * @property string $image Изображение категории.
 *
 * @package App\Models
 */
class Category extends Model
{
    use HasFactory, AsSource, Attachable;

    protected $fillable = [
        'title',
        'image',
    ];
}
