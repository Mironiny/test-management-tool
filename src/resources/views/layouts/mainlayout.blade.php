<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dasboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/timeline.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../css/dataTables/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- For better responsity -->
    <style>
        @media screen and (max-width: 650px) {
            body {
                padding-top: 60px;
            }
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Testos</a>
            </div>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

            <!-- Top Navigation: Left Menu -->
            <ul class="nav navbar-nav navbar-left navbar-top-links">
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}"><a href="{{ url('/dashboard') }}"> @lang('layout.dashboard')</a></li>
                <li class="{{ Request::is('requirements') ? 'active' : '' }}"><a href="{{ url('/requirements') }}"> @lang('layout.requirements')</a></li>
                <li><a href="#"> @lang('layout.test_runs')</a></li>
                <li><a href="#"> @lang('layout.test_library')</a></li>
            </ul>

            <!-- Top Navigation: Right Menu -->
            <ul class="nav navbar-right navbar-top-links">


                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-folder fa-fw"></i> Project: {{ $selectedProject->Name}} <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">

                        @foreach ($projects as $project)
                        <li>
                            <a href="{{ url('/projects/changeproject') }}" onclick="event.preventDefault();
                             document.getElementById('changeForm{{ $project->SUT_id}}').submit();">{{ $project->Name }}</a>

                                <form id="changeForm{{ $project->SUT_id}}" action="{{ url('/projects/changeproject') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="number" name="id" value="{{ $project->SUT_id }}">
                                    <input type="text" name="url" value="{{ Request::url() }}">
                                </form>
                        </li>
                        @endforeach

                        <li class="divider"></li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Projects settings</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> {{ Auth::user()->name }} <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{ url('/user') }}"><i class="fa fa-user fa-fw"></i> @lang('layout.user_profile')</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> @lang('layout.settings')</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-fw"></i> @lang('layout.logout')</a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        </li>
                    </ul>
                </li>
            </ul>

            @yield('sidemenu')

        </nav>

        @yield('content')

    </div>


    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../js/dataTables/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/startmin.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function(){
        $('#myTable').DataTable();
    });
    </script>

    <script src="../js/raphael.min.js"></script>
    <script src="../js/morris.min.js"></script>
    <script src="../js/morris-data.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->

</body>

</html>
