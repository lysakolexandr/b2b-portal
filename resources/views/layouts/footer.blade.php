<script src="{{ asset('js/fslightbox.js') }}"></script>
<footer class="section-footer bg-white mt-auto">
	<div class="container">
	    <div id="back_to_top" class="back-to-top">
            <svg xmlns="http://www.w3.org/2000/svg" style="transform: rotate(270deg);" width="24" height="24" viewBox="0 0 24 24"><path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z"/></svg>
        </div>
        <script>
        $('.back-to-top').click(function () {
    $('body,html').animate({ scrollTop: 0}, 800); // 800 - Скорость анимации
});

$(window).scroll(function() { // Отслеживаем начало прокрутки
    let scrolled = $(window).scrollTop(); // Вычисляем сколько было прокручено.

    if(scrolled > 350) { // Если высота прокрутки больше 350 - показываем кнопку
        $('.back-to-top').addClass('active');
    } else {
        $('.back-to-top').removeClass('active');
    }
});
</script>
		<section class="border-top">
				<p class="text-muted"> &copy 2022 {{$_SERVER['SERVER_NAME']}} </p>
		</section>
	</div>
</footer>
