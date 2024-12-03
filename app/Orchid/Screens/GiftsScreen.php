<?php

namespace App\Orchid\Screens;

use App\Models\Gift;
use App\Models\Shop;
use App\Orchid\Layouts\GiftsTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class GiftsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'gifts' => Gift::query()
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Подарки при покупке';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('platform.gift.create'),
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
            GiftsTable::class,
        ];
    }

    public function delete(Request $request): void
    {
        $gift = Gift::findOrFail($request->get('id'));
        $gift->delete();

        Toast::warning(__('Подарок удален'));
    }
}
