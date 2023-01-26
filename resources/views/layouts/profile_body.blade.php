@foreach ($trusted_users as $item)
    <div class="card p-3 mb-3 border-primary">
        <header class="d-lg-flex">
            <div class="flex-grow-1">
                <h6 class="mb-0">{{ $item->name }} <i class="dot"></i>
                    <span class="">{{ $item->email }}</span>
                </h6>
                <span class="text-muted">{{ __('l.show_price') }}: <i
                        class="me-2  @if ($item->price_view == 1) text-success fa fa-check @else text-danger fa fa-times @endif "></i></span>
                <span class="text-muted">{{ __('l.show_sattlements') }}: <i
                        class="me-2  @if ($item->settlements_view == 1) text-success fa fa-check @else text-danger fa fa-times @endif "></i></span>
            </div>
            <div>
                <a href="./user-edit/{{$item->id}}" class="text-decoration-none">
                    <span class="icon icon-xs rounded box">
                        <i class="fas fa-pen"></i>
                    </span>
                </a>
                <a href="javascript:void(0);" class="text-decoration-none delete-user" data-user-id="{{$item->id}}">
                    <span class="icon icon-xs rounded box">
                        <i class="far fa-trash-alt"></i>
                    </span>
                </a>
            </div>
        </header>
    </div>
@endforeach
