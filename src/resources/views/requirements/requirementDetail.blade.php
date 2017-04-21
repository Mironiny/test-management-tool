@extends('layouts.mainLayout')

@section('title')
    Requirements - Detail
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    @include('layouts.status')

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url('requirements') }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> @lang('layout.back')</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Requirement <span class="glyphicon glyphicon-chevron-right"></span> detail</a>
            </div>
        </div>
    </div>

    </br>
    </br>

    <form class="form-horizontal" action="{{ url("requirements/update/$requirementDetail->TestRequirementOverview_id")}}" method="POST"> {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Requirement name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name='name' maxlength="45" value="{{ $requirementDetail->Name }}" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Requirement description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description" placeholder="Enter requirement description">{{ $requirementDetail->RequirementDescription }}</textarea>
                <div class="pull-right">
                    <div id="description_feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-2">
                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
            </div>
            <div class="col-sm-2">
                <button type="button" data-toggle="modal" data-target="#cover" class="btn btn-default">{{ ($count = App\Requirement::find($requirementDetail->TestRequirement_id)->testCases()->count()) < 1 ? "Cover by test case" : "Edit coverage" }} </button>
            </div>
            <div class="col-sm-2">
                <button type="button" data-toggle="modal" data-target="#history" class="btn btn-default">History</button>
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-2">
                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default" role="button">Delete requirement</button>
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
                        <p>Do you really want to delete requirement?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url("requirements/terminate/$requirementDetail->TestRequirementOverview_id")}}" class="btn btn-default" role="button">Yes</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

        <!--DIV for cover by test case-->
        <div id="cover" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cover requirement by test cases</h4>
                    </div>
                    <div class="modal-body">
                    <form name="cover" class="form-horizontal" action="{{ url("requirements/cover/$requirementDetail->TestRequirementOverview_id")}}" method="POST"> {{ csrf_field() }}

                        <select id="optgroup" name="testcases[]" multiple='multiple'>
                            @if (isset($testSuites))
                                @foreach ($testSuites as $testSuite)
                                    <optgroup label="{{ $testSuite->Name }}">
                                        @foreach ($testSuite->testCases->where('ActiveDateTo', null) as $testCase)
                                            <option value="{{$testCase->TestCaseOverview_id}}" {{$coverTestCases->contains('TestCaseOverview_id', $testCase->TestCaseOverview_id) ? 'selected' : ''}}>{{$testCase->Name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif

                        </select>

                    </div>
                    <div class="modal-footer">
                        {{-- <div class="col-sm-2"> --}}
                            <button type="submit" name="submitCover" class="btn btn-primary">Save changes</button>
                        {{-- </div> --}}
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!--DIV for cover by test case-->
        <div id="history" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">History of requirement</h4>
                    </div>
                    <div class="modal-body">

                        <table class="table table-striped table-bordered table-hover" id="historyTable">
                            <thead>
                                <tr>
                                    <th>Version id</th>
                                    <th>Created at</th>
                                    <th>Name</th>
                                    <th>TestCases</th>
                                    <th></th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($requirementsHistory))
                                <?php $id = 1; ?>
                                    @foreach ($requirementsHistory as $requirementVersion)
                                        <tr class="{{ $requirementVersion->TestRequirement_id == $requirementDetail->TestRequirement_id ? 'success' : '' }}">
                                            <td class="col-md-1">{{ $id }}</td>
                                            <td class="col-md-4">{{ $requirementVersion->ActiveDateFrom }}</td>
                                            <td class="col-md-4">{{ $requirementVersion->Name }}</td>
                                            <td class="col-md-1">{{ $requirementVersion->testCases()->count() }}</td>
                                            <td class="col-md-1">
                                                <button class="btn btn-default {{ $requirementVersion->TestRequirement_id == $requirementDetail->TestRequirement_id ? 'disabled' : ''}}" onclick="changeVersion('{{ $requirementVersion->TestRequirement_id}}')">
                                                    Restore
                                                </button>
                                            </td>
                                            <td class="col-md-1">
                                                <button class="btn btn-default {{ $requirementVersion->TestRequirement_id == $requirementDetail->TestRequirement_id ? 'disabled' : ''}}" onclick="removeVersion('{{ $requirementVersion->TestRequirement_id}}')">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php $id++; ?>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <form id="changeVersion-form" action="{{ url("requirements/changeversion/$requirementDetail->TestRequirementOverview_id") }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" id="versionToChange" name="versionToChange" value="">
                        </form>
                        <form id="removeVersion-form" action="{{ url("requirements/removeversion/$requirementDetail->TestRequirementOverview_id") }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" id="versionToChangeRemove" name="versionToChangeRemove" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <br/>
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
                @if (isset($coverTestCases))
                <?php $id = 1; ?>
                    @foreach ($coverTestCases as $testCase)
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
<script src="/js/jquery.are-you-sure.js"></script>
{{-- <script src="/js/jquery.qubit.js"></script> --}}
<script src="/js/jquery.multi-select.js"></script>
{{-- <script type="text/javascript" src="https://cdn.rawgit.com/patosai/tree-multiselect/v2.1.3/dist/jquery.tree-multiselect.min.js"></script> --}}

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('#myTable').DataTable();
        $('#historyTable').DataTable();
    });
</script>

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

    //
    function changeVersion(versionId) {
        document.getElementById('versionToChange').value = versionId;
        $( "#changeVersion-form" ).submit();
    }
</script>

@endsection
