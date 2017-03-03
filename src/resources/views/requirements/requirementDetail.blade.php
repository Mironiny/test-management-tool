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

    <form class="form-horizontal" action="{{ url("requirements/update/$requirementDetail->TestRequirement_id")}}" method="POST"> {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Requirement name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" disabled="disabled" name='name' maxlength="45" value="{{ $requirementDetail->Name }}" required autofocus>
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
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            <div class="col-sm-2">
                <button type="button" data-toggle="modal" data-target="#cover" class="btn btn-default">Cover by test case</button>
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-2">
                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default" role="button">Delete project</button>
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
                        <a href="{{ url("requirements/terminate/$requirementDetail->TestRequirement_id")}}" class="btn btn-default" role="button">Yes</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

        <!--DIV for confirmation dialog-->
        <div id="cover" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cover requirement by test cases</h4>
                    </div>
                    <div class="modal-body">
                    <form class="form-horizontal" action="{{ url("requirements/cover/$requirementDetail->TestRequirement_id")}}" method="POST"> {{ csrf_field() }}

                        <select id='optgroup' name="testcases[]" multiple='multiple'>
                            @if (isset($testSuites))
                                @foreach ($testSuites as $testSuite)
                                    <optgroup label='{{ $testSuite->Name }}'>
                                        @foreach ($testSuite->testCases->where('ActiveDateTo', null) as $testCase)
                                            <option value='{{$testCase->TestCase_id}}' {{$coverTestCases->contains('TestCase_id', $testCase->TestCase_id) ? 'selected' : ''}}>{{$testCase->Name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif

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
        <br/>
        <br/>
        <table class="table table-striped table-bordered table-hover" id="myTable">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Test case name</th>
                    <th>Test Suite</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($coverTestCases))
                <?php $id = 1; ?>
                    @foreach ($coverTestCases as $testCase)
                        <tr>
                            <td>{{ $id }}</td>
                            <td><a href="{{ url("library/testcase/detail/$testCase->TestCase_id")}}">{{ $testCase->Name }}</a></td>
                            <td><a href="{{ url("library/filter/$testCase->TestSuite_id")}}">{{ App\TestSuite::find($testCase->TestSuite_id)->Name }}</a></td>
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
