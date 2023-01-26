<aside class="col-md-12 col-lg-3">
    <?php
                        $sum_uah = app(App\Http\Controllers\CartController::class)->getSumUah();
                        $sum_usd = app(App\Http\Controllers\CartController::class)->getSumUsd();
                        $sum_eur = app(App\Http\Controllers\CartController::class)->getSumEur();
                        $total_sum = app(App\Http\Controllers\CartController::class)->getTotalSum();
                      ?>
    <div class="card">
    <div class="card-body">
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
          <dt>{{__('l.sum')}} UAH:</dt> <dd class="text-end text-dark h5">{{$total_sum}}</dd>
        </dl>
        <hr>
        {{-- {{dd($delivery_points)}} --}}
        <select class="form-select">
            <option>{{__('l.own_delivery')}}</option>
            @foreach ($delivery_points as $item)
            <option>{{$item->name}}</option>
            @endforeach

        </select>

        <a href="#" class="btn btn-primary mt-2 w-100">Оформить заказ</a>
    </div>
    </div>
    <br><br>
</aside>
