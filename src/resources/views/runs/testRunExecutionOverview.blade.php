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
    <br/>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Test case name</th>
                        <th>Test Suite</th>
                        <th>Status</th>
                        <th>Change status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($testCases))
                    <?php $id = 1; ?>
                        @foreach ($testCases as $testCase)
                            <?php
                                $class = "";
                                if ($testCase->pivot->Status == App\Enums\TestCaseStatus::PASS ) {
                                    $class = "success";
                                }
                                else if ($testCase->pivot->Status == App\Enums\TestCaseStatus::FAIL) {
                                    $class = "danger";
                                }
                                else if ($testCase->pivot->Status == App\Enums\TestCaseStatus::NOT_TESTED) {
                                    $class = "warning";
                                }
                                else if ($testCase->pivot->Status == App\Enums\TestCaseStatus::BLOCKED) {
                                    $class = "blocked";
                                }
                             ?>
                            <tr class="{{ $class }}" style="{{$class == "blocked" ? "background-color:#d9d9d9" : ''}}">
                                <td class="col-md-1">{{ $id }}</td>
                                <td class="col-md-4"><a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/testcase/$testCase->TestCase_id")}}">{{ $testCase->testCaseOverview->Name }}</a></td>
                                <td class="col-md-3">{{ App\TestSuite::find($testCase->testCaseOverview->TestSuite_id)->Name }}</td>
                                <td class="col-md-3">{{ $testCase->pivot->Status }}</td>
                                <td class="col-md-1"><button class="btn btn-default {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled'}}" onclick="showDialog('{{ $testCase->TestCase_id}}', '{{ $testCase->pivot->Status}}')">Change</button></td>
                            </tr>
                            <?php $id++; ?>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

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
                    </form>
                    {{-- <a href="{{ url("projects/terminate/$projectDetail->SUT_id")}}" class="btn btn-default" role="button">Yes</a> --}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Change status</h4>
                </div>
                <div class="modal-body">

                    <form name="submitForm" id="submitForm" action="{{ url("sets_runs/run/execution/$testRun->TestRun_id/changestatus") }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" id="testCaseId" name="testCaseId" value="">
                            <input type="hidden" id="testStatus" name="testStatus" value="">
                            <button type="submit" name="move" class="btn btn-primary">Save and move to next</button>
                    </form>

                    <a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/changestatus") }}" class="btn btn-warning" onclick="event.preventDefault();
                      document.getElementById('testStatus').value = ('{{ App\Enums\TestCaseStatus::NOT_TESTED }}'); document.getElementById('submitForm').submit();">
                        <i id="not_tested"></i>  {{App\Enums\TestCaseStatus::NOT_TESTED }}
                    </a>

                     <a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/changestatus") }}" class="btn btn-success" onclick="event.preventDefault();
                      document.getElementById('testStatus').value = ('{{ App\Enums\TestCaseStatus::PASS }}');document.submitForm.submit();">
                       <i id="pass"></i> {{App\Enums\TestCaseStatus::PASS }}
                     </a>

                      <a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/changestatus") }}" class="btn btn-danger" onclick="event.preventDefault();
                       document.getElementById('testStatus').value = ('{{ App\Enums\TestCaseStatus::FAIL }}');document.getElementById('submitForm').submit();">
                       <i id="fail"></i> {{App\Enums\TestCaseStatus::FAIL }}</a>

                       <a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/changestatus") }}" class="btn btn-danger" style="background-color: black; border-color: black" onclick="event.preventDefault();
                        document.getElementById('testStatus').value = ('{{ App\Enums\TestCaseStatus::BLOCKED }}');document.getElementById('submitForm').submit();">
                        <i id="blocked"></i> {{App\Enums\TestCaseStatus::BLOCKED }}</a>


                    <p> </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    </br>
    </br>

</div>
@endsection

@section('javascript')
<script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
</script>
<script src="/js/jquery.are-you-sure.js"></script>
<script>
    $(document).ready(function() {
        $('form').areYouSure();
        $('.list .ll a').click(function() {
            $(this).parent().find('ul').toggle();
            $(this).find("span").toggleClass('glyphicon glyphicon-plus glyphicon glyphicon-minus');
        });
    });

    function showDialog(testCaseId,status) {

        var mymodal = $('#myModal');
        mymodal.modal('show');

        $("#not_tested").removeClass();
        $("#pass").removeClass();
        $("#fail").removeClass();
        $("#blocked").removeClass();
        switch (status) {
            case "Not tested":
                $("#not_tested").addClass("fa fa-hand-o-right fa-fw");
                break;
            case "Pass":
                 $("#pass").addClass("fa fa-hand-o-right fa-fw");
                break;
            case "Fail":
                 $("#fail").addClass("fa fa-hand-o-right fa-fw");
                break;
            case "Blocked":
                $("#blocked").addClass("fa fa-hand-o-right fa-fw");
                break;
        }
        document.getElementById('testCaseId').value = testCaseId;
    }

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
