@extends('layouts.mainLayout')

@section('title')
    Test case create
@endsection

@section('sidemenu')
    @include('library.librarySidemenu')
@endsection

@section('content')
<div class="col-xs-12" style="height:40px;"></div>
<span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
<div class="container">

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url('library')}}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Test suite <span class="glyphicon glyphicon-chevron-right"></span> create</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="height:10px;"></div>
    <br/>
    <br/>

    <form class="form-horizontal" action="{{ url('library/testsuite/create')}}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Test suite name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name='name' maxlength="45" value="{{ old('name') }}" placeholder="Enter test suite name" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="test">Test suite description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name='description' placeholder="Enter test suite description">{{ old('description') }}</textarea>
                <div class="pull-right">
                    <div id="feedback2"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="What test suite test?" class="glyphicon glyphicon-info-sign"></span> Test suite goals:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="goals" name='goals' placeholder="Enter test suite goals">{{ old('goals') }}</textarea>
                <div class="pull-right">
                    <div id="feedback1"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </div>
    </form>

</div>
@endsection

@section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            Stretchy.resize('select');
            $('form').areYouSure();
            var text_max = 1023;

            $('#description').keyup(function() {
                var text_length = $('#description').val().length;
                var text_remaining = text_max - text_length;

                $('#feedback1').html(text_remaining + ' characters remaining');
            });

            $('#goals').keyup(function() {
                var text_length = $('#goals').val().length;
                var text_remaining = text_max - text_length;

                $('#feedback2').html(text_remaining + ' characters remaining');
            });

            $('#goals').keyup();
            $('#description').keyup();
        });
    </script>

    @endsection
