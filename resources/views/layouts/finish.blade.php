<article class="card">
	<div class="card-body">

		<figure class="mt-4 mx-auto text-center" style="max-width:600px">
			<svg width="96px" height="96px" viewBox="0 0 96 96" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
			        <g id="round-check">
			            <circle id="Oval" fill="#D3FFD9" cx="48" cy="48" r="48"></circle>
			            <circle id="Oval-Copy" fill="#87FF96" cx="48" cy="48" r="36"></circle>
			            <polyline id="Line" stroke="#04B800" stroke-width="4" stroke-linecap="round" points="34.188562 49.6867496 44 59.3734993 63.1968462 40.3594229"></polyline>
			        </g>
			    </g>
			</svg>
			<figcaption class="my-3">
				<h4>{{__('l.thank_you_for_order')}}</h4>
			</figcaption>
		</figure>

		<ul class="steps-wrap mx-auto" style="max-width: 600px">
			<li class="step active">
				<span class="icon">1</span>
				<span class="text">{{__('l.new')}}</span>
			</li> <!-- step.// -->
			<li class="step ">
				<span class="icon">2</span>
				<span class="text">{{__('l.confirm')}}</span>
			</li> <!-- step.// -->
			<li class="step ">
				<span class="icon">3</span>
				<span class="text">{{__('l.success')}}</span>
			</li> <!-- step.// -->
		</ul> <!-- tracking-wrap.// -->

		<br>

	</div>
	<div class="card p-3">
   <nav class="nav nav-pills mx-auto">
      <!--<a class="nav-link active" aria-current="page" href="/">{{__('l.home')}}</a>-->
      <a class="nav-link active m-1" href="/">{{__('l.order_list')}}</a>
      <a class="nav-link active m-1" href="/wishlist">{{__('l.wishlist')}}</a>
      <a class="nav-link active m-1" href="/balance">{{__('l.mutualSettlements')}}</a>
    </nav>
</article>
