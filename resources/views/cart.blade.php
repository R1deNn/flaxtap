@section('title', 'Корзина')
<x-guest-layout>
    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">КОРЗИНА</h2>

            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
            <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                <div class="space-y-6">

                    @if($cart->count() == 0)
                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:p-6">
                            <div class="space-y-4 md:items-center md:justify-between md:gap-6 md:space-y-0">
                                <p class="py-4 text-2xl font-medium text-gray-900">Ваша корзина пуста.</p>
                                <a href="{{route('/shop')}}" class="underline py-4 text-xl font-medium text-gray-900">Исправим?</a>
                            </div>
                        </div>
                    @endif

                    @foreach ($cart as $item)

                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:p-6">
                            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                            <a href="#" class="shrink-0 md:order-1">
                                <img class="h-20 w-20" src="@if(isset($item->product->image)){{$item->product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif" alt="{{$item->product->title}}.." />
                            </a>

                            <label for="counter-input" class="sr-only">Количество:</label>
                            <div class="flex items-center justify-between md:order-3 md:justify-end">
                                <div class="flex items-center">
                                    <a type="button" id="decrement-button-{{$item->id}}" data-input-counter-decrement="counter-input-{{$item->id}}" class="cart-decrement-{{$item->id}} inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                        <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                        </svg>
                                    </a>
                                    <input type="text" id="counter-input-{{$item->id}}" data-input-counter class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0" placeholder="" value="{{$item->amount}}" required />
                                    <a type="button" id="increment-button-{{$item->id}}" data-input-counter-increment="counter-input-{{$item->id}}" class="cart-increment-{{$item->id}} inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                        <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                        </svg>
                                    </a>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        $('.cart-increment-{{$item->id}}').click(function () {
                                            var productId = {{$item->product->id}};

                                            $.ajax({
                                                type: 'POST',
                                                url: '/cart-increment/' + productId,
                                                data: {
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                success: function (response) {
                                                    console.log(response);
                                                },
                                                error: function (error) {
                                                    console.log(error);
                                                }
                                            });
                                        });
                                    });

                                    $(document).ready(function() {
                                        $('.cart-decrement-{{$item->id}}').click(function () {
                                            var productId = {{$item->product->id}};

                                            $.ajax({
                                                type: 'POST',
                                                url: '/cart-decrement/' + productId,
                                                data: {
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                success: function (response) {
                                                    console.log(response);
                                                },
                                                error: function (error) {
                                                    console.log(error);
                                                }
                                            });
                                        });
                                    });
                                </script>

                                <div class="text-end md:order-4 md:w-32">
                                <p class="text-base font-bold text-gray-900">
                                    @if (auth()->user()->getRoles()->where('slug', 'graduate')->count() > 0)
                                    <p class="text-base font-bold text-red-900">
                                        <span class="line-through">{{ number_format($item->product->default_price, 0, '', ' ') }} ₽</span>
                                    </p>
                                    @if($item->amount >= 5)
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($item->product->price_opt_student, 0, '', ' ') }} ₽</p>
                                        @else
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($item->product->price_student, 0, '', ' ') }} ₽</p>
                                    @endif
                                    @else
                                        <p class="text-2xl font-bold text-gray-900">{{ number_format($item->product->default_price, 0, '', ' ') }} ₽</p>
                                    @endif

                                    @if ($item->amount >= 5)
                                        <p class="text-base font-bold text-red-900">
                                            <span class="">Оптовая покупка</span>
                                        </p>
                                    @endif
                                </p>
                                </div>
                            </div>

                            <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                <a href="{{route('/shop/show/{id}', $item->product->id)}}" class="text-base font-medium text-gray-900 hover:underline">{{$item->product->title}}</a>

                                <div class="flex items-center gap-4">

                                <a href="{{route('/cart-delete', $item->product->id)}}" type="button" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline">
                                    <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                    </svg>
                                    Удалить
                                </a>
                                </div>
                            </div>
                            </div>
                        </div>

                    @endforeach

                </div>

                @if(count($gifts) > 0)
                    <div class="hidden xl:mt-8 xl:block">
                        <h3 class="text-2xl font-semibold text-gray-900">ПОДАРКИ</h3>
                        <div class="mt-6 grid grid-cols-1 gap-4 sm:mt-8">

                            @foreach($gifts as $gift)
                                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:p-6">
                                    <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                        <a href="{{route('/shop/show/{id}', $gift->product->id)}}" class="shrink-0 md:order-1">
                                            <img class="h-20 w-20" src="@if(isset($gift->product->image)){{$gift->product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif" alt="{{$gift->product->title}}.." />
                                        </a>

                                        <label for="counter-input" class="sr-only">Количество:</label>
                                        <div class="flex items-center justify-between md:order-3 md:justify-end">
                                            <div class="flex items-center">
                                                <input type="text" id="counter-input-{{$gift->id}}" data-input-counter class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0" placeholder="" value="{{$gift->amount}}" required />
                                            </div>
                                            <div class="flex items-center">
                                                <p>шт.</p>
                                            </div>
                                        </div>

                                        <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                            <a href="{{route('/shop/show/{id}', $gift->product->id)}}" class="text-base font-medium text-gray-900 hover:underline">{{$gift->product->title}}</a>
                                        </div>

                                        <div class="text-end md:order-4 md:w-32">
                                            <p class="text-base font-bold text-gray-900">
                                            <p class="text-base font-bold text-red-900">
                                                <span class="">БЕСПЛАТНО</span>
                                            </p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif

                <div class="hidden xl:mt-8 xl:block">
                    <h3 class="text-2xl font-semibold text-gray-900">Пользователи также покупают</h3>
                    <div class="mt-6 grid grid-cols-3 gap-4 sm:mt-8">

                        @foreach($random_products as $product)
                            <div class="space-y-6 overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <a href="{{route('/shop/show/{id}', $product->id)}}" class="overflow-hidden rounded">
                                    <img class="mx-auto h-44 w-44" src="@if(isset($product->image)){{$product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif" alt="{{$product->title}}...." />
                                    <img class="mx-auto hidden h-44 w-44" src="{{$product->image}}" alt="{{$product->title}}..." />
                                </a>
                                <div>
                                    <a href="{{route('/shop/show/{id}', $product->id)}}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline">{{$product->title}}</a>
                                    <p class="mt-2 text-base font-normal text-gray-500">
                                        @if(isset($product->category->title))
                                            {{ $product->category->title }}
                                        @else
                                            Нет категории
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-900">
                                        @if (auth()->user()->getRoles()->where('slug', 'graduate')->count() <= 0)
                                            {{ number_format($product->default_price, 0, '', ' ') }} ₽
                                        @else
                                            <p class="text-base font-bold text-red-900">
                                                <span class="line-through">{{ number_format($product->default_price, 0, '', ' ') }} ₽</span>
                                            </p>
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($product->price_student, 0, '', ' ') }} ₽</p>
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-6 flex items-center gap-2.5">
                                    <button data-tooltip-target="favourites-tooltip-{{$product->id}}" type="button" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white p-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100">
                                        <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z"></path>
                                        </svg>
                                    </button>
                                    <a href="{{route('/cart-add', $product->id)}}" type="button" class="inline-flex w-full items-center justify-center rounded-lg bg-[#5e5e5e] px-5 py-2.5 text-sm font-medium  text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                                        <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4" />
                                        </svg>
                                            В корзину
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            @if($cart->count() != 0)
            <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6">

                <div class="space-y-4">
                    <dl class="flex items-center justify-between gap-4 pt-2">
                    <dt class="text-base font-bold text-gray-900">ИТОГО</dt>
                    <dd class="text-base font-bold text-gray-900">{{ number_format($sum-$discount, 0, '', ' ') }} ₽</dd>
                    </dl>
                </div>

                <a href="{{route('/checkout')}}" class="flex w-full items-center justify-center rounded-lg border-2 border-[#5e5e5e] duration-300 hover:bg-[#5e5e5e] hover:text-white px-5 py-2.5 text-sm font-medium text-[#5e5e5e] hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">Перейти к оформлению</a>

                <div class="flex items-center justify-center gap-2">
                    <span class="text-sm font-normal text-gray-500"> или </span>
                    <a href="{{route('/shop')}}" title="" class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline">
                    Продолжить покупки
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                    </svg>
                    </a>
                </div>
                </div>
            </div>
            @endif

            </div>
        </div>
    </section>
</x-guest-layout>

@if(session('information'))
<script>
    Swal.fire({
    title: "Заказ оформлен",
    text: "Наш менеджер скоро с вами свяжется для уточнения деталей. Для отслеживания статуса заказа перейдите в личный кабинет",
    icon: "success",

    confirmButtonColor: "#d5d5d5",
    confirmButtonText: "Перейти в личный кабинет"
    }).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "{{route('/dashboard-your-orders')}}";
    }
    });
</script>
@endif
