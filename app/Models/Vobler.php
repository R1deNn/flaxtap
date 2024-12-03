<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

/**
 * Модель представляет собой сущность "Воблер", которая имеет следующие поля:
 * - title (строка) - заголовок Воблера
 * - background_color (строка) - цвет фона Воблера
 * - color (строка) - цвет текста Воблера
 */
class Vobler extends Model
{
    use HasFactory, AsSource, Attachable;

    protected $fillable = [
        'title',
        'background_color',
        'color',
    ];
}
