<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Gift;
use App\Orchid\Layouts\CategoryEditLayout;
use App\Orchid\Layouts\GiftEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GiftEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $gift;
    public function query(Gift $gift): iterable
    {
        return [
            'gift' => $gift,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->gift->exists ? 'Редактирование подарка' : 'Создание подарка';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(GiftEditLayout::class)
                ->title(__('Информация о товаре'))
                ->description(__('Обновите информацию о товаре'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->gift->exists)
                        ->method('save')
                ),
        ];
    }

    public function save(Gift $gift, Request $request)
    {
        $gift
            ->fill($request->collect('gift')->except(['gift.product_id', 'gift.from_price', 'gift.amount'])->toArray())
            ->save();

        Toast::info(__('Подарок сохранен'));

        return redirect()->route('platform.gifts');
    }
}
