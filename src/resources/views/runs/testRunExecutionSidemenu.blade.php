{{-- Test run execution side menu --}}

<div id="sidemenu">
    <button type="button" data-toggle="modal" data-target="#finishModal" class="btn btn-default btn-xs {{ $testRun->Status == App\Enums\TestRunStatus::RUNNING ? '' : 'disabled'}}">Finish test run </button>
    <hr />
    <button type="button" class="btn btn-warning btn-xs disabled">Not tested yet <span class="badge">{{ $testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::NOT_TESTED)->count() }}</span></button></br></br>
    <button type="button" class="btn btn-success btn-xs disabled">Pass <span class="badge">{{ $testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::PASS)->count() }}</span></button></br></br>
    <button type="button" class="btn btn-danger btn-xs disabled">Fail <span class="badge">{{ $testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::FAIL)->count() }}</span></button></br></br>
    <button type="button" class="btn btn-danger btn-xs disabled" style="background-color: black; border-color: black">Blocked <span class="badge">{{ $testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::BLOCKED)->count() }}</span></button>
    <br />
    <hr />
    <button type="button" class="btn btn-default btn-xs" onclick="expand()">Expand all</button>
    <button type="button" class="btn btn-default btn-xs" onclick="collapse()">Collapse all</button>
    <br />
    <br />
</div>
<div class="list">
    <a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/overview") }}" style="padding: 8px 8px 8px 15px; {{ Request::is("sets_runs/run/execution/$testRun->TestRun_id/overview") ? 'color:white' : '' }}"><strong>Test run overview</strong></a>
    @if (isset($testSuites))
        @foreach ($testSuites as $testSuite)
            <div class="ll">
                <a><span style="font-size: 11px" class="glyphicon glyphicon-minus"></span> <strong>{{$testSuite->Name}}</strong></a>
                <ul id="insideExecution">
                    @if (isset($testCases))
                        @foreach ($testCases as $testCase)
                            @if ($testCase->testSuite->TestSuite_id == $testSuite->TestSuite_id )
                                <li>
                                    <a href="{{ url("sets_runs/run/execution/$testRun->TestRun_id/testcase/$testCase->TestCase_id") }}" style="padding: 0px 0px 0px 0px; {{ isset($selectedTestCase) && $testCase->TestCase_id == $selectedTestCase->TestCase_id ? 'color:white' : '' }}">
                                        @if ($testCase->pivot->Status == App\Enums\TestCaseStatus::PASS )
                                            <span class="label label-success"> </span>
                                        @elseif ($testCase->pivot->Status == App\Enums\TestCaseStatus::FAIL )
                                            <span class="label label-danger"> </span>
                                        @elseif ($testCase->pivot->Status == App\Enums\TestCaseStatus::BLOCKED )
                                            <span class="label label-info" style="background-color:black"> </span>
                                        @elseif ($testCase->pivot->Status == App\Enums\TestCaseStatus::NOT_TESTED )
                                            <span class="label label-warning"> </span>
                                        @endif
                                        &nbsp;{{ $testCase->Name }}
                                     </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach
    @endif

</div>
