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
            <a href="{{ URL::previous() }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Test case <span class="glyphicon glyphicon-chevron-right"></span> create</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="height:10px;"></div>
    <br/>
    <br/>

    <form class="form-horizontal" action="{{ url('library/testcase/create')}}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Test case name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name='name' maxlength="45" value="{{ old('name') }}" placeholder="Enter test case name" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="Suite"><span class="text-danger">*</span>Test suite:</label>
            <div class="col-sm-10">
                <select class="form-control" id="suite" name="testSuite">
                   @if (isset($testSuites))
                       @foreach ($testSuites as $testSuite)
                           <option value="{{ $testSuite->TestSuite_id }}" {{ isset($selectedSuite) && $selectedSuite == $testSuite->TestSuite_id ? 'selected=\"selected\"' : ''}}>{{ $testSuite->Name }} </option>
                       @endforeach
                   @endif
               </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="description"><span data-toggle="tooltip" data-placement="top" title="What is the purpose of this test? Give tester test overview." class="glyphicon glyphicon-info-sign"></span> Test case description:
            </label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name='description' placeholder="Enter test case description">{{ old('description') }}</textarea>
                <div class="pull-right">
                    <div id="feedback1"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="What should tester do before test execution?" class="glyphicon glyphicon-info-sign"></span> Test case prefixes:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="prefixes" name='prefixes' placeholder="Enter test case prefixes">{{ old('prefixes') }}</textarea>
                <div class="pull-right">
                    <div id="feedback2"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="steps"><span data-toggle="tooltip" data-placement="top" title="What should tester do exactly?" class="glyphicon glyphicon-info-sign"></span> Test case steps:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="steps" name='steps' placeholder="Enter ordered steps of test case">{{ old('steps') }}</textarea>
                <div class="pull-right">
                    <div id="feedback4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="steps"><span data-toggle="tooltip" data-placement="top" title="What should tester check? What should be done?" class="glyphicon glyphicon-info-sign"></span> Test case expected result:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="expectedResult" name='expectedResult' placeholder="Enter expected result">{{ old('expectedResult') }}</textarea>
                <div class="pull-right">
                    <div id="feedback5"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="What should tester do after test execution?" class="glyphicon glyphicon-info-sign"></span> Test case suffixes:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="suffixes" name='suffixes' placeholder="Enter test case suffixes">{{ old('suffixes') }}</textarea>
                <div class="pull-right">
                    <div id="feedback3"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection

@section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>

<script>
    $(document).ready(function() {
        Stretchy.resize('select');
        $('[data-toggle="tooltip"]').tooltip();
        $('form').areYouSure();
        var text_max = 1023;

        $('#description').keyup(function() {
            var text_length = $('#description').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback1').html(text_remaining + ' characters remaining');
        });

        $('#prefixes').keyup(function() {
            var text_length = $('#prefixes').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback2').html(text_remaining + ' characters remaining');
        });

        $('#steps').keyup(function() {
            var text_length = $('#steps').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback4').html(text_remaining + ' characters remaining');
        });

        $('#expectedResult').keyup(function() {
            var text_length = $('#expectedResult').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback5').html(text_remaining + ' characters remaining');
        });

        $('#suffixes').keyup(function() {
            var text_length = $('#suffixes').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback3').html(text_remaining + ' characters remaining');
        });


        $('#prefixes').keyup();
        $('#description').keyup();
        $('#steps').keyup();
        $('#expectedResult').keyup();
        $('#suffixes').keyup();
    });
</script>

@endsection
