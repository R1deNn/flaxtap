<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Like;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    /**
     * Отображает страницу с товарами. Принимает запрос и возвращает представление с товарами и категориями.
     *
     * @param Request $request запрос с параметрами сортировки и направления сортировки
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View представление с товарами и категориями
     */
    public function index(Request $request)
    {
        return view('shop', [
            'products' => $this->getSortedProducts($request),
            'categories' => Category::all()
        ]);
    }

    /**
     * Возвращает отсортированные товары в зависимости от переданных параметров
     *
     * @param Request $request запрос с параметрами сортировки и направления сортировки
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator отсортированные товары
     */
    private function getSortedProducts(Request $request)
    {
        $sortBy = $request->input('sort_by', 'default_price');
        $direction = $request->input('direction', 'asc');
        $category = $request->input('category');

        if(!empty($category)){
            return Shop::where('id_category', $category)->orderedBy($sortBy, $direction)->paginate(12);
        } else {
            return Shop::orderedBy($sortBy, $direction)->paginate(12);
        }
    }

    public function show_category($id)
    {
        return view('shop', [
            'products' => Shop::where('id_category', $id)->paginate(12),
            'categories' => Category::all()
        ]);
    }

    /**
     * Отображает страницу товара с указанным id.
     * Проверяет, есть ли у текущего пользователя товар в избранном или в корзине.
     *
     * @param int $id id товара
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View страница товара
     */
    public function show($id)
    {
        $amountInCart = 0;

        if(auth()->user() != null){
            // проверяем, есть ли товар в избранном у текущего пользователя
            $likeStatus = Like::where('user_id', auth()->user()->id)->where('product_id', $id)->exists();
            // проверяем, есть ли товар в корзине у текущего пользователя
            $cartStatus = Cart::where('id_user', auth()->user()->id)->where('id_product', $id)->exists();

            if($cartStatus == 1){
                $amountInCart = Cart::where('id_user', auth()->user()->id)->where('id_product', $id)->first();
                $amountInCart = $amountInCart->amount;
            }
        } else {
            // если пользователь не авторизован, ставим флаги в false
            $likeStatus = false;
            $cartStatus = false;
        }

        return view('current-product', [
            'product' => Shop::where('id', $id)->first(),
            'amountInCart' => $amountInCart,
            'certificates' => Shop::where('id', $id)->first()->attachment()->get(),
            'random_products' => Shop::inRandomOrder()->limit(3)->get(),
            'likeStatus' => $likeStatus,
            'cartStatus' => $cartStatus,
        ]);
    }
}
