<?php

namespace App\Orchid\Screens;

use App\Models\Shop;
use App\Orchid\Layouts\ShopTable;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ShopScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'shop' => Shop::query()
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
        return 'Товары';
    }

    public function description(): ?string
    {
        return 'Здесь вы можете добавить, отредактировать и снять товар с продажи';
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
                ->route('platform.systems.shop.create'),
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
            ShopTable::class,
        ];
    }

    public function activate(Request $request): void
    {
        $category = Shop::findOrFail($request->get('id'));
        $category->active = true;
        $category->save();

        Toast::success(__('Товар теперь видно пользователям'));
    }

    public function disable(Request $request): void
    {
        $category = Shop::findOrFail($request->get('id'));
        $category->active = false;
        $category->save();

        Toast::warning(__('Товар теперь не видно пользователям'));
    }
}
