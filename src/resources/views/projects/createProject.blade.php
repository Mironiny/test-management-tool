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

    <br/>
    <br/>

    <form class="form-horizontal" action="{{ url('projects/create')}}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Project name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name='name' maxlength="45" value="{{ old('name') }}" placeholder="Enter project name" autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Project description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description" placeholder="Enter project description">{{ old('description') }}</textarea>
                <div class="pull-right">
                    <div id="description_feedback"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="How project will be tested? Describe test aproach." class="glyphicon glyphicon-info-sign"></span> Testing description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="testDescription" name='testDescription' placeholder="Enter testing description">{{ old('testDescription') }}</textarea>
                <div class="pull-right">
                    <div id="testDescription_feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection @section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>

<script>
    $(document).ready(function() {
        $('form').areYouSure();
        $('[data-toggle="tooltip"]').tooltip();
        var text_max = 1023;
        $('#description_feedback').html(text_max + ' characters remaining');
        $('#testDescription_feedback').html(text_max + ' characters remaining');

        $('#description').keyup(function() {
            var text_length = $('#description').val().length;
            var text_remaining = text_max - text_length;

            $('#description_feedback').html(text_remaining + ' characters remaining');
        });

        $('#testDescription').keyup(function() {
            var text_length = $('#testDescription').val().length;
            var text_remaining = text_max - text_length;

            $('#testDescription_feedback').html(text_remaining + ' characters remaining');
        });
    });
</script>

@endsection
