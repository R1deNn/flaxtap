<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Gift extends Model
{
    use HasFactory, AsSource, Attachable;

    protected $fillable = [
        'product_id',
        'from_price',
        'amount',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'product_id');
    }
}
