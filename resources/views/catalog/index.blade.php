@extends('layouts.app')

@section('content')


<section class="section-content pt-0">
    <div class="container">
        <div class="row">
            @include('layouts.particles.aside')
            <main class="col-md-9 d-none d-lg-block">
                @include('layouts.particles.content_header_desktop')
                @include('layouts.particles.content_products_desktop') 
                @include('layouts.particles.pagination')
            </main>            
        </div>    
        <div class="d-block d-sm-none">
            @include('layouts.particles.content_products_mobile')
            
        </div>     
        
    </div>
</section>

@endsection
