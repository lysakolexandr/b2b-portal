<div class="d-block d-lg-none">
	<nav class="nav-bottom">
		<a href="/" class="nav-link active">
			<i class="fa fa-home text-primary"></i><span class="text">{{ __('l.home') }}</span>
		</a>
		<a href="#menu" class="nav-link">
			<i class="fa fa-bars text-primary"></i><span class="text">{{ __('l.menu') }}</span>
		</a>
		<a href="/wishlist" class="nav-link position-relative ">
            <i class="fa fa-heart text-primary"></i>
            <span class="text">{{ __('l.wishlist') }}</span>
            <b class="notify" id="wishlist-qty-m">{{$wishlistCount}}</b>
        </a>
		<a href="/cart" class="nav-link position-relative ">
            <i class="fa fa-shopping-cart text-primary"></i>
            <span class="text">{{ __('l.cart') }}</span>
            <b class="notify" id="cart-qty-m">{{ $cartCount }}</b>
        </a>
	</nav>
	<nav id="menu">
        <ul>
            <li>
                <span>{{ __('l.catalog') }}</span>
                <ul>
                    @foreach ($categories as $item)
                        <li>
                            <span>{{ $item->name }}</span>
                            <ul>
                                @foreach ($item->children as $item_children)
                                    <li><a
                                            href="{{ route('catalog', ['id' => $item_children->id]) }}">{{ $item_children->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <span>{{ __('l.language') }}</span>
                <ul>
                    <li><a href="{{ route('locale', ['locale' => 'ua']) }}">UKR</a></li>
                    <li><a href="{{ route('locale', ['locale' => 'ru']) }}">RUS</a></li>
                </ul>
            </li>
            <li><a href=".././prices-xml">{{__('l.e_xml')}}</a></li>
			<li><a href=".././prices-xls">{{__('l.e_xls')}}</a></li>
			<li><a href=".././api-settings">{{__('l.e_api')}}</a></li>
			<li><a href=".././profile">{{__('l.profile')}}</a></li>

            <button class="btn btn-primary" type="button" id="change_hide_price"
                                onclick="changeHidePrice()">
                                <img id="btn-loading" style="width: 15px;padding-bottom: 4px;display: none;"
                                    src="../img/loading.gif" alt="loading">
                                <i id="btn-check" class="fas fa-check-circle" style="display: none;"></i>
                                {{ __('l.show_hide_my_price') }}
                            </button>

			<form id="frm-logout" method="POST" action="{{ route('logout') }}">
            @csrf
            <a type="button" class="nav-link" type="submit"
            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">{{ __('l.logout') }}</a>
          </form>

        </ul>

    </nav>
</div>
<script>
document.addEventListener(
	"DOMContentLoaded", () => {
		const menu = new MmenuLight(
		document.querySelector( "#menu" ),
		"(max-width: 1200px)"
		);

const navigator = menu.navigation({
    //selectedClass: 'Selected',
    //slidingSubmenus: true,
    //theme: 'light',
    title: '{{ __('l.menu') }}'
});
const drawer = menu.offcanvas();

document.querySelector( "a[href='#menu']" )
	.addEventListener( "click", ( evnt ) => {
	evnt.preventDefault();
	drawer.open();
	});
		}
		);
</script>
