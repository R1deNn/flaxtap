<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{

    /**
     * Отображает главную страницу сайта.
     *
     * Возвращает представление с информацией о баннере, топ-3 товаров по количеству покупок
     * и товаров со скидкой.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('welcome', [
            'banners' => Banner::where('id', 1)->first()->attachment()->get(),
            'link' => Banner::where('id', 1)->value('button_link'),
            'categorys' => Category::all(),
            'bestsellers' => Shop::query()->orderedBy('amount_buys', 'desc')->take(5)->get(),
            'sales' => Shop::query()->where('id_vobler', '!=', '0')->take(3)->get(),
        ]);
    }
}
