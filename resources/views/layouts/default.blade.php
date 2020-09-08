@include('layouts.header')
<div class="app-body">
@include('layouts.sidebar')

<!-- Main content -->
    <main class="main">
        {{-- Breadcrumbs --}}
        {!! Breadcrumbs::render($crumbroute) !!}

        <div class="container-fluid">

            @if (count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    @foreach ( $errors->all() as $error )
                        <p> {!! $error !!} </p>
                    @endforeach
                </div>
            @endif

            @if(Session::has('flash'))
                <div class="flash-mesg-section">
                    <div class="alert alert-<?php echo (Session::get('flash')['status'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show"
                         role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {!!  Session::get('flash')['mesg'] !!}
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

@include('layouts.footer')


