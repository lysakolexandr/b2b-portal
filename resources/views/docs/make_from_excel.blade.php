@extends('layouts.app')
{{-- @include('layouts.app') --}}

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
<script>var upload_order = "{{ __('l.please_upload_files') }}";</script>
<script>var upload_error = "{{ __('l.error_upload_order') }}";</script>
<script>var cart_url = "{{ route('cart')}}";</script>
@endsection

@section('content')
<header class="section-header sticky-top">
    @include('layouts.header_top')
    @include('layouts.header_battom')
</header>

    <div class="container">
        <h3 class="jumbotron">{{ __('l.upload_file_like_this_template') }}</h3>
                <div class="mx-auto" style="max-width: 760px;">
        <img src ="{{ asset('img/price.png') }}" class="img-fluid" />
        </div>
        <br>
        <p class="b">* {{ __('l.upload_file_p1') }}
            <br>* {{ __('l.upload_file_p2') }}
        </p>

        <form method="post" action="{{ route('upload-order') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
            @csrf
        </form>
    </div>

<script type="text/javascript">
    Dropzone.options.dropzone = {
        maxFilesize: 256,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        acceptedFiles: ".xls,.xlsx",
        uploadMultiple: false,
        maxFiles: 1,
        dictDefaultMessage: upload_order,
        addRemoveLinks: true,
        timeout: 5000,
        success: function(file, response) {
            window.location.href = cart_url;
        },
        error: function(file, response) {
            toastr.options.positionClass = 'toast-top-center';
            toastr.options.timeOut = 30000;
            toastr.error(upload_error);
            $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(upload_error);
        }
    };
</script>
@endsection
