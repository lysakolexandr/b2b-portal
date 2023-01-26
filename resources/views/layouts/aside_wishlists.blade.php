{{-- <aside class="col-lg-3">
	<div id="aside_filter" class="collapse card d-lg-block mb-5 ">

			<article class="filter-group">
			<div class="collapse show" id="collapse_aside4">
				<div class="card-body-on">
					<label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" name="choose_x">
						<span class="form-check-label"> Только доступние</span>
					</label>

				</div>
			</div>
			</article>
		<div class="d-grid my-2">
			<button class="btn btn-warning btn-sm mx-2" type="button"> Очистить </button>
		</div>
			<article class="filter-group">
				<header class="card-header">
				  <a href="#" class="title" data-bs-toggle="collapse" data-bs-target="#collapse_aside_brands">
					<i class="icon-control fa fa-chevron-down"></i>
					Brands
				  </a>
				</header>
				<div class="collapse show" id="collapse_aside_brands">
				  <div class="card-body">
					  <label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="" checked>
						<span class="form-check-label"> Mercedes </span>
						<b class="badge rounded-pill bg-gray-dark float-end">120</b>
					  </label> <!-- form-check end.// -->

					  <label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="" checked>
						<span class="form-check-label"> Toyota </span>
						<b class="badge rounded-pill bg-gray-dark float-end">15</b>
					  </label> <!-- form-check end.// -->

					  <label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="" checked>
						<span class="form-check-label"> Mitsubishi </span>
						<b class="badge rounded-pill bg-gray-dark float-end">35</b>
					  </label> <!-- form-check end.// -->

					  <label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="" checked>
						<span class="form-check-label"> Nissan </span>
						<b class="badge rounded-pill bg-gray-dark float-end">89</b>
					  </label> <!-- form-check end.// -->

					  <label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="">
						<span class="form-check-label"> Honda </span>
						<b class="badge rounded-pill bg-gray-dark float-end">30</b>
					  </label> <!-- form-check end.// -->

					  <label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="">
						<span class="form-check-label"> Honda accord </span>
						<b class="badge rounded-pill bg-gray-dark float-end">30</b>
					  </label> <!-- form-check end.// -->
				  </div>
				</div>
			</article>

	</div>
</aside> --}}
<aside class="col-lg-3">
	<div id="aside_filter" class="collapse card d-lg-block mb-5 ">

			<article class="filter-group">
			<div class="collapse show" id="collapse_aside4">
				<div class="card-body-on">
					<label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" name="choose_x" id="only-available" {{ $available=='true' ? 'checked="checked"' : '' }}>
						<span class="form-check-label"> {{__('l.only_available')}} </span>
					</label>

				</div>
			</div>
			</article>
		<div class="d-grid my-2">
			<button class="btn btn-warning btn-sm mx-2" type="button" id="clean_filters"> {{__('l.to_clean')}} </button>
		</div>

	</div>
</aside>
