@section('title', 'Каталог')
<x-guest-layout>
  <div>
    <main class="mx-auto px-4 lg:max-w-7xl lg:px-8">
      <div class="border-b border-gray-200 pt-24 pb-10">
        <h1 class="text-4xl tracking-tight text-[#5e5e5e]">КАТАЛОГ</h1>
      </div>
      <div class="pt-12 pb-24 lg:grid lg:grid-cols-3 lg:gap-x-8 xl:grid-cols-4">
        <aside>
          <div class="hidden lg:block">
            <form class="divide-y divide-gray-200 space-y-10" action="{{ route('/shop') }}" method="GET">
                <div>
                    <fieldset>
                        <legend class="block text-sm font-medium text-gray-900">Категория</legend>
                        <div class="pt-6 space-y-3">
                            <div class="flex items-center">
                                <select name="category" id="underline_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-[#5e5e5e] appearance-none focus:outline-none focus:ring-0 focus:border-[#5e5e5e] peer">
                                    <option value="disabled" disabled selected>Все категории</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>

              <div class="pt-10">
                <fieldset>
                  <legend class="block text-sm font-medium text-gray-900">Отсортировать</legend>
                  <div class="pt-6 space-y-3">
                    <div class="">
                      <select name="sort_by" id="underline_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-[#5e5e5e] appearance-none focus:outline-none focus:ring-0 focus:border-[#5e5e5e] peer">
                          <option value="default_price">Цена</option>
                      </select>
                      <select name="direction" id="underline_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-[#5e5e5e] appearance-none focus:outline-none focus:ring-0 focus:border-[#5e5e5e] peer">
                          <option value="asc">По возрастанию</option>
                          <option value="desc">По убыванию</option>
                      </select>
                    </div>
                    <button type="submit" class="w-full bg-[#ffffff] hover:bg-[#5e5e5e] text-[#5e5e5e] hover:text-[#ffffff] duration-300 py-2 px-4 rounded border-2 border-[#5e5e5e]">Применить</button>
                  </div>
                </fieldset>
              </div>
            </form>
          </div>
        </aside>

        <section aria-labelledby="product-heading" class="mt-6 lg:mt-0 lg:col-span-2 xl:col-span-3">

          <div class="grid grid-cols-2 gap-y-4 sm:grid-cols-3 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-4">
            @foreach($products as $product)
                  <div class="ml-2 mr-2 group relative bg-white border border-gray-200 rounded-lg flex flex-col shadow-md hover:shadow-2xl duration-300">
                    <a href="{{route('/shop/show/{id}', $product->id)}}">
                      @if($product->id_vobler != null)
                          @if(strlen($product->vobler->title) > 10)
                                <div class="text-center py-5" style="background-color: {{$product->vobler->background_color}}">
                                    <span aria-hidden="true" class="inset-0 text-center" style="color: {{$product->vobler->color}};">{{$product->vobler->title}}</span>
                                </div>
                            @else
                                <div class="text-center py-5" style="background-color: {{$product->vobler->background_color}}">
                                    <span aria-hidden="true" class=" inset-0 text-center" style="color: {{$product->vobler->color}};">{{$product->vobler->title}}</span>
                                </div>
                            @endif
                      @endif
                      <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 duration-300 sm:aspect-none">
                        <img src="@if(isset($product->image)){{$product->image}}@else{{asset('/images/other/notfound.jpg')}}@endif" alt="{{$product->title}}." class="w-full h-full object-center object-cover sm:w-half sm:h-half">
                      </div>
                      <div class="flex-1 p-4 space-y-2 flex flex-col">
                        <h3 class="text-sm font-medium text-gray-900">
                          <a href="{{route('/shop/show/{id}', $product->id)}}">
                            <span aria-hidden="true" class="inset-0 p-5"></span>
                            {{ $product->title }}
                          </a>
                        </h3>
                        <div class="flex-1 flex flex-col justify-end">
                          <p class="text-sm italic text-gray-500">
                            @if(isset($product->category->title))
                              {{ $product->category->title }}
                            @endif
                          </p>
                            <p class="mt-4 text-2xl font-bold text-red-600">
                                <p class="text-base font-medium text-gray-900">{{ number_format($product->default_price, 0, '', ' ') }} ₽</p>
                            </p>
                            <p class="mt-4 text-2xl font-bold text-red-600">
                                <p class="text-base font-medium text-gray-900">Для вас: {{ number_format($product->price_student, 0, '', ' ') }} ₽</p>
                            </p>
                        </div>
                      </div>
                    </a>
                    @auth
                          @php
                              $cartStatus = 0;
                              foreach (Auth::user()->cart as $item) {
                                  if ($item->id_product == $product->id) {
                                      $cartStatus = 1;
                                      $amount = $item->amount;
                                      break;
                                  }
                              }
                          @endphp

                      @if($cartStatus == 0)
                          <div class="p-2">
                              <button id='cart_add_{{$product->id}}' class="cart-add-{{$product->id}} py-2 px-4 cart_add_{{$product->id}}">
                                  <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M5.00014 14H18.1359C19.1487 14 19.6551 14 20.0582 13.8112C20.4134 13.6448 20.7118 13.3777 20.9163 13.0432C21.1485 12.6633 21.2044 12.16 21.3163 11.1534L21.9013 5.88835C21.9355 5.58088 21.9525 5.42715 21.9031 5.30816C21.8597 5.20366 21.7821 5.11697 21.683 5.06228C21.5702 5 21.4155 5 21.1062 5H4.50014M2 2H3.24844C3.51306 2 3.64537 2 3.74889 2.05032C3.84002 2.09463 3.91554 2.16557 3.96544 2.25376C4.02212 2.35394 4.03037 2.48599 4.04688 2.7501L4.95312 17.2499C4.96963 17.514 4.97788 17.6461 5.03456 17.7462C5.08446 17.8344 5.15998 17.9054 5.25111 17.9497C5.35463 18 5.48694 18 5.75156 18H19M7.5 21.5H7.51M16.5 21.5H16.51M8 21.5C8 21.7761 7.77614 22 7.5 22C7.22386 22 7 21.7761 7 21.5C7 21.2239 7.22386 21 7.5 21C7.77614 21 8 21.2239 8 21.5ZM17 21.5C17 21.7761 16.7761 22 16.5 22C16.2239 22 16 21.7761 16 21.5C16 21.2239 16.2239 21 16.5 21C16.7761 21 17 21.2239 17 21.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                              </button>
                          </div>
                          <div class="flex items-center p-2" style="display: none;" id="cart_amount_{{$product->id}}">
                              <a type="button" id="decrement-button-{{$product->id}}" data-input-counter-decrement="counter-input-{{$product->id}}" class="cart-decrement-{{$product->id}} inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                  <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                  </svg>
                              </a>
                              <input type="text" id="counter-input-{{$product->id}}" data-input-counter class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0" placeholder="" value="1" required />
                              <a type="button" id="increment-button-{{$product->id}}" data-input-counter-increment="counter-input-{{$product->id}}" class="cart-increment-{{$product->id}} inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                  <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                  </svg>
                              </a>
                          </div>
                          @else
                              <div class="flex items-center p-2">
                                  <a type="button" id="decrement-button-{{$product->id}}" data-input-counter-decrement="counter-input-{{$product->id}}" class="cart-decrement-{{$product->id}} inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                      <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                      </svg>
                                  </a>
                                  <input type="text" id="counter-input-{{$product->id}}" data-input-counter class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0" placeholder="" value="{{$amount}}" required />
                                  <a type="button" id="increment-button-{{$product->id}}" data-input-counter-increment="counter-input-{{$product->id}}" class="cart-increment-{{$product->id}} inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                      <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                      </svg>
                                  </a>
                              </div>
                      @endif

                          <script>
                              $(document).ready(function() {
                                  $('.cart-increment-{{$product->id}}').click(function () {
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
                                  $('.cart-decrement-{{$product->id}}').click(function () {
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
                                $('.cart-add-{{$product->id}}').click(function () {
                                    var productId = {{$product->id}};

                                    $.ajax({
                                        type: 'POST',
                                        url: '/cart-add/' + productId,
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function (response) {
                                            document.getElementById('cart_add_{{$product->id}}').style.display = 'none';
                                            document.getElementById('cart_amount_{{$product->id}}').style.display = 'block';
                                        },
                                        error: function (error) {
                                            console.log(error);
                                        }
                                    });
                                });
                            });
                        </script>
                    @endauth
                </div>
            @endforeach
          </div>
        </section>
      </div>
    </main>
  </div>
</div>

      <div class='flex justify-center items-center'>
        <div class="py-8">
          <div class="flex items-center gap-8">
            @if($products->onFirstPage())
            <button disabled
              class="relative h-8 max-h-[32px] w-8 max-w-[32px] select-none rounded-lg border border-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:opacity-75 focus:ring focus:ring-gray-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
              type="button">
              <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                  aria-hidden="true" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
              </span>
            </button>
            @else
            <a href="{{ $products->previousPageUrl() }}"
              class="relative h-8 max-h-[32px] w-8 max-w-[32px] select-none rounded-lg border border-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:opacity-75 focus:ring focus:ring-gray-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
              type="button">
              <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                  aria-hidden="true" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
              </span>
            </a>
            @endif
            <p class="block font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
              Страница <strong class="text-gray-900">{{ $products->currentPage() }}</strong> из
              <strong class="text-gray-900">{{ $products->lastPage() }}</strong>
            </p>
            @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}"
              class="relative h-8 max-h-[32px] w-8 max-w-[32px] select-none rounded-lg border border-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:opacity-75 focus:ring focus:ring-gray-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
              type="button">
              <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                  aria-hidden="true" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
              </span>
            </a>
            @else
            <button disabled
              class="relative h-8 max-h-[32px] w-8 max-w-[32px] select-none rounded-lg border border-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:opacity-75 focus:ring focus:ring-gray-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
              type="button">
              <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                  aria-hidden="true" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
              </span>
            </button>
            @endif
          </div>
        </div>
      </div>
  </div>
</div>
</x-guest-layout>
