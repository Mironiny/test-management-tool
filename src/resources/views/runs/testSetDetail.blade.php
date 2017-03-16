@extends('layouts.mainLayout')

@section('title')
    Test set detail
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

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

    <form class="form-horizontal" action="{{ url("sets_runs/set/update/$set->TestSet_id")}}" method="POST">
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

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
        </div>
    </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-2">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            <div class="col-sm-2">
                <button type="button" data-toggle="modal" data-target="#cover" class="btn btn-default">Show test cases</button>
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-2">
                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default">Close test set</button>
            </div>
        </div>

    </form>

        <div id="cover" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cover requirement by test cases</h4>
                    </div>
                    <div class="modal-body">
                    <form class="form-horizontal" action="{{ url("sets_runs/set/updateTestCases/$set->TestSet_id")}}" method="POST"> {{ csrf_field() }}

                        <select id='optgroup' name="testcases[]" multiple='multiple'>
                            @if (isset($set))
                                @foreach ($testSuites as $testSuite)
                                    <optgroup label='{{ $testSuite->Name }}'>
                                        @foreach ($testSuite->testCases->where('ActiveDateTo', null) as $testCase)
                                            <option value='{{$testCase->TestCase_id}}' {{$set->testCases->contains('TestCase_id', $testCase->TestCase_id) ? 'selected' : ''}}>{{$testCase->Name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif
                        </select>

                        </select>

                    </div>
                    <div class="modal-footer">
                        {{-- <div class="col-sm-2"> --}}
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        {{-- </div> --}}
                    </div>
                </form>
                </div>
            </div>
        </div>

                <!--DIV for confirmation dialog-->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p>Do you really want close test set?</p>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ url("/sets_runs/set/finish/$set->TestSet_id") }}" class="btn btn-default" onclick="event.preventDefault();
                                 document.getElementById('close-form').submit();"> Yes</a>

                                <form id="close-form" action="{{ url("/sets_runs/set/finish/$set->TestSet_id") }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                </form>
                                {{-- <a href="{{ url("projects/terminate/$projectDetail->SUT_id")}}" class="btn btn-default" role="button">Yes</a> --}}
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>

    <br/>

    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            {{-- <a href="{{ url("sets_runs/run/create/$set->TestSet_id")}}" class="btn btn-success btn-block {{ isset($selectedProject) ? '' : 'disabled' }}" role="button">
                <span class="glyphicon glyphicon-play"></span> New run
            </a> --}}

            <a href="{{ url('sets_runs/run/create') }}" onclick="event.preventDefault();
             document.getElementById('newRunForm').submit();" class="btn btn-success btn-block {{ isset($selectedProject) ? '' : 'disabled' }}" role="button">
                <span class="glyphicon glyphicon-play"></span> New run
            </a>

                <form id="newRunForm" action="{{ url('/sets_runs/run/create') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" name="setId" value="{{ $set->TestSet_id }}">
                </form>

        </div>
    </div>

    <br/>

    <table class="table table-striped table-bordered table-hover" id="myTable">
        <thead>
            <tr>
                <th></th>
                <th>Status</th>
                <th>Last update</th>
                <th>Results</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($runs))
            <?php $id = 1; ?>
                @foreach ($runs as $run)
                    <tr>
                        <td class="col-md-2"><a href="{{ url("sets_runs/run/execution/$run->TestRun_id/testcase")}}">{{ $id }}</a></td>
                        <td class="col-md-3">{{ $run->Status }}</td>
                        <td class="col-md-3">{{ $run->LastUpdate }}</td>
                        <td class="col-md-4">
                            <div class="progress">
                            <?php $succes = round(($run->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::PASS)->count() / $run->testCases()->count() * 100), 1) ; ?>
                             <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width:{{ $succes }}%">
                              {{ $succes }}% Pass
                             </div>
                             <?php $fail = round(($run->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::FAIL)->count() / $run->testCases()->count() * 100), 1) ; ?>
                             <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width:{{ $fail }}%">
                               {{ $fail }}% Fail
                             </div>
                             <?php $blocked = round(($run->testCases()->wherePivot('Status', App\Enums\TestCaseStatus::BLOCKED)->count() / $run->testCases()->count() * 100), 1) ; ?>
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

@endsection

@section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>
{{-- <script src="/js/jquery.qubit.js"></script> --}}
<script src="/js/jquery.multi-select.js"></script>
{{-- <script type="text/javascript" src="https://cdn.rawgit.com/patosai/tree-multiselect/v2.1.3/dist/jquery.tree-multiselect.min.js"></script> --}}

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<script>
    $(document).ready(function() {
        $('form').areYouSure();
        var text_max = 1023;

    $('#optgroup').multiSelect({
        selectableOptgroup: true,
        selectableHeader: "<div class='custom-header'>Test cases to select</div>",
        selectionHeader: "<div class='custom-header'><span class=\"text-danger\">*</span>Selected test cases</div>"
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
