@extends('layouts.mainLayout')

@section('title')
    Requirements
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    @include('layouts.status')

    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            <a href="{{ url('requirements/create')}}" class="btn btn-primary btn-block {{ isset($selectedProject) ? '' : 'disabled' }}" role="button">
                <span class="glyphicon glyphicon-plus"></span> @lang('requirements.newRequirement')
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
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($requirements))
                    <?php $id = 1; ?>
                    @foreach ($requirements as $requirement)
                    <tr>
                        <td>{{ $id }}</td>
                        <td><a href="{{ url("requirements/detail/$id")}}">{{ $requirement->Name }}</a></td>
                        <td>Not covered</td>
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
