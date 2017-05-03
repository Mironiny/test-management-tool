<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Material Design fonts -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    {{--<link rel="stylesheet" type="text/css" href="/css/bootstrap-material-design.min.css">--}}
    {{--<link rel="stylesheet" type="text/css" href="/css/ripples.min.css">--}}

    <!-- Bootstrap core CSS -->

    <!-- MetisMenu CSS -->
    <link href="/css/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="/css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <link href="/css/multi-select.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="/css/dataTables/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/css/startmin.css" rel="stylesheet">

    <!-- Sidebar and others -->
    <link href="/css/tmt.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Sidebar and others -->
    <link href="/css/tmt.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="/js/jquery.min.js"></script>

    @yield('javascript')

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/js/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables/dataTables.bootstrap.min.js"></script>

    <script src="/js/stretchy.js"></script>


    <!-- Custom Theme JavaScript -->
    <script src="/js/startmin.js"></script>

    <script>

    var sidenNavWidth = "225px";

    function openNav() {
        if (document.getElementById("mySidenav").style.width == sidenNavWidth) {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }
        else {
            document.getElementById("mySidenav").style.width = sidenNavWidth;
            document.getElementById("main").style.marginLeft = sidenNavWidth;
        }

    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
    }

    </script>

</head>

<body>

    {{-- <div id="wrapper"> --}}

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <img class="navbar-brand" src="/img/testos-logo.png" style="padding: 5px 5px;">
                <a class="navbar-brand" href="{{ url('/') }}">Testos</a>
            </div>

            <!-- Top Navigation: Left Menu -->
            <ul class="nav navbar-nav navbar-left navbar-top-links">
                <li class="{{ Request::is('dashboard*') ? 'active' : '' }}"><a href="{{ url('/dashboard') }}"> @lang('layout.dashboard')</a></li>
                <li class="{{ Request::is('requirements*') ? 'active' : '' }}"><a href="{{ url('/requirements') }}"> @lang('layout.requirements')</a></li>
                <li class="{{ Request::is('sets_runs*') ? 'active' : '' }}"><a href="{{ url('/sets_runs') }}"> @lang('layout.test_runs')</a></li>
                <li class="{{ Request::is('library*') ? 'active' : '' }}"><a href="{{ url('/library') }}"> @lang('layout.test_library')</a></li>
            </ul>

            <!-- Top Navigation: Right Menu -->
            <ul class="nav navbar-right navbar-top-links">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-folder fa-fw"></i> @lang('layout.project'): {{ isset($selectedProject->Name) ? $selectedProject->Name : '' }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">

                        @if (isset($projects))
                            @foreach ($projects as $project)
                            <li>
                                <a href="{{ url('/projects/changeproject') }}" onclick="event.preventDefault();
                                 document.getElementById('changeForm{{ $project->SUT_id}}').submit();"><i class="{{ isset($selectedProject->Name) &&  $selectedProject->SUT_id == $project->SUT_id ? 'fa fa-hand-o-right fa-fw' : ''}}"></i> {{ $project->Name }}</a>

                                    <form id="changeForm{{ $project->SUT_id}}" action="{{ url('/projects/changeproject') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="number" name="id" value="{{ $project->SUT_id }}">
                                        <input type="text" name="url" value="{{ Request::url() }}">
                                    </form>
                            </li>
                            @endforeach
                        @endif

                        <li class="divider"></li>
                        <li><a href="{{ url('/projects') }}"><i class="fa fa-gear fa-fw"></i>@lang('layout.projects_management')</a></li>
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
                                    <button type="submit" name="logout"></button>
                                </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        {{-- Standart nav --}}
        <div id="mySidenav" class="sidenav" style="{{ isset($sidemenuToogle) ? 'width:225px' : 'width:0px' }}">
            @yield('sidemenu')
        </div>

        <div id="main" style="{{ isset($sidemenuToogle) ? 'margin-left:225px' : 'margin-left:0px' }}">
            <div id="page-wrapper">
                @yield('content')
            </div>
        </div>


    {{-- </div> --}}

</body>
{{--<script src="/js/material.min.js"></script>--}}
{{--<script src="/js/ripples.min.js"></script>--}}
{{--<script>--}}
    {{--$(document).ready(function() {--}}
        {{--$.material.init()--}}
        {{--})--}}
{{--</script>--}}
</html>
