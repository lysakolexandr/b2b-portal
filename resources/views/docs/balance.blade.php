
@extends('layouts.app')
@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('js/orders.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.ua.min.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.ru.min.js') }}" charset="UTF-8"></script>
@endsection

@section('content')
	<header class="section-header sticky-top">
		@include('layouts.header_top')
		@include('layouts.header_battom')
	</header>
	<section class="padding-y-sm bg-light">
		<div class="container">
			<div class="row">
<article class="card">
    <div class="card-body">
        <div class=" ">
            <form>
                <div class="row g-2">
                    <div class="col-auto">
                        <h5 class="card-title ">{{ __('l.change_period') }}</h5>
                    </div>

                    <?php
                    $fromDate = new DateTime('-30 days');
                    $fromDate = ($fromDate->format('d').'.'.$fromDate->format('m').'.'.$fromDate->format('Y'));
                    $endDate = new DateTime();
                    $endDate = ($endDate->format('d').'.'.$endDate->format('m').'.'.$endDate->format('Y'));
                    ?>
                    <div class="col-sm-12  col-md-3">
                        <input type="text" class="form-control date-from" id="date-from" value="{{$fromDate}}"
                            placeholder="{{ __('l.from_date') }}">
                    </div>

                    <div class="col-sm-12  col-md-3">
                        <input type="text" class="form-control date-to" id="date-to" value="{{$endDate}}" placeholder="{{ __('l.to_date') }}">
                    </div>
                    <div class="col-sm-12  col-md-3">
                        <a class="btn w-100 btn-primary" href="javascript:void(0);" id="get-report">{{ __('l.get_report') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <hr>
        <div id="loading" style="text-align: center; padding-top: 20vh; display: none;">
            <img style="width: 20vh;" src="https://{{setting('domain')}}/img/loading.gif" alt="loading">
        </div>
        <div id="ajax-reload" style="display: none;">
            <iframe id="my_pdf" src="" title="testPdf" height="500px" width="100%"></iframe>
        </div>
    </div>
</article>
<script>
    $('.date-from').datepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
        language: "{{ app()->getLocale() }}",
    });
    $('.date-to').datepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
        language: "{{ app()->getLocale() }}",
    });
</script>

{{-- @include('layouts.mobile_footer_menu') --}}
@include('layouts.footer')
@endsection


