<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;


/**
 * Модель баннера для отображения рекламных карточек на сайте.
 *
 * @property string $title Заголовок баннера
 * @property string $description Описание баннера
 * @property string $button_text Текст кнопки баннера
 * @property string $button_link Ссылка кнопки баннера
 * @property string $image Изображение баннера
 */
class Banner extends Model
{
    use HasFactory, AsSource, Attachable;

    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'image',
    ];
}
