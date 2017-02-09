@extends('layouts.mainLayout')

@section('title')
    Projects - create
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url('projects') }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
            <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Projecs <span class="glyphicon glyphicon-chevron-right"></span> create</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="height:10px;"></div>
    <form action="{{ url('projects/create')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="name"><span class="text-danger">*</span>Project name:</label>
                    <input type="text" class="form-control" id="name" name='name' value="{{ old('name') }}" placeholder="Enter project name" autofocus>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="description">Project description:</label>
                    <textarea class="form-control" rows="5" id="description" name="description" placeholder="Enter project description">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="test">Testing description:</label>
                    <textarea class="form-control" rows="5" id="test" name='testDescription' placeholder="Enter testing description">{{ old('testDescription') }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection

@section('javascript')


@endsection
