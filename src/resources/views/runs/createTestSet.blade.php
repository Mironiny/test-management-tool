@extends('layouts.mainLayout')

@section('title')
    Requirements - create
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ URL::previous() }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span>  @lang('layout.back')</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Test set <span class="glyphicon glyphicon-chevron-right"></span> create</a>
            </div>
        </div>
    </div>

    </br>
    </br>

    <form class="form-horizontal" action="{{ url('sets_runs/set/create')}}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Test set name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name='name' maxlength="45" value="{{ old('name') }}" placeholder="Enter test set name" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Test set description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description" placeholder="Enter test set description">{{ old('description') }}</textarea>
                <div class="pull-right">
                    <div id="description_feedback"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">

                <select id='optgroup' name="testcases[]" multiple='multiple'>
                    @if (isset($testSuites))
                        @foreach ($testSuites as $testSuite)
                            <optgroup label='{{ $testSuite->Name }}'>
                                @foreach ($testSuite->testCases->where('ActiveDateTo', null) as $testCase)
                                    <option value='{{$testCase->TestCase_id}}'>{{$testCase->Name}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    @endif
                </select>
        </div>
    </div>

    <br/>
    <br/>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    </br>
    </br>


@endsection

@section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>
<script src="/js/jquery.multi-select.js"></script>

<script>
    $(document).ready(function() {
        $('form').areYouSure();
        var text_max = 1023;

        $('#optgroup').multiSelect({
            selectableOptgroup: true,
            selectableHeader: "<div class='custom-header'>Test cases to select</div>",
            selectionHeader: "<div class='custom-header'>Selected test cases</div>"
        });

        $('#description').keyup(function() {
            var text_length = $('#description').val().length;
            var text_remaining = text_max - text_length;

            $('#description_feedback').html(text_remaining + ' characters remaining');
        });

        $('#description').keyup();
    });
</script>

@endsection
