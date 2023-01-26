<?php

$count = ($orders->total()-($orders->perPage()*($orders->currentPage()-1)))+1;

?>
@foreach ($orders as $key=>$order )
<?php
$count = $count-1;
?>
<a href="{{route('order-detail',['id'=>$order->id])}}" class="text-decoration-none">
    <div class="row">
        <div class="col-xl-5">
            <div class="row">
                <div class=" col-xs-11 col-sm-11 col-md-10">
                    <span class="aside icon-xs h6">{{$count}}</span>

                    <span class="b"><span class="text-dark">№ </span>{{$order->code}}</span>
                    <span class="b text-dark">{{__('l.from')}} {{$order->created_at}}</span>

                </div>
            </div>
        </div>
        <div class="col-xl-1">
            <span class="badge {{$order->sourceClass}}"> {{$order->sourceName}} </span>
        </div>
        <div class="col-xl-6">
            <ul class="steps-wrap">
                <li class="step @if ($order->status>=1) active @endif">
                    <span class="icon">1</span>
                    <span class="text">{{__('l.new')}}</span>
                </li>
                <li class="step @if ($order->status>=2) active @endif">
                    <span class="icon">2</span>
                    <span class="text">{{__('l.confirm')}}</span>
                </li>
                <li class="step @if ($order->status>=3) active @endif">
                    <span class="icon">3</span>
                    <span class="text">{{__('l.success')}}</span>
                </li>
            </ul>
        </div>
    </div>
    <span class="">{{__('l.comment')}}: <b class="text-dark">{{$order->comment}}</b></span>

</a>
<hr>
@endforeach
{!! $orders->links('layouts.mobile_pagination') !!}

