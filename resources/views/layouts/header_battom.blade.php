<section class="header-main border-bottom bg-white">
    <div class="container">
        <div class="row gx-2 gy-6 pt-2 align-items-center ">

            <div class="col-xl col-lg col-6 col-sm-6 col-md flex-grow-0">
                <div class="dropdown float-end">
                    <nav class="navbar navbar-expand-sm d-none d-lg-block">
                        <div class="container-fluid">
                            <!--<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"  aria-expanded=	"false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
        </button> -->
                            <div class="collapse navbar-collapse" id="main_nav">
                                <ul class="navbar-nav">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link btn btn-outline-primary text-nowrap px-4" href="#"
                                            data-bs-toggle="dropdown">
                                            <i class="fa fa-bars me-2 "></i> {{ __('l.catalog') }} </a>
                                        <ul class="dropdown-menu">
                                            @foreach ($categories as $item)
                                                <li class="has-megasubmenu">
                                                    <a class="dropdown-item" href="#"> {!! $item->name !!}
                                                        &raquo; </a>
                                                    <div class="megasubmenu dropdown-menu">
                                                        <div class="row">
                                                            <div class="col">
                                                                <ul class="list-unstyled">
                                                                    <?php $page = 1; ?>
                                                                    @foreach ($item->children as $item_children)
                                                                        <li> <a class="newlink"
                                                                                href="{{ route('catalog', ['id' => $item_children->id]) }}">&raquo;
                                                                                {!! $item_children->name !!}</a>
                                                                        </li>
                                                                        @if ($page % 10 === 0)
                                                                </ul>
                                                            </div>
                                                            <div class="col">
                                                                <ul class="list-unstyled">
                                            @endif
                                            <?php $page++; ?>
                                            @endforeach

                                        </ul>
                            </div>
                        </div>
                </div>
                </li>
                @endforeach

                </ul>
                </li>
                </ul>
            </div>
        </div>
        </nav>
    </div>
    </div>
    <div class="col-xl col-lg-5 col-12 col-md-12 col-sm-12">
        <form action="{{ route('catalog') }}" class="search mobile-p-y">
            <div class="input-group">
                <input type="search" name="q" id="searchbox" class="form-control" style="width:55%"
                    placeholder="{{ __('l.search_product') }}">
                <button class="btn btn-primary">
                    <i class="fa fa-search"></i>
                </button>
                <div class="autocomplete search-dropdown" style="display: none;">

                </div>


            </div>
        </form>
    </div>
    <div class="col-xl-4 col-lg col-md-12 col-12 d-none d-lg-block">
        <div class="widgets-wrap float-md-end">
            <div class="widget-header mx-2">
                <div class="" id="main_nav">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link widget-icontop" href="#" data-bs-toggle="dropdown">
                                <div class="icon-area">
                                    <i class="fa fa-sitemap "></i>
                                </div><span class="text">{{ __('l.e_catalog') }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href=".././prices-xml">{{ __('l.e_xml') }}</a></li>
                                <li><a class="dropdown-item" href=".././prices-xls">{{ __('l.e_xls') }}</a></li>
                                <li><a class="dropdown-item" href=".././api-settings">{{ __('l.e_api') }}</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="widget-header mx-2">
                <a href="/profile" class="widget-icontop position-relative ">
                    <div class="icon-area ">
                        <i class="fa fa-user"></i>
                    </div>
                    <span class="text">{{ __('l.profile') }}</span>

                </a>
            </div>
            <div class="widget-header mx-2">
                <a href="/wishlist" class="widget-icontop position-relative ">
                    <div class="icon-area">
                        <i class="fa fa-heart "></i>
                    </div>
                    <span class="text">{{ __('l.wishlist') }}</span>
                    <b class="notify" id="wishlist-qty">{{ $wishlistCount }}</b>
                </a>
            </div>
            <div class="widget-header mx-2">
                <a href="/cart" class="widget-icontop position-relative ">
                    <div class="icon-area">
                        <i class="fa fa-shopping-cart "></i>
                    </div>
                    <span class="text">{{ __('l.cart') }}</span>
                    <b class="notify" id="cart-qty">{{ $cartCount }}</b>
                </a>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        var search_url = "{{ route('smart-search') }}";
    </script>
    {{-- var products = "{{ __('l.products') }}";
        var categories = "{{ __('l.categories') }}";
        $(document).ready(function() {
            $('#searchbox').selectize({
                valueField: "url",
                labelField: "name",
                searchField: "name",
                create: false,
                render: {
                    option: function(item, escape) {
                        return (
                            "<div>" + escape(item.name) + "</div>"
                        );
                    },
                },
                score: function(search) {
                    var score = this.getScoreFunction(search);
                    return function(item) {
                        return score(item) * (1 + Math.min(item.watchers / 100, 1));
                    };
                },
                load: function(query, callback) {
                    if (!query.length) return callback();
                    $.ajax({
                        url: "https://api.github.com/legacy/repos/search/" + encodeURIComponent(query),
                        //url: url,
                        data:{
                            q:query,
                        },
                        type: "GET",
                        error: function() {
                            callback();
                        },
                        success: function(res) {
                            callback(res.repositories.slice(0, 10));
                            //callback(res.data);
                        },
                    });
                },
            });
        });
    </script> --}}
</section>
