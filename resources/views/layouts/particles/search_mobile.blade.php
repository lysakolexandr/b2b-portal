      
    <section class="header-main border-bottom bg-white pt-1 pb-1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-10">
                        <form action="#" class="">
                            <div class="input-group w-100">
                                <input type="text" class="form-control" placeholder="Search">
                                <div class="input-group-append">
                                <button class="btn bg-blue white" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-1">
                        <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-trigger="#filter_mobile" data-toggle="tooltip" title="Setting"> 
                            <i class="fas fa-filter"></i>
				        </a>
                    </div>
                </div>
            </div>
        </section>

        @include('layouts.particles.filter_mobile')

        @include('layouts.particles.content_header_mobile')
