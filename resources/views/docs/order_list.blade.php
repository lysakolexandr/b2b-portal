<article class="card">
    <div class="card-body">
        <div class="d-none d-lg-block">
            <form>
                <div class="row g-2">
                    <div class="col-auto">
                        <h5 class="card-title ">{{ __('l.order_list') }}</h5>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="number-search" placeholder="{{ __('l.number_search') }}">
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control date-from" id="date-from" value=""
                            placeholder="{{ __('l.from_date') }}">
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control date-to" id="date-to" value="" placeholder="{{ __('l.to_date') }}">
                    </div>
                    <!--<div class="col-auto">-->
                    <!--    <select class="form-select" id="source-order">-->
                    <!--        <option value="4" selected>{{ __('l.all_source_order') }}</option>-->
                    <!--        <option value="1">{{ __('l.b2b') }}</option>-->
                    <!--        <option value="2">{{ __('l.manager') }}</option>-->
                    <!--        <option value="3">{{ __('l.office') }}</option>-->
                    <!--    </select>-->
                    <!--</div>-->
                    <div class="col-auto">
                        <a class="btn w-100 btn-primary" href="javascript:void(0);" id="go-filter-orders">{{ __('l.filter') }}</a>
                    </div>
                    <div class="col-auto">
                        <a class="btn w-100 btn-gray" href="javascript:void(0);" id="clear-filter">{{ __('l.clear_filter') }}</a>
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-primary ms-5" href="{{route('make-order-from-excel')}}">{{ __('l.make_order_from_excel') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-auto d-block d-lg-none">
            <h5 class="card-title ">{{ __('l.order_list') }}</h5>
        </div>
        <hr>
        <div id="ajax-reload">
            @include('docs.order_list_body')
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
