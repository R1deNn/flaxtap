<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;

/**
 * Модель пользователя.
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Attachable;

    /**
     * Атрибуты, которые могут быть присвоены на mass-assignment.
     *
     * @var array
     */
    protected $fillable = [
        'fio',
        'email',
        'tel',
        'password',
        'certificate',
        'role',
    ];

    /**
     * Атрибуты, которые должны быть скрыты для массива/JSON.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в собственные типы.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * Атрибуты, по которым можно фильтровать в url.
     *
     * @var array
     */
    protected $allowedFilters = [
           'id'         => Where::class,
           'name'       => Like::class,
           'email'      => Like::class,
           'updated_at' => WhereDateStartEnd::class,
           'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * Атрибуты, по которым можно сортировать в url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function cart()
    {
        return $this->hasMany(Cart::class, 'id_user');
    }
}
