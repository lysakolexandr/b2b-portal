<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">{{__('Your manager')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span style="font-size: 1.1em; color: Dodgerblue;">
                    <?php
                    $user = auth()->user();
                    ?>

                    <i class="fas fa-user"></i> {{$user->settings['manager_name']}}
                </span>
                <hr>
                <span style="font-size: 1.2em; color: Dodgerblue;">
                    <i class="fas fa-mobile-alt"></i> {{$user->settings['manager_phone']}}
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-blue white" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalTeh" tabindex="-1" aria-labelledby="exampleModalLabelTeh" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">{{__('Support')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<a class="" href="#">
					<span style="font-size: 1.2em; color: Dodgerblue;">
						<i class="fas fa-mobile-alt"></i> +380123456789
					</span>
				</a>
                <hr>
				<a class="" href="#">
					<span style="font-size: 1.2em; color: #824aad;">
						<i class="fab fa-viber"></i> +380123456789
					</span>
				</a>
                <hr>
				<a class="" href="#">
					<span style="font-size: 1.2em; color: #3d8fc7;">
						<i class="fab fa-telegram"></i> +380123456789
					</span>
				</a>
                <hr>
				<a class="" href="#">
					<span style="font-size: 1.1em; color: Dodgerblue;">
						<i class="fas fa-at"></i> webmaster@site.com.ua
					</span>
				</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-blue white" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
