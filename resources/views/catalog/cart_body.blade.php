@foreach ($carts as $item)
    <article class="card {{$item->product->myAmount===0 ? 'zero' : ""}}  mb-2 p-2">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6">
                <a href="{{ route('product', ['id' => $item->product->id]) }}" class="itemside align-items-center">
                    <div class="aside">
                        <img src="{{ Str::replaceFirst('public', 'storage', asset($item->product->mainPicture))}}" height="72" width="72"
                            class="img-thumbnail img-sm">
                    </div>
                    <div class="info">
                        <p class="title">{{ $item->product->name }} </p>
                        <span class="text-muted"><b>{{ __('l.code') }}:</b> {{ $item->product->code }}</span>

                    </div>

                </a>
            </div>
            <div class="col-auto">
                <div class="input-group input-spinner">
                    {{-- <button class="btn btn-light button-minus-cart" data-cart-id = "{{$item->id}}" type="button" data-multiplisity="{{ $item->product->multiplicity }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#999" viewBox="0 0 24 24"> <path d="M19 13H5v-2h14v2z"></path>
                        </svg>
                    </button>
                    <input type="text" class="form-control" id="value-{{$item->id}}"  value="{{$item->qty}}">
                    <button class="btn btn-light button-plus-cart" type="button " data-cart-id = "{{$item->id}}" data-multiplisity="{{ $item->product->multiplicity }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#999" viewBox="0 0 24 24"> <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path>  </svg>
                    </button> --}}
                    <button class="btn btn-icon btn-light button-minus" data-product-id="{{ $item->product->id }}"
                        data-row-id="{{ $item->id }}" data-multiplicity="{{ $item->product->multiplicity }}"
                        data-is_cart="1" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999"
                            viewBox="0 0 24 24">
                            <path d="M19 13H5v-2h14v2z" />
                        </svg>
                    </button>
                    <input class="form-control text-center" id="qty-{{ $item->id }}" placeholder=""
                        value="{{ $item->qty }}">
                    <button class="btn btn-icon btn-light button-plus" data-product-id="{{ $item->product->id }}"
                        data-row-id="{{ $item->id }}" data-is_cart="1"
                        data-multiplicity="{{ $item->product->multiplicity }}" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999"
                            viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="col">
                <div class="price-wrap lh-sm">

                    @if (!$hidePrice)
                    <?php $price = $item->product->personal_price;?>
                    <?php $currency = $item->product->personal_price_currency;?>
                    @else
                    <?php $price = $item->product->retailPriceUah;?>
                    <?php $currency = 'UAH';?>
                    @endif
                    <var class="price h6"
                        id="sum-{{ $item->id }}">{{ number_format($price * $item->qty, 2) }}
                        {{ $currency }}</var> <br>
                    <small class="text-muted"> {{ number_format($price,2) }}
                        {{ $currency }} / {{ $item->product->multiplicity_unit }}.
                    </small>
                </div>
                {{-- <strong class="price" id="sum-{{$item->id}}">{{$item->product->personal_price*$item->qty}} {{$item->product->personal_price_currency}} </strong> --}}
            </div>
            <div class="col text-end">
                <a href="javascript:void(0);" class="btn btn-icon btn-light delete-cart-row"
                    data-row-id="{{ $item->id }}"> <i class="fa fa-trash"></i> </a>
            </div>

        </div>
                                <div class="col">
                            <strong class="price">{{ __('l.your_stock') }}: {{ $item->product->myAmount }}
                                {{ $item->product->multiplicity_unit }}.<b
                                    class="dot mx-1"></b>{{ __('l.main_stock') }}:
                                {{ $item->product->centerAmount }} {{ $item->product->multiplicity_unit }}.</strong>
                        </div>
    </article>
@endforeach

<div>
    <label class="form-label"></label>
    <textarea class="form-control" name="comment" id = "comment" placeholder="{{__('l.comment')}}" rows="4"></textarea>
</div>
