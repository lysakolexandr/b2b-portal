
@foreach ($products as $item)
<article class="card card-product-list">
	<div class="row no-gutters">
		<aside class="col-md-2">
			<a href="/product"class="img-wrap">
				<span class="badge bg-blue white"> Новинка</span>
                <span class="badge  bg-red white"> Распродажа</span>
                <span class="badge badge-success"> Под заказ</span>
				<img src="{{ asset('img/products/1_main.jpeg') }}">
			</a>
		</aside>
		<div class="col-md-7">
			<div class="info-main">
				<a href="/product" class="h5 title">{{$item->name}}</a>

				<span class="price"> {{__('l.code')}}: </span>
				<span class="b dark">{{$item->code}}</span>
				<span class="price pl-3"> {{__('l.brand')}}: </span>
				<span class="b dark"> {{$item->brandName}}</span>

			</div>

			<div class="info-main">
				<span class="price"> {{__('l.retail_price')}}: </span>
				<span class="b dark"> 1222,00 UAH</span>
				<br>
				<span class="price"> {{__('l.personal_price_uah')}}: </span>
				<span class="b dark"> 1096,19 UAH</span>
				<br>
				<span class="price"> {{__('l.personal.price_currency')}}: </span>
				<span class="b dark"> 39,29 USD</span>
			</div>
		</div>
		<aside class="col-sm-3">
			<div class="info-aside">
				<div class="price-wrap">
				<span class="price"> Виница: </span>
				<span class="b dark"> 1шт.</span>
				<br>
				<span class="price"> Основной склад: </span>
				<span class="b dark"> >10 </span>
				<br>
				<span class="price"> {{__('l.pack')}} 1: </span>
				<span class="b dark"> {{$item->pack_qty}} шт.</span>
				<br>
				<span class="price"> {{__('l.pack')}} 2: </span>
				<span class="b dark"> {{$item->pack_qty2}} шт.</span>

				</div>
				<div class=" col-md flex-grow-0  border rounded pb-2">

				<p class="h7 pb-2 text-center">{{__('l.add_to_cart')}}</p>
			<span class="d-flex justify-content-center">
			<div class="input-group input-spinner">
			  <div class="input-group-prepend">
			  <button class="btn btn-light" type="button" id="button-minus"> &minus; </button>
			  </div>
			  <input type="text" class="form-control" value="1">
			  <div class="input-group-append">
			    <button class="btn btn-light" type="button" id="button-plus"> + </button>
			  </div>

			</div>
			<a href="#" class="btn btn-outline-primary ml-2"> <span class=""></span> <i class="fas fa-shopping-cart"></i>  </a>
			<span>
		</div>

			</div>
		</aside>
	</div>
</article>
@endforeach
