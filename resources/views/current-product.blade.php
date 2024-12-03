@section('title', $product->title)
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .swiper {
        width: 600px;
        height: 600px;
    }

</style>
<x-guest-layout>
          <section class="container flex-grow mx-auto max-w-[1200px] border-b py-5 lg:grid lg:grid-cols-2 lg:py-10">
            <div class="container mx-auto px-4">
                <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                    <img
                    class="w-full"
                    src="@if(isset($product->image)){{$product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif"
                    alt=""
                  />
                </button>

                @if($certificates->count() > 0)
                    <!-- Slider main container -->
                    <div class="swiper container flex-grow mx-auto max-w-[1200px] border-b py-5 lg:grid lg:grid-cols-2 lg:py-10">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            @foreach($certificates as $certificate)
                                    <div class="swiper-slide">
                                        <button data-modal-target="default-modal-{{$certificate->id}}" data-modal-toggle="default-modal-{{$certificate->id}}" class="block focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                                            <img src="{{asset('storage/' . $certificate->path . $certificate->name . '.' . $certificate->extension)}}" alt="">
                                        </button>
                                    </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination"></div>

                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                    </div>
                @endif
            </div>

              <div class="mx-auto px-5 lg:px-5 max-w-md">
              <h2 class="pt-3 text-2xl font-bold lg:pt-0">{{$product->title}}</h2>

              <p class="font-bold mt-4">
                Категория: <span class="font-normal">
                  @if(isset($product->category->title))
                    {{ $product->category->title }}
                  @else
                    Нет категории
                  @endif
                </span>
              </p>

              @auth
                  @if (auth()->user()->getRoles()->where('slug', 'graduate')->count() >= 0)
                    <p class="mt-4 text-2xl font-bold text-red-600">
                      <span class='text-[#5e5e5e] line-through'>{{ number_format($product->default_price, 0, ',', ' ') }} ₽</span> {{number_format($product->price_student)}} ₽
                    </p>
                  @else
                    <p class="mt-4 text-4xl font-bold text-[#5e5e5e]">
                      {{ number_format($product->default_price, 0, ',', ' ') }} ₽
                    </p>
                  @endif

              @else
                    <p class="mt-4 text-4xl font-bold text-[#5e5e5e]">
                      {{ number_format($product->default_price, 0, ',', ' ') }} ₽
                    </p>
              @endauth

                  <p>Оптовая цена (от 5 шт.): {{ number_format($product->price_opt_student, 0, ',', ' ') }} ₽</p>

              <p class="pt-5 text-sm leading-5 text-gray-500">
                {!! $product->description !!}
              </p>

                  @if($cartStatus == 1)
                      <div class="flex items-center">
                          <a type="button" id="cart-decrement" data-input-counter-decrement="counter-input-4" class="cart-decrement inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                              <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                              </svg>
                          </a>
                          <input type="text" id="counter-input-4" data-input-counter class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0" placeholder="" value="{{$amountInCart}}" required />
                          <a type="button" id="cart-increment" data-input-counter-increment="counter-input-4" class="cart-increment inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                              <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                              </svg>
                          </a>
                      </div>
                  @endif

              <div class="mt-7 flex flex-row gap-6">
                    @auth
                        @if($cartStatus == 1)
                        <a href="{{route('/cart')}}" id="cart-remove" class="cart-remove py-2 px-4 bg-[#5e5e5e] text-[#ffffff] border border-[#5e5e5e] duration-300 hover:bg-[#ffffff] hover:text-[#5e5e5e] hover:rounded">
                          В корзине
                        </a>
                        @else
                            @if($product->only_trainer == 1)
                                @if(auth()->user()->getRoles()->where('slug', 'trainer')->count() >= 1)
                                  <button id='cart_add' class="cart-add py-2 px-4 bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded">
                                      В корзину
                                  </button>
                                @else
                                    <p>Этот товар только для пользователей, имеющих статус "Тренер"</p>
                                @endif

                            @else
                              <button id='cart_add' class="cart-add py-2 px-4 bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded">
                                  В корзину
                              </button>
                            @endif


                          <a href="{{route('/cart')}}" id="cart_remove" style="display: none" class="cart-remove py-2 px-4 bg-[#5e5e5e] text-[#ffffff] border border-[#5e5e5e] duration-300 hover:bg-[#ffffff] hover:text-[#5e5e5e] hover:rounded">
                              В корзине
                          </a>
                        @endif
                        @else
                            <a href="{{route('login')}}"
                              class="py-2 px-4 bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded"
                              >
                                  В корзину
                            </a>
                        @endauth

                        @auth
                        @if($likeStatus == 1)
                            <button id='dislike-button'
                            class="color-[#5e5e5e] dislike-button py-2 px-4 bg-white border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded"
                        >
                          <svg id='Dislike_24' width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>


                            <g transform="matrix(0.4 0 0 0.4 12 12)" >
                            <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" translate(-25, -25)" d="M 0.99023438 -0.009765625 C 0.583311277086795 -0.009658780598669443 0.21702530776786666 0.23698978690308747 0.06390329693557661 0.6140044721593956 C -0.08921871389671332 0.9910191574157037 0.0013573911775308645 1.4232192226171734 0.29296875 1.7070312 L 48.292969 49.707031 C 48.54378557313715 49.96827179479288 48.91623599221815 50.07350663500295 49.26667805152247 49.98214981098403 C 49.6171201108268 49.89079298696512 49.89079298696512 49.6171201108268 49.98214981098403 49.26667805152247 C 50.07350663500295 48.91623599221815 49.96827179479288 48.54378557313715 49.707031 48.292969 L 38.707031 37.292969 C 43.727352 32.997375 48 27.511313 48 20 C 48 12.832 42.168 7 35 7 C 31.104 7 27.458 8.7342187 25 11.699219 C 22.542 8.7342188 18.896 7 15 7 C 13.072792 7 11.246985 7.4305093 9.6015625 8.1875 L 1.7070312 0.29296875 C 1.5187601198341525 0.09943601102382937 1.2602360845040457 -0.009749897834049892 0.9902343800000003 -0.009765624999999778 z M 15 9 C 18.692 9 22.119969 10.841781 24.167969 13.925781 L 25 15.181641 L 25.832031 13.925781 C 27.880031 10.841781 31.308 9 35 9 C 41.065 9 46 13.935 46 20 C 46 26.769239 42.000718 31.840614 37.279297 35.865234 L 11.134766 9.7207031 C 12.338504 9.2624331 13.637182 9 15 9 z M 6.2070312 10.451172 C 3.6280312 12.828172 2 16.223 2 20 C 2 31.601 12.169703 38.407906 19.595703 43.378906 C 21.499703 44.652906 23.142375 45.754531 24.359375 46.769531 L 25 47.302734 L 25.640625 46.769531 C 26.857625 45.754531 28.500297 44.653906 30.404297 43.378906 C 32.036297 42.286906 33.800547 41.105688 35.560547 39.804688 L 34.125 38.367188 C 32.475 39.579188 30.822969 40.692797 29.292969 41.716797 C 27.648969 42.817797 26.193 43.79275 25 44.71875 C 23.807 43.79275 22.351031 42.817797 20.707031 41.716797 C 13.656031 36.996797 4 30.533 4 20 C 4 16.775 5.4030938 13.878234 7.6210938 11.865234 L 6.2070312 10.451172 z" stroke-linecap="round" />
                            </g>
                          </svg>
                          </button>

                          <button id='like-button' style="display: none"
                          class="like-button py-2 px-4 bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded"
                      >
                        <svg id='Heart_Outline_24' width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>
                          <g transform="matrix(0.5 0 0 0.5 12 12)" >
                          <path style="color: rgb(12, 105, 180); stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" translate(-25, -26.66)" d="M 16.375 9 C 10.117188 9 5 14.054688 5 20.28125 C 5 33.050781 19.488281 39.738281 24.375 43.78125 L 25 44.3125 L 25.625 43.78125 C 30.511719 39.738281 45 33.050781 45 20.28125 C 45 14.054688 39.882813 9 33.625 9 C 30.148438 9 27.085938 10.613281 25 13.0625 C 22.914063 10.613281 19.851563 9 16.375 9 Z M 16.375 11 C 19.640625 11 22.480469 12.652344 24.15625 15.15625 L 25 16.40625 L 25.84375 15.15625 C 27.519531 12.652344 30.359375 11 33.625 11 C 38.808594 11 43 15.144531 43 20.28125 C 43 31.179688 30.738281 37.289063 25 41.78125 C 19.261719 37.289063 7 31.179688 7 20.28125 C 7 15.144531 11.1875 11 16.375 11 Z" stroke-linecap="round" />
                          </g>
                        </svg>
                        </button>
                        @else
                            <button id='dislike-button' style="display: none"
                            class="dislike-button py-2 px-4 bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded"
                        >
                            <svg id='Dislike_24' width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>


                              <g transform="matrix(0.4 0 0 0.4 12 12)" >
                              <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" translate(-25, -25)" d="M 0.99023438 -0.009765625 C 0.583311277086795 -0.009658780598669443 0.21702530776786666 0.23698978690308747 0.06390329693557661 0.6140044721593956 C -0.08921871389671332 0.9910191574157037 0.0013573911775308645 1.4232192226171734 0.29296875 1.7070312 L 48.292969 49.707031 C 48.54378557313715 49.96827179479288 48.91623599221815 50.07350663500295 49.26667805152247 49.98214981098403 C 49.6171201108268 49.89079298696512 49.89079298696512 49.6171201108268 49.98214981098403 49.26667805152247 C 50.07350663500295 48.91623599221815 49.96827179479288 48.54378557313715 49.707031 48.292969 L 38.707031 37.292969 C 43.727352 32.997375 48 27.511313 48 20 C 48 12.832 42.168 7 35 7 C 31.104 7 27.458 8.7342187 25 11.699219 C 22.542 8.7342188 18.896 7 15 7 C 13.072792 7 11.246985 7.4305093 9.6015625 8.1875 L 1.7070312 0.29296875 C 1.5187601198341525 0.09943601102382937 1.2602360845040457 -0.009749897834049892 0.9902343800000003 -0.009765624999999778 z M 15 9 C 18.692 9 22.119969 10.841781 24.167969 13.925781 L 25 15.181641 L 25.832031 13.925781 C 27.880031 10.841781 31.308 9 35 9 C 41.065 9 46 13.935 46 20 C 46 26.769239 42.000718 31.840614 37.279297 35.865234 L 11.134766 9.7207031 C 12.338504 9.2624331 13.637182 9 15 9 z M 6.2070312 10.451172 C 3.6280312 12.828172 2 16.223 2 20 C 2 31.601 12.169703 38.407906 19.595703 43.378906 C 21.499703 44.652906 23.142375 45.754531 24.359375 46.769531 L 25 47.302734 L 25.640625 46.769531 C 26.857625 45.754531 28.500297 44.653906 30.404297 43.378906 C 32.036297 42.286906 33.800547 41.105688 35.560547 39.804688 L 34.125 38.367188 C 32.475 39.579188 30.822969 40.692797 29.292969 41.716797 C 27.648969 42.817797 26.193 43.79275 25 44.71875 C 23.807 43.79275 22.351031 42.817797 20.707031 41.716797 C 13.656031 36.996797 4 30.533 4 20 C 4 16.775 5.4030938 13.878234 7.6210938 11.865234 L 6.2070312 10.451172 z" stroke-linecap="round" />
                              </g>
                            </svg>
                            </button>

                          <button id='like-button'
                            class="like-button py-2 px-4 bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded"
                        >
                          <svg id='Heart_Outline_24' width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>
                            <g transform="matrix(0.5 0 0 0.5 12 12)" >
                            <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" translate(-25, -26.66)" d="M 16.375 9 C 10.117188 9 5 14.054688 5 20.28125 C 5 33.050781 19.488281 39.738281 24.375 43.78125 L 25 44.3125 L 25.625 43.78125 C 30.511719 39.738281 45 33.050781 45 20.28125 C 45 14.054688 39.882813 9 33.625 9 C 30.148438 9 27.085938 10.613281 25 13.0625 C 22.914063 10.613281 19.851563 9 16.375 9 Z M 16.375 11 C 19.640625 11 22.480469 12.652344 24.15625 15.15625 L 25 16.40625 L 25.84375 15.15625 C 27.519531 12.652344 30.359375 11 33.625 11 C 38.808594 11 43 15.144531 43 20.28125 C 43 31.179688 30.738281 37.289063 25 41.78125 C 19.261719 37.289063 7 31.179688 7 20.28125 C 7 15.144531 11.1875 11 16.375 11 Z" stroke-linecap="round" />
                            </g>
                          </svg>
                          </button>
                        @endif
                    @else

                    @endauth


              </div>
                @auth
                  @if($cartStatus == 1)
                    <div class="mt-4">
                      <a href="{{route('/cart-delete', $product->id)}}" class="text-[#5e5e5e] duration-300 hover:underline hover:text-[#5e5e5e] hover:rounded">
                        Удалить из корзины
                      </a>
                    </div>
                  @else
                      <div class="mt-4" style="display: none" id="remove_from_cart">
                          <a href="{{route('/cart-delete', $product->id)}}" class="text-[#5e5e5e] duration-300 hover:underline hover:text-[#5e5e5e] hover:rounded">
                              Удалить из корзины
                          </a>
                      </div>
                  @endif
                @endauth
            </div>
          </section>

          <p class="mx-auto mt-10 mb-5 max-w-[1200px] px-5">Мы рекомендуем: </p>

          <section
            class="container mx-auto grid max-w-[1200px] grid-cols-2 gap-3 px-5 pb-10 lg:grid-cols-4"
          >

            @foreach ($random_products as $random_product)
              <div class="flex flex-col">
                <img
                  class=""
                  src="@if(isset($random_product->image)){{$random_product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif"
                  alt="{{$random_product->title}}..."
                />

                <div>
                  <p class="mt-2">{{$random_product->title}}</p>
                  <p class="font-medium text-black mt-2">
                    @auth
                      @if (auth()->user()->getRoles()->where('slug', 'graduate')->count() >= 0)
                        <p class="mt-4 text-xl font-bold text-red-600">
                          <span class='text-[#5e5e5e] line-through'>{{ number_format($random_product->default_price, 0, ',', ' ') }} ₽</span> {{number_format($random_product->price_student)}} ₽
                        </p>
                      @else
                        <p class="mt-4 text-2xl font-bold text-[#5e5e5e]">
                          {{ number_format($random_product->default_price, 0, ',', ' ') }} ₽
                        </p>
                      @endif

                      @else
                            <p class="mt-4 text-2xl font-bold text-[#5e5e5e]">
                              {{ number_format($random_product->default_price, 0, ',', ' ') }} ₽
                            </p>
                    @endauth
                  </p>
                  <div class="mt-5">
                      <a href="{{route('/shop/show/{id}', $random_product->id)}}"
                          class="flex h-12 w-1/2 items-center justify-center bg-white text-[#5e5e5e] border border-[#5e5e5e] duration-300 hover:bg-[#ededed] hover:rounded"
                      >
                          Подробнее
                      </a>
                  </div>
                </div>
              </div>
            @endforeach
          </section>
    <!-- Main modal -->
    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <p class="font-normal text-gray-500">
                        <img
                        class="w-full"
                        src="@if(isset($product->image)){{$product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif"
                        alt=""
                      />
                    </p>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    @foreach($certificates as $certificate)
        <div id="default-modal-{{$certificate->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <p class="font-normal text-gray-500 dark:text-gray-400">
                            <img
                                class="h-96 w-full"
                                src="{{asset('storage/' . $certificate->path . $certificate->name . '.' . $certificate->extension)}}"
                                alt=""
                            />
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button data-modal-hide="default-modal-{{$certificate->id}}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</x-guest-layout>
<script>
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'vertical',
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
    });

</script>
@auth

<script>
    $(document).ready(function() {
        $('.cart-add').click(function () {
            var productId = {{$product->id}};

            $.ajax({
                type: 'POST',
                url: '/cart-add/' + productId,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Товар добавлен в корзину",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    document.getElementById('cart_add').style.display = 'none';
                    document.getElementById('cart_remove').style.display = 'block';
                    document.getElementById('remove_from_cart').style.display = 'block';

                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.cart-increment').click(function () {
            var productId = {{$product->id}};

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
        $('.cart-decrement').click(function () {
            var productId = {{$product->id}};

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

<script>
  $(document).ready(function() {
    $('.like-button').click(function() {
        var productId = {{$product->id}};
        var userId = {{ auth()->user()->id }};

        $.ajax({
            type: 'POST',
            url: '/shop/like/' + productId + '/' + userId,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Товар добавлен в избранное",
                    showConfirmButton: false,
                    timer: 1500
                });

                document.getElementById('like-button').style.display = 'none';
                document.getElementById('dislike-button').style.display = 'block';

            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('.dislike-button').click(function() {
      var productId = {{$product->id}};
      var userId = {{ auth()->user()->id }};

      $.ajax({
        type: 'POST',
        url: '/shop/dislike/' + productId + '/' + userId,
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          document.getElementById('dislike-button').style.display = 'none';
          document.getElementById('like-button').style.display = 'block';
        },
        error: function(error) {
          console.log(error);
        }
      });
    });
  });
</script>
@endauth
