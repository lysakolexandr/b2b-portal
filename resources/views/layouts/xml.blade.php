<main class="col-lg-9">
    <article class="card mb-3">
        <div class="card-body">
            <figure class="itemside align-items-center">
                <figcaption class="info">
                    <h4 class="title">{{ __('l.all_products') }}</h4>
                </figcaption>
            </figure>
            <hr>
            <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
            [
                Auth::user()->id,
                "ua",
                0,
                1,
                'xml'
            ])}}" disabled="">
        <span class="btn btn-light copy-link text-primary b"> {{ __('l.all_products') }} (UA) </span>
        <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
            [
                Auth::user()->id,
                "ru",
                0,
                1,
                'xml'
            ])}}" disabled="">
        <span class="btn btn-light copy-link text-primary b"> {{ __('l.all_products') }} (RU) </span>

        <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
        [
            Auth::user()->id,
            "ua",
            1,
            1,
            'xml'
        ])}}" disabled="">
    <span class="btn btn-light copy-link text-primary b"> {{ __('l.all_products') }} PROM.UA (UA) </span>

    <input type="text" class="form-control" name="lorem" id="route-currency" style="display: none" value="{{route('price-download',
    [
        Auth::user()->id,
        "ru",
        1,
        1,
        'xml'
    ])}}" disabled="">
<span class="btn btn-light copy-link text-primary b"> {{ __('l.all_products') }} PROM.UA (RU) </span>




        </div>
    </article>
    <div id="body-ajax">
        @include('layouts.xml_body')
    </div>
</main>
