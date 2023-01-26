@include('layouts.aside_settings')
<main class="col-lg-9">
    <article class="card mb-3">
        <div class="card-body">
            <figure class="itemside align-items-center">
                <figcaption class="info">
                    <h4 class="title">{{ __('l.add_trusted_user') }}</h4>
                </figcaption>
            </figure>
            <hr>
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            {{ Form::open(['url' => '/user-add']) }}
            {{-- {{ Form::token() }} --}}
            <div class="row gx-3">
                <div class="col-xs-12 col-lg-6 mb-3">
                    <span class="form-check-label">{{ __('l.name') }}</span>
                    <input type="text" name="name" class="form-control" required placeholder="">
                </div>
                <div class="col-xs-12 col-lg-6 mb-3">
                    <span class="form-check-label">email</span>
                    <input type="email" name="email" required class="form-control" placeholder="">
                </div>
            </div>
            <ul class="row row-cols-xxl-3 row-cols-2  mb-3">
                <li>
                    <label class="form-check">
                        <input class="form-check-input" name="purchase_prices" type="checkbox">
                        <span class="form-check-label"> {{ __('l.purchase_prices') }}</span>
                    </label>
                </li>
                <li>
                    <label class="form-check">
                        <input class="form-check-input" name="mutual_settlements" type="checkbox">
                        <span class="form-check-label">
                            {{ __('l.act_of_reconciliation_of_mutual_settlements') }}</span>
                    </label>
                </li>
            </ul>
            <div class="col-auto">
                {{-- {{Form::submit(__('l.save'),['class'=>"btn btn-primary w-100"])}} --}}
                <button class="btn btn-primary w-100 btn-submit" type="button">{{ __('l.save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </article>
</main>

<script type="text/javascript">

    $(document).ready(function() {
        $(".btn-submit").click(function(e){
            e.preventDefault();

            var _token = $("input[name='_token']").val();
            var name = $("input[name='name']").val();
            var email = $("input[name='email']").val();
            var purchase_prices = $("input[name='purchase_prices']").is(':checked');
            var mutual_settlements = $("input[name='mutual_settlements']").is(':checked');

            $.ajax({
                url: "{{ route('user-save') }}",
                type:'POST',
                data: {
                    _token:_token,
                    name:name,
                    email:email,
                    purchase_prices:purchase_prices,
                    mutual_settlements:mutual_settlements
                },
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        window.location.href = '{{URL::to('profile')}}';
                    }else{
                        printErrorMsg(data.error);
                    }
                }
            });

        });

        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
    });


</script>
