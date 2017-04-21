@extends('layouts.mainLayout')

@section('title')
    Test set - detail
@endsection

@section('sidemenu')
    <a href="{{ url("sets_runs/set/detail/$set->TestSet_id")}}" style="{{ Request::is("sets_runs/set/detail/$set->TestSet_id") ? 'color:white' : '' }}">Test runs</a>
    <a href="{{ url("sets_runs/set/detail/$set->TestSet_id/testcases")}}" style="{{ Request::is("sets_runs/set/detail/$set->TestSet_id/testcases") ? 'color:white' : '' }}">Assigned test cases</a>
    <a href="{{ url("sets_runs/set/detail/$set->TestSet_id/filter/archived") }}" style="{{ Request::is("sets_runs/set/detail/$set->TestSet_id/filter/archived") ? 'color:white' : '' }}">Archived test runs</a>
@endsection

@section('content')
    <div class="col-xs-12" style="height:40px;"></div>
    <span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="container">


    @include('layouts.formErrors')
    @include('layouts.status')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url('sets_runs') }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span>  @lang('layout.back')</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Test set <span class="glyphicon glyphicon-chevron-right"></span> detail</a>
            </div>
        </div>
    </div>

    </br>
    </br>

    <form class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Test set name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" disabled="disabled" name='name' maxlength="45" value="{{ $set->Name }}" placeholder="Enter test set name" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Test set description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description" placeholder="Enter test set description">{{ $set->TestSetDescription }}</textarea>
                <div class="pull-right">
                    <div id="description_feedback"></div>
                </div>
            </div>
        </div>
    </form>

    <br/>

    <table class="table table-striped table-bordered table-hover" id="myTable">
        <thead>
            <tr>
                <th>id</th>
                <th>Test case name</th>
                <th>Version id</th>
                <th>Test Suite</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($testCases))
            <?php $id = 1; ?>
                @foreach ($testCases as $testCase)
                    <tr>
                        <td>{{ $id }}</td>
                        <td class="{{  App\testCaseOverview::find($testCase->TestCaseOverview_id)->ActiveDateTo ==  null ? '' : 'danger' }}"><a href="{{ url("library/testcase/detail/$testCase->TestCaseOverview_id")}}">
                            {{ $testCase->testCaseOverview->Name }}</a>
                            <?php  if (App\testCaseOverview::find($testCase->TestCaseOverview_id)->ActiveDateTo !=  null) { ?>
                                    <span data-toggle="tooltip" data-placement="top" title="Already deleted test case." class="glyphicon glyphicon-exclamation-sign"></span>
                                <?php } ?>
                        </td>
                        <?php $TestSuite_id = $testCase->testCaseOverview->TestSuite_id; ?>
                        <td class="{{  App\testCaseOverview::find($testCase->TestCaseOverview_id)->testCases()->whereNull('ActiveDateTo')->first()->Version_id ==  $testCase->Version_id ? '' : 'danger' }}">
                            {{ $testCase->Version_id }}
                                <?php  if (App\testCaseOverview::find($testCase->TestCaseOverview_id)->testCases()->whereNull('ActiveDateTo')->first()->Version_id !=  $testCase->Version_id) { ?>
                                        <span data-toggle="tooltip" data-placement="top" title="Not up-to-date version." class="glyphicon glyphicon-exclamation-sign"></span>
                                    <?php } ?>

                        </td>
                        <?php $TestSuite_id = $testCase->testCaseOverview->TestSuite_id; ?>
                        <td><a href="{{ url("library/filter/$TestSuite_id")}}">{{ App\TestSuite::find($TestSuite_id)->Name }}</a></td>
                    </tr>
                    <?php $id++; ?>
                @endforeach
            @endif
        </tbody>
    </table>

</div>

@endsection

@section('javascript')

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        });
    </script>

@endsection
