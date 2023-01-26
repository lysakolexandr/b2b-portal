<main class="col-lg-9">

    <header class="d-sm-flex align-items-center border-bottom mb-2 pb-2 ">
        {{-- <nav class="mb-3 mb-lg-0  d-none d-lg-block">
			<ol class="breadcrumb mb-0">
    			<li class="breadcrumb-item active">Ванная комната</li>
                <li class="breadcrumb-item active">Утеплювач для труб ламінований</li>
			</ol>
		</nav> --}}
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
                <label class="btn {{ !$hidePrice ? 'btn-primary' : 'btn-outline-primary' }}" for="btn-check-outlined"
                    id="hide-price"><i class="fa fa-dollar-sign"></i></label>
            </div>
        </div>
    </header>
    <div id="products_wrap">
        @include('layouts.wishlist_body')
    </div>

    <hr>

    {{-- <footer class="d-flex mt-4">
        <div>
            <a href="javascript: history.back()" class="btn btn-light"> &laquo; Go back</a>
        </div>
        <nav class="ms-3">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <span class="page-link">2</span>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </footer> --}}

</main>
