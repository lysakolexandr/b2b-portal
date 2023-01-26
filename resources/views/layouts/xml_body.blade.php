@foreach ($prices as $item)
<article class="card mb-3">
	<div class="card-body">


	<figure class="itemside align-items-center">
		<div class="aside">
			<a href="javascript:void(0);" class="text-decoration-none delete-price" data-price-id="{{$item->id}}"  data-price-type="xml">
				<span class="icon icon-xs rounded box">
					<i class="far fa-trash-alt"></i>
				</span>
			</a>
			<a href="prices-xml-edit?id={{$item->id}}" class="text-decoration-none">
				<span class="icon icon-xs rounded box">
					<i class="fas fa-pen"></i>
				</span>
			</a>
		</div>
		<figcaption class="info">
			<h5 class="title">{{$item->name}}</h5>
		</figcaption>
            @if ($item->created==1)
            <p class="me-2 text-success b">{{ __('l.url_generated') }}</p>
            <i class="me-2 text-success fa fa-check  "></i>
            @else
            <p class="me-2 text-danger b">{{ __('l.url_not_generate') }}</p>
            <i class="me-2 text-danger fa fa-clock"></i>
            @endif
	</figure>
	<hr>

        <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
        [
            $item->id,
            "ua",
            0,
            0,
            'xml'
        ])}}" disabled="">
        <span class="btn btn-light copy-link text-primary b"> {{$item->name}} (UA) </span>
        <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
        [
            $item->id,
            "ru",
            0,
            0,
            'xml'
        ])}}" disabled="">
        <span class="btn btn-light copy-link text-primary b"> {{$item->name}} (RU) </span>
        <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
        [
            $item->id,
            "ua",
            1,
            0,
            'xml'
        ])}}" disabled="">
        <span class="btn btn-light copy-link text-primary b"> {{$item->name}} PROM.UA (UA) </span>
        <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
        [
            $item->id,
            "ru",
            1,
            0,
            'xml'
        ])}}" disabled="">
        <span class="btn btn-light copy-link  text-primary b"> {{$item->name}} PROM.UA (RU) </span>

	</div>
</article>
@endforeach
