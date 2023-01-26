<main class="col-lg-9">
    <article class="card mb-3">
        <div class="card-body">
            <figure class="itemside align-items-center">
                <figcaption class="info">
                    <h4 class="title">{{ __('l.all_products') }}</h4>
                </figcaption>
            </figure>
            <hr>
            <a class="btn btn-light mb-1 b" href="{{route('download-xls',['all'=>1,'id'=>Auth::user()->id,'lang'=>'ua'])}}" role="button">{{__('l.all_products')}} (UA)</a>
            <a class="btn btn-light mb-1 b" href="{{route('download-xls',['all'=>1,'id'=>Auth::user()->id,'lang'=>'ru'])}}" role="button">{{__('l.all_products')}} (RU)</a>
        </div>
    </article>
    <div id="body-ajax">
        @include('layouts.xls_body')
    </div>
</main>
