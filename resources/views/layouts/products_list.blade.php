<main class="col-lg-9" id="loading" style="text-align: center;padding-top: 20vh;display: none;">
    <img style="width: 20vh;" src="{{ asset('img/loading.gif') }}" alt="loading">
</main>
<main class="col-lg-9" id="product_list" style="display: none;">

    <header class="d-sm-flex align-items-center border-bottom mb-2 pb-2 ">
        <nav class="mb-3 mb-lg-0  d-none d-lg-block">
            <ol class="breadcrumb mb-0">
                @if ($main_category == null)
                    <li class="breadcrumb-item active">{{ __('l.search_result_for') }}</li>

                    <b>: "{{ $search }}"</b>
                @else
                    <li class="breadcrumb-item active"><a href="{{route('category',['id'=>$main_category_id])}}">{{ $main_category }}</a></li>

                    <li class="breadcrumb-item active">{{ $local_category }}</li>
                @endif

            </ol>
        </nav>
        <div class="ms-auto  d-none d-lg-block">
            <select class="form-select d-inline-block w-auto" id="count-select">
                <option value="10" {{ $count==10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $count==25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $count==50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $count==100 ? 'selected' : '' }}>100</option>
            </select>
            <select class="form-select d-inline-block w-auto" id="sort-select">
                <option value="0" {{ $sort==0 ? 'selected' : '' }}>{{__('l.sort_by_name')}}</option>
                <option value="1" {{ $sort==1 ? 'selected' : '' }}>{{__('l.sort_by_price_up')}}</option>
                <option value="2" {{ $sort==2 ? 'selected' : '' }}>{{__('l.sort_by_price_down')}}</option>
            </select>
            <div class="btn-group">
                <input type="checkbox" class="btn-check" id="btn-check-outlined" autocomplete="off">
                @if ($blockHidePrice)
                @else
                <label class="btn {{ !$hidePrice ? 'btn-primary' : 'btn-outline-primary' }}" for="btn-check-outlined"
                    id="hide-price"><i class="fa fa-dollar-sign"></i></label>
                @endif
            </div>
        </div>
    </header>
    <div id="products_wrap">
        @include('layouts.products_body')
    </div>
    {{-- {!! $products->appends(app('request')->input())->links() !!} --}}


</main>
