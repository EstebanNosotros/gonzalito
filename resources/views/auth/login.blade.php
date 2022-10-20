<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar Sesión - {{ ENV('APP_NAME') }}</title>
        <link rel="icon" href="{{ asset('/storage/favicon.png') }}" type="image/png" />
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/adminlte.min.css') }}">

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

        <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/slick.min.js') }}"></script>
        {!! htmlScriptTagJsApi() !!}
    </head>
 {{--   <body class="hold-transition login-page">
        @php
            if (!$errors->isEmpty()) {
                alert()->error('Aviso', implode('<br>', $errors->all()))->toToast()->toHtml();
            }
        @endphp
        <style>
            /* ICONS START HERE */

            .username,
            .password {
            display: flex;
            align-items: center;
            position: relative;
            border-radius: 2px;
                border:1px solid #ECF0EF;
                height: 35px;
                -webkit-box-shadow: 0px 0px 28px -15px rgba(0,0,0,1);
                -moz-box-shadow: 0px 0px 28px -15px rgba(0,0,0,1);
                box-shadow: 0px 0px 28px -15px rgba(0,0,0,1);
            }

            .username input[type="text"],
            .password input[type="password"]{
                padding: 5px 27.5px;
                position: relative;
                border: none;
                background-color: unset;
            }

            .username i:first-child,
            .password i:first-child {
            left: 7.5px;
                position: absolute;
                z-index: 9999;
            }
            .username i:last-child,
            .password i:last-child {
                right: 7.5px;
                position: absolute;
                z-index: 9999;
            }
            .password input::after{
                content: '<i class="fa fa-envelope icon"> ';
            }

            /* ICONS END HERE */
        </style>
        <div class="login-box">
        <!-- /.login-logo -->
            <img src="{{ env('APP_URL') }}/storage/logo-gonzalito2.png" alt="Tienda Gonzalito" style="width: 60%; margin-top: -60%; margin-bottom: 20%; margin-left: 15%;">
            <div class="card card-outline">
                <div class="card-header text-center" style="border-bottom: 0px;">
                    <b class="h1">Bienvenido</b><br/>
                    <small style="color: #777777">Ingres&aacute; tus datos para acceder al cat&aacute;logo</small>
                </div>
                <div class="card-body">
                    <form method="POST" id="#recaptcha-form" action="{{ route('login') }}">
                        @csrf
                        <label>Correo Electr&oacute;nico</label>
                        <div class="input-group mb-3 username">
                            <!--i class="fa fa-user icon"></i-->
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="     Correo Electr&oacute;nico">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <!--i class="fa fa-envelope icon"></i-->
                        </div>
                        <label>Contrase&ntilde;a</label><br/>
                        <div class="input-group mb-3 password">
                            <!--i class="fa fa-lock icon"></i-->
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder=" Contrase&ntilde;a">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <!--i class="fa fa-envelope icon"></i-->
                        </div>
                       {{-- <div class="form-group mb-3">
                            <div class="col-md-12">
                                {!! htmlFormSnippet([
                                    "theme" => "light",
                                    "size" => "normal",
                                    "tabindex" => "3",
                                    "callback" => "callbackFunction",
                                    "expired-callback" => "expiredCallbackFunction",
                                    "error-callback" => "errorCallbackFunction",
                                ]) !!}
                            </div>
                        </div> --}-}
                        <div class="row">
                            <!--div class="col-8">
                                <div class="icheck-primary">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div-->
                            <!-- /.col -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block" style="background-color: #E87B14; border: 0px; padding: 10px; margin-top: 25px;">{{ __('Ingresar') }}</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        @include('sweetalert::alert')
        <!-- /.login-box -->
        <!-- jQuery -->
        <script src="{{ asset('template/admin/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('template/admin/dist/js/adminlte.js') }}"></script>
    </body> --}}
    <body>
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('fail'))
            <div class="alert alert-danger">
            {{Session::get('fail')}}
            </div>
        @endif
        <main role="main" class="container-fluid" id="login">
            <div class="wrapper d-flex align-items-center">
                <div>                    
                    <div class="logo">
                        <img src="{{ asset('img/Logo-login.svg') }}" alt="Logo Gonzalito">
                    </div>
                    <div class="form-wrap">
                        <h1>Bienvenido</h1>
                        <p>Ingresá tus datos para acceder al catálogo</p>
            
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Correo Electr&oacute;nico</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><img src="{{ asset('img/person.svg') }}"></div>
                                    </div>
                                    <input type="text" class="form-control" id="email" name="email" autofocus>
                                </div>
                                <div>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                            <label for="password">Contraseña</label>
            
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><img src="{{ asset('img/lock-icon.svg') }}"></div>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-4" >Ingresar</button>
                        </form>
                    </div>
                </div>
            </div>
    
    
        </main><!-- /.container -->
        <div class="footer-line"></div>
        @include('sweetalert::alert')
        <!-- /.login-box -->
        <!-- jQuery -->
        <script src="{{ asset('template/admin/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('template/admin/dist/js/adminlte.js') }}"></script>
    </body>
</html>
