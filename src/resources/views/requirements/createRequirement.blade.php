@extends('layouts.mainLayout')

@section('title')
    Requirements - create
@endsection

@section('content')
    <div class="container">
        <div class="col-md-12" style="height:65px;"></div>

        @include('layouts.formErrors')

        <a href="{{ url('requirements') }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </br>
        </br>
        <form action="{{ url('requirements/create')}}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><span class="text-danger">*</span>Requirement name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter requirement name" autofocus>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="description">Requirement description:</label>
                        <textarea class="form-control" rows="5" id="description" name="description" placeholder="Enter requirement description">{{ old('description') }}</textarea>
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
