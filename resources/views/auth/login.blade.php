<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/css/main.css')}}">
    <link rel="shortcut icon" href="{{ asset('img/wgroup.png') }}" type="image/x-icon">

    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('login_css/img/loader.gif') }}") 50% 50% no-repeat white;
            opacity: .8;
            background-size: 120px 120px;
        }
    </style>

</head>

<body class="gray-bg">
    {{-- <div class="container my-auto" id="login-container" style="height: 100vh">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <h2>Online MDR System</h2>
                <h3 class="text-center">Login</h3>
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form class="m-t" role="form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required="">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required="">
                    </div>
                    <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        
                    <a href="{{ route('password.request') }}"><small>Forgot password?</small></a>
                </form>
            </div>
        </div>
    </div> --}}
    <div id="loader" style="display:none;" class="loader"></div>
    
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form method="POST" action="{{ route('login') }}"  class="login100-form validate-form" onsubmit="show()">
                    @csrf
                
                    <span class="login100-form-title p-b-43">
                        {{ config('app.name', 'Laravel') }}
                    </span>
                    <span class="login100-form-title p-b-43">
                        <h5>Login to continue</h5>
                    </span>
                    
                    @if($errors->any())
                        <div class="form-group alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <strong>{{$errors->first()}}</strong>
                        </div>
                    @endif

                    <div class="wrap-input100" data-validate = "Valid email is required: ex@abc.xyz">
                        <input  type="email" class="input100" name="email" value="" required >
                        <span class="focus-input100"></span>
                        <span class="label-input100">Email</span>
                    </div>
                    
                    
                    <div class="wrap-input100" data-validate="Password is required">
                        <input  id="password" type="password" class="input100" name="password" required>
                        <span class="focus-input100"></span>
                        <span class="label-input100">Password</span>
                    </div>
    
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type='submit'>
                            Login
                        </button>
                    </div>
                    <div class="flex-sb-m w-full p-t-3 p-b-32">
    
                        <div>
                            <a href="{{ route('password.request') }}" class="txt1">
                                Forgot Password?
                            </a>
                        </div>
                    </div>
                </form>
    
                <div class="login100-more" style="background-image: url('login_design/images/WGROUP.png');">
                </div>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    
    <!--===============================================================================================-->
	<script src="{{ asset('login_design/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('login_design/vendor/animsition/js/animsition.min.js')}}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('login_design/js/main.js')}}"></script>

        <script>
            function show() {
                document.getElementById("loader").style.display = "block";
            }
        </script>
</body>

</html>
