<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Orchid\Layouts\CategorysTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CategorysScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categorys' => Category::query()
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
        return 'Категории товаров';
    }

    public function description(): string
    {
        return 'Здесь вы можете отредактировать категории, либо создать новую.';
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
                ->route('platform.category.create'),
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
            CategorysTable::class,
        ];
    }

    public function activate(Request $request): void
    {
        $category = Category::findOrFail($request->get('id'));
        $category->active = true;
        $category->save();

        Toast::success(__('Категорию теперь видно пользователям'));
    }

    public function disable(Request $request): void
    {
        $category = Category::findOrFail($request->get('id'));
        $category->active = false;
        $category->save();

        Toast::warning(__(' Категорию теперь не видно пользователям'));
    }
}
