<main class="col-lg-9">
    <input id="to-copy" hidden value="">
    <article class="card mb-2">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{{ __('l.exchange_currency') }}</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="lorem" id="route-currency"
                        value="{{ $route_currency }}" disabled="">
                    <span class="btn btn-primary copy"> {{ __('l.copy') }} </span>
                </div>
            </div>
        </div>
    </article>
    <article class="card mb-2">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{{ __('l.category') }} (UA)</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="lorem" value="{{ $route_categories_ua }}"
                        id="route-categories-ua" disabled="">
                    <span class="btn btn-primary copy"> {{ __('l.copy') }} </span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('l.category') }} (RU)</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="lorem" value="{{ $route_categories_ru }}"
                        id="route-categories-ru" disabled="">
                    <span class="btn btn-primary copy"> {{ __('l.copy') }} </span>
                </div>
            </div>
        </div>
    </article>
    <article class="card mb-2">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{{ __('l.products') }} (UA)</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="lorem" value="{{ $route_products_ua }}"
                        id="route-products-ua" disabled="">
                    <span class="btn btn-primary copy"> {{ __('l.copy') }} </span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('l.products') }} (RU)</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="lorem" value="{{ $route_products_ru }}"
                        id="route-products-ru" disabled="">
                    <span class="btn btn-primary copy"> {{ __('l.copy') }} </span>
                </div>
            </div>
        </div>
    </article>
    <article class="card mb-2">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{{ __('l.available') }}</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="lorem" value="{{ $route_available }}"
                        id="route-available" disabled="">
                    <span class="btn btn-primary copy"> {{ __('l.copy') }} </span>
                </div>
            </div>
        </div>
    </article>

</main>
