<header class="section-header d-block d-sm-none sticky-top">
    <nav class="navbar navbar-dark navbar-expand p-1 bg-blue">
        <div class="container mb-1 mt-1">
            <ul class="navbar-nav d-none d-flex justify-content-start ml-3 navbar-toggler white">
                <a style="font-size: 1.5em; color: #fff;" type="button" data-trigger="#main_nav">
                    <i class="fas fa-bars"></i>
                </a>
            </ul>

         <ul class="navbar-nav d-none d-flex navbar-toggler white">
            <li class="nav-item"><a href="#" class="" style="font-size: 1.5em; color: #fff;" data-toggle="modal" data-target="#exampleModalD"><i class="fas fa-funnel-dollar"></i></a></li>
        </ul>
        <ul class="navbar-nav d-none d-flex navbar-toggler white">
            <li class="nav-item">
                <a href="#" class="" style="font-size: 1.5em; color: #fff;" data-toggle="modal" data-target="#exampleModalMobi"><i class="fa fa-info-circle"></i>
                </a>
            </li>
        </ul>
          <ul class="navbar-nav d-none d-flex navbar-toggler white">
                <a style="font-size: 1.5em; color: #fff;" type="button" data-trigger="#main_nav_user">
                   <i class="fas fa-user-cog"></i>
                </a>
            </ul>
            <ul class="navbar-nav d-flex justify-content-end navbar-toggler mr-3">
                <div class=" white">
                    <a href="#" style="font-size: 1.5em; color: #fff" class="" data-toggle="tooltip" title="" data-original-title="Корзина"><i class="fa fa-shopping-cart"></i></a>
                    <span class="badge badge-pill badge-danger notify">290</span>
                </div>
            </ul>
        </div>
    </nav>




<div class="navbar-collapse" id="main_nav" style="z-index:100">
    <div class="offcanvas-header mt-3">
        <button class="btn btn-danger btn-close float-right"> ×</button>
        <h5 class="py-2 text-white">$28,40 | €34,40</h5>
    </div>
    <div id="MainMenu">
        <div class=" list-group list-group-flush">
            <a href="#demo1" class="white top p-3" data-toggle="collapse" data-parent="#MainMenu">Аксессуары <i class="fa fa-caret-down"></i></a>
          <div class="collapse" id="demo1">
            <a href="" class="list-group-item">Для ванной</a>
            <a href="" class="list-group-item">Для санузлов</a>
            <a href="" class="list-group-item">Стекло, Ерши, Дозаторы</a>
          </div>
          <a href="#demo2" class="white top p-3" data-toggle="collapse" data-parent="#MainMenu">Баки и гидроаккумуляторы <i class="fa fa-caret-down"></i></a>
          <div class="collapse" id="demo2">
            <a href="" class="list-group-item">Гидроаккумуляторы</a>
            <a href="" class="list-group-item">Мембраны, фланцы</a>
            <a href="" class="list-group-item">Расширительные баки</a>
          </div>
          <a href="#demo3" class="white top p-3" data-toggle="collapse" data-parent="#MainMenu">Ванны и панели <i class="fa fa-caret-down"></i></a>
          <div class="collapse" id="demo3">
            <a href="" class="list-group-item">Ванны</a>
            <a href="" class="list-group-item">Панели для ванной</a>
          </div>
          <a href="#demo4" class="white top p-3" data-toggle="collapse" data-parent="#MainMenu">Водоочистка <i class="fa fa-caret-down"></i></a>
          <div class="collapse" id="demo4">
            <a href="" class="list-group-item">Многоступенчатые системы</a>
            <a href="" class="list-group-item">Картриджи</a>
            <a href="" class="list-group-item">Комплектующие</a>
            <a href="" class="list-group-item">Системы обратного осмоса</a>
            <a href="" class="list-group-item">Системы комплексной очистки</a>
            <a href="" class="list-group-item">Соль, загрузки</a>
            <a href="" class="list-group-item">Фильтры-колбы</a>
          </div>
        </div>
    </div>



</div>

<!-- momile-menu-user.start -->

<div class="navbar-collapse-u" id="main_nav_user" style="z-index:100">
    <div class="offcanvas-header mt-3">
        <button class="btn btn-danger btn-close float-right"> ×</button>
        <h5 class="py-2 text-white">ID 8989</h5>
    </div>
	@include('layouts.particles.search_mobile')

    </header>

    @include('layouts.modal.modal_mobile')
