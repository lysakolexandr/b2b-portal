
<!-- breakpoints xxl -->
<section class="d-none d-xxl-block section-content pt-0">
			@include('layouts.breakpoints_xxl.products_xxl')
</section>


<!-- breakpoints xl -->
<section class="d-none d-xl-block d-xxl-none section-content pt-0">

            @include('layouts.breakpoints_xl.products_xl')

</section>


<!-- breakpoints lg -->
<section class="d-none d-lg-block d-xl-none bg-warning section-content pt-0">

            @include('layouts.breakpoints_lg.products_lg')

</section>


<!-- breakpoints md -->
<section class="d-none d-md-block d-lg-none bg-info section-content pt-0">

			@include('layouts.breakpoints_md.products_md')

</section>


<!-- breakpoints sm -->
<section class="d-none d-sm-block d-md-none bg-dark section-content pt-0">

			@include('layouts.breakpoints_sm.products_sm')


</section>


<!-- breakpoints xs -->
<section class="d-block d-sm-none">

			@include('layouts.breakpoints_xs.products_xs')

</section>

