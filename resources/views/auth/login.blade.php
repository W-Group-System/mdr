<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('img/wgroup.png') }}" type="image/x-icon">

    <!-- Gritter -->
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
</head>

<style>
    #login-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loginscreen {
        padding: 0;
        margin-bottom: 70px;
    }
</style>

<body class="gray-bg">
    <div class="container my-auto" id="login-container" style="height: 100vh">
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
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="js/plugins/toastr/toastr.min.js"></script>

</body>

</html>
