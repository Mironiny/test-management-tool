@extends('layouts.mainLayout')

@section('title')
    Test run
@endsection

@section('sidemenu')
    <a href="{{ url('sets_runs')}}" style="{{ Request::is('sets_runs') ? 'color:white' : '' }}">Active test set</a>
    <a href="{{ url('sets_runs/filter/finished') }}" style="{{ Request::is('sets_runs/filter/finished') ? 'color:white' : '' }}">Closed test set</a>
@endsection

@section('content')
    <div class="col-xs-12" style="height:40px;"></div>
    <span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="container">

        @include('layouts.status')

        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <a href="{{ url('sets_runs/set/create')}}" class="btn btn-primary btn-block {{ isset($selectedProject) ? '' : 'disabled' }}" role="button">
                    <span class="glyphicon glyphicon-plus"></span> New test set
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
                        <th>Test Set name</th>
                        <th>Test runs</th>
                        <th>Status</th>
                        <th>Last result</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($testSets))
                        <?php $id = 1; ?>
                        @foreach ($testSets as $testSet)
                        <tr>
                            <td class="col-md-1"><a href="{{ url("sets_runs/set/detail/$testSet->TestSet_id")}}">{{ $id }}</a></td>
                            <td class="col-md-2"><a href="{{ url("sets_runs/set/detail/$testSet->TestSet_id")}}">{{ $testSet->Name }}</a></td>
                            <td class="col-md-2">{{ App\TestSet::find($testSet->TestSet_id)->testRuns()->count() }}</td>
                            <td class="col-md-2">{{ $testSet->ActiveDateTo == null ? 'Active' : 'Finished' }}</td>
                            <td class="col-md-4">
                                <div class="progress">
                                <?php $testRuns = $testSet->testRuns->where('Status',  App\Enums\TestRunStatus::FINISHED);
                                    $succes = 0;
                                    $fail = 0;
                                    $blocked = 0;
                                    if ($testRuns != null && $testRuns->count() > 0) {
                                                $testRun = $testRuns[$testRuns->count() - 1];
                                                $succes = round($testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::PASS)->count() / $testRun->testCases()->count() * 100);
                                                $fail = round($testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::FAIL)->count() / $testRun->testCases()->count() * 100);
                                                $blocked = round($testRun->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::BLOCKED)->count() / $testRun->testCases()->count() * 100);
                                    }
                                ?>
                                 <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width:{{ $succes }}%">
                                  {{ $succes }}% Pass
                                 </div>
                                 <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width:{{ $fail }}%">
                                   {{ $fail }}% Fail
                                 </div>
                                 <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width:{{ $blocked }}%">
                                   {{ $blocked }}% Blocked
                                 </div>
                               </div>
                            </td>
                        </tr>
                        <?php $id++; ?>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        </div>

@endsection

@section('javascript')
    <script>
    $(document).ready(function(){
        $('#myTable').DataTable();
    });
    </script>

@endsection
