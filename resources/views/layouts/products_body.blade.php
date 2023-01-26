<textarea id="filter_json" hidden>
    {{ $all_products }}
</textarea>
<textarea id="filtered_products_json" hidden>
    {{ $filtered_products }}
</textarea>
<textarea id="local_category_id" hidden>
    {{ $local_category_id }}
</textarea>
@foreach ($products as $item)
    <article class="card card-product-list">
        <div class="row g-0">
            <aside class="col-xl-3 col-lg-4 col-md-12 col-12">
                @if ($item->statusName != '')
                    <span class="badge bg-{{ $item->statusColor }}"> {{ $item->statusName }}</span>
                @endif

                <a href="{{ route('product', ['id' => $item->id]) }}" class="img-wrap"> <img
                        src="{{ Str::replaceFirst('public', 'storage', asset($item->mainPicture))}}"> </a>
            </aside> <!-- col.// -->
            <div class="col-xl-6 col-lg col-md-7 col-sm-7 border-start">
                <div class="card-body card-body-list">

                    <a href="{{ route('product', ['id' => $item->id]) }}" class="title b">{!! $item->name !!}</a>
                    <ul class="list-dots mb-2">

                        <li><b>{{ __('l.code') }}:</b> {{ $item->code }}</li>
                        <li><b>{{ __('l.brand') }}:</b> {{ $item->brandName }}</li>


                        <li><b>{{ __('l.retail_price') }}:</b>
                        {{ number_format($item->retailPriceUah, 2, ',', ' ') }}
                            UAH / {{ $item->multiplicity_unit }}.</li>
                        @if (!$hidePrice)
                            <li><b>{{ __('l.personal_price_uah') }}:</b>
                                {{ number_format($item->personalPriceUah, 2, ',', ' ') }} UAH /
                                {{ $item->multiplicity_unit }}.</li>
                            <li><b>{{ __('l.personal_price_currency') }}:</b>
                                {{ number_format($item->personalPrice, 2, ',', ' ') }}
                                {{ $item->personalPriceCurrency }} / {{ $item->multiplicity_unit }}.</li>
                        @endif


                    </ul>
                </div> <!-- card-body.// -->
            </div> <!-- col.// -->
            <aside class="col-xl-3 col-lg-auto col-md-5 col-sm-5">
                <div class="info-aside">
                    <div class="price-wrap">
                        <span class="b">{{ __('l.your_stock') }}: </span>
                        <span class="">{{ $item->myAmount }} {{ $item->multiplicity_unit }}.</span>
                        <br>
                        <span class="b">{{ __('l.main_stock') }}: </span>
                        <span class="">{{ $item->centerAmount }} {{ $item->multiplicity_unit }}.</span>
                        <br>
                        <span class="b">{{ __('l.pack') }} 1:</span>
                        <span class="">{{ $item->pack_qty }} {{ $item->multiplicity_unit }}.</span>
                        <br>
                        <span class="b">{{ __('l.pack') }} 2:</span>
                        <span class="">{{ $item->pack_qty2 }} {{ $item->multiplicity_unit }}.</span>
                    </div>

                    <div class="input-group input-spinner my-3">
                        <button class="btn btn-icon btn-light qty-minus" data-product-id="{{ $item->id }}"
                            data-multiplisity="{{ $item->multiplicity }}" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999"
                                viewBox="0 0 24 24">
                                <path d="M19 13H5v-2h14v2z" />
                            </svg>
                        </button>
                        <input class="form-control text-center" id="qty-{{ $item->id }}" placeholder=""
                            value="{{ $item->multiplicity }}">
                        <button class="btn btn-icon btn-light qty-plus" data-product-id="{{ $item->id }}"
                            data-multiplisity="{{ $item->multiplicity }}" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999"
                                viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                            </svg>
                        </button>
                    </div> {{ $item->multiplicity_unit }}.
                    <nav>
                    <a href="javascript:void(0);"
                        class="btn {{ $item->myWishlist ? 'btn-primary' : 'btn-light' }} btn-icon add-to-wishlist"
                        data-product-id="{{ $item->id }}"> <i class="fa fa-heart"></i></a>
                    <a href="javascript:void(0);" class="btn  ml-2 add-to-cart {{$item->inCart ? 'btn-success text-white widget-icontop position-relative' : 'btn-primary'}}"
                        data-product-id="{{ $item->id }}">{{ $item->inCart ? __('l.added_to_cart') : __('l.add_to_cart') }}</a>

                    </nav>
                </div>
            </aside>
        </div>
    </article>
@endforeach

{!! $products->links('layouts.mobile_pagination') !!}
{{-- {{dd(app('request')->input())}} --}}
