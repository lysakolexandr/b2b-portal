<div class="col-lg-12">
    <div class="card">
        <div class="content-body">
            <nav class="row gy-3">
                @foreach ($cats as $item)
                    <div class="col-3">
                        <a href="{{route('catalog',['id'=>$item->id])}}" class="box boxed-link icontext">
                            <span class="icon"> 
                                <img width="100" height="100" src="{{$item->picture}}">
                               </span>
                               <span class="text-primary b">{{$item->name}}</span>
                        </a> 
                    </div> 
                @endforeach
            </nav>
        </div>
    </div>
</div>


