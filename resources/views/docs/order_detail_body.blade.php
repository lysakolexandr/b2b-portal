<div class="col-lg-8">
    <!-- ================ COMPONENT COMPARE ================== -->
    <div class="card">
        <div class="card-body">
            <nav class="nav nav-pills mx-auto">
                <a class="nav-link border me-2" href="{{ route('home') }}"><i class="fa fa-arrow-left "></i>
                    {{ __('l.to_order_list') }}</a>
                <a class="nav-link border me-2" href="{{route('order-repeat',['id'=>$order->id])}}">{{ __('l.repeat_order') }}</a>
                @if ((Auth::user()->price_view != 0 && Auth::user()->trusted==1) || Auth::user()->trusted==0)
                <a class="nav-link border me-2" href="{{route('print-order',['id'=>$order->id])}}">{{ __('l.print-order') }}</a>
                @endif
                <a class="nav-link border me-2" href="{{route('print-retail-order',['id'=>$order->id])}}">{{ __('l.print-retail-order') }}</a>
            </nav>
            <hr>
            <span class="badge {{ $order->sourceClass }}"> {{ $order->sourceName }} </span>
            <h5 class=" ">{{ __('l.order') }} № {{ $order->code }} {{ __('l.from') }} {{ $order->created_at }}</h5>
            <hr>
            <!-- item-row -->
            <?php
            $total_retail = 0;
            ?>
            @foreach ($order->products as $row)
                <article class="row gy-3  mb-4">
                    <div class="col-lg-8">
                        <figure class="itemside  me-lg-5">
                            <div class="aside"><img src="{{ Str::replaceFirst('public', 'storage', asset($row->product->mainPicture))}}" class="img-sm border rounded">
                            </div>
                            <figcaption class="info">
                                <a href="{{ route('product', ['id' => $row->product_id]) }}"
                                    class="title mb-1 text-decoration-none">{{ $row->product->name }}</a>
                                <!--<span><i class="me-2 text-success fa fa-check"></i>Номенклатура отгружена</span>-->
                            </figcaption>
                        </figure>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-6">
                        <div class="price-wrap lh-sm">

                            @if ((Auth::user()->price_view != 0 && Auth::user()->trusted==1) || Auth::user()->trusted==0)
                            <var class="price h6">{{number_format($row->price,2)}} {{$row->currency_symbol}} / {{$row->product->multiplicity_unit}}.</var> <br>
                            <small class="text-muted"> {{number_format($row->price,2)}} {{$row->currency_symbol}} х {{ number_format($row->qty,0) }} = {{number_format($row->price*$row->qty,2)}} {{$row->currency_symbol}} </small>
                            @else
                            <?php
                            $total_retail = $total_retail + ($row->product->retailPriceUah*$row->qty);
                            ?>
                            <var class="price h6">{{number_format($row->product->retailPriceUah,2)}} UAH / {{$row->product->multiplicity_unit}}.</var> <br>
                            <small class="text-muted"> {{number_format($row->product->retailPriceUah,2)}} UAH х {{ number_format($row->qty,0) }} = {{number_format($row->product->retailPriceUah*$row->qty,2)}} UAH </small>
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-4">
                        <div class="float-lg-end">
                            <a href="javascript:void(0);" class="btn btn-light" data-product-id="{{$row->product_id}}"> <i class="fa fa-heart" add-to-wishlist></i></a>
                        </div>
                    </div>
                </article>
                <hr>
            @endforeach
            <span class="b">{{__('l.comment')}}:</span>
            <p class="comments">{{$order->comment}}</p>
            <article class="card-body border-top">

                <table style="max-width:360px" class="table table-sm float-lg-end">
                    <tbody>
                        @if ((Auth::user()->price_view != 0 && Auth::user()->trusted==1) || Auth::user()->trusted==0)
                        <tr>
                            <td> USD: </td>
                            <td> {{$order->sumUsd}} </td>
                        </tr>
                        <tr>
                            <td> EUR: </td>
                            <td> {{$order->sumEur}} </td>
                        </tr>

                        <tr>
                            <td> UAH: </td>
                            <td> {{$order->sumUah}}</td>
                        </tr>

                        <tr>
                            <td> <strong>{{__('l.order_in_UAH')}}:</strong> </td>
                            <td> <strong class="h5 price">{{$order->totalSum}}</strong> </td>
                        </tr>
                        @else
                        <tr>
                            <td> <strong>{{__('l.order_in_UAH')}}:</strong> </td>
                            <td> <strong class="h5 price">{{number_format($total_retail,2)}}</strong> </td>
                        </tr>
                        @endif
                    </tbody>
                </table>


            </article>
            <!-- item-row //end -->

        </div>
    </div>

    <!-- =================== COMPONENT COMPARE .// ================== -->
</div>
<div class="col-lg-4">
    <!-- =================== COMPONENT SELLER ====================== -->
    <article class="card">
        <div class="card-body">
            <h5 class="card-title">{{__('l.order_statuses')}}</h5>

            <br>

            <ul class="steps-vertical">
                @if ($order->status >= 1)
                    <li class="step">
                        <b class="icon"></b>
                        <h6 class="title mb-0">{{ __('l.new') }}</h6>
                        <p class="text-muted">{{ __('l.OrderAccepted') }}</p>
                    </li>
                @endif
                @if ($order->status >= 2)
                    <li class="step">
                        <b class="icon"></b>
                        <h6 class="title  mb-0">{{ __('l.confirm') }}</h6>
                        <p class="text-muted">{{ __('l.OrderAccepted') }}</p>
                    </li>
                @endif
            </ul>

            @if ($order->status >= 3)
                <ul class="steps-vertical-f">

                    <li class="step">




                        <div class="row">
                            <div class="col-6">

                                <h6 class="title  mb-0">{{ __('l.invoice') }}</h6>
                                <p class="text-muted">{{ $order->invoice_number }}</p>
                                <b class="icon"></b>
                            </div>
                            <div class="col-6">
                                @if ((Auth::user()->price_view != 0 && Auth::user()->trusted==1) || Auth::user()->trusted==0)
                                <a class="nav-link border me-2" href="{{route('print-invoice',['id'=>$order->id])}}">{{ __('l.print-invoice') }}</a>
                                @endif
                            </div>

                        </div>

                    </li>
                </ul>
            @endif
        </div>
    </article>
    <!-- =================== COMPONENT SELLER .// ================== -->
</div>
