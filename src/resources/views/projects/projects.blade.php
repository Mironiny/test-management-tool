@extends('layouts.mainLayout')

@section('sidemenu')
    <a href="#" style="color:white">Active projects</a>
    <a href="#">Finished projects</a>
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
                    <th>Role</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->SUT_id }}</td>
                    <td><a href="#">{{ $project->Name }}</a></td>
                    <td>{{ $project->pivot->Role}}</td>
                    <td>{{ $project->ActiveDateFrom }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>

@endsection
