@extends('layouts.mainLayout')

@section('sidemenu')
    <a href="{{ url('projects')}}" style="{{ Request::is('projects') ? 'color:white' : '' }}">Active projects</a>
    <a href="{{ url('projects/filter/finished')}}" style="{{ Request::is('projects/filter/finished') ? 'color:white' : '' }}">Completed projects</a>
@endsection

@section('title')
    Projects
@endsection

@section('content')
<div class="col-xs-12" style="height:40px;"></div>
    <span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="container">

    @include('layouts.status')

    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            <a href="{{ url('/projects/create') }}" class="btn btn-primary btn-block" role="button">
          <span class="glyphicon glyphicon-plus"></span> @lang('projects.newProject')
            </a>
        </div>
    </div>
    <div class="col-md-12" style="height:20px;"></div>
    <div class="row">
        <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="myTable">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Project name</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php $id = 1; ?>
                @if (isset($projectsFilter))
                    @foreach ($projectsFilter as $project)
                    <tr>
                        <td>{{ $id }}</td>
                        <td><a href="{{ url("projects/detail/$id")}}">{{ $project->Name }}</a></td>
                        <td>{{ $project->ActiveDateFrom }}</td>
                    </tr>
                    <?php $id++; ?>
                    @endforeach
                @else
                    @foreach ($projects as $project)
                    <tr>
                        <td>{{ $id }}</td>
                        <td><a href="{{ url("projects/detail/$id")}}">{{ $project->Name }}</a></td>
                        <td>{{ $project->ActiveDateFrom }}</td>
                    </tr>
                    <?php $id++; ?>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    </div>
</div>

@endsection

@section('javascript')
    <script>
    $(document).ready(function(){
        $('#myTable').DataTable();
    });
    </script>

@endsection
