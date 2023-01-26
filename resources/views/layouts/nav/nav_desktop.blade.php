<header class="section-header d-none d-lg-block bg-white sticky-top">
    <nav class="navbar navbar-expand p-0 bg-grey">
        <div class="container">
            <ul class="navbar-nav d-none d-flex justify-content-start mr-auto">
                <li class="nav-link">$28,40</li>
                <li class="nav-link">€34,40</li>
                <li class="nav-link">|</li>
                <li class="nav-item "><a href="#" class="nav-link blue top" data-toggle="modal"
                        data-target="#exampleModal"><i class="fas fa-user-tie"></i> {{__('l.your_manager')}}</a></li>
            </ul>

            <ul class="navbar-nav d-none d-flex justify-content-center mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle blue top" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="true" aria-expanded="false"><i class="fas fa-dollar-sign"></i> Продажа
                        безналичные</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Продажа безналичные</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Продажа наличные</a>

                    </div>
                </li>
            </ul>

            <?php $locale = Session::get('locale'); ?>
                    <?php echo '333'; echo $locale;?>
                    dsfdsf
            <ul class="navbar-nav d-flex justify-content-end">
                <li class="nav-item"><a href="#" class="nav-link blue top" data-toggle="modal"
                        data-target="#exampleModalTeh"><i class="fa fa-info-circle"></i>{{__('l.support')}}</a></li>
                <li class="nav-item dropdown">

                    @if ($locale === 'ua')

                        <a href="{{ route('locale', ['locale' => 'ua']) }}" class="nav-link dropdown-toggle blue top"
                            data-toggle="dropdown">UKR</a>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 10px;">
                            <li><a class="dropdown-item blue" href="{{ route('locale', ['locale' => 'ru']) }}">RUS</a>
                            </li>
                        </ul>
                    @else
                        <a href="{{ route('locale', ['locale' => 'ru']) }}" class="nav-link dropdown-toggle blue top"
                            data-toggle="dropdown">RUS</a>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 10px;">
                            <li><a class="dropdown-item blue" href="{{ route('locale', ['locale' => 'ua']) }}">UKR</a>
                            </li>
                        </ul>
                    @endif

                </li>
                <li class="nav-item"><a href="#" class="nav-link blue top"><i class="fas fa-user-cog"></i> {{__('l.profile')}}</a>
                </li>
                <li class="nav-item"><a href="#" class="nav-link blue top"><i class="fas fa-sign-out-alt"></i>
                        {{ __('l.logout') }}</a></li>
            </ul>
        </div>
    </nav>

    <section class="header-main border-bottom p-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-1 col-6">
                    <a href="/" class="brand-wrap">
                        <img class="logo" src="{{ asset('img/logo.png') }}">
                    </a>
                </div>
                <div class="col-lg-2 col-6 ">
                    <div class="category-wrap dropdown d-inline-block float-right">
                        <button type="button" class="btn bg-blue white dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa fa-bars"></i> {{__('l.menu')}}
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start"
                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">

                            <a class="dropdown-item" href="#">{{__('l.order_list')}} </a>
                            <a class="dropdown-item" href="#">{{__('l.product_return')}}</a>
                            <a class="dropdown-item" href="#">{{__('l.brands')}}</a>
                            <a class="dropdown-item" href="#">{{__('l.xml_upload')}}</a>
                            <a class="dropdown-item" href="#">{{__('l.personal_price_xls')}}</a>
                            <a class="dropdown-item" href="#">{{__('l.API_Integration')}}</a>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-9 col-sm-9">
                    <form action="#" class="search">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" placeholder="{{__('l.search')}}">
                            <div class="input-group-append">
                                <button class="btn bg-blue white" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widgets-wrap float-lg-right">
                        <div class="widget-header  mr-3">
                            <a href="#" class="icon icon-sm rounded border" data-toggle="tooltip" title="{{__('l.draft')}}"><i
                                    class="fas fa-clipboard-list"></i></a>
                        </div>

                        <div class="widgets-wrap float-lg-right">
                            <div class="widget-header  mr-3">
                                <a href="#" class="icon icon-sm rounded border" data-toggle="tooltip" title="{{__('l.cart')}}"><i
                                        class="fa fa-shopping-cart"></i></a>
                                <span class="badge badge-pill badge-danger notify">2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <nav class="navbar navbar-main navbar-expand-lg navbar-light border-bottom p-0">
        <div class="container-fluid bg-blue">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav"
                aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse nav_center pt-2 pb-2" id="main_nav">
                <ul class="navbar-nav ">
                    @foreach ($categories as $item)
                        <li class="nav-item nav-block">
                            <a class="nav-link white " href="#" data-toggle="dropdown"><span
                                    class="white menu h5">{{ $item->name }}</span> </a>
                            <div class="dropdown-menu dropdown-large  drt">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="list-unstyled h5">
                                            <?php $page = 1; ?>
                                            @foreach ($item->children as $item_children)
                                                @if ($page % 7 == 0)
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="list-unstyled h5">
                    @endif
                    <li class="bv"><a class=" blue" href="{{ route('catalog', ['id' => $item_children->id]) }}">{{ $item_children->name }}</a></li>
                    <?php $page++; ?>
                    @endforeach
                </ul>
            </div>
        </div>
        </div>
        </li>
        @endforeach
        </ul>
        </div>
        </div>
    </nav>
</header>

@include('layouts.modal.modal_desktop')
