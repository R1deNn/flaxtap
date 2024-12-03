<?php

namespace App\Orchid\Layouts;

use App\Models\Shop;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class GiftEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Relation::make('gift.product_id')
                ->fromModel(Shop::class, 'title')
                ->title('Выберите товар (можете начать вводить название с клавиатуры)')
                ->required(),

            Input::make('gift.from_price')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Каждые (руб.)'))
                ->placeholder(__('')),

            Input::make('gift.amount')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Сколько штук'))
                ->placeholder(__('')),
        ];
    }
}
