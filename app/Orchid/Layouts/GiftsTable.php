<?php

namespace App\Orchid\Layouts;

use App\Models\Category;
use App\Models\Gift;
use App\Models\Order;
use App\Models\Shop;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GiftsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'gifts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('Идентификатор'))
                ->cantHide()
                ->sort()
                ->align(TD::ALIGN_CENTER),

            TD::make('product_id', __('Товар'))
                ->cantHide()
                ->render(function (Gift $gift) {
                    $shop = Shop::find($gift->product_id);
                    return "<a href='shop/$gift->product_id/edit' target='_blank'>$shop->title</a>";
                }),

            TD::make('from_price', __('От (руб.)'))
                ->cantHide()
                ->sort()
                ->align(TD::ALIGN_CENTER),

            TD::make('amount', __('Кол-во'))
                ->cantHide()
                ->sort()
                ->align(TD::ALIGN_CENTER),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Gift $gift) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make(__('Редактировать'))
                            ->route('platform.gift.edit', $gift->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Удалить'))
                            ->icon('bs.trash3')
                            ->confirm(__('Как только вы совершите это действие, подарок навсегда удалится'))
                            ->method('delete', [
                                'id' => $gift->id,
                            ]),
                    ])),
        ];
    }
}
