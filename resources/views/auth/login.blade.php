<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Paywatch Login </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/login.css')}}">
</head>
<body>

<div class="limiter">
    <div class="container-login100">

        <div class="wrap-login100">
            <form method="POST" action="{{ url('login') }}" class="login100-form validate-form">
                {{ csrf_field() }}
                @if($errors->any())
                    <div class="text-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{!!  $error!!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <span class="login100-form-title p-b-26">
						Paywatch
					</span>
                <span class="login100-form-title p-b-48">
						<img src="{{asset('assets/images/logo.png')}}" alt="Paywatch">
					</span>

                <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">
                    <input class="input100" id="email" type="text" name="email">
                    <span class="focus-input100" data-placeholder="Email"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input class="input100" id="password" type="password" name="password">
                    <span class="focus-input100" data-placeholder="Password"></span>
                </div>

                <div class="validate-input" data-validate="Enter password">
                    <input class="" type="checkbox" value="1" name="remember" checked>
                    <label>Remember Me</label>

                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn">
                            Login
                        </button>
                    </div>
                </div>

                <div class="text-center" align="center">
                    <a class="txt2" href="{{route('password.request')}}">
                        Forgot Password ?
                    </a>
                </div>

            </form>

        </div>

    </div>
</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script>
    $('#email,#password').trigger('click');

    (function ($) {
        "use strict";


        /*==================================================================
        [ Focus input ]*/
        $('.input100').each(function () {
            $(this).on('blur', function () {
                if ($(this).val().trim() != "") {
                    $(this).addClass('has-val');
                } else {
                    $(this).removeClass('has-val');
                }
            })
        })


        /*==================================================================
        [ Validate ]*/
        var input = $('.validate-input .input100');

        $('.validate-form').on('submit', function () {
            var check = true;

            for (var i = 0; i < input.length; i++) {
                if (validate(input[i]) == false) {
                    showValidate(input[i]);
                    check = false;
                }
            }

            return check;
        });


        $('.validate-form .input100').each(function () {
            $(this).focus(function () {
                hideValidate(this);
            });
        });

        function validate(input) {
            if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
                if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                    return false;
                }
            } else {
                if ($(input).val().trim() == '') {
                    return false;
                }
            }
        }

        function showValidate(input) {
            var thisAlert = $(input).parent();

            $(thisAlert).addClass('alert-validate');
        }

        function hideValidate(input) {
            var thisAlert = $(input).parent();

            $(thisAlert).removeClass('alert-validate');
        }

        /*==================================================================
        [ Show pass ]*/
        var showPass = 0;
        $('.btn-show-pass').on('click', function () {
            if (showPass == 0) {
                $(this).next('input').attr('type', 'text');
                $(this).find('i').removeClass('zmdi-eye');
                $(this).find('i').addClass('zmdi-eye-off');
                showPass = 1;
            } else {
                $(this).next('input').attr('type', 'password');
                $(this).find('i').addClass('zmdi-eye');
                $(this).find('i').removeClass('zmdi-eye-off');
                showPass = 0;
            }

        });


    })(jQuery);
</script>
</body>
</html>

{{--<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->
    <title>{{ Config::get('app.name') }}</title>
    <!-- Icons -->
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/scss/backend-style.css') }}" rel="stylesheet">
</head>
<body class="login-page">
<div class="container">
    <div class="row">
        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
            @if (count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    @foreach ( $errors->all() as $error )
                        <p> {{ $error }} </p>
                    @endforeach
                </div>
            @endif
            <div class="card-group ">
                <div class="card p-a-2">
                    <div class="card-block">

                        <form method="POST" action="{{ url('login') }}">
                            {{ csrf_field() }}
                            <h1>Login</h1>
                            <p class="text-muted">Sign In to your account</p>
                            <div class="input-group m-b-1">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Username">
                            </div>
                            <div class="input-group m-b-2">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>
                            <div class="field remember-me">
                                <div class="ui checkbox">
                                    <input type="checkbox" tabindex="0" class="hidden" name="remember">
                                    <label>Remember Me</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-md-6">
                                    <button type="submit" class="btn btn-primary p-x-2">Login</button>
                                </div>
                                <div class="col-xs-6 text-xs-right">
                                    <a href="#" class="btn btn-link p-x-0">Forgot password?</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="card card-inverse card-primary p-y-3" style="width:44%">
                    <div class="card-block text-xs-center">
                        <div>
                            <h2>Forgot Password ?</h2>
                            <a href="{{route('password.request')}}"  class="btn btn-primary active m-t-1">Reset Password Here!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Bootstrap and necessary plugins -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.js') }}"></script>
<script src="{{ asset('assets/js/tether.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script>
    function verticalAlignMiddle()
    {
        var bodyHeight = $(window).height();
        var formHeight = $('.vamiddle').height();
        var marginTop = (bodyHeight / 2) - (formHeight / 2);
        if (marginTop > 0)
        {
            $('.vamiddle').css('margin-top', marginTop);
        }
    }
    $(document).ready(function()
    {
        verticalAlignMiddle();
    });
    $(window).bind('resize', verticalAlignMiddle);
</script>
</body>
</html>--}}
