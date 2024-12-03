<?php

namespace App\Models;

use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Metrics\Chartable;
/**
 * @property int $id
 * @property int $id_category
 * @property string $title
 * @property string $description
 * @property float $default_price
 * @property float $price_student
 * @property float $price_opt_student
 * @property bool $active
 * @property string $image
 *
 * @property Category $category
 * @property Vobler $vobler
 */
class Shop extends Model
{
    use HasFactory, AsSource, Chartable;
    use Attachable;
    protected $fillable = [
        'id_category',
      	'id_vobler',
        'title',
        'description',
        'default_price',
        'price_student',
        'price_opt_student',
        'active',
        'only_trainer',
        'image',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function vobler(): BelongsTo
    {
        return $this->belongsTo(Vobler::class, 'id_vobler');
    }

    public function scopeOrderedBy($query, $column, $direction = 'asc')
    {
        return $query->orderBy($column, $direction);
    }

    public function certificate()
    {
        return $this->hasMany(Attachment::class)->where('group','certificate');
    }
}
