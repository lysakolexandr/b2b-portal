
<main class="col-md-12 col-lg-9">
    @include('catalog.cart_body')

</main>
<aside class="col-md-12 col-lg-3">


    <?php
                        $sum_uah = app(App\Http\Controllers\CartController::class)->getSumUah();
                        $sum_usd = app(App\Http\Controllers\CartController::class)->getSumUsd();
                        $sum_eur = app(App\Http\Controllers\CartController::class)->getSumEur();
                        $total_sum = app(App\Http\Controllers\CartController::class)->getTotalSum();
                        $total_sum_retail = app(App\Http\Controllers\CartController::class)->getTotalSumRetail();
                      ?>
    <div class="card">
    <div class="card-body">
        <a href="{{route('clear-cart')}}" class="btn btn-primary mt-2 w-100" id="clear_cart">{{__('l.clear_cart')}}</a>
        <hr>
        @if (!$hidePrice)
        <dl class="dlist-align">
          <dt>USD:</dt>  <dd class="text-end text-success"> {{$sum_usd}}</dd>
        </dl>
        <dl class="dlist-align">
          <dt>EUR:</dt> <dd class="text-end text-success"> {{$sum_eur}} </dd>
        </dl>
        <dl class="dlist-align">
          <dt>UAH:</dt> <dd class="text-end text-success"> {{$sum_uah}} </dd>
        </dl>
        <dl class="dlist-align">
          <dt>{{__('l.sum')}} UAH:</dt> <dd class="text-end text-dark h5">{{$total_sum}} </dd>
        </dl>
        @else
        <dl class="dlist-align">
            <dt>{{__('l.sum')}} UAH:</dt> <dd class="text-end text-dark h5">{{$total_sum_retail}} </dd>
          </dl>
        @endif
        <hr>
        {{-- {{dd($delivery_points)}} --}}

        <select class="form-select b" name = "delivery" id="id-delivery">
            <option disabled selected value> {{__('l.select_delivery')}} </option>
            <option value='0'>{{__('l.own_delivery')}}</option>
            @foreach ($delivery_points as $item)
            <option value = "{{$item->id}}">{{$item->name}}</option>
            @endforeach

        </select>

        {{-- <a href="#" class="btn btn-primary mt-2 w-100">{{__('l.ordering')}}</a> --}}
        {{Form::submit(__('l.ordering'),['class' => 'btn btn-primary btn-submit mt-2 w-100'])}}

    </div>
    </div>
    <br><br>
</aside>

<script type="text/javascript">

    $(document).ready(function() {
        $(".btn-submit").click(function(e){
            e.preventDefault();

            var _token = $("input[name='_token']").val();
            var delivery = $('#id-delivery').val();
            var comment = $('#comment').val();

            $.ajax({
                url: "{{ route('make-order') }}",
                type:'POST',
                data: {
                    _token:_token,
                    comment:comment,
                    delivery:delivery
                },
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                       window.location.href = '{{URL::to("/finish")}}';
                    }else{
                        printErrorMsg(data.error);
                    }
                }
            });

        });

        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
    });


</script>

