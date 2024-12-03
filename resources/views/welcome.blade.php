@section('title', 'Главная')
<x-guest-layout>
    <style>
        .swiper {
            width: 80%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 1px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            object-fit: cover;
        }

        .swiper-button-next, .swiper-button-prev {
            color: #5e5e5e;
        }
        .swiper-pagination-bullet-active{
            color: #5e5e5e;
            background-color: #5e5e5e;
        }
    </style>
        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide" data-swiper-autoplay="4000">
                        <img src="{{asset('storage/' . $banner->path . $banner->name . '.' . $banner->extension)}}" alt="">
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Initialize Swiper -->
        <script>
            var swiper = new Swiper(".mySwiper", {
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false
                },
                pagination: {
                    el: ".swiper-pagination",
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },

                on: {
                    tap: function (){
                        window.open('{{$link}}', '_blank');
                    }
                }
            });
        </script>

    <div class="2xl:container 2xl:mx-auto">
        <div class="lg:px-20 md:px-6 px-4 md:py-12 py-8">
            <div>
                <h1 class="text-3xl lg:text-4xl font-semibold text-[#5e5e5e] text-left">КАТЕГОРИИ</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8 mt-8 md:mt-10">
                @foreach($categorys as $category)
                    <a href="{{route('/shop/category/{id}', $category->id)}}">
                        <div class="bg-gray-50 p-8 rounded shadow-md">
                            <div class="flex justify-center items-center mt-8 md:mt-24">
                                @if($category->image == null)
                                    <img class="rounded" src="{{asset('/images/other/notfound.jpg')}}" alt="{{$category->title}}..." role="img" />
                                @else
                                    <img class="rounded" src="{{asset($category->image)}}" alt="{{$category->title}}..." role="img" />
                                @endif
                            </div>
                            <div class="product-name">
                                <h2 class="title text-xl text-center text-gray-600">{{$category->title}}</h2>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="2xl:container 2xl:mx-auto">
        <div class="lg:px-20 md:px-6 px-4 md:py-12 py-8">
            <div>
                <h1 class="text-3xl lg:text-4xl font-semibold text-[#5e5e5e] text-left">БЕСТСЕЛЛЕРЫ</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8 mt-8 md:mt-10">
                @foreach($bestsellers as $bestseller)
                    <a href="{{route('/shop/show/{id}', $bestseller->id)}}">
                        <div class="bg-gray-50 p-4 rounded shadow-md">
                            <div class="product-name p-4">
                                <h2 class="title text-xl text-gray-600">{{$bestseller->title}}</h2>
                                <p class="text-xl font-semibold text-gray-800 mt-2">{{ number_format($bestseller->default_price, 0, '', ' ') }} ₽</p>
                            </div>
                            <div class="flex justify-center items-center mt-4 mb-4 md:mt-12">
                                @if($bestseller->image == null)
                                    <img class="rounded" src="{{asset('/images/other/notfound.jpg')}}" alt="{{$category->title}}..." role="img" />
                                @else
                                    <img class="rounded" src="{{asset($bestseller->image)}}" alt="{{$bestseller->title}}..." role="img" />
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if($sales->count() > 0)

    <div class="2xl:container 2xl:mx-auto">
        <div class="lg:px-20 md:px-6 px-4 md:py-12 py-8">
            <div>
                <h1 class="text-3xl lg:text-4xl font-semibold text-[#5e5e5e] text-left">АКЦИИ</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mt-8 md:mt-10">
                @foreach($sales as $sale)
                    <div class="bg-gray-50 p-8 rounded shadow-md">
                        <a href="{{route('/shop/show/{id}', $sale->id)}}">
                            <div class="product-name">
                                <h2 class="title text-xl text-gray-600">{{$sale->title}}</h2>
                                <p class="text-xl font-semibold text-gray-800 mt-2">{{ number_format($sale->default_price, 0, '', ' ') }} ₽</p>
                            </div>
                            @if($sale->id_vobler != 0)
                                <div class="rounded mt-4 md:mt-24" style="background-color: {{$sale->vobler->background_color}}; display: flex; justify-content: center; align-items: center;">
                                    <span aria-hidden="true" class="p-2" style="color: {{$sale->vobler->color}};">{{$sale->vobler->title}}</span>
                                </div>
                            @endif
                            <div class="flex justify-center items-center mt-2 md:mt-4">
                                @if($sale->image == null)
                                    <img class="rounded" src="{{asset('/images/other/notfound.jpg')}}" alt="{{$category->title}}..." role="img" />
                                @else
                                    <img class="rounded" src="{{asset($sale->image)}}" alt="{{$sale->title}}}..." role="img" />
                                @endif
                            </div>
                            <div class="flex justify-end items-center space-x-2 mt-8 md:mt-24">
                                <a href="{{route('/shop/show/{id}', ['id' => $sale->id])}}" aria-label="show in white color" class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600 rounded">
                                    <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.00014 14H18.1359C19.1487 14 19.6551 14 20.0582 13.8112C20.4134 13.6448 20.7118 13.3777 20.9163 13.0432C21.1485 12.6633 21.2044 12.16 21.3163 11.1534L21.9013 5.88835C21.9355 5.58088 21.9525 5.42715 21.9031 5.30816C21.8597 5.20366 21.7821 5.11697 21.683 5.06228C21.5702 5 21.4155 5 21.1062 5H4.50014M2 2H3.24844C3.51306 2 3.64537 2 3.74889 2.05032C3.84002 2.09463 3.91554 2.16557 3.96544 2.25376C4.02212 2.35394 4.03037 2.48599 4.04688 2.7501L4.95312 17.2499C4.96963 17.514 4.97788 17.6461 5.03456 17.7462C5.08446 17.8344 5.15998 17.9054 5.25111 17.9497C5.35463 18 5.48694 18 5.75156 18H19M7.5 21.5H7.51M16.5 21.5H16.51M8 21.5C8 21.7761 7.77614 22 7.5 22C7.22386 22 7 21.7761 7 21.5C7 21.2239 7.22386 21 7.5 21C7.77614 21 8 21.2239 8 21.5ZM17 21.5C17 21.7761 16.7761 22 16.5 22C16.2239 22 16 21.7761 16 21.5C16 21.2239 16.2239 21 16.5 21C16.7761 21 17 21.2239 17 21.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @endif

    <section class="bg-[#d7d7d7]">
        <div class="mx-auto w-full max-w-7xl px-5 md:px-10 py-6">
            <div class="text-center sm:p-10 md:p-16">
            <h2 class="mb-4 text-3xl text-[#5e5e5e] font-bold md:text-3xl">ПОДПИШИТЕСЬ НА НОВОСТИ И АКЦИИ</h2>
            <form name="email-form" method="get" class="relative mx-auto mb-4 flex w-full max-w-2xl flex-col items-start justify-center sm:flex-row">
                <input type="email" class="mb-3 mr-6 block h-9 w-full px-6 py-7 bg-[#d7d7d7] border-[#5e5e5e] text-[#5e5e5e] text-sm focus:border-[#5e5e5e]" placeholder="Введите ваш e-mail" required="" />
                <button type="submit" class="inline-block w-full cursor-pointer px-10 py-4 text-center bg-[#f5f5f5] font-medium text-[#5e5e5e] transition sm:w-64">ПОДПИСАТЬСЯ</button>
            </form>
            </div>
        </div>
    </section>
</x-guest-layout>
