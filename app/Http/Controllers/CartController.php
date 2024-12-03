<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Gift;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
+     * Проверяет является ли пользователь выпускником, и возвращает цену товара соответственно
+     *
+     * @param User $user пользователь
+     * @param Shop $product товар
+     * @return float цена товара
+     */
    public function checkRole($user, $product){
        if($user->getRoles()->where('slug', 'graduate')->count() > 0){
          // Если пользователь является выпускником, то возвращаем цену для выпускников
            return $product->price_student;
        } else {
            // Если пользователь не является выпускником, то возвращаем базовую цену
            return $product->default_price;
        }
    }

     /**
+      * Проверяет является ли пользователь выпускником, получает товар по id и возвращает цену товара соответственно
+      *
+      * @param int $idUser id пользователя
+      * @param int $rules 1 - пользователь является выпускником, 0 - пользователь не является выпускником
+      * @param int $id_product id товара
+      * @return float цена товара
+      */
     public function checkPrice($idUser, $rules, $id_product){
        // Получаем товар по id
        $product = Shop::where('id', $id_product)->first();
        // Получаем пользователя по id
        $user = User::where('id', $idUser)->first();

        // Проверяем является ли пользователь выпускником и возвращаем цену товара соответственно
        $price = $this->checkRole($user, $product);

        return $price;
     }

     /**
      * Вычисляет скидку на товары, если пользователь является выпускником.
      *
      * @param int $idUser id пользователя
      * @param int $rules 1 - пользователь является выпускником, 0 - пользователь не является выпускником
      * @return float скидка на товары
      */
     private function calculateDiscount($idUser, $rules){
        $cart = Cart::where('id_user', $idUser)->get();

        $sum_price_default = 0; // Сумма цен товаров для не выпускников
        $sum_price_graduate = 0; // Сумма цен товаров для выпускников

        foreach($cart as $item){
            if($rules == 0){
                $discount = 0; // Если пользователь не является выпускником, то скидка = 0
            } else {
                if($item->amount < 5){
                    $sum_price_default += $item->product->default_price * $item->amount; // Суммируем цены товаров для не выпускников
                    $sum_price_graduate += $item->product->price_student * $item->amount; // Суммируем цены товаров для выпускников
                }
            }
        }

        $discount = $sum_price_default - $sum_price_graduate; // Скидка = сумма цен товаров для не выпускников - сумма цен товаров для выпускников

        return $discount;
     }

    /**
    +      * Вычисляет сумму товаров в корзине пользователя
    +      * если пользователь не является выпускником, то цена товара берется из поля default_price
    +      * если пользователь является выпускником, то цена товара берется из поля price_student
    +      * @param int $idUser id пользователя
    +      * @param int $rules 1 - пользователь является выпускником, 0 - пользователь не является выпускником
    +      * @return int сумма товаров в корзине пользователя
    +      */
    private function calculateSum($idUser, $rules){
        $cart = Cart::where('id_user', $idUser)->get();

        $sum = 0;

        foreach($cart as $item){
            if($rules == 0){
                $sum += $item->product->default_price * $item->amount;
                $discount = 0;
            } else {
                if($item->amount >= 5){
                    $sum += $item->product->price_opt_student * $item->amount;
                } else {
                    $sum += $item->product->default_price * $item->amount;
                }
            }
        }

        return $sum;
    }

    public function getGifts($id, $rules)
    {
        $sum = $this->calculateSum($id, $rules);
        $discount = $this->calculateDiscount(Auth::user()->id, $rules);
        $sum = $sum - $discount;

        $gifts = Gift::query()->get();
        $userGifts = [];

        foreach ($gifts as $gift) {
            $giftCount = floor($sum / $gift->from_price);
            for ($i = 0; $i < $giftCount; $i++) {
                $userGifts[] = $gift;
            }
        }

        return $userGifts;
    }

    /**
     * Отображает страницу корзины с товарами, суммой и скидкой.
     *
     * @return \Illuminate\Contracts\View\View
     */

    public function index()
    {
        // Проверяем, является ли пользователь администратором или выпускником
        $rules = 0;
        if(auth()->user()->getRoles()->where('slug', 'admin')->count() > 0){
            $rules = 1;
        } elseif(auth()->user()->getRoles()->where('slug','graduate')->count() > 0){
            $rules = 1;
        }

        // Вычисляем сумму и скидку на товары
        $sum = $this->calculateSum(Auth::user()->id, $rules);
        $discount = $this->calculateDiscount(Auth::user()->id, $rules);

        // Отображаем страницу корзины с товарами, суммой и скидкой
        return view('cart', [
            'cart' => Cart::where('id_user', Auth::user()->id)->get(),
            'random_products' => Shop::inRandomOrder()->limit(3)->get(),
            'sum' => $sum,
            'discount' => $discount,
            'gifts' => $this->getGifts(Auth::user()->id, $rules),
        ]);
    }

    /**
     * Добавление товара в корзину.
     *
     * @param Request $request HTTP-запрос с идентификатором пользователя и идентификатором товара.
     * @return \Illuminate\Http\RedirectResponse Возвращает редирект на предыдущую страницу с указанием статуса операции.
     */
    public function add(Request $request)
    {
        // Получаем существующую корзину для данного пользователя и товара
        $cart = Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $request->id)
            ->first();

        // Если корзина существует, увеличиваем количество товара и сохраняем изменения
        if ($cart) {
            $cart->amount += 1;
            $cart->save();
        } else {
            // Если корзины нет, создаем новую корзину с указанным пользователем, товаром и количеством 1
            $newCart = new Cart();
            $newCart->id_user = Auth::user()->id;
            $newCart->id_product = $request->id;
            $newCart->amount = 1;

            $newCart->save();
        }

        // Возвращаем редирект на предыдущую страницу с указанием статуса операции
        return back()->with('success', 'ok');
    }

    /**
     * Увеличивает количество товара в корзине для текущего пользователя.
     *
     * @param Request $request HTTP-запрос с идентификатором товара.
     * @return \Illuminate\Http\RedirectResponse Возвращает редирект на предыдущую страницу с указанием статуса операции.
     */
    public function increment(Request $request){
        // Получаем существующую корзину для текущего пользователя и товара
        $cart = Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $request->id)
            ->first();

        $product = Shop::where('id', $request->id)->first();

        // Если корзина существует, увеличиваем количество товара и сохраняем изменения
        if($cart){
            $cart->amount += 1;
            $cart->save();

            return back()->with('success', 'ok');
        }
        return back();
    }

    /**
     * Уменьшает количество товара в корзине для текущего пользователя.
     *
     * @param Request $request HTTP-запрос с идентификатором товара.
     * @return \Illuminate\Http\RedirectResponse Возвращает редирект на предыдущую страницу с указанием статуса операции.
     */

    public function decrement(Request $request){
        $cart = Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $request->id)
            ->first();

        if($cart){
            $cart->amount -= 1;
            if($cart->amount == 0){
                $cart->delete();
            } else {
                $cart->save();
            }
        }

        return back();
    }

    /**
     * Удаляет товар из корзины текущего пользователя.
     *
     * @param Request $request HTTP-запрос с идентификатором товара.
     * @return \Illuminate\Http\RedirectResponse Возвращает редирект на предыдущую страницу с указанием статуса операции.
     */
    public function delete(Request $request)
    {
        // Получаем товар из корзины текущего пользователя по его идентификатору
        $cart = Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $request->id)
            ->first();

        // Если товар существует, удаляем его из корзины
        if ($cart) {
            $cart->delete();
        }

        // Возвращаем редирект на предыдущую страницу с указанием статуса операции
        return back()->with('deleted', 'ok');
    }

    /**
     * Обрабатывает запрос на оформление заказа.
     *
     * @param Request $request The HTTP request.
     * @return \Illuminate\Contracts\View\View The checkout view.
     */
    public function checkout(Request $request)
    {
        // Определяем правила для вычисления суммы и скидки
        $rules = auth()->user()->getRoles()->whereIn('slug', ['admin', 'graduate'])->count() > 0 ? 1 : 0;

        // Вычисляем сумму и скидку
        $sum = $this->calculateSum(Auth::user()->id, $rules);
        $discount = $this->calculateDiscount(Auth::user()->id, $rules);

        // Возвращаем представление checkout с данными корзины, суммы и скидки
        return view('checkout', [
            'cart' => Cart::where('id_user', Auth::user()->id)->get(),
            'sum' => $sum,
            'discount' => $discount,
        ]);
    }

    /**
     * Удаляет все товары из корзины пользователя по его идентификатору.
     *
     * @param int $id Идентификатор пользователя.
     * @return void
     */
    public function emptyCart($id)
    {
        // Получаем корзину пользователя по его идентификатору
        $cart = Cart::where('id_user', $id);

        // Удаляем все товары из корзины
        $cart->delete();
    }

    /**
     * Создает новый заказ на основе данных из корзины пользователя.
     *
     * @param Request $request данные заказа
     * @return \Illuminate\Http\RedirectResponse перенаправление на страницу корзины
     */
    public function makeorder(Request $request)
    {
        // Определяем правила для вычисления суммы и скидки
        $rules = 0;
        if(auth()->user()->getRoles()->where('slug', 'admin')->count() > 0){
            $rules = 1;
        } elseif(auth()->user()->getRoles()->where('slug','graduate')->count() > 0){
            $rules = 1;
        } else {
            $rules = 0;
        }


        // Вычисляем сумму и скидку
        $sum = $this->calculateSum(Auth::user()->id, $rules);
        $discount = $this->calculateDiscount(Auth::user()->id, $rules);

        // Вычисляем итоговую сумму
        $result_sum = $sum - $discount;

        // Создаем новый заказ
        $newOrder = new Order();
        $newOrder->user_id = Auth::user()->id;
        $newOrder->type_delivery = $request->delivery;
        $newOrder->type_payment = $request->payment;
        $newOrder->fio = $request->fio;
        $newOrder->adress = $request->adress;
        $newOrder->tel = $request->tel;
        $newOrder->email = $request->email;
        $newOrder->vk = $request->vk;
        $newOrder->price = $result_sum;

        // Сохраняем заказ
        $newOrder->save();

        // Получаем корзину пользователя
        $cart = Cart::where('id_user', Auth::user()->id)->get();

        // Создаем детали заказа
        foreach ($cart as $item) {
            if($item->product->only_trainer == 1){
                if(Auth::user()->getRoles()->where('slug', 'admin')->count() <= 0){
                    die('Тренерские курсы доступны только для администраторов');
                }
            }

            $newOrderDetail = new OrderDetail();
            $newOrderDetail->order_id = $newOrder->id;
            $newOrderDetail->product_id = $item->product->id;
            $newOrderDetail->amount = $item->amount;
            $newOrderDetail->price = $this->checkPrice(Auth::user()->id, $rules, $item->product->id);

            // Сохраняем детали заказа
            $newOrderDetail->save();

            // Обновляем количество товара и количество покупок
            $decrementAmountProduct = Shop::where('id', $item->product->id)->first();
            $decrementAmountProduct->amount_buys++;
            $decrementAmountProduct->save();
        }

        $gifts = $this->getGifts(Auth::user()->id, $rules);

        foreach ($gifts as $gift) {
            $newOrderDetail = new OrderDetail();
            $newOrderDetail->order_id = $newOrder->id;
            $newOrderDetail->product_id = $gift->product->id;
            $newOrderDetail->amount = $gift->amount;
            $newOrderDetail->price = 0;
            $newOrderDetail->save();
        }

        // Очищаем корзину пользователя
        $this->emptyCart(Auth::user()->id);

        // Перенаправляем на страницу корзины с сообщением
        return redirect()->route('/cart')->with('information', 'created');
    }
}
