<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <title>@if(View::hasSection('title'))@yield('title') | @endif {{ Config::get('app.name')  }}
    </title>
    <!-- Icons -->
    <link href="{{ asset('assets/css/bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome 5.13.0/css/all.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/css/simple-line-icons.css') }}" rel="stylesheet">
     <link href="{{asset('assets/selectize/dist/css/selectize.bootstrap3.css') }}" type="text/css" rel="stylesheet"/>
     <link href="{{ asset('assets/css/jquery.businessHours.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/css/stacktable.css') }}" rel="stylesheet">--}}
    {{-- <link rel="stylesheet" type="text/css"
           href="//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.2.17/jquery.timepicker.min.css"/>--}}
    {{--   <link href="{{ asset('assets/css/lightbox.min.css') }}" rel="stylesheet">
       <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
       <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css" rel="stylesheet"
             type="text/css"/>

       --}}{{--Nepali date picker--}}{{--

       <link href="{{ asset('nepalidate/nepali.datepicker.v2.2.min.css') }}" rel="stylesheet">
       <link href="{{ asset('assets/css/iconfonts.css')  }}" rel="stylesheet">--}}

    <style>
        .required-field {
            color: red;
        }

        .level {
            display: flex;
        }

        .flex {
            flex: 1;
        }

        .background-color-brown {
            background-color: #eee;
        }

    </style>

    @yield('style')
{{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">--}}

    <!-- Main styles for this application -->
    {{--  <link href="{{ asset('assets/css/print.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/scss/backend-style.css').'?v='.rand(43454,994384) }}" rel="stylesheet">--}}

<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158518065-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-158518065-1');
    </script>

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
    <a class="navbar-brand" href="{{URL::TO('/')}}"></a>
    <ul class="nav navbar-nav ml-auto">

        <li class="nav-item dropdown">
            <a class="nav-link nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false">
                {{Auth::user()->name}}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                {{--                <a href="{{route('password.reset')}}" class="dropdown-item"><i class="fa fa-key"></i> Change Password</a>--}}
                {{--                <a href="" class="dropdown-item"><i class="fa fa-key"></i> Change Password</a>--}}
                <a class="dropdown-item" href="{{route('user-change-password')}}">
                    <i class="fa fa-user"></i> Change Password</a>

                <a class="dropdown-item" href="{{ route('password.request', ['token' => csrf_token()]) }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> {{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{csrf_field()}}
                </form>
            </div>
        </li>
    </ul>
    <button class="navbar-toggler sidebar-minimizer d-md-down-none" type="button">☰</button>
</header>
