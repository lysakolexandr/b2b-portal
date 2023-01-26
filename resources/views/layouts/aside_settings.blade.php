<aside class="col-lg-3 col-xl-3">
    <nav class="nav flex-lg-column nav-pills mb-4">
        <a class="nav-link active" href="profile">{{ __('l.settings') }}</a>
        @if (Auth::user()->settlements_view == 1)
            <a class="nav-link" href="balance">{{ __('l.mutualSettlements') }}</a>
        @endif

        <form id="frm-logout" method="POST" action="{{ route('logout') }}">
            @csrf
            <a type="button" class="nav-link" type="submit"
                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">{{ __('l.logout') }}</a>
        </form>
    </nav>
</aside>
