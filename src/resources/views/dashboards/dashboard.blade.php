@extends('layouts.mainLayout')

@section('title') Dashboard @endsection

@section('sidemenu')
    <a href="#">Project progress</a>
    <a href="#">Test results</a>
    <a href="#">Proggress bar</a>
@endsection

@section('content')
    <div class="col-xs-12" style="height:40px;"></div>
    <span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="container">

        <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Area Chart Example
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                          Actions
                                          <span class="caret"></span>
                                      </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="#">Action</a>
                                    </li>
                                    <li><a href="#">Another action</a>
                                    </li>
                                    <li><a href="#">Something else here</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div id="morris-area-chart"></div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

                <!-- /.panel -->

        </div>
@endsection

{{-- @section('javascript')
    <script>
    $(document).ready(function(){
        $('#myTable').DataTable();
        openNav();
    });
    </script>

@endsection --}}
