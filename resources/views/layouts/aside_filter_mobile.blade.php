<span class="screen-darken"></span>
<article class="card mobile-offcanvas bg-light d-block d-lg-none" id="card_mobile">
    <div class="offcanvas-header bg-light sticky-top">

        <div class="box-sm box-check  mb-2">
            <label class="form-check ">
                <input class="form-check-input " type="checkbox" name="" id="only-available-m" {{ $available=='true' ? 'checked="checked"' : '' }}>
                <b class="border-oncheck"></b>
                <span class="form-check-label"> {{__('l.only_available')}}</span>
            </label>
        </div>




        <div class="d-flex gap-2 mb-2">
            <select class="form-select d-inline-block w-auto" id="m-count-select">
                <option value="10" {{ $count==10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $count==25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $count==50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $count==100 ? 'selected' : '' }}>100</option>
            </select>
            <div class="btn-group">
                <input type="checkbox" class="btn-check" id="btn-check-outlined-m" autocomplete="off">
			<label class="btn {{ !$hidePrice ? 'btn-primary' : 'btn-outline-primary' }} " data-hide = "{{ !$hidePrice ? '0' : '1' }}" for="btn-check-outlined-m" id="hide-price-m"><i class="fa fa-dollar-sign"></i></label>
            </div>
        </div>

                <div class="d-flex gap-2 mb-2">
            <select class="form-select d-inline-block" id="sort-select-m">
                <option value="0" {{ $sort==0 ? 'selected' : '' }}>{{__('l.sort_by_name')}}</option>
                <option value="1" {{ $sort==1 ? 'selected' : '' }}>{{__('l.sort_by_price_up')}}</option>
                <option value="2" {{ $sort==2 ? 'selected' : '' }}>{{__('l.sort_by_price_down')}}</option>
            </select>
        </div>
        <div class="d-flex gap-2 mb-2">
            <button class="btn w-100 btn-outline-warning btn-sm" type="button" id="clean_filters-m">{{__('l.to_clean')}}</button>
            <button class="btn w-100 btn-outline-primary filter-close btn-sm" type="button">{{__('l.close')}}</button>
        </div>
    </div>
    <div class="card">

        {{-- <article class="filter-group">
            <header class="card-header">
                <a href="#" class="title" data-bs-toggle="collapse" data-bs-target="#collapse_aside4">
                    <i class="icon-control fa fa-chevron-down"></i>
                    Brand
                </a>
            </header>
            <div class="collapse show" id="collapse_aside4">
                <div class="card-body">

                    <label class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="choose_x">
                        <span class="form-check-label"> Samsung </span>
                    </label>

                    <label class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="choose_x">
                        <span class="form-check-label"> Huawei </span>
                    </label>

                    <label class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="choose_x">
                        <span class="form-check-label"> Tesla model </span>
                    </label>

                    <label class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="choose_x">
                        <span class="form-check-label"> Oneplus </span>
                    </label>

                    <label class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="choose_x">
                        <span class="form-check-label"> Panasonic </span>
                    </label>

                </div>
            </div>
        </article> --}}
    </div>


</article>
