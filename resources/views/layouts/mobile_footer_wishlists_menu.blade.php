<div class="d-block d-lg-none">
	<nav class="nav-bottom">
		<a href="/" class="nav-link active">
			<i class="fa fa-home text-primary"></i><span class="text">{{__('l.home')}}</span>
		</a>
		<a href="#menu" class="nav-link">
			<i class="fa fa-bars text-primary"></i><span class="text">{{__('l.menu')}}</span>
		</a>
		<a href="/wishlist" class="nav-link position-relative ">
			<i class="fa fa-heart text-primary"></i>
			<span class="text">{{__('l.wishlist')}}</span>
			<b class="notify" id="wishlist-qty-m">{{$wishlistCount}}</b>
		</a>
		<a href="" class="nav-link active" data-trigger="card_mobile">
			<i class="fa fa-filter text-primary"></i><span class="text">{{__('l.filter')}}</span>
		</a>
		<a href="/cart" class="nav-link position-relative ">
			<i class="fa fa-shopping-cart text-primary"></i>
			<span class="text">{{__('l.cart')}}</span>
			<b class="notify"  id="cart-qty-m">{{$cartCount}}</b>
		</a>
	</nav>
	<nav id="menu">
		<ul>
			<li>
				<span>{{__('l.catalog')}}</span>
					<ul>
                        @foreach ($categories as $item)
						<li>
							<span>{{$item->name}}</span>
								<ul>
                                    @foreach ($item->children as $item_children)
									<li><a href="{{ route('catalog', ['id' => $item_children->id]) }}">{{$item_children->name}}</a></li>
									@endforeach
								</ul>
						</li>
                        @endforeach
					</ul>
			</li>
			<li><a href="#">XML Выгрузка</a></li>
			<li><a href="#">XLS Прайсы</a></li>
			<li><a href="#">API Интеграция</a></li>
			<li><a href="#">Профиль</a></li>
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

const navigator = menu.navigation();
const drawer = menu.offcanvas();

document.querySelector( "a[href='#menu']" )
	.addEventListener( "click", ( evnt ) => {
	evnt.preventDefault();
	drawer.open();
	});
		}
		);
</script>
