<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Online MDR System</title>

    <link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('img/wgroup.png') }}" type="image/x-icon">
    <!-- Gritter -->
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

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

        @media (min-width: 768px) {
            #kpiModal {
                width: 1200px;
                margin: 30px auto;
            }
        }
    </style>

    @yield('css')
    
</head>
<body>
    <div id="wrapper">
        <div id="loader" style="display:none;" class="loader"></div>

        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                            {{-- <span>
                                <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                            </span> --}}
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong></span>
                                {{-- <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> --}}
                                <span class="text-muted text-xs block">
                                    {{auth()->user()->role}}
                                </span>
                            </a>
                        </div>
                    </li>
                    @if(auth()->user()->role != "Human Resources")
                    <li class="{{ Route::currentRouteName() == "dashboard" ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->role == "Administrator")
                        <li class="{{ Route::currentRouteName() == "mdr" ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-file"></i>
                                <span class="nav-label">MDR</span> 
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li class=""><a href="{{ url('mdr_group') }}">MDR Group</a></li>
                                <li class=""><a href="{{ url('mdr_setup') }}">MDR Setup</a></li>
                            </ul>
                        </li>
                        <li class="{{ Route::currentRouteName() == 'settings' ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-cog"></i>
                                <span class="nav-label">Settings</span> 
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li class=""><a href="{{ url('companies') }}">Companies</a></li>
                                <li class=""><a href="{{ url('departments') }}">Departments</a></li>
                                <li class=""><a href="{{ url('user-accounts') }}">User Accounts</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
                        <li class="{{ Route::currentRouteName() == "forApproval" ? 'active' : '' }}">
                            <a href="{{ url('for_approval') }}">
                                <i class="fa fa-pencil-square-o"></i>
                                <span class="nav-label">For Approval MDR</span>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteName() == "pendingMdr" ? 'active' : '' }}">
                            <a href="{{ url('pending_mdr') }}">
                                <i class="fa fa-clock-o"></i>
                                <span class="nav-label">Pending MDR</span>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteName() == "historyMdr" ? 'active' : '' }}">
                            <a href="{{ url('history_mdr') }}">
                                <i class="fa fa-calendar"></i>
                                <span class="nav-label">Monthly MDR Data</span>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteName() == "listOfPenalties" ? 'active' : '' }}">
                            <a href="{{ url('list_of_penalties') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span class="nav-label">List of Penalties</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->role == "Department Head" || auth()->user()->role == "Users" || auth()->user()->role == "Business Process Manager")
                        <li class="{{ Route::currentRouteName() == "mdr" ? 'active' : '' }}">
                            <a href="{{ url('mdr') }}">
                                <i class="fa fa-file"></i>
                                <span class="nav-label">MDR</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->role == "Human Resources" || auth()->user()->role == "Department Head" || auth()->user()->role == "Users")
                        <li class="{{ Route::currentRouteName() == "listOfPenalties" ? 'active' : '' }}">
                            <a href="{{ url('list_of_penalties') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span class="nav-label">List of Penalties</span>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteName() == 'ntePenalties' ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-ban"></i>
                                <span class="nav-label">Penalties</span> 
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li class=""><a href="{{ url('notice_of_explanation') }}">Notice of Explanation</a></li>
                                <li class=""><a href="{{ url('notice_of_disciplinary') }}">Notice of Disciplinary</a></li>
                                <li class=""><a href="{{ url('performance_improvement_plan') }}">Performance Improvement Plan</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome to Online MDR System</span>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="logout(); show();">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
            @yield('content')
        </div>
    </div>

    {{-- Sweet Alert --}}
    @include('sweetalert::alert')

    <script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
    
    @stack('scripts')
    <!-- Mainly scripts -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Flot -->
    {{-- <script src="{{ asset('js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script> --}}

    <!-- Peity -->
    {{-- <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script> --}}

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('js/inspinia.js') }}"></script>
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>

    <!-- jQuery UI -->
    {{-- <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script> --}}

    <!-- Jvectormap -->
    {{-- <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> --}}

    <!-- EayPIE -->
    {{-- <script src="js/plugins/easypiechart/jquery.easypiechart.js"></script> --}}

    <!-- Sparkline -->
    {{-- <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script> --}}

    <!-- Sparkline demo data  -->
    {{-- <script src="js/demo/sparkline-demo.js"></script> --}}
    <script>
        function show() {
            document.getElementById("loader").style.display = "block";
        }

        function logout() {
            event.preventDefault(); 
            
            document.getElementById('logout-form').submit()
        }
    </script>
</body>
</html>