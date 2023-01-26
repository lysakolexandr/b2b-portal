@foreach ($prices as $item)
<article class="card mb-3">
	<div class="card-body">


	<figure class="itemside align-items-center">
		<div class="aside">
			<a href="javascript:void(0);" class="text-decoration-none delete-price" data-price-id="{{$item->id}}" data-price-type="xls">
				<span class="icon icon-xs rounded box">
					<i class="far fa-trash-alt"></i>
				</span>
			</a>
			<a href="prices-xls-edit?id={{$item->id}}" class="text-decoration-none">
				<span class="icon icon-xs rounded box">
					<i class="fas fa-pen"></i>
				</span>
			</a>
		</div>
		<figcaption class="info">
			<h5 class="title">{{$item->name}}</h5>
		</figcaption>
        @if ($item->created==1)
            <p class="me-2 text-success b">{{ __('l.file_generated') }}</p>
            <i class="me-2 text-success fa fa-check"></i>
            @else
            <p class="me-2 text-danger b">{{ __('l.file_not_generate') }}</p>
            <i class="me-2 text-danger fa fa-clock"></i>
            @endif
	</figure>
	<hr>
		<a class="btn btn-light mb-1 b" href="{{route('download-xls',['all'=>0,'id'=>$item->id,'lang'=>'ua'])}}" role="button">{{$item->name}} (UA)</a>
        <a class="btn btn-light mb-1 b" href="{{route('download-xls',['all'=>0,'id'=>$item->id,'lang'=>'ru'])}}" role="button">{{$item->name}} (RU)</a>
	</div>
</article>
@endforeach
