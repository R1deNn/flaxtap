<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class CertificateProduct extends Model
{
    use AsSource, Attachable;

    protected $table = 'certificates_product';

    protected $fillable = [
        'id_product',
        'image',
    ];
}
