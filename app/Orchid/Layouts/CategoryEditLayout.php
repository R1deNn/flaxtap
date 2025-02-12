<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CategoryEditLayout extends Rows
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
            Input::make('category.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Заголовок'))
                ->placeholder(__('')),

            Cropper::make('category.image')
                ->title(__('Изображение'))
                ->width(1000)
                ->height(1000)
                ->targetRelativeUrl(),
        ];
    }
}
