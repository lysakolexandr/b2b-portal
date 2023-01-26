    @include('layouts.aside_settings')
    <main class="col-lg-9">
        <?php
        $user = auth()->user();
        ?>
        <article class="card mb-3">
            <div class="content-body">

                <figure class="itemside align-items-center">

                    <figcaption class="info">
                        <h5 class="title mb-2">{{ __('l.yourId') }}: {{ $user->user_code }}</h5>
                        <h6 class="title">{{ __('l.yourManager') }}:</h6>
                        <p class="mb-2">{{ $user->settings['manager_name'] }},<br>
                            {{ $user->settings['manager_phone'] }}</p>
                        <h6 class="title">Support:</h6>
                        <p>
                            <span class="b">Tel: </span>{{ setting('phone_admin') }}<br>
                            <span class="b">Viber: </span>{{ setting('viber_admin') }}<br>
                            <span class="b">Telegram: </span>{{ setting('telegram_admin') }}<br>
                            <span class="b">Email: </span>{{ setting('email_admin') }}
                        </p>
                    </figcaption>
                </figure>
                <figure class="itemside align-items-center">

                    <figcaption class="info">
                <button class="btn btn-primary" type="button" id="change_hide_price"
                                onclick="changeHidePrice()">
                                <img id="btn-loading" style="width: 15px;padding-bottom: 4px;display: none;"
                                    src="../img/loading.gif" alt="loading">
                                <i id="btn-check" class="fas fa-check-circle" style="display: none;"></i>
                                {{ __('l.show_hide_my_price') }}
                            </button>
                        </figcaption>
                    </figure>
            </div>
        </article>
        <article class="card mb-3">
            <div class="">
                <a href="https://invite.viber.com/?g2=AQAxsUILLSap5E0F05J0X0qrKoBweVgwKL%2FzsoCfEaWeMHtxs20xg6hDNOsRgcKv" target="_blank" class="text-decoration-none">
        	        <div class="icontext me-4" style="color: Mediumslateblue;">
        		        <span class="icon-lg ">
        			        <i class="fab fa-viber"></i>
        		        </span>
            		    <div class="text">
            			    <b>{{ __('l.viber') }}</b>
            		    </div>
        	        </div>
                </a>
        	    <a href="https://t.me/+S-HzNMPGz0YxNmEy" target="_blank" class="text-decoration-none">
                	<div class="icontext me-4" style="color: Dodgerblue;">
                		<span class="icon-lg">
                			<i class="fab fa-telegram"></i>
                		</span>
                		<div class="text">
                			<b>{{ __('l.telegram') }}</b>
                		</div>
        	        </div>
                </a>
        </div>
        </article>
        <article class="card mb-3">
            <div class="card-body">
                <figure class="itemside align-items-center">
                    <figcaption class="info">
                        <h4 class="title">{{ __('l.changePassword') }}</h4>
                    </figcaption>
                </figure>
                <hr>
                <form>
                    <div class="row gx-3">
                        <div class="col-xs-12 col-lg-5 mb-4">
                            <input type="text" class="form-control" id="password" placeholder="">
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __('l.pass_and_repass_is_requared') }}.</strong>
                            </span>
                        </div>
                        <div class="col-xs-12 col-lg-5 mb-4">
                            <input type="text" class="form-control" id="password_repeat" placeholder="">
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __('l.pass_and_repass_not_mutch') }}.</strong>
                            </span>
                        </div>

                        <div class="col-auto">
                            <button class="btn btn-primary" type="button" id="change_password"
                                onclick="changePassword()">
                                <img id="btn-loading" style="width: 15px;padding-bottom: 4px;display: none;"
                                    src="../img/loading.gif" alt="loading">
                                <i id="btn-check" class="fas fa-check-circle" style="display: none;"></i>
                                {{ __('l.change') }}</button>
                        </div>
                    </div>
                </form>


            </div>
        </article>

        @if (Auth::user()->parent_id ==null)
            <article class="card mb-4">
                <div class="card-body">
                    <figure class="itemside align-items-center">
                        <figcaption class="info">
                            <h4 class="title">{{ __('l.trustedUsers') }}</h4>
                        </figcaption>
                    </figure>
                    <hr>
                    <a href="user-add" class="btn btn-light mb-2"> <i class="me-2 fa fa-plus"></i>
                        {{ __('l.addUser') }}
                    </a>
                    <div id="ajax-trusted-user">
                        @include('layouts.profile_body')
                    </div>
                </div>
            </article>
        @endif


    </main>
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <script></script>
