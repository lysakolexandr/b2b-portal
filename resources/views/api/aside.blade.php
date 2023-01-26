<aside class="col-lg-3">
    <div class="card">
        <div class="card-body">
            <input type="text" class="form-control" id="api-token" value="{{ $token }}" disabled>
            <div class="form-text b text-center">API {{ __('l.key') }}</div>
            <div class="d-grid gap-2">
                <a href="javascript:void(0);" class="btn btn-outline-primary mt-2" id="change-token">{{ __('l.change_key') }}</a>
                <a href="api-manual" class="btn btn-outline-primary mt-2">{{ __('l.manual') }}</a>
            </div>
        </div>
    </div>
</aside>
