  <section class="header-top-light border-bottom bg-light">
    <div class="container">
      <nav class="d-flex justify-content-between align-items-center flex-column flex-md-row">
        <div class="nav d-none d-lg-block">
		<a href="/">
			<img class="logo" height="25" src="{{ asset('img/logo.png') }}">
		</a>
        </div>

		<div class="nav">
			<li class="fw-bold text-primary"><b>USD {{$USD_rate}}</b></li>
			<li class="fw-bold px-2"><b>|</b></li>
            <li class="fw-bold text-primary"><b>EUR {{$EUR_rate}}</b></li>
			<li class="fw-bold px-2"><b>|</b></li>
			<li class="fw-bold text-primary"><b>EUR/USD - {{$EUR_USD_rate}}</b></li> 
		</div>

        <ul class="nav d-none d-lg-block">
            <?php $locale = App::getLocale(); ?>
            @if ($locale == 'ua')
                        {{-- <a href="{{ route('locale', ['locale' => 'ua']) }}" class="nav-link dropdown-toggle blue b text-uppercase"
                            id="navbarDogovor" role="button" data-bs-toggle="dropdown" aria-expanded="false">UKR</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDogovorLink">
                            <li>
                                <a class="dropdown-item blue b text-uppercase" href="{{ route('locale', ['locale' => 'ru']) }}">RUS</a>
                            </li>
                        </ul> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><b>UKR</b></a>
                            <ul class="dropdown-menu">
                              <li>  <a class="dropdown-item" href="{{ route('locale', ['locale' => 'ua']) }}"><b>UKR</b></a></li>
                              <li> <a class="dropdown-item" href="{{ route('locale', ['locale' => 'ru']) }}"><b>RUS</b></a></li>
                            </ul>
                          </li>
                    @else
                        {{-- <a href="{{ route('locale', ['locale' => 'ru']) }}" class="nav-link dropdown-toggle blue b text-uppercase"
                           id="navbarDogovor" role="button" data-bs-toggle="dropdown" aria-expanded="false">RUS</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDogovorLink">
                            <li>
                                <a class="dropdown-item blue b text-uppercase" href="{{ route('locale', ['locale' => 'ua']) }}">UKR</a>
                            </li>
                        </ul> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><b>RUS</b></a>
                            <ul class="dropdown-menu">
                              <li>  <a class="dropdown-item" href="{{ route('locale', ['locale' => 'ua']) }}"><b>UKR</b></a></li>
                              <li> <a class="dropdown-item" href="{{ route('locale', ['locale' => 'ru']) }}"><b>RUS</b></a></li>
                            </ul>
                          </li>
                    @endif

        </ul>
      </nav>
    </div>
  </section>

