@extends('layouts.mainLayout')

@section('title')
    Test case execution {{ $testRun->Status }}
@endsection

@section('sidemenu')
    @include('runs.testRunExecutionSidemenu')
@endsection

@section('content')
<div class="col-xs-12" style="height:40px;"></div>
<span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
<div class="container">

    @include('layouts.status')
    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url("sets_runs/set/detail/$testRun->TestSet_id") }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span>  @lang('layout.back')</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Test run <span class="glyphicon glyphicon-chevron-right"></span> execution</a>
            </div>
        </div>
    </div>

    </br>
    </br>

        <form class="form-horizontal" action="{{ url("sets_runs/run/execution/$testRun->TestRun_id/testcase/$selectedTestCase->TestCase_id")}}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Test case name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" disabled="disabled" name='name' maxlength="45" value="{{ $selectedTestCase->testCaseOverview->Name }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="description"> Test case version:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="version" name='version' disabled="disabled" value="{{ $selectedTestCase->Version_id }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="description"> Test case description:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="description" name='description' disabled="disabled">{{ $selectedTestCase->TestCaseDescription }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="prefixes"> Test case prefixes:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="prefixes" name='prefixes' disabled="disabled">{{ $selectedTestCase->TestCasePrefixes }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="suffixes"> Test case suffixes:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="suffixes" name='suffixes' disabled="disabled">{{ $selectedTestCase->TestCaseSuffixes }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="steps"> Test case steps:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="steps" name='steps' disabled="disabled">{{ $selectedTestCase->TestSteps }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="steps"> Test case expected result:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="expectedResult" name='expectedResult' disabled="disabled">{{ $selectedTestCase->ExpectedResult }}</textarea>
                </div>
            </div>

            </br>
            </br>
            </hr>
            </br>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="alert alert-info">
                    @if ($testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Author == null)
                        Not tested yet.
                    @else
                        Last update at {{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->LastUpdate }} by {{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Author}}.
                    @endif
                </div>
            </div>
            {{-- <div class="row">
            <div class="col-md-6"> --}}
                <div class="form-group">
                    <label class="control-label col-sm-2" for="status"> Status</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="status" {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled=\"disabled\"'}} name="status">
                                   <option {{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Status == App\Enums\TestCaseStatus::NOT_TESTED ? 'selected=\"selected\"' : ''}}>{{ App\Enums\TestCaseStatus::NOT_TESTED}}</option>
                                   <option {{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Status == App\Enums\TestCaseStatus::PASS ? 'selected=\"selected\"' : ''}}>{{ App\Enums\TestCaseStatus::PASS}}</option>
                                   <option {{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Status == App\Enums\TestCaseStatus::FAIL ? 'selected=\"selected\"' : ''}}>{{ App\Enums\TestCaseStatus::FAIL}}</option>
                                   <option {{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Status == App\Enums\TestCaseStatus::BLOCKED ? 'selected=\"selected\"' : ''}}>{{ App\Enums\TestCaseStatus::BLOCKED}}</option>
                       </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="duration"> Execution duration [min]:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="duration" {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled=\"disabled\"'}} name='duration' maxlength="45" value="{{ $testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->ExecutionDuration }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="steps"> Note:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="note" {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled=\"disabled\"'}} name='note'>{{$testRun->testCases()->find($selectedTestCase->TestCase_id)->pivot->Note}}</textarea>
                    </div>
                </div>
            {{-- </div>
        </div> --}}


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                    <button type="submit" name="dontMove" class="btn btn-primary {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled'}}">Save</button>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                    <button type="submit" name="move" class="btn btn-primary {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled'}}">Save and move to next</button>
                </div>
            </div>

        </form>


            <!--DIV for confirmation dialog-->
            <div id="finishModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirmation</h4>
                        </div>
                        <div class="modal-body">
                            <p>Do you want finish test run?</p>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ url("/sets_runs/run/execution/{$testRun->TestRun_id}/close") }}" class="btn btn-default" onclick="event.preventDefault();
                             document.getElementById('close-form').submit();"> Yes</a>

                            <form id="close-form" action="{{ url("/sets_runs/run/execution/{$testRun->TestRun_id}/close") }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    <button type="submit" name="finish">Save</button>
                            </form>
                            {{-- <a href="{{ url("projects/terminate/$projectDetail->SUT_id")}}" class="btn btn-default" role="button">Yes</a> --}}
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>


</div>

@endsection

@section('javascript')
    <script src="/js/jquery.are-you-sure.js"></script>
    <script>
        $(document).ready(function() {
            $('form').areYouSure();
            $('.list .ll a').click(function() {
                $(this).parent().find('ul').toggle();
                $(this).find("span").toggleClass('glyphicon glyphicon-plus glyphicon glyphicon-minus');
            });
        });

        function expand() {
            $('.list > .ll ul').css('display', '');
            $('.list > .ll  > a > span').attr('class', 'glyphicon glyphicon-minus');
        }
        function collapse() {
            $('.list > .ll ul').css('display', 'none');
            $('.list > .ll  > a > span').attr('class', 'glyphicon glyphicon-plus');
        }

    </script>
@endsection
