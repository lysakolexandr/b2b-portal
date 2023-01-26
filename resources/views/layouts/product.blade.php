<section class="padding-y-sm">
    <div class="container">
        <textarea id="properties" hidden>
            {{ $product->properties }}
        </textarea>

        <div class="row">
            <aside class="col-lg-6">
                <article class="gallery-wrap">

                    <div class="img-big-wrap img-thumbnail">
                        @if ($product->statusName!='')
                        <span class="badge bg-{{ $product->statusColor }}"> {!! $product->statusName !!} </span>
                        @endif

                        <a data-fslightbox="mygalley" data-type="image"
                            href="{{ Str::replaceFirst('public', 'storage', asset($product->mainPicture)) }}">
                            <img height="560"
                                src="{{ Str::replaceFirst('public', 'storage', asset($product->mainPicture)) }}">
                        </a>
                    </div> <!-- img-big-wrap.// -->
                    <div class="thumbs-wrap">
                        <?php $first = true;?>
                        @foreach ($product->pictures as $picture)
                            @if (!$first)
                                <a data-fslightbox="mygalley" data-type="image"
                                    href="{{ Str::replaceFirst('public', 'storage', asset($picture)) }}"
                                    class="item-thumb">
                                    <img width="60" height="60"
                                        src="{{ Str::replaceFirst('public', 'storage', asset($picture)) }}"
                                        alt="{{ $product->name }}">
                                </a>

                            @endif
                            <?php $first = false ?>
                        @endforeach
                    </div> <!-- thumbs-wrap.// -->
                </article> <!-- gallery-wrap .end// -->
            </aside>
            <main class="col-lg-6">
                <article class="ps-lg-3">
                    <h4 class="title text-dark">{{ $product->name }}</h4>
                    <hr>
                    <dl class="row">
                        <dt class="col-3">{{ __('l.code') }}:</dt>
                        <dd class="col-9">{{ $product->code }}</dd>

                        <dt class="col-3">{{ __('l.brand') }}:</dt>
                        <dd class="col-9">{{ $product->brand_name }}</dd>

                        <dt class="col-3">{{ __('l.category') }}:</dt>
                        <dd class="col-9"><a href="{{ route('catalog', ['id' => $product->category_id]) }}"
                                class="text-decoration-none">{{ $product->categoryName }}</a></dd>

                        <dt class="col-3">{{ __('l.delivery') }}:</dt>
                        <dd class="col-9">{{ __('l.own_delivery') }} | {{ __('l.transport_company') }} <!--|
                            <img src="{{ asset('img/nova-poshta.png') }}" class="nova-poshta-icon">-->
                        </dd>
                    </dl>

                    <hr>
                    <dl class="row">
                        <dt class="col-6">{{ __('l.retail_price') }}:</dt>
                        <dd class="col-6">{{ number_format($product->retailPriceUah, 2, ',', ' ') }}  UAH /
                            {{ $product->multiplicity_unit }}.</dd>

                        @if (!$hidePrice)
                            <dt class="col-6">{{ __('l.personal_price_uah') }}:</dt>
                            <dd class="col-6">{{ number_format($product->personalPriceUah, 2, ',', ' ') }}  UAH /
                                {{ $product->multiplicity_unit }}.</dd>

                            <dt class="col-6">{{ __('l.personal_price_currency') }}:</dt>
                            <dd class="col-6">{{ number_format($product->personalPrice, 2, ',', ' ') }}
                                {{ $product->personalPriceCurrency }}
                                / {{ $product->multiplicity_unit }}.</dd>
                        @endif
                        <hr>
                        <dl class="row">
                            <dt class="col-6">{{ __('l.your_stock') }}:</dt>
                            <dd class="col-6">{{ $product->myAmount }} {{ $product->multiplicity_unit }}.</dd>

                            <dt class="col-6">{{ __('l.main_stock') }}:</dt>
                            <dd class="col-6">{{ $product->centerAmount }} {{ $product->multiplicity_unit }}.</dd>

                            <dt class="col-6">{{ __('l.pack') }} 1:</dt>
                            <dd class="col-6">{{ $product->pack_qty }} {{ $product->multiplicity_unit }}.</dd>

                            <dt class="col-6">{{ __('l.pack') }} 2:</dt>
                            <dd class="col-6">{{ $product->pack_qty2 }}
                                {{ $product->multiplicity_unit }}.
                            </dd>

                        </dl>
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="input-group input-spinner">
                                    <button class="btn btn-icon btn-light qty-minus"
                                        data-product-id="{{ $product->id }}"
                                        data-multiplisity="{{ $product->multiplicity }}" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            fill="#999" viewBox="0 0 24 24">
                                            <path d="M19 13H5v-2h14v2z" />
                                        </svg>
                                    </button>
                                    <input class="form-control text-center" id="qty-{{ $product->id }}"
                                        placeholder="" value="{{ $product->multiplicity }}">
                                    <button class="btn btn-icon btn-light qty-plus"
                                        data-product-id="{{ $product->id }}"
                                        data-multiplisity="{{ $product->multiplicity }}" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            fill="#999" viewBox="0 0 24 24">
                                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div> <!-- col.// -->
                            <div class="col-3 col-xl-5 mb-2">

                                {{-- <a href="javascript:void(0);" class="btn btn-primary mb-2 add-to-cart" --}}
                                <a href="javascript:void(0);" class="btn {{ $product->inCart ? 'btn-success text-white widget-icontop position-relative mb-2' : 'btn btn-primary mb-2' }}  add-to-cart "
                                    data-product-id="{{ $product->id }}">
                                    <span class="d-block d-sm-none">
                                        <i class="fa fa-shopping-basket"></i>
                                    </span>
                                    <span class="d-none d-sm-block">{{$product->inCart ? __('l.added_to_cart'): __('l.add_to_cart')}}</span>
                                </a>


                            </div>
                            <div class="col-3 col-xl-2 mb-2">
                                    <a href="javascript:void(0);"
                        class="btn {{ $product->myWishlist ? 'btn-primary' : 'btn-light' }} btn-icon add-to-wishlist"
                        data-product-id="{{ $product->id }}"> <i class="fa fa-heart"></i></a>
                            </div>
                        </div>
                </article> <!-- product-info-aside .// -->
            </main> <!-- col.// -->
        </div> <!-- row.// -->

    </div> <!-- container .//  -->
</section>
<!-- ============== SECTION CONTENT END// ============== -->

<!-- ============== SECTION  ============== -->
<section class="padding-y bg-light border-top">
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- =================== COMPONENT SPECS ====================== -->
                <div class="card">
                    <header class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a href="#" data-bs-target="#tab_specs" data-bs-toggle="tab"
                                    class="nav-link active">{{ __('l.description') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-bs-target="#tab_warranty" data-bs-toggle="tab"
                                    class="nav-link">{{ __('l.properties') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-bs-target="#tab_shipping" data-bs-toggle="tab"
                                    class="nav-link">{{ __('l.certificates') }}</a>
                            </li>
                        </ul>
                    </header>
                    <div class="tab-content">
                        <article id="tab_specs" class="tab-pane show active card-body">
                            <p>{!! $product->description !!}</p>
                        </article> <!-- tab-content.// -->
                        <article id="tab_warranty" class="tab-pane card-body">
                            <table class="table border table-hover">
                                <tr>
                                    <th> {{ __('l.country_of_consignment') }}: </th>
                                    <td> {{ $product->countryOfConsignmentName }}</td>
                                </tr>
                                <tr id="table-prop">
                                    <th> {{ __('l.country_of_brand_registration') }}: </th>
                                    <td> {{ $product->countryOfBrandRegistrationName }} </td>
                                </tr>
                                <tr id="table-prop">
                                    <th> {{ __('l.weight') }}: </th>
                                    <td> {{ $product->weight }} </td>
                                </tr>
                                <tr id="table-prop">
                                    <th> {{ __('l.volume') }}: </th>
                                    <td> {{ $product->volume }} </td>
                                </tr>
                            </table>
                        </article>
                        <article id="tab_shipping" class="tab-pane card-body">
                            <div class="row">
                            @foreach ($product->certificatesFiles as $picture)
                                <div class="col-sm-12 col-md-6">
                                    <img src="{{ Str::replaceFirst('public', 'storage', asset($picture)) }}"
                                        class="img-fluid">
                                </div>
                            @endforeach
                            </div>
                        </article>
                    </div>
                </div>
                <!-- =================== COMPONENT SPECS .// ================== -->
            </div> <!-- col.// -->

        </div>

        <br><br>

    </div><!-- container // -->
</section>
